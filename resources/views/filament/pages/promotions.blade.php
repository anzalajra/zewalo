<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Filters --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white dark:bg-gray-800 rounded-xl p-4 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
            {{-- Search --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Search</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name or code..."
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >
            </div>

            {{-- Type Filter --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Type</label>
                <select 
                    wire:model.live="typeFilter"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >
                    <option value="">All Types</option>
                    <option value="discount">Kode Diskon</option>
                    <option value="daily">Diskon Harian</option>
                    <option value="date">Promo Tanggal</option>
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1 block">Status</label>
                <select 
                    wire:model.live="statusFilter"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                >
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Name</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300">Type</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 hidden md:table-cell">Code</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 hidden sm:table-cell">Description</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600 dark:text-gray-300 hidden lg:table-cell">Valid Until</th>
                        <th class="px-4 py-3 text-center font-medium text-gray-600 dark:text-gray-300">Status</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600 dark:text-gray-300">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($this->getPromotions() as $promo)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                {{ $promo['name'] }}
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($promo['color'] === 'primary') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                    @elseif($promo['color'] === 'success') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                    @elseif($promo['color'] === 'warning') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                    @endif
                                ">
                                    {{ $promo['type_label'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 hidden md:table-cell">
                                @if($promo['code'])
                                    <span class="inline-flex items-center px-2 py-1 rounded bg-gray-100 dark:bg-gray-600 text-xs font-mono">
                                        {{ $promo['code'] }}
                                    </span>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-600 dark:text-gray-400 hidden sm:table-cell">
                                {{ $promo['description'] }}
                            </td>
                            <td class="px-4 py-3 hidden lg:table-cell">
                                <span class="{{ $promo['is_expired'] ? 'text-red-600' : 'text-gray-600 dark:text-gray-400' }}">
                                    {{ $promo['validity'] }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($promo['is_active'] && !$promo['is_expired'])
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-200">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="3"/></svg>
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ $promo['edit_url'] }}" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                                    </svg>
                                    <p class="font-medium">No promotions found</p>
                                    <p class="text-sm mt-1">Create a new promotion to get started</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-4 border border-blue-100 dark:border-blue-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-blue-100 dark:bg-blue-800 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-blue-600 dark:text-blue-400">Kode Diskon</p>
                        <p class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ \App\Models\Discount::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4 border border-green-100 dark:border-green-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-green-100 dark:bg-green-800 rounded-lg">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-green-600 dark:text-green-400">Diskon Harian</p>
                        <p class="text-xl font-bold text-green-700 dark:text-green-300">{{ \App\Models\DailyDiscount::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-4 border border-yellow-100 dark:border-yellow-800">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-yellow-100 dark:bg-yellow-800 rounded-lg">
                        <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-yellow-600 dark:text-yellow-400">Promo Tanggal</p>
                        <p class="text-xl font-bold text-yellow-700 dark:text-yellow-300">{{ \App\Models\DatePromotion::where('is_active', true)->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
