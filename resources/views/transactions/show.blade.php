<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Transaksi') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('transactions.edit', $transaction) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <a href="{{ route('transactions.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Transaction Header -->
                    <div class="border-b border-gray-200 pb-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">{{ $transaction->description }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $transaction->transaction_date->format('d F Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $transaction->amount_with_sign }}
                                </p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->type == 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $transaction->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Details -->
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Category -->
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <div class="flex items-center">
                                <div class="w-4 h-4 rounded-full mr-3" style="background-color: {{ $transaction->category->color }}"></div>
                                <span class="text-sm font-medium text-gray-500">Kategori</span>
                            </div>
                            <span class="text-sm text-gray-900 font-medium">{{ $transaction->category->name }}</span>
                        </div>

                        <!-- Amount -->
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Jumlah</span>
                            <span class="text-sm text-gray-900 font-medium">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                        </div>

                        <!-- Type -->
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Tipe</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $transaction->type_label }}</span>
                        </div>

                        <!-- Transaction Date -->
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Tanggal Transaksi</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $transaction->transaction_date->format('d F Y') }}</span>
                        </div>

                        <!-- Description -->
                        <div class="flex items-start justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Deskripsi</span>
                            <span class="text-sm text-gray-900 font-medium text-right max-w-xs">{{ $transaction->description }}</span>
                        </div>

                        <!-- Notes -->
                        @if($transaction->notes)
                        <div class="flex items-start justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Catatan</span>
                            <span class="text-sm text-gray-900 text-right max-w-xs">{{ $transaction->notes }}</span>
                        </div>
                        @endif

                        <!-- Created At -->
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Dibuat</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $transaction->created_at->format('d F Y H:i') }}</span>
                        </div>

                        <!-- Updated At -->
                        @if($transaction->updated_at != $transaction->created_at)
                        <div class="flex items-center justify-between py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-500">Diperbarui</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $transaction->updated_at->format('d F Y H:i') }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex items-center justify-between">
                        <!-- Delete Button -->
                        <form action="{{ route('transactions.destroy', $transaction) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-500 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200" title="Hapus Transaksi">
                                <i class="fas fa-trash mr-2"></i>
                                Hapus
                            </button>
                        </form>
                        
                        <div class="flex space-x-3">
                            <!-- Edit Button -->
                            <a href="{{ route('transactions.edit', $transaction) }}" class="inline-flex items-center px-3 py-2 bg-blue-500 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200" title="Edit Transaksi">
                                <i class="fas fa-edit mr-2"></i>
                                Edit
                            </a>
                            <!-- Back Button -->
                            <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-3 py-2 bg-gray-500 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200" title="Kembali ke Daftar">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>