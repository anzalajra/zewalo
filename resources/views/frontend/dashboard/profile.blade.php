@extends('layouts.frontend')

@section('title', __('portal.profile'))

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ activeTab: 'profile' }">
    <h1 class="text-2xl font-bold mb-8">{{ __('portal.profile_verification') }}</h1>

    <!-- Account Status & Verification Card -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Verification Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>

            <div class="relative z-10">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('portal.verification_status') }}
                </h2>

                <div class="mb-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($verificationStatus === 'verified') bg-green-100 text-green-800
                        @elseif($verificationStatus === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-red-100 text-red-800 @endif">
                        <span class="w-2 h-2 mr-2 rounded-full
                            @if($verificationStatus === 'verified') bg-green-500
                            @elseif($verificationStatus === 'pending') bg-yellow-500
                            @else bg-red-500 @endif"></span>
                        {{ $customer->getVerificationStatusLabel() }}
                    </span>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed">
                    @if($verificationStatus === 'verified')
                        {{ __('portal.verified_desc') }}
                    @elseif($verificationStatus === 'pending')
                        {{ __('portal.pending_desc') }}
                    @else
                        {{ __('portal.unverified_desc') }}
                    @endif
                </p>

                @if($verificationStatus !== 'verified' && $verificationStatus !== 'pending')
                    <button @click="activeTab = 'documents'" class="mt-4 text-sm text-primary-600 font-medium hover:text-primary-700 flex items-center gap-1">
                        {{ __('portal.upload_documents') }}
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </button>
                @endif
            </div>
        </div>

        <!-- Account Category Status -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <svg class="w-24 h-24 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>

            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        {{ __('portal.account_status') }}
                    </h2>
                </div>

                <div class="mb-6">
                    @if($customer->category)
                        <div class="text-3xl font-bold mb-1" style="color: {{ $customer->category->badge_color ?? '#fbbf24' }}">
                            {{ $customer->category->name }}
                        </div>
                        <p class="text-gray-500 text-sm">{{ __('portal.current_membership_level') }}</p>
                    @else
                        <div class="text-3xl font-bold mb-1 text-gray-400">
                            {{ __('portal.regular_member') }}
                        </div>
                        <p class="text-gray-500 text-sm">{{ __('portal.increase_transactions') }}</p>
                    @endif
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <p class="text-sm font-medium text-gray-700 mb-3">{{ __('portal.benefits') }}</p>
                    @if($customer->category && !empty($customer->category->benefits))
                        <div class="flex flex-wrap gap-2">
                            @foreach($customer->category->benefits as $benefit)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $benefit }}
                                </span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-sm text-gray-500 italic">{{ __('portal.no_benefits_yet') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6 border-b border-gray-200 overflow-x-auto">
        <nav class="-mb-px flex space-x-8 min-w-max" aria-label="Tabs">
            <button @click="activeTab = 'profile'"
                :class="{ 'border-primary-500 text-primary-600': activeTab === 'profile', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'profile' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ __('portal.personal_info') }}
            </button>
            <button @click="activeTab = 'documents'"
                :class="{ 'border-primary-500 text-primary-600': activeTab === 'documents', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'documents' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ __('portal.verification_documents') }}
            </button>
            <button @click="activeTab = 'password'"
                :class="{ 'border-primary-500 text-primary-600': activeTab === 'password', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'password' }"
                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                {{ __('portal.change_password') }}
            </button>
        </nav>
    </div>

    <!-- Profile Form -->
    <div x-show="activeTab === 'profile'" class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">{{ __('portal.personal_info') }}</h2>

        <form action="{{ route('customer.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('portal.full_name') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required class="w-full border rounded-lg px-3 py-2">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('portal.email') }}</label>
                    <input type="email" value="{{ $customer->email }}" disabled class="w-full border rounded-lg px-3 py-2 bg-gray-100">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('portal.phone_number') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}" required class="w-full border rounded-lg px-3 py-2">
                    @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('portal.nik') }} <span class="text-red-500">*</span></label>
                    <input type="text" name="nik" value="{{ old('nik', $customer->nik) }}" required maxlength="16" minlength="16" placeholder="{{ __('portal.nik_placeholder') }}" class="w-full border rounded-lg px-3 py-2">
                    @error('nik') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            @if(isset($customFields) && count($customFields) > 0)
                <div class="mb-4 pt-4 border-t border-gray-100">
                    <h3 class="text-sm font-medium text-gray-900 mb-4">{{ __('portal.additional_info') }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($customFields as $field)
                            @php
                                $fieldName = 'custom_' . $field['name'];
                                $visibleCats = $field['visible_for_categories'] ?? [];
                                $isRequired = $field['required'] ?? false;
                                $currentValue = $customer->custom_fields[$field['name']] ?? '';

                                // Check visibility
                                if (!empty($visibleCats) && !in_array($customer->customer_category_id, $visibleCats)) {
                                    continue;
                                }
                            @endphp

                            <div class="col-span-1">
                                @if($field['type'] !== 'checkbox')
                                    <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $field['label'] }}
                                        @if($isRequired) <span class="text-red-500">*</span> @endif
                                    </label>
                                @endif

                                @if($field['type'] === 'select')
                                    <select id="{{ $fieldName }}" name="{{ $fieldName }}" class="w-full border rounded-lg px-3 py-2 bg-white focus:ring-primary-500 focus:border-primary-500">
                                        <option value="">{{ __('portal.select_option', ['label' => $field['label']]) }}</option>
                                        @foreach($field['options'] ?? [] as $option)
                                            <option value="{{ $option['value'] }}" {{ $currentValue == $option['value'] ? 'selected' : '' }}>
                                                {{ $option['label'] }}
                                            </option>
                                        @endforeach
                                    </select>

                                @elseif($field['type'] === 'radio')
                                    <div class="mt-2 space-y-2">
                                        @foreach($field['options'] ?? [] as $option)
                                            <div class="flex items-center">
                                                <input id="{{ $fieldName }}_{{ $loop->index }}" name="{{ $fieldName }}" type="radio" value="{{ $option['value'] }}"
                                                    {{ $currentValue == $option['value'] ? 'checked' : '' }}
                                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300">
                                                <label for="{{ $fieldName }}_{{ $loop->index }}" class="ml-3 block text-sm font-medium text-gray-700">
                                                    {{ $option['label'] }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                @elseif($field['type'] === 'checkbox')
                                    <div class="flex items-start mt-2">
                                        <div class="flex items-center h-5">
                                            <input id="{{ $fieldName }}" name="{{ $fieldName }}" type="checkbox" value="1"
                                                {{ $currentValue ? 'checked' : '' }}
                                                class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="{{ $fieldName }}" class="font-medium text-gray-700">
                                                {{ $field['label'] }}
                                                @if($isRequired) <span class="text-red-500">*</span> @endif
                                            </label>
                                        </div>
                                    </div>

                                @elseif($field['type'] === 'textarea')
                                    <textarea id="{{ $fieldName }}" name="{{ $fieldName }}" rows="3"
                                        class="w-full border rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">{{ $currentValue }}</textarea>

                                @else
                                    <input id="{{ $fieldName }}" name="{{ $fieldName }}" type="{{ $field['type'] === 'number' ? 'number' : 'text' }}"
                                        value="{{ $currentValue }}"
                                        class="w-full border rounded-lg px-3 py-2 focus:ring-primary-500 focus:border-primary-500">
                                @endif

                                @error($fieldName)
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('portal.address') }}</label>
                <textarea name="address" rows="3" class="w-full border rounded-lg px-3 py-2">{{ old('address', $customer->address) }}</textarea>
            </div>

            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700">
                {{ __('portal.save_changes') }}
            </button>
        </form>
    </div>

    <!-- Document Upload -->
    <div x-show="activeTab === 'documents'" class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">{{ __('portal.verification_documents') }}</h2>
        <p class="text-sm text-gray-600 mb-6">{{ __('portal.upload_documents_desc') }}</p>

        <form action="{{ route('customer.documents.upload') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                @foreach($documentTypes as $type)
                    @php
                        $uploadedDoc = $uploadedDocuments->get($type->id);
                    @endphp
                    <div class="border rounded-lg p-4 @if($type->is_required) border-primary-300 bg-primary-50 @endif">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-medium">{{ $type->name }}</h3>
                                    @if($type->is_required)
                                        <span class="px-2 py-0.5 bg-primary-100 text-primary-700 text-xs rounded">{{ __('portal.required') }}</span>
                                    @endif
                                </div>
                                @if($type->description)
                                    <p class="text-sm text-gray-500 mt-1">{{ $type->description }}</p>
                                @endif

                                @if($uploadedDoc)
                                    <div class="mt-3 flex items-center gap-4">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <span class="text-sm">{{ $uploadedDoc->file_name }}</span>
                                            <span class="text-xs text-gray-400">({{ $uploadedDoc->getFileSizeFormatted() }})</span>
                                        </div>
                                        <span class="px-2 py-1 rounded text-xs font-medium
                                            @if($uploadedDoc->status === 'approved') bg-green-100 text-green-700
                                            @elseif($uploadedDoc->status === 'rejected') bg-red-100 text-red-700
                                            @else bg-yellow-100 text-yellow-700 @endif">
                                            @if($uploadedDoc->status === 'approved') {{ __('portal.doc_approved') }}
                                            @elseif($uploadedDoc->status === 'rejected') {{ __('portal.doc_rejected') }}
                                            @else {{ __('portal.doc_pending') }} @endif
                                        </span>
                                    </div>

                                    @if($uploadedDoc->status === 'rejected' && $uploadedDoc->rejection_reason)
                                        <p class="mt-2 text-sm text-red-600">{{ __('portal.rejection_reason') }}: {{ $uploadedDoc->rejection_reason }}</p>
                                    @endif
                                @endif
                            </div>

                            <div class="ml-4">
                                @if($uploadedDoc)
                                    <div class="flex items-center gap-2">
                                        <a href="{{ \App\Services\Storage\R2Url::signed($uploadedDoc->file_path) }}" target="_blank" class="text-primary-600 hover:underline text-sm">{{ __('portal.view') }}</a>
                                        @if($uploadedDoc->status !== 'approved')
                                            <button type="button" onclick="deleteDocument('{{ route('customer.documents.delete', $uploadedDoc) }}')" class="text-red-600 hover:underline text-sm">{{ __('portal.delete') }}</button>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(!$uploadedDoc || $uploadedDoc->status === 'rejected')
                            <div class="mt-4">
                                <label class="block">
                                    <span class="sr-only">{{ __('portal.choose_file') }}</span>
                                    <input type="file" name="files[{{ $type->id }}]" accept=".jpg,.jpeg,.png,.pdf"
                                        class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-lg file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-primary-600 file:text-white
                                        hover:file:bg-primary-700
                                    "/>
                                </label>
                                <p class="text-xs text-gray-400 mt-1">{{ __('portal.file_format_notice') }}</p>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700">
                    {{ __('portal.upload_documents') }}
                </button>
            </div>
        </form>
    </div>

    <!-- Password Form -->
    <div x-show="activeTab === 'password'" class="bg-white rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold mb-4">{{ __('portal.change_password') }}</h2>

        <form action="{{ route('customer.password.change') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('portal.current_password') }}</label>
                    <input type="password" name="current_password" required class="w-full border rounded-lg px-3 py-2">
                    @error('current_password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('portal.new_password') }}</label>
                    <input type="password" name="password" required class="w-full border rounded-lg px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('portal.confirm_new_password') }}</label>
                    <input type="password" name="password_confirmation" required class="w-full border rounded-lg px-3 py-2">
                </div>
            </div>

            <button type="submit" class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700">
                {{ __('portal.change_password') }}
            </button>
        </form>
    </div>
</div>

<form id="delete-doc-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function deleteDocument(url) {
        if (confirm('{{ __('portal.delete_document_confirm') }}')) {
            const form = document.getElementById('delete-doc-form');
            form.action = url;
            form.submit();
        }
    }
</script>
@endsection