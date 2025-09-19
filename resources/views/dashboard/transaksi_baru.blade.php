<!-- Recent Transactions -->
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Transaksi Terbaru</h3>
            <a href="{{ route('transactions.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                Lihat Semua
            </a>
        </div>

        @php
            $recentTransactions = auth()->user()->transactions()->with('category')->latest()->take(5)->get();
        @endphp

        @if ($recentTransactions->count() > 0)
            <div class="space-y-3">
                @foreach ($recentTransactions as $transaction)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-3 h-3 rounded-full mr-3"
                                style="background-color: {{ $transaction->category->color }}"></div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $transaction->description }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->category->name }} •
                                    {{ $transaction->transaction_date->format('d/m/Y') }}</p>
                            </div>
                        </div>
                        <span
                            class="font-semibold {{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }} amount-value" data-original="{{ $transaction->amount_with_sign }}">
                            {{ $transaction->amount_with_sign }}
                        </span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Belum ada transaksi</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Mulai dengan menambahkan transaksi pertama
                    Anda.</p>
                <div class="mt-6">
                    <a href="{{ route('transactions.create') }}"
                        class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200"
                        title="Tambah Transaksi">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah Transaksi
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
