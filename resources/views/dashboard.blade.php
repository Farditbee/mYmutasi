<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center space-x-3">
                <button id="privacyToggle"
                    class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200"
                    title="Sensor Nominal">
                    <i id="privacyIcon" class="fas fa-eye mr-2"></i>
                    <span id="privacyText"></span>
                </button>
                <a href="{{ route('transactions.create') }}"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Tambah Transaksi
                </a>
            </div>
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

    <!-- Privacy Toggle Styles and Script -->
    <style>
        .amount-blurred {
            filter: blur(9px);
            transition: filter 0.3s ease;
            user-select: none;
        }

        .amount-hidden {
            color: transparent !important;
            text-shadow: 0 0 8px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const privacyToggle = document.getElementById('privacyToggle');
            const privacyIcon = document.getElementById('privacyIcon');
            const privacyText = document.getElementById('privacyText');
            const amountElements = document.querySelectorAll('.amount-value');

            let isPrivacyMode = false;

            // Load privacy state from localStorage
            const savedPrivacyState = localStorage.getItem('dashboardPrivacyMode');
            if (savedPrivacyState === 'true') {
                togglePrivacyMode();
            }

            privacyToggle.addEventListener('click', function() {
                togglePrivacyMode();
            });

            function togglePrivacyMode() {
                isPrivacyMode = !isPrivacyMode;

                amountElements.forEach(function(element) {
                    if (isPrivacyMode) {
                        element.classList.add('amount-blurred');
                    } else {
                        element.classList.remove('amount-blurred');
                        element.textContent = element.getAttribute('data-original');
                    }
                });

                // Update button appearance
                if (isPrivacyMode) {
                    privacyIcon.className = 'fas fa-eye-slash mr-2';
                    privacyText.textContent = '';
                    privacyToggle.className =
                        'flex items-center px-3 py-2 text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors duration-200';
                } else {
                    privacyIcon.className = 'fas fa-eye mr-2';
                    privacyText.textContent = '';
                    privacyToggle.className =
                        'flex items-center px-3 py-2 text-sm font-medium text-gray-600 hover:text-gray-800 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors duration-200';
                }

                // Save privacy state to localStorage
                localStorage.setItem('dashboardPrivacyMode', isPrivacyMode.toString());
            }
        });
    </script>
</x-app-layout>
