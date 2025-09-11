<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Tambah Transaksi') }}
            </h2>
            <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('transactions.store') }}" method="POST" id="transactionForm">
                        @csrf

                        <!-- Type Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Tipe Transaksi</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="radio" id="income" name="type" value="income" class="sr-only" {{ old('type') == 'income' ? 'checked' : '' }}>
                                    <label for="income" class="flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-green-50 hover:border-green-300 transition-colors duration-200 type-option" data-type="income">
                                        <div class="text-center">
                                            <div class="w-8 h-8 mx-auto mb-2 bg-green-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">Pemasukan</span>
                                        </div>
                                    </label>
                                </div>
                                <div>
                                    <input type="radio" id="expense" name="type" value="expense" class="sr-only" {{ old('type') == 'expense' ? 'checked' : '' }}>
                                    <label for="expense" class="flex items-center justify-center p-4 border-2 border-gray-200 rounded-lg cursor-pointer hover:bg-red-50 hover:border-red-300 transition-colors duration-200 type-option" data-type="expense">
                                        <div class="text-center">
                                            <div class="w-8 h-8 mx-auto mb-2 bg-red-100 rounded-full flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-900">Pengeluaran</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category Selection -->
                        <div class="mb-6">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed" required disabled>
                                <option value="">Pilih tipe transaksi terlebih dahulu...</option>
                            </select>
                            @error('category_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div class="mb-6">
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Jumlah</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount') }}" class="pl-12 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0" required>
                            </div>
                            @error('amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <input type="text" name="description" id="description" value="{{ old('description') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan deskripsi transaksi" required>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tambahkan catatan jika diperlukan">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Transaction Date -->
                        <div class="mb-6">
                            <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', date('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            @error('transaction_date')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-lg transition-colors duration-200" title="Batal">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200" title="Simpan Transaksi">
                                <i class="fas fa-check mr-2"></i>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        /* Custom Select2 styling to match Tailwind CSS */
        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            background-color: white;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 26px;
            color: #374151;
        }
        
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
        }
        
        .select2-dropdown {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
        }
        
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3b82f6;
        }
        
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeOptions = document.querySelectorAll('.type-option');
            const categorySelect = document.getElementById('category_id');
            const typeRadios = document.querySelectorAll('input[name="type"]');

            // Function to update UI based on selected type
            function updateTypeSelection(selectedType) {
                typeOptions.forEach(option => {
                    const type = option.dataset.type;
                    if (type === selectedType) {
                        if (type === 'income') {
                            option.classList.add('bg-green-50', 'border-green-500');
                            option.classList.remove('border-gray-200');
                        } else {
                            option.classList.add('bg-red-50', 'border-red-500');
                            option.classList.remove('border-gray-200');
                        }
                    } else {
                        option.classList.remove('bg-green-50', 'border-green-500', 'bg-red-50', 'border-red-500');
                        option.classList.add('border-gray-200');
                    }
                });
            }

            // Function to update categories based on selected type using AJAX
            function updateCategories(type) {
                console.log('updateCategories dipanggil dengan type:', type);
                
                // Clear existing options
                categorySelect.innerHTML = '';
                
                // Add default option
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Memuat kategori...';
                categorySelect.appendChild(defaultOption);
                
                if (type === '') {
                    console.log('Type kosong, disable dropdown kategori');
                    categorySelect.disabled = true;
                    defaultOption.textContent = 'Pilih tipe transaksi terlebih dahulu...';
                    return;
                }
                
                // Disable dropdown while loading
                categorySelect.disabled = true;
                
                // Make AJAX request to get categories by type
                const url = type === 'income' ? '{{ route("categories.income") }}' : '{{ route("categories.expense") }}';
                
                fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(categories => {
                        console.log('Kategori berhasil dimuat:', categories);
                        
                        // Destroy existing Select2 instance
                        $('#category_id').select2('destroy');
                        
                        // Clear loading option
                        categorySelect.innerHTML = '';
                        
                        // Add default option
                        const defaultOption = document.createElement('option');
                        defaultOption.value = '';
                        defaultOption.textContent = 'Pilih kategori...';
                        categorySelect.appendChild(defaultOption);
                        
                        // Add categories
                        if (categories && categories.length > 0) {
                            categories.forEach(category => {
                                const option = document.createElement('option');
                                option.value = category.id;
                                option.textContent = category.name;
                                
                                categorySelect.appendChild(option);
                                console.log('Kategori ditambahkan:', category.name);
                            });
                            
                            // Enable dropdown
                            categorySelect.disabled = false;
                        } else {
                            // No categories available
                            defaultOption.textContent = 'Tidak ada kategori tersedia';
                            categorySelect.disabled = true;
                        }
                        
                        // Reinitialize Select2
                        $('#category_id').select2({
                            placeholder: 'Pilih kategori...',
                            allowClear: true,
                            width: '100%'
                        });
                        
                        // Set old category if exists (for form validation errors)
                        const oldCategoryId = '{{ old("category_id") }}';
                        if (oldCategoryId) {
                            $('#category_id').val(oldCategoryId).trigger('change');
                        }
                    })
                    .catch(error => {
                        console.error('Error loading categories:', error);
                        
                        // Destroy existing Select2 instance
                        $('#category_id').select2('destroy');
                        
                        // Clear loading option and show error
                        categorySelect.innerHTML = '';
                        const errorOption = document.createElement('option');
                        errorOption.value = '';
                        errorOption.textContent = 'Gagal memuat kategori';
                        categorySelect.appendChild(errorOption);
                        categorySelect.disabled = true;
                        
                        // Reinitialize Select2 with error state
                        $('#category_id').select2({
                            placeholder: 'Gagal memuat kategori',
                            allowClear: false,
                            width: '100%'
                        });
                    });
            }

            // Handle type selection
            typeOptions.forEach(option => {
                option.addEventListener('click', function() {
                    const type = this.dataset.type;
                    const radio = document.getElementById(type);
                    radio.checked = true;
                    
                    updateTypeSelection(type);
                    updateCategories(type);
                });
            });

            // Handle radio change (for keyboard navigation)
            typeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.checked) {
                        updateTypeSelection(this.value);
                        updateCategories(this.value);
                    }
                });
            });

            // Initialize Select2 for category dropdown
            $(document).ready(function() {
                $('#category_id').select2({
                    placeholder: 'Pilih kategori...',
                    allowClear: true,
                    width: '100%'
                });
            });

            // Initialize with old value if exists (for form validation errors)
            const oldType = '{{ old("type") }}';
            if (oldType) {
                const oldRadio = document.getElementById(oldType);
                if (oldRadio) {
                    oldRadio.checked = true;
                    updateTypeSelection(oldType);
                    updateCategories(oldType);
                }
            } else {
                // Initialize with empty state
                updateCategories('');
            }

            // Format amount input
            const amountInput = document.getElementById('amount');
            amountInput.addEventListener('input', function() {
                let value = this.value.replace(/[^0-9.]/g, '');
                this.value = value;
            });
        });
    </script>
    @endpush
</x-app-layout>