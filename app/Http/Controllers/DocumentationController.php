<?php

namespace App\Http\Controllers;

use App\Models\CentralSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class DocumentationController extends Controller
{
    public function show()
    {
        [$pageTitle, $subtitle, $intro, $sections] = Cache::remember(
            'public_documentation_render',
            now()->addMinutes(30),
            function () {
                $title = CentralSetting::get('documentation_page_title') ?: 'Dokumentasi';
                $subtitle = CentralSetting::get('documentation_page_subtitle') ?: '';
                $introRaw = CentralSetting::get('documentation_intro') ?: '';

                $sectionsRaw = CentralSetting::get('documentation_sections', '[]');
                if (is_string($sectionsRaw)) {
                    $sectionsRaw = json_decode($sectionsRaw, true) ?: [];
                }
                if (! is_array($sectionsRaw)) {
                    $sectionsRaw = [];
                }

                $converter = $this->markdownConverter();

                $intro = $introRaw !== '' ? (string) $converter->convert($introRaw) : '';

                $sections = [];
                foreach ($sectionsRaw as $section) {
                    if (! ($section['published'] ?? true)) {
                        continue;
                    }
                    $sectionTitle = $section['title'] ?? '';
                    $slug = $section['slug'] ?? '';
                    if ($slug === '' && $sectionTitle !== '') {
                        $slug = Str::slug($sectionTitle);
                    }
                    $sections[] = [
                        'title' => $sectionTitle,
                        'slug' => $slug,
                        'html' => (string) $converter->convert($section['content'] ?? ''),
                    ];
                }

                return [$title, $subtitle, $intro, $sections];
            }
        );

        return view('landing.documentation', [
            'pageTitle' => $pageTitle,
            'subtitle' => $subtitle,
            'introHtml' => $intro,
            'sections' => $sections,
        ]);
    }

    public function changelog()
    {
        $entries = Cache::remember(
            'public_changelog_render',
            now()->addMinutes(15),
            function () {
                $path = base_path('CHANGELOG.md');
                if (! File::exists($path)) {
                    return [];
                }

                $raw = File::get($path);
                return $this->parseChangelog($raw);
            }
        );

        return view('landing.changelog', [
            'pageTitle' => 'Changelog',
            'subtitle' => 'Catatan rilis dan pembaruan ' . (CentralSetting::get('branding_site_name') ?: 'Zewalo') . '.',
            'entries' => $entries,
        ]);
    }

    private function markdownConverter(): MarkdownConverter
    {
        $environment = new Environment([
            'html_input' => 'escape',
            'allow_unsafe_links' => false,
        ]);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        return new MarkdownConverter($environment);
    }

    /**
     * Parse the CHANGELOG.md content into structured entries.
     * Expected format per entry:
     *   ## [vX.Y.Z] - YYYY-MM-DD
     *   - (Tag) Item line
     *   - (Tag) Item line
     */
    private function parseChangelog(string $raw): array
    {
        $lines = preg_split("/\r\n|\n|\r/", $raw);
        $entries = [];
        $current = null;

        foreach ($lines as $line) {
            $trimmed = rtrim($line);

            // Skip the file's leading "# Changelog" header
            if (preg_match('/^#\s+Changelog/i', $trimmed)) {
                continue;
            }

            // Skip command/comment hint lines at the bottom of the file
            if (preg_match('/^#\s*Use command:/i', $trimmed)) {
                continue;
            }

            // Match heading like:  ## [v1.5.1] - 2026-03-02   or   ## v1.5.1 - 2026-03-02
            if (preg_match('/^##\s*\[?v?([0-9][0-9A-Za-z.\-_]*)\]?\s*[-–]\s*(\d{4}-\d{2}-\d{2})\s*$/', $trimmed, $m)) {
                if ($current !== null) {
                    $entries[] = $current;
                }
                $current = [
                    'version' => $m[1],
                    'date' => $m[2],
                    'items' => [],
                ];
                continue;
            }

            if ($current === null) {
                continue;
            }

            // List item: -  or  *
            if (preg_match('/^\s*[-*]\s+(.*)$/', $trimmed, $m)) {
                $text = trim($m[1]);
                $tag = null;

                // Pull out leading "(Tag)"
                if (preg_match('/^\(([^)]+)\)\s*(.*)$/', $text, $tm)) {
                    $tag = trim($tm[1]);
                    $text = trim($tm[2]);
                }

                $current['items'][] = [
                    'tag' => $tag,
                    'text' => $text,
                ];
            }
        }

        if ($current !== null) {
            $entries[] = $current;
        }

        return $entries;
    }
}
