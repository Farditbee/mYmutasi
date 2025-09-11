<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Kategori') }}
            </h2>
            <a href="{{ route('categories.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form id="categoryForm" action="{{ route('categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Nama Kategori -->
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Nama Kategori <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}"
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                   placeholder="Masukkan nama kategori"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tipe Kategori -->
                        <div class="mb-4">
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Tipe Kategori <span class="text-red-500">*</span>
                            </label>
                            <select id="type" 
                                    name="type" 
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                    required>
                                <option value="">Pilih tipe kategori</option>
                                <option value="income" {{ old('type', $category->type) == 'income' ? 'selected' : '' }}>Pemasukan</option>
                                <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>Pengeluaran</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Deskripsi
                            </label>
                            <textarea id="description" 
                                      name="description" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white"
                                      placeholder="Masukkan deskripsi kategori (opsional)">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>



                        <!-- Status Aktif -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active" 
                                       value="1"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <label for="is_active" class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Kategori aktif
                                </label>
                            </div>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Info Transaksi -->
                        @if($category->transactions()->count() > 0)
                            <div class="mb-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-md">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-yellow-800 dark:text-yellow-200">
                                        <strong>Perhatian:</strong> Kategori ini memiliki {{ $category->transactions()->count() }} transaksi. 
                                        Perubahan dapat mempengaruhi data transaksi yang ada.
                                    </span>
                                </div>
                            </div>
                        @endif

                        <!-- Tombol Submit -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('categories.index') }}" 
                               class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 font-medium rounded-md transition duration-200">
                                Batal
                            </a>
                            <button type="button" 
                                    id="submitBtn"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition duration-200">
                                Update Kategori
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
        const submitBtn = document.getElementById('submitBtn');
        const form = document.getElementById('categoryForm');
        const hasTransactions = {{ $category->transactions()->count() > 0 ? 'true' : 'false' }};

        SweetAlertHelper.setupFormSubmission({
            form: form,
            submitBtn: submitBtn,
            fields: [
                { element: document.getElementById('name'), name: 'Nama kategori', required: true },
                { element: document.getElementById('type'), name: 'Tipe kategori', required: true }
            ],
            confirmation: {
                title: hasTransactions ? 'Perhatian!' : 'Konfirmasi',
                text: hasTransactions ? 
                    'Kategori ini memiliki transaksi. Perubahan dapat mempengaruhi data transaksi yang ada. Lanjutkan?' : 
                    'Apakah Anda yakin ingin mengupdate kategori ini?',
                action: 'update',
                hasWarning: hasTransactions
            }
        });
    });
    </script>
    @endpush
</x-app-layout>