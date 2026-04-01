@extends('layouts.frontend')

@section('title', __('storefront.checkout.booking_confirmed'))

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="text-center mb-8">
        <div class="text-6xl mb-4">✅</div>
        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('storefront.checkout.booking_submitted') }}</h1>
        <p class="text-gray-600">{{ __('storefront.checkout.booking_submitted_desc') }}</p>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">{{ __('storefront.checkout.booking_details') }}</h2>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm text-gray-500">{{ __('storefront.checkout.booking_code') }}</label>
                <p class="font-semibold text-lg">{{ $rental->rental_code }}</p>
            </div>
            <div>
                <label class="block text-sm text-gray-500">{{ __('storefront.checkout.status') }}</label>
                <span class="inline-block px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm font-medium">{{ ucfirst($rental->status) }}</span>
            </div>
            <div>
                <label class="block text-sm text-gray-500">{{ __('storefront.checkout.start_date') }}</label>
                <p class="font-medium">{{ $rental->start_date->format('d M Y H:i') }}</p>
            </div>
            <div>
                <label class="block text-sm text-gray-500">{{ __('storefront.checkout.end_date') }}</label>
                <p class="font-medium">{{ $rental->end_date->format('d M Y H:i') }}</p>
            </div>
        </div>

        <hr class="my-4">

        <h3 class="font-semibold mb-3">{{ __('storefront.checkout.items') }}</h3>
        <div class="space-y-3">
            @foreach($rental->items as $item)
                <div class="flex justify-between">
                    <div>
                        <p class="font-medium">
                            {{ $item->productUnit->product->name }}
                            @if($item->productUnit->variation)
                                <span class="text-gray-500 font-normal">({{ $item->productUnit->variation->name }})</span>
                            @endif
                        </p>
                        <p class="text-sm text-gray-500">{{ $item->days }} {{ __('storefront.days') }} × Rp {{ number_format($item->daily_rate, 0, ',', '.') }}</p>
                    </div>
                    <p class="font-medium">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                </div>
            @endforeach
        </div>

        <hr class="my-4">

        <div class="flex justify-between font-bold text-lg">
            <span>{{ __('storefront.checkout.total') }}</span>
            <span class="text-primary-600">Rp {{ number_format($rental->total, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm text-gray-600 mt-2">
            <span>{{ __('storefront.checkout.deposit_required') }}</span>
            <span>Rp {{ number_format($rental->deposit, 0, ',', '.') }}</span>
        </div>
    </div>

    @if($rental->payment_method === 'manual_transfer' && isset($manualTransferDetails) && $manualTransferDetails)
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
        <h3 class="font-semibold text-yellow-800 mb-3">Instruksi Transfer</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm text-yellow-700">Bank</label>
                <p class="font-semibold text-yellow-900">{{ $manualTransferDetails['bank_name'] }}</p>
            </div>
            <div>
                <label class="block text-sm text-yellow-700">Nomor Rekening</label>
                <p class="font-semibold text-yellow-900">{{ $manualTransferDetails['account_number'] }}</p>
            </div>
            <div>
                <label class="block text-sm text-yellow-700">Atas Nama</label>
                <p class="font-semibold text-yellow-900">{{ $manualTransferDetails['account_holder'] }}</p>
            </div>
        </div>

        @if(! $rental->transfer_proof_path)
        <form action="{{ route('checkout.upload-proof', $rental) }}" method="POST" enctype="multipart/form-data" class="mt-4 border-t border-yellow-200 pt-4">
            @csrf
            <label class="block text-sm font-medium text-yellow-800 mb-2">Upload Bukti Transfer</label>
            <input type="file" name="transfer_proof" accept="image/*" required
                class="block w-full text-sm text-gray-700 border border-yellow-300 rounded-lg cursor-pointer bg-white focus:outline-none">
            @error('transfer_proof')
                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
            @enderror
            <button type="submit" class="mt-3 bg-yellow-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-yellow-700 transition">
                Upload Bukti Transfer
            </button>
        </form>
        @else
        <div class="mt-4 border-t border-yellow-200 pt-4">
            <p class="text-sm text-green-700 font-medium">Bukti transfer sudah diupload. Menunggu verifikasi.</p>
        </div>
        @endif
    </div>
    @endif

    <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 mb-6">
        <h3 class="font-semibold text-primary-800 mb-2">{{ __('storefront.checkout.whats_next') }}</h3>
        <ul class="text-sm text-primary-700 space-y-1">
            <li>{{ __('storefront.checkout.next_step_review') }}</li>
            <li>{{ __('storefront.checkout.next_step_deposit') }}</li>
            <li>{{ __('storefront.checkout.next_step_id') }}</li>
        </ul>
    </div>

    <div class="flex justify-center space-x-4">
        <a href="{{ route('customer.rentals') }}" class="bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-700">
            {{ __('storefront.checkout.view_my_rentals') }}
        </a>
        <a href="{{ route('catalog.index') }}" class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-300">
            {{ __('storefront.checkout.continue_browsing') }}
        </a>
    </div>
</div>
@endsection