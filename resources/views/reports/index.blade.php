<x-app-layout>
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <div class="container mx-auto px-4 py-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Laporan Keuangan</h1>
                    <p class="text-gray-600 mt-1">Analisis dan statistik transaksi keuangan</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('transactions.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-list mr-2"></i>Lihat Transaksi
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <label for="monthFilter" class="text-sm font-medium text-gray-700">Filter Bulan:</label>
                    <select id="monthFilter" class="border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @php
                        $currentSelectedMonth = $selectedMonth ?? date('Y-m');
                    @endphp
                    @for($i = 1; $i <= 12; $i++)
                        @php
                            $month = date('Y') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                            $monthName = date('F Y', strtotime($month . '-01'));
                        @endphp
                        <option value="{{ $month }}" {{ $currentSelectedMonth == $month ? 'selected' : '' }}>{{ $monthName }}</option>
                    @endfor
                </select>
                    <button id="loadingIndicator" class="hidden text-blue-500">
                        <i class="fas fa-spinner fa-spin mr-2"></i>Memuat...
                    </button>
                </div>
            </div>

            <!-- Charts Section -->
             <div class="grid grid-cols-1 gap-6 mb-8">
                 <!-- Monthly Daily Chart -->
                 <div class="bg-white border border-gray-200 rounded-lg p-6">
                     <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Harian Bulan {{ now()->format('F Y') }} ({{ now()->daysInMonth }} Hari)</h3>
                     <div id="monthlyDailyChart"></div>
                 </div>

                 <!-- 12 Months Comparison Chart -->
                 <div class="bg-white border border-gray-200 rounded-lg p-6">
                     <div class="flex justify-between items-center mb-4">
                         <h3 class="text-lg font-semibold text-gray-800">Perbandingan 12 Bulan Terakhir</h3>
                         <div class="flex items-center space-x-2">
                             <label for="yearFilter" class="text-sm font-medium text-gray-700">Filter Tahun:</label>
                             <select id="yearFilter" class="border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                 @php
                                     $currentYear = date('Y');
                                     $selectedYear = $selectedYear ?? $currentYear;
                                 @endphp
                                 @for($year = $currentYear - 2; $year <= $currentYear + 2; $year++)
                                     <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>{{ $year }}</option>
                                 @endfor
                             </select>
                             <button id="yearLoadingIndicator" class="hidden bg-blue-500 text-white px-3 py-2 rounded-lg text-sm">
                                 <i class="fas fa-spinner fa-spin mr-2"></i>Memuat...
                             </button>
                         </div>
                     </div>
                     <div id="yearlyChart"></div>
                 </div>
             </div>
        </div>
    </div>

    <script>
          // Dynamic data from backend
          const monthlyDailyData = {
              categories: {!! json_encode($monthlyDailyData->pluck('date')) !!},
              income: {!! json_encode($monthlyDailyData->pluck('income')) !!},
              expense: {!! json_encode($monthlyDailyData->pluck('expense')) !!}
          };
  
          const yearlyData = {
              categories: {!! json_encode($last12Months->pluck('month')) !!},
              income: {!! json_encode($last12Months->pluck('income')) !!},
              expense: {!! json_encode($last12Months->pluck('expense')) !!}
          };

        // Monthly Daily Chart (30/31 days)
         const monthlyDailyOptions = {
             series: [{
                 name: 'Pemasukan',
                 data: monthlyDailyData.income
             }, {
                 name: 'Pengeluaran',
                 data: monthlyDailyData.expense
             }],
             chart: {
                 height: 400,
                 type: 'line',
                 toolbar: {
                     show: false
                 }
             },
             colors: ['#10B981', '#EF4444', '#8B5CF6'],
             dataLabels: {
                 enabled: false
             },
             stroke: {
                 curve: 'smooth',
                 width: 3
             },
             xaxis: {
                 categories: monthlyDailyData.categories,
                 title: {
                     text: 'Tanggal'
                 }
             },
             yaxis: {
                 labels: {
                     formatter: function (val) {
                         return 'Rp ' + val.toLocaleString('id-ID');
                     }
                 }
             },
             tooltip: {
                 y: {
                     formatter: function (val) {
                         return 'Rp ' + val.toLocaleString('id-ID');
                     }
                 }
             },
             legend: {
                 position: 'top'
             },
             grid: {
                 borderColor: '#e7e7e7',
                 row: {
                     colors: ['#f3f3f3', 'transparent'],
                     opacity: 0.5
                 }
             }
         };

         const monthlyDailyChart = new ApexCharts(document.querySelector("#monthlyDailyChart"), monthlyDailyOptions);
         monthlyDailyChart.render();

         // Yearly Comparison Chart (12 months)
         const yearlyOptions = {
             series: [{
                 name: 'Pemasukan',
                 data: yearlyData.income
             }, {
                 name: 'Pengeluaran',
                 data: yearlyData.expense
             }],
             chart: {
                 height: 400,
                 type: 'bar',
                 toolbar: {
                     show: false
                 }
             },
             colors: ['#3B82F6', '#F59E0B'],
             plotOptions: {
                 bar: {
                     horizontal: false,
                     columnWidth: '55%',
                     endingShape: 'rounded'
                 }
             },
             dataLabels: {
                 enabled: false
             },
             stroke: {
                 show: true,
                 width: 2,
                 colors: ['transparent']
             },
             xaxis: {
                 categories: yearlyData.categories,
                 title: {
                     text: 'Bulan'
                 }
             },
             yaxis: {
                 labels: {
                     formatter: function (val) {
                         return 'Rp ' + (val / 1000000).toFixed(0) + 'M';
                     }
                 }
             },
             fill: {
                 opacity: 1
             },
             tooltip: {
                 y: {
                     formatter: function (val) {
                         return 'Rp ' + val.toLocaleString('id-ID');
                     }
                 }
             },
             legend: {
                 position: 'top'
             },
             grid: {
                 borderColor: '#e7e7e7',
                 row: {
                     colors: ['#f3f3f3', 'transparent'],
                     opacity: 0.5
                 }
             }
         };

         const yearlyChart = new ApexCharts(document.querySelector("#yearlyChart"), yearlyOptions);
        yearlyChart.render();

        // Month Filter Handler
        document.getElementById('monthFilter').addEventListener('change', function() {
            const selectedMonth = this.value;
            const loadingIndicator = document.getElementById('loadingIndicator');
            
            // Show loading indicator
            loadingIndicator.classList.remove('hidden');
            
            // Make AJAX request for JSON data
            fetch(`{{ route('reports.index') }}?month=${selectedMonth}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update daily chart title
                const dailyTitle = document.querySelector('#monthlyDailyChart').previousElementSibling;
                dailyTitle.textContent = `Tren Harian Bulan ${data.selectedMonthName} (${data.dailyData.categories.length} Hari)`;
                
                // Update daily chart data
                monthlyDailyChart.updateOptions({
                    series: data.dailyData.series,
                    xaxis: {
                        categories: data.dailyData.categories,
                        title: {
                            text: 'Tanggal'
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data. Silakan coba lagi.');
            })
            .finally(() => {
                // Hide loading indicator
                loadingIndicator.classList.add('hidden');
            });
        });

        // Year Filter Handler
        document.getElementById('yearFilter').addEventListener('change', function() {
            const selectedYear = this.value;
            const loadingIndicator = document.getElementById('yearLoadingIndicator');
            
            // Show loading indicator
            loadingIndicator.classList.remove('hidden');
            
            // Make AJAX request
            fetch(`{{ route('reports.index') }}?year=${selectedYear}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Update yearly chart
                yearlyChart.updateOptions({
                    series: data.yearlyData.series,
                    xaxis: {
                        categories: data.yearlyData.categories,
                        title: {
                            text: 'Bulan'
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data tahun. Silakan coba lagi.');
            })
            .finally(() => {
                // Hide loading indicator
                loadingIndicator.classList.add('hidden');
            });
        });
    </script>
</x-app-layout>