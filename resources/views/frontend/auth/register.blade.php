@extends('layouts.frontend')

@section('title', __('auth.register'))

@section('content')
@php
    $oldCategoryId = old('customer_category_id');
    $oldCategory = $oldCategoryId ? $categories->find($oldCategoryId) : null;

    // Build top-level categories (no parent) for complex registration
    $topLevel = $categories->whereNull('parent_id');

    // For complex registration: build JSON data for Alpine.js
    $topLevelJson = $topLevel->values()->map(fn ($c) => [
        'id' => $c->id,
        'name' => $c->name,
        'description' => $c->description,
        'icon' => $c->icon,
        'children' => $c->children->map(fn ($ch) => [
            'id' => $ch->id,
            'name' => $ch->name,
        ])->values()->toArray(),
    ])->toArray();

    // For simple registration: flatten all leaf categories (categories without children)
    $leafCategories = $categories->filter(fn ($c) => $c->children->isEmpty());

    // Determine initial state for complex registration
    $initialStep = 'type_selection';
    $initialParentId = '';
    $initialCategoryId = $oldCategoryId ?? '';

    if ($oldCategoryId && $complexRegistration) {
        $initialStep = 'form';
        // Check if old category is a top-level one
        $oldCat = $categories->find($oldCategoryId);
        if ($oldCat) {
            $initialParentId = $oldCat->parent_id ?? $oldCat->id;
        }
    }

    // Auto-skip: if only one leaf category and no complex registration, auto-select it
    $autoSelectId = '';
    if (!$complexRegistration && $leafCategories->count() === 1) {
        $autoSelectId = $leafCategories->first()->id;
    }
    // Complex mode: one top-level with no children → auto-select
    if ($complexRegistration && $topLevel->count() === 1 && $topLevel->first()->children->isEmpty()) {
        $autoSelectId = $topLevel->first()->id;
        $initialStep = 'form';
        $initialCategoryId = $autoSelectId;
    }
@endphp

@if($complexRegistration)
{{-- ============================================================ --}}
{{-- COMPLEX REGISTRATION: Multi-step parent → child selection    --}}
{{-- ============================================================ --}}
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    x-data="{
        step: '{{ $initialStep }}',
        selectedParentId: '{{ $initialParentId }}',
        categoryId: '{{ $initialCategoryId }}',
        topLevelCategories: @js($topLevelJson),

        get selectedParent() {
            return this.topLevelCategories.find(c => c.id == this.selectedParentId) || null;
        },

        get selectedCategoryName() {
            if (!this.categoryId) return '';
            for (const cat of this.topLevelCategories) {
                if (cat.id == this.categoryId) return cat.name;
                const child = cat.children.find(ch => ch.id == this.categoryId);
                if (child) return child.name;
            }
            return '';
        },

        selectParent(parent) {
            this.selectedParentId = parent.id;
            if (parent.children.length === 0) {
                this.categoryId = parent.id;
                this.step = 'form';
            } else {
                this.categoryId = '';
                this.step = 'sub_selection';
            }
        },

        selectChild(childId) {
            this.categoryId = childId;
            this.step = 'form';
        },

        goBack() {
            if (this.step === 'form' && this.selectedParent && this.selectedParent.children.length > 0) {
                this.step = 'sub_selection';
                this.categoryId = '';
            } else {
                this.step = 'type_selection';
                this.selectedParentId = '';
                this.categoryId = '';
            }
        }
    }"
>
    <div class="max-w-md w-full space-y-8">

        {{-- Header --}}
        <div>
            <h2 class="text-center text-3xl font-bold text-gray-900">
                <span x-show="step === 'type_selection'">{{ __('auth.select_account_type') }}</span>
                <span x-show="step === 'sub_selection'" x-text="selectedParent ? selectedParent.name : ''"></span>
                <span x-show="step === 'form'">{{ __('auth.create_account') }}</span>
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600" x-show="step === 'type_selection'">
                {{ __('auth.already_have_account') }} <a href="{{ route('customer.login') }}" class="text-primary-600 hover:underline">{{ __('auth.sign_in') }}</a>
            </p>
            <p class="mt-2 text-center text-sm text-gray-600" x-show="step === 'sub_selection'">
                {{ __('auth.select_your_category') }}
            </p>
        </div>

        {{-- Step 1: Top-level category selection --}}
        <div x-show="step === 'type_selection'" class="space-y-4 mt-8" x-transition>
            <template x-for="cat in topLevelCategories" :key="cat.id">
                <button @click="selectParent(cat)"
                    class="w-full group relative flex items-center p-5 bg-white border-2 border-gray-100 rounded-2xl hover:border-primary-500 hover:shadow-lg hover:shadow-primary-500/10 transition-all duration-200 transform hover:-translate-y-1">
                    <div class="flex-shrink-0 h-14 w-14 min-w-[3.5rem] flex items-center justify-center rounded-xl bg-primary-50 text-primary-600 group-hover:bg-primary-600 group-hover:text-white transition-colors duration-200">
                        <template x-if="cat.icon">
                            <span x-html="cat.icon" class="w-6 h-6 [&>svg]:w-6 [&>svg]:h-6"></span>
                        </template>
                        <template x-if="!cat.icon">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </template>
                    </div>
                    <div class="ml-4 text-left flex-1">
                        <h3 class="text-lg font-bold text-gray-900 group-hover:text-primary-700" x-text="cat.name"></h3>
                        <p class="text-sm text-gray-500 mt-1" x-show="cat.description" x-text="cat.description"></p>
                    </div>
                    <div class="flex-shrink-0 ml-4 text-gray-300 group-hover:text-primary-500 transition-colors duration-200">
                        <svg class="w-6 h-6 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </button>
            </template>
        </div>

        {{-- Step 2: Sub-category selection (only when parent has children) --}}
        <div x-show="step === 'sub_selection'" class="mt-8" x-transition>
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="flex justify-between items-center mb-3">
                    <label class="block text-sm font-medium text-gray-700">{{ __('auth.i_am') }}</label>
                    <button @click="goBack()" class="text-xs text-gray-500 hover:text-gray-700 underline">{{ __('auth.go_back') }}</button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <template x-for="child in (selectedParent ? selectedParent.children : [])" :key="child.id">
                        <button type="button"
                            @click="selectChild(child.id)"
                            :class="categoryId == child.id
                                ? 'bg-primary-600 text-white border-primary-600 shadow-md ring-2 ring-primary-500 ring-offset-2'
                                : 'bg-white text-gray-700 border-gray-200 hover:border-primary-400 hover:bg-gray-50 hover:shadow-sm'"
                            class="relative flex items-center justify-center space-x-2 px-4 py-3 border rounded-xl focus:outline-none transition-all duration-200">
                            <span class="font-medium text-sm" x-text="child.name"></span>
                            <div x-show="categoryId == child.id" x-transition.scale.origin.center>
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Step 3: Registration Form --}}
        <div x-show="step === 'form'" class="mt-8" x-transition>

            {{-- Selected category badge --}}
            <div class="mb-6" x-show="categoryId">
                <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 flex items-center justify-between">
                    <div>
                        <span class="text-sm text-primary-600 font-medium">{{ __('auth.registering_as') }}</span>
                        <span class="ml-2 text-primary-800 font-bold" x-text="selectedCategoryName"></span>
                    </div>
                    <button @click="goBack()" class="text-sm text-primary-600 hover:text-primary-800 underline">{{ __('auth.change') }}</button>
                </div>
            </div>

            @include('frontend.auth._register-form')
        </div>
    </div>
</div>

@else
{{-- ============================================================ --}}
{{-- SIMPLE REGISTRATION: Flat category selection or auto-select  --}}
{{-- ============================================================ --}}
<div class="min-h-[60vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
    x-data="{ categoryId: '{{ $oldCategoryId ?: $autoSelectId }}' }"
>
    <div class="max-w-md w-full space-y-8">
        <div>
            <h2 class="text-center text-3xl font-bold text-gray-900">{{ __('auth.create_account') }}</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('auth.already_have_account') }} <a href="{{ route('customer.login') }}" class="text-primary-600 hover:underline">{{ __('auth.sign_in') }}</a>
            </p>
        </div>

        <div class="mt-8">
            {{-- Show category selector only if more than one leaf category --}}
            @if($leafCategories->count() > 1)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.category') }}</label>
                    <div class="grid grid-cols-1 sm:grid-cols-{{ min($leafCategories->count(), 3) }} gap-3">
                        @foreach($leafCategories as $category)
                            <button type="button"
                                @click="categoryId = '{{ $category->id }}'"
                                :class="categoryId == '{{ $category->id }}'
                                    ? 'bg-primary-600 text-white border-primary-600 shadow-md ring-2 ring-primary-500 ring-offset-2'
                                    : 'bg-white text-gray-700 border-gray-200 hover:border-primary-400 hover:bg-gray-50 hover:shadow-sm'"
                                class="relative flex items-center justify-center space-x-2 px-4 py-3 border rounded-xl focus:outline-none transition-all duration-200">
                                <span class="font-medium text-sm">{{ $category->name }}</span>
                                <div x-show="categoryId == '{{ $category->id }}'" x-transition.scale.origin.center>
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                            </button>
                        @endforeach
                    </div>
                    @error('customer_category_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            @include('frontend.auth._register-form')
        </div>
    </div>
</div>
@endif
@endsection