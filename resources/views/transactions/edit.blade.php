<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Transaksi') }}
            </h2>
            <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Balance Summary -->
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-2">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-4 text-gray-900">
                        <div class="flex items-center justify-center">
                            <div class="p-2 bg-blue-100 rounded-full">
                                <i class="fas fa-wallet text-blue-600 text-lg"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-xs font-medium text-gray-500">Saldo</p>
                                <p class="text-lg font-semibold {{ ($totalIncome - $totalExpense) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                    Rp {{ number_format($totalIncome - $totalExpense, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('transactions.update', $transaction) }}" method="POST" id="transactionForm">
                        @csrf
                        @method('PUT')

                        <!-- Type Selection -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">Tipe Transaksi</label>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <input type="radio" id="income" name="type" value="income" class="sr-only" {{ old('type', $transaction->type) == 'income' ? 'checked' : '' }}>
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
                                    <input type="radio" id="expense" name="type" value="expense" class="sr-only" {{ old('type', $transaction->type) == 'expense' ? 'checked' : '' }}>
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
                            <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 disabled:bg-gray-100 disabled:cursor-not-allowed" required>
                                <option value="">Pilih kategori...</option>
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
                                <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount', $transaction->amount) }}" class="pl-12 mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0" required>
                            </div>
                            @error('amount')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <input type="text" name="description" id="description" value="{{ old('description', $transaction->description) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Masukkan deskripsi transaksi" required>
                            @error('description')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-6">
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                            <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Tambahkan catatan jika diperlukan">{{ old('notes', $transaction->notes) }}</textarea>
                            @error('notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Transaction Date -->
                        <div class="mb-6">
                            <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" id="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date->format('Y-m-d')) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
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
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200" title="Update Transaksi">
                                <i class="fas fa-check mr-2"></i>
                                Update Transaksi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeOptions = document.querySelectorAll('.type-option');
            const categorySelect = document.getElementById('category_id');
            const typeRadios = document.querySelectorAll('input[name="type"]');

            // Categories data from server
            const categories = @json($categories->groupBy('type'));
            const currentCategoryId = {{ old('category_id', $transaction->category_id) }};

            // Function to update category options
            function updateCategories(type) {
                categorySelect.innerHTML = '<option value="">Pilih kategori...</option>';
                categorySelect.disabled = false;
                
                if (categories[type]) {
                    categories[type].forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.id;
                        option.textContent = category.name;
                        option.style.color = category.color;
                        
                        if (category.id == currentCategoryId) {
                            option.selected = true;
                        }
                        
                        categorySelect.appendChild(option);
                    });
                } else {
                    categorySelect.disabled = true;
                    categorySelect.innerHTML = '<option value="">Tidak ada kategori tersedia</option>';
                }
            }

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

            // Initialize with current transaction data
            const currentType = '{{ old("type", $transaction->type) }}';
            if (currentType) {
                updateTypeSelection(currentType);
                updateCategories(currentType);
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