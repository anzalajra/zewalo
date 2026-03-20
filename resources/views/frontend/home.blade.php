@extends('layouts.frontend')

@section('title', __('storefront.home'))

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">{{ __('storefront.hero_title') }}</h1>
            <p class="text-xl mb-8 text-primary-100">{{ __('storefront.hero_subtitle') }}</p>
            <a href="{{ route('catalog.index') }}" class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                {{ __('storefront.browse_catalog') }}
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-8 text-center">{{ __('storefront.browse_by_category') }}</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('catalog.index', ['category' => $category->id]) }}" class="bg-white rounded-lg shadow p-6 text-center hover:shadow-lg transition">
                    <div class="text-4xl mb-2">📷</div>
                    <h3 class="font-semibold">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->products_count }} {{ __('storefront.products') }}</p>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="py-16 bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-8 text-center">{{ __('storefront.featured_products') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredProducts as $product)
                <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition">
                    <div class="h-48 bg-gray-200 flex items-center justify-center">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                        @else
                            <span class="text-6xl">📷</span>
                        @endif
                    </div>
                    <div class="p-4">
                        <p class="text-xs text-primary-600 mb-1">{{ $product->category->name }}</p>
                        <h3 class="font-semibold mb-2">{{ $product->name }}</h3>
                        <p class="text-primary-600 font-bold">Rp {{ number_format($product->daily_rate, 0, ',', '.') }}/{{ __('storefront.day') }}</p>
                        <a href="{{ route('catalog.show', $product) }}" class="mt-3 block text-center bg-primary-600 text-white py-2 rounded hover:bg-primary-700 transition">
                            {{ __('storefront.view_details') }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('catalog.index') }}" class="text-primary-600 font-semibold hover:underline">{{ __('storefront.view_all_products') }} →</a>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-bold mb-12 text-center">{{ __('storefront.how_it_works') }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">1</div>
                <h3 class="font-semibold mb-2">{{ __('storefront.step_browse') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('storefront.step_browse_desc') }}</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">2</div>
                <h3 class="font-semibold mb-2">{{ __('storefront.step_book') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('storefront.step_book_desc') }}</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">3</div>
                <h3 class="font-semibold mb-2">{{ __('storefront.step_pickup') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('storefront.step_pickup_desc') }}</p>
            </div>
            <div class="text-center">
                <div class="w-16 h-16 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-4">4</div>
                <h3 class="font-semibold mb-2">{{ __('storefront.step_return') }}</h3>
                <p class="text-gray-600 text-sm">{{ __('storefront.step_return_desc') }}</p>
            </div>
        </div>
    </div>
</section>
@endsection