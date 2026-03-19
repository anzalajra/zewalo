<div class="min-h-screen flex items-center justify-center p-4 font-[Inter]"
     style="background-color: #f6f8f8;">

    
    <div class="fixed inset-0 -z-10 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full blur-3xl" style="background: rgba(20,184,166,0.06);"></div>
        <div class="absolute bottom-0 right-0 w-80 h-80 rounded-full blur-3xl" style="background: rgba(20,184,166,0.1);"></div>
        <div class="absolute inset-0"
             style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 24px 24px; mask-image: radial-gradient(ellipse 50% 50% at 50% 50%, #000 70%, transparent 100%); -webkit-mask-image: radial-gradient(ellipse 50% 50% at 50% 50%, #000 70%, transparent 100%);">
        </div>
    </div>

    <div class="w-full max-w-md"
         x-data="{}"
         x-init="$nextTick(() => { $el.classList.add('opacity-100', 'translate-y-0'); })"
         class="opacity-0 translate-y-4 transition-all duration-700 ease-out">

        
        <div class="text-center mb-8">
            <a href="/" class="inline-flex items-center gap-2.5">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl text-white" style="background-color: #14B8A6;">
                    <span class="material-symbols-outlined text-2xl">storefront</span>
                </div>
                <span class="text-2xl font-bold tracking-tight text-slate-900">Zewalo</span>
            </a>
        </div>

        
        <div class="bg-white shadow-2xl rounded-xl overflow-hidden border border-slate-200">

            
            <div class="px-8 pt-8 pb-6 border-b border-slate-100">
                <h1 class="text-slate-900 text-2xl font-extrabold tracking-tight">Masuk ke Toko Anda</h1>
                <p class="text-slate-500 text-sm mt-1.5">Login dengan akun admin toko Anda.</p>
            </div>

            <div class="px-8 py-6">
                <form wire:submit="login" class="space-y-4">
                    
                    <div>
                        <label class="text-slate-700 text-sm font-semibold mb-1.5 block">Email</label>
                        <div class="flex items-stretch rounded-lg shadow-sm border border-slate-200 bg-white overflow-hidden transition-all focus-within:ring-2 focus-within:ring-[#14B8A6]/20 focus-within:border-[#14B8A6] <?php echo e($errors->has('email') ? 'border-red-400' : ''); ?>">
                            <div class="flex items-center justify-center w-12 bg-slate-50 border-r border-slate-200 text-slate-400 pointer-events-none">
                                <span class="material-symbols-outlined text-xl">mail</span>
                            </div>
                            <input
                                wire:model="email"
                                type="email"
                                placeholder="admin@email.com"
                                class="flex-1 w-full bg-transparent px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-0 border-none"
                                autofocus
                            >
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div>
                        <label class="text-slate-700 text-sm font-semibold mb-1.5 block">Password</label>
                        <div class="flex items-stretch rounded-lg shadow-sm border border-slate-200 bg-white overflow-hidden transition-all focus-within:ring-2 focus-within:ring-[#14B8A6]/20 focus-within:border-[#14B8A6] <?php echo e($errors->has('password') ? 'border-red-400' : ''); ?>">
                            <div class="flex items-center justify-center w-12 bg-slate-50 border-r border-slate-200 text-slate-400 pointer-events-none">
                                <span class="material-symbols-outlined text-xl">lock</span>
                            </div>
                            <input
                                wire:model="password"
                                type="password"
                                placeholder="••••••••"
                                class="flex-1 w-full bg-transparent px-4 py-3 text-slate-900 placeholder:text-slate-400 focus:outline-none focus:ring-0 border-none"
                            >
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-1 text-sm text-red-500"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errorMessage): ?>
                        <div class="flex items-start gap-3 rounded-lg bg-red-50 border border-red-100 px-4 py-3">
                            <span class="material-symbols-outlined text-red-400 text-xl mt-0.5">error</span>
                            <p class="text-sm text-red-600"><?php echo e($errorMessage); ?></p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:loading.class="opacity-75 cursor-wait"
                        class="w-full text-white font-bold py-3.5 px-6 rounded-lg shadow-lg transition-all flex items-center justify-center gap-2 hover:opacity-90"
                        style="background-color: #14B8A6; box-shadow: 0 10px 15px -3px rgba(20,184,166,0.2);">
                        <span wire:loading.remove class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-xl">login</span>
                            Masuk
                        </span>
                        <span wire:loading class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </button>
                </form>
            </div>

            
            <div class="px-8 py-4 bg-slate-50 border-t border-slate-100">
                <a href="/register-tenant" class="text-xs font-semibold hover:underline" style="color: #14B8A6;">
                    Belum punya toko? Daftar gratis
                </a>
            </div>
        </div>

        
        <p class="text-center text-xs text-slate-400 mt-6">&copy; <?php echo e(date('Y')); ?> Zewalo. All rights reserved.</p>
    </div>
</div>
<?php /**PATH /var/www/resources/views/livewire/tenant-login.blade.php ENDPATH**/ ?>