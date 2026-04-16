<div class="mb-4 rounded-lg border border-blue-300 bg-blue-50 p-4 dark:border-blue-700 dark:bg-blue-900/20">
    <div class="flex items-center gap-3">
        <x-heroicon-o-rocket-launch class="h-5 w-5 shrink-0 text-blue-600 dark:text-blue-400" />
        <p class="flex-1 text-sm font-medium text-blue-800 dark:text-blue-200">
            {{ __('admin.setup_wizard.banner_message') }}
        </p>
        <a href="{{ $wizardUrl }}"
           class="shrink-0 rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-700">
            {{ __('admin.setup_wizard.banner_button') }}
        </a>
    </div>
</div>
