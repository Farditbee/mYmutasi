<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('transactions.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Tambah Transaksi
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Selamat datang di MyMutasi!</h3>
                    <p class="text-gray-600 dark:text-gray-400">Kelola catatan pemasukan dan pengeluaran Anda dengan
                        mudah.</p>
                </div>
            </div>

            @include('dashboard.informasi_saldo')

            @include('dashboard.menu_cepat')

            @include('dashboard.transaksi_baru')
        </div>
    </div>
</x-app-layout>
