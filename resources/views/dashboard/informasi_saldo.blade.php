<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-full">
                    <i class="fas fa-arrow-up text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pemasukan</p>
                    <p class="text-2xl font-semibold text-green-600">Rp
                        {{ number_format($totalIncome, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-full">
                    <i class="fas fa-arrow-down text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengeluaran</p>
                    <p class="text-2xl font-semibold text-red-600">Rp
                        {{ number_format($totalExpense, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-full">
                    <i class="fas fa-wallet text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Saldo</p>
                    <p class="text-2xl font-semibold {{ $balance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        Rp {{ number_format($balance, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
