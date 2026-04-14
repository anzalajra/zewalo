<!-- Footer -->
<footer class="bg-slate-50 border-t border-slate-200 dark:border-slate-800 py-16">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12">
<div class="col-span-2 lg:col-span-2">
<div class="flex items-center gap-2 mb-6">
<a href="{{ route('home') }}" class="flex items-center gap-2">
<x-central-brand-logo class="h-6 w-6" :showName="true" nameClass="text-xl font-bold tracking-tight" />
</a>
</div>
<p class="text-slate-500 text-sm leading-relaxed max-w-xs mb-6">
                        {{ __('landing.footer.description') }}
                    </p>
<div class="flex gap-4">
<a class="text-slate-400 hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined">public</span>
</a>
<a class="text-slate-400 hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined">alternate_email</span>
</a>
<a class="text-slate-400 hover:text-primary transition-colors" href="#">
<span class="material-symbols-outlined">share</span>
</a>
</div>
</div>
<div>
<h4 class="font-bold text-sm mb-6 uppercase tracking-wider">{{ __('landing.footer.company') }}</h4>
<ul class="space-y-4">
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.about_us') }}</a></li>
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.careers') }}</a></li>
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.blog') }}</a></li>
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.contact') }}</a></li>
</ul>
</div>
<div>
<h4 class="font-bold text-sm mb-6 uppercase tracking-wider">{{ __('landing.footer.legal') }}</h4>
<ul class="space-y-4">
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.privacy') }}</a></li>
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.terms') }}</a></li>
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.security') }}</a></li>
</ul>
</div>
<div>
<h4 class="font-bold text-sm mb-6 uppercase tracking-wider">{{ __('landing.footer.support') }}</h4>
<ul class="space-y-4">
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.help_center') }}</a></li>
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.documentation') }}</a></li>
<li><a class="text-sm text-slate-500 hover:text-primary" href="#">{{ __('landing.footer.system_status') }}</a></li>
</ul>
</div>
</div>
<div class="mt-16 pt-8 border-t border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
<p class="text-sm text-slate-500">{{ __('landing.footer.copyright', ['year' => date('Y')]) }}</p>
<x-language-switcher />
</div>
</div>
</footer>
