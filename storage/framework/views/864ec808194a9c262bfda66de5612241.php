<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['class' => '']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $currentLocale = app()->getLocale();
    $locales = [
        'id' => ['label' => 'ID', 'flag' => '🇮🇩', 'name' => 'Bahasa Indonesia'],
        'en' => ['label' => 'EN', 'flag' => '🇺🇸', 'name' => 'English'],
    ];
    $current = $locales[$currentLocale] ?? $locales['id'];
?>

<div x-data="{ open: false }" class="relative <?php echo e($class); ?>" @click.outside="open = false">
    <button @click="open = !open" type="button"
        class="flex items-center gap-1.5 text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors px-2 py-1.5 rounded-lg hover:bg-gray-100">
        <span><?php echo e($current['flag']); ?></span>
        <span><?php echo e($current['label']); ?></span>
        <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute right-0 mt-1 w-44 bg-white rounded-lg shadow-lg ring-1 ring-black/5 py-1 z-50">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $locales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $locale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(request()->fullUrlWithQuery(['lang' => $code])); ?>"
                class="flex items-center gap-2.5 mx-1 px-2.5 py-2 text-sm rounded-md <?php echo e($currentLocale === $code ? 'text-indigo-600 bg-indigo-50 font-medium' : 'text-gray-700 hover:bg-gray-50'); ?>">
                <span><?php echo e($locale['flag']); ?></span>
                <span><?php echo e($locale['name']); ?></span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($currentLocale === $code): ?>
                    <svg class="w-4 h-4 ml-auto text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </a>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</div>
<?php /**PATH /var/www/resources/views/components/language-switcher.blade.php ENDPATH**/ ?>