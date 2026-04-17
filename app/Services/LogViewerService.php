<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Aggregates log files from storage/logs and parses them into filterable entries.
 *
 * Discovers every *.log file under storage/logs (Laravel, queue worker, scheduler,
 * php-errors, backup-restore, etc). Returns a unified stream with source, level,
 * timestamp, message, and stack trace for display in Central Admin.
 */
class LogViewerService
{
    protected const LEVELS = ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug'];

    /**
     * List all log files with metadata (path, size, modified, line count).
     *
     * @return array<int, array{name: string, path: string, size: int, size_formatted: string, modified: string, mtime: int}>
     */
    public function listFiles(): array
    {
        $dir = storage_path('logs');

        if (! is_dir($dir)) {
            return [];
        }

        $files = [];
        $items = @scandir($dir) ?: [];

        foreach ($items as $name) {
            if ($name === '.' || $name === '..') {
                continue;
            }

            $path = $dir.DIRECTORY_SEPARATOR.$name;

            if (! is_file($path)) {
                continue;
            }

            if (! preg_match('/\.(log|txt)$/i', $name)) {
                continue;
            }

            $size = @filesize($path) ?: 0;
            $mtime = @filemtime($path) ?: 0;

            $files[] = [
                'name' => $name,
                'path' => $path,
                'size' => $size,
                'size_formatted' => $this->formatBytes($size),
                'modified' => $mtime ? date('Y-m-d H:i:s', $mtime) : 'N/A',
                'mtime' => $mtime,
            ];
        }

        usort($files, fn ($a, $b) => $b['mtime'] <=> $a['mtime']);

        return $files;
    }

    /**
     * Parse a log file and return filtered entries (newest first).
     *
     * @param  string|null  $fileName  Filename under storage/logs, or null for "all files"
     * @param  string|null  $level     emergency/alert/critical/error/warning/notice/info/debug
     * @param  string|null  $search    Case-insensitive keyword in message/trace
     * @param  int  $limit  Max entries returned
     * @return Collection<int, array{source: string, datetime: string, env: string, level: string, message: string, context: string, line_in_file: int}>
     */
    public function parse(?string $fileName = null, ?string $level = null, ?string $search = null, int $limit = 500): Collection
    {
        $files = $fileName
            ? array_filter($this->listFiles(), fn ($f) => $f['name'] === $fileName)
            : $this->listFiles();

        $entries = collect();

        foreach ($files as $file) {
            $parsed = $this->parseFile($file['path'], $file['name']);

            foreach ($parsed as $entry) {
                if ($level && strtolower($entry['level']) !== strtolower($level)) {
                    continue;
                }

                if ($search !== null && $search !== '') {
                    $needle = mb_strtolower($search);
                    $haystack = mb_strtolower($entry['message'].' '.$entry['context']);
                    if (! str_contains($haystack, $needle)) {
                        continue;
                    }
                }

                $entries->push($entry);
            }
        }

        return $entries
            ->sortByDesc(fn ($e) => $e['datetime'].'-'.$e['line_in_file'])
            ->values()
            ->take($limit);
    }

    /**
     * Parse a single log file.
     *
     * Supports both Laravel default format:
     *   [2026-04-17 10:23:45] production.ERROR: Message here {context}
     * And raw stderr style lines (treated as "info" without timestamp match).
     *
     * @return array<int, array{source: string, datetime: string, env: string, level: string, message: string, context: string, line_in_file: int}>
     */
    protected function parseFile(string $path, string $source): array
    {
        if (! is_readable($path)) {
            return [];
        }

        $contents = @file_get_contents($path);
        if ($contents === false) {
            return [];
        }

        // Split on the start of each Laravel log entry. Keep delimiters.
        // Pattern: [YYYY-MM-DD HH:MM:SS] env.LEVEL:
        $pattern = '/\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] ([a-zA-Z0-9_-]+)\.([A-Z]+): /';

        $entries = [];

        if (preg_match($pattern, $contents)) {
            $parts = preg_split($pattern, $contents, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            // parts layout: [preamble?], datetime, env, LEVEL, body, datetime, env, LEVEL, body, ...
            $i = 0;
            $lineCounter = 0;

            // Skip any non-matching preamble
            if (! preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $parts[0] ?? '')) {
                array_shift($parts);
            }

            while (isset($parts[$i], $parts[$i + 1], $parts[$i + 2], $parts[$i + 3])) {
                $datetime = $parts[$i];
                $env = $parts[$i + 1];
                $level = strtolower($parts[$i + 2]);
                $body = rtrim($parts[$i + 3]);

                $firstBreak = strpos($body, "\n");
                if ($firstBreak === false) {
                    $message = $body;
                    $context = '';
                } else {
                    $message = substr($body, 0, $firstBreak);
                    $context = substr($body, $firstBreak + 1);
                }

                $entries[] = [
                    'source' => $source,
                    'datetime' => $datetime,
                    'env' => $env,
                    'level' => $level,
                    'message' => trim($message),
                    'context' => trim($context),
                    'line_in_file' => $lineCounter++,
                ];

                $i += 4;
            }
        } else {
            // Raw log (stderr, queue-worker stdout, php-errors) — one line per entry
            $lines = preg_split('/\r?\n/', $contents) ?: [];
            $lineCounter = 0;

            foreach ($lines as $line) {
                if (trim($line) === '') {
                    continue;
                }

                $level = 'info';
                $lower = strtolower($line);
                foreach (self::LEVELS as $candidate) {
                    if (str_contains($lower, $candidate)) {
                        $level = $candidate;
                        break;
                    }
                }

                $entries[] = [
                    'source' => $source,
                    'datetime' => '',
                    'env' => '',
                    'level' => $level,
                    'message' => $line,
                    'context' => '',
                    'line_in_file' => $lineCounter++,
                ];
            }
        }

        return $entries;
    }

    /**
     * Get raw file contents (for download).
     */
    public function getRaw(string $fileName): ?string
    {
        $file = $this->resolveFile($fileName);

        return $file ? @file_get_contents($file) : null;
    }

    /**
     * Clear a single log file (truncate, keep file).
     */
    public function clear(string $fileName): bool
    {
        $file = $this->resolveFile($fileName);

        if (! $file) {
            return false;
        }

        return @file_put_contents($file, '') !== false;
    }

    /**
     * Count entries per level across all files (for summary cards).
     *
     * @return array<string, int>
     */
    public function summary(): array
    {
        $counts = array_fill_keys(self::LEVELS, 0);

        foreach ($this->listFiles() as $file) {
            foreach ($this->parseFile($file['path'], $file['name']) as $entry) {
                $level = $entry['level'];
                if (isset($counts[$level])) {
                    $counts[$level]++;
                }
            }
        }

        return $counts;
    }

    protected function resolveFile(string $fileName): ?string
    {
        // Prevent path traversal
        $safe = basename($fileName);
        $path = storage_path('logs').DIRECTORY_SEPARATOR.$safe;

        return is_file($path) && is_readable($path) ? $path : null;
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision).' '.$units[$pow];
    }

    /**
     * @return array<int, string>
     */
    public static function availableLevels(): array
    {
        return self::LEVELS;
    }
}
