<form class="space-y-6" action="{{ route('customer.register') }}" method="POST">
    @csrf
    <input type="hidden" name="customer_category_id" :value="categoryId">

    @if($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-lg text-sm">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="space-y-4">
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">{{ __('auth.full_name') }}</label>
            <input id="name" name="name" type="text" required value="{{ old('name') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">{{ __('auth.email') }}</label>
            <input id="email" name="email" type="email" required value="{{ old('email') }}" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">{{ __('auth.phone') }}</label>
            <input id="phone" name="phone" type="text" required value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">{{ __('auth.password') }}</label>
            <input id="password" name="password" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('auth.confirm_password') }}</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
        </div>

        {{-- Custom Fields --}}
        @if(isset($customFields) && count($customFields) > 0)
            <div class="space-y-4 pt-4 border-t border-gray-200">
                @foreach($customFields as $field)
                    @php
                        $fieldName = 'custom_' . $field['name'];
                        $visibleCats = $field['visible_for_categories'] ?? [];
                        $visibleCats = array_map('strval', $visibleCats);
                        $visibleCatsJson = json_encode($visibleCats);
                        $isRequired = $field['required'] ?? false;
                    @endphp
                    <div x-data="{ visibleCats: {{ $visibleCatsJson }} }"
                         x-show="visibleCats.length === 0 || visibleCats.includes(String(categoryId))"
                         x-transition>

                        @if($field['type'] !== 'checkbox')
                            <label for="{{ $fieldName }}" class="block text-sm font-medium text-gray-700">
                                {{ $field['label'] }}
                                @if($isRequired) <span class="text-red-500">*</span> @endif
                            </label>
                        @endif

                        @if($field['type'] === 'text' || $field['type'] === 'number' || $field['type'] === 'email')
                            <input id="{{ $fieldName }}" name="{{ $fieldName }}" type="{{ $field['type'] === 'number' ? 'number' : 'text' }}"
                                value="{{ old($fieldName) }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">

                        @elseif($field['type'] === 'textarea')
                            <textarea id="{{ $fieldName }}" name="{{ $fieldName }}" rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">{{ old($fieldName) }}</textarea>

                        @elseif($field['type'] === 'select')
                            <select id="{{ $fieldName }}" name="{{ $fieldName }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring-primary-500 focus:border-primary-500">
                                <option value="">{{ __('auth.select_option', ['label' => $field['label']]) }}</option>
                                @foreach($field['options'] ?? [] as $option)
                                    <option value="{{ $option['value'] }}" {{ old($fieldName) == $option['value'] ? 'selected' : '' }}>
                                        {{ $option['label'] }}
                                    </option>
                                @endforeach
                            </select>

                        @elseif($field['type'] === 'radio')
                            <div class="mt-2 space-y-2">
                                @foreach($field['options'] ?? [] as $option)
                                    <div class="flex items-center">
                                        <input id="{{ $fieldName }}_{{ $option['value'] }}" name="{{ $fieldName }}" type="radio" value="{{ $option['value'] }}"
                                            {{ old($fieldName) == $option['value'] ? 'checked' : '' }}
                                            class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300">
                                        <label for="{{ $fieldName }}_{{ $option['value'] }}" class="ml-2 block text-sm text-gray-700">
                                            {{ $option['label'] }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>

                        @elseif($field['type'] === 'checkbox')
                            <div class="mt-2 flex items-center">
                                <input id="{{ $fieldName }}" name="{{ $fieldName }}" type="checkbox" value="1"
                                    {{ old($fieldName) ? 'checked' : '' }}
                                    class="focus:ring-primary-500 h-4 w-4 text-primary-600 border-gray-300 rounded">
                                <label for="{{ $fieldName }}" class="ml-2 block text-sm text-gray-700">
                                    {{ $field['label'] }} @if($isRequired) <span class="text-red-500">*</span> @endif
                                </label>
                            </div>
                        @endif

                        @error($fieldName)
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
        {{ __('auth.create_account') }}
    </button>
</form>