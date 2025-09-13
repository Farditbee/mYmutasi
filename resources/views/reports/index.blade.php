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
                    <a href="{{ route('transactions.index') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                        <i class="fas fa-list mr-2"></i>Lihat Transaksi
                    </a>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex items-center space-x-4">
                    <label for="monthFilter" class="text-sm font-medium text-gray-700">Filter Bulan:</label>
                    <select id="monthFilter"
                        class="border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @php
                            $currentSelectedMonth = $selectedMonth ?? date('Y-m');
                        @endphp
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $month = date('Y') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                $monthName = date('F Y', strtotime($month . '-01'));
                            @endphp
                            <option value="{{ $month }}" {{ $currentSelectedMonth == $month ? 'selected' : '' }}>
                                {{ $monthName }}</option>
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
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Tren Harian Bulan {{ now()->format('F Y') }}
                        ({{ now()->daysInMonth }} Hari)</h3>
                    <div id="monthlyDailyChart"></div>
                </div>

                <!-- 12 Months Comparison Chart -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Perbandingan 12 Bulan Terakhir</h3>
                        <div class="flex items-center space-x-2">
                            <label for="yearFilter" class="text-sm font-medium text-gray-700">Filter Tahun:</label>
                            <select id="yearFilter"
                                class="border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @php
                                    $currentYear = date('Y');
                                    $selectedYear = $selectedYear ?? $currentYear;
                                @endphp
                                @for ($year = $currentYear - 2; $year <= $currentYear + 2; $year++)
                                    <option value="{{ $year }}" {{ $selectedYear == $year ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endfor
                            </select>
                            <button id="yearLoadingIndicator"
                                class="hidden bg-blue-500 text-white px-3 py-2 rounded-lg text-sm">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Memuat...
                            </button>
                        </div>
                    </div>
                    <div id="yearlyChart"></div>
                </div>

                <!-- Expense by Category Chart -->
                <div class="bg-white border border-gray-200 rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Pengeluaran per Kategori</h3>
                        <div class="flex items-center space-x-2">
                            <label for="categoryMonthFilter" class="text-sm font-medium text-gray-700">Filter
                                Bulan:</label>
                            <select id="categoryMonthFilter"
                                class="border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                @php
                                    $currentSelectedMonth = $selectedMonth ?? date('Y-m');
                                @endphp
                                @for ($i = 1; $i <= 12; $i++)
                                    @php
                                        $month = date('Y') . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
                                        $monthName = date('F Y', strtotime($month . '-01'));
                                    @endphp
                                    <option value="{{ $month }}"
                                        {{ $currentSelectedMonth == $month ? 'selected' : '' }}>{{ $monthName }}
                                    </option>
                                @endfor
                            </select>
                            <button id="categoryLoadingIndicator"
                                class="hidden bg-blue-500 text-white px-3 py-2 rounded-lg text-sm">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Memuat...
                            </button>
                        </div>
                    </div>
                    <div id="categoryChart"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        console.log('Reports page script loaded');
        console.log('Document ready state:', document.readyState);

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
        console.log('Yearly data from server:', yearlyData);

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
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function(val) {
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
                    formatter: function(val) {
                        return 'Rp ' + (val / 1000000).toFixed(0) + 'M';
                    }
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
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
                    console.log('Month filter response status:', response.status);
                    console.log('Month filter response headers:', response.headers);
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Month filter data received:', data);
                    console.log('Daily data:', data.dailyData);
                    console.log('Selected month name:', data.selectedMonthName);

                    if (data.dailyData && data.dailyData.series && data.dailyData.categories) {
                        // Update daily chart title
                        const dailyTitle = document.querySelector('#monthlyDailyChart').previousElementSibling;
                        dailyTitle.textContent =
                            `Tren Harian Bulan ${data.selectedMonthName} (${data.dailyData.categories.length} Hari)`;

                        console.log('Updating daily chart with:', {
                            series: data.dailyData.series,
                            categories: data.dailyData.categories
                        });

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
                    } else {
                        console.warn('Invalid daily data structure:', data);
                    }
                })
                .catch(error => {
                    console.error('Month filter error:', error);
                    console.error('Month filter error details:', {
                        message: error.message,
                        stack: error.stack,
                        name: error.name
                    });
                    alert('Terjadi kesalahan saat memuat data bulan. Silakan coba lagi.');
                })
                .finally(() => {
                    // Hide loading indicator
                    loadingIndicator.classList.add('hidden');
                });
        });

        // Initialize Category Chart
        let categoryChart;

        function initializeCategoryChart(data) {
            console.log('initializeCategoryChart called with:', data);

            // Process data for chart
            const chartData = data.map(item => {
                const total = parseFloat(item.total);
                console.log(`Category: ${item.category_name}, Total: ${total}`);
                return total;
            });
            const categories = data.map(item => item.category_name);

            console.log('Chart data:', chartData);
            console.log('Categories:', categories);

            const categoryOptions = {
                series: [{
                    name: 'Pengeluaran',
                    data: chartData
                }],
                chart: {
                    height: 400,
                    type: 'bar',
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#EF4444'],
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
                    categories: categories,
                    title: {
                        text: 'Kategori'
                    }
                },
                yaxis: {
                    title: {
                        text: 'Jumlah (Rp)'
                    },
                    labels: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            };

            if (categoryChart) {
                console.log('Destroying existing chart');
                categoryChart.destroy();
            }

            console.log('Creating new chart with options:', categoryOptions);
            categoryChart = new ApexCharts(document.querySelector('#categoryChart'), categoryOptions);

            categoryChart.render().then(() => {
                console.log('Chart rendered successfully');
            }).catch(error => {
                console.error('Error rendering chart:', error);
            });
        }

        // Load initial category data
        function loadCategoryData(month = null) {
            const currentMonth = month || document.getElementById('categoryMonthFilter').value;
            const loadingIndicator = document.getElementById('categoryLoadingIndicator');

            // Show loading indicator
            loadingIndicator.classList.remove('hidden');

            // Make AJAX request to getPengeluaranByCategory endpoint
            fetch(`{{ route('reports.pengeluaran-by-category') }}?bulan=${currentMonth}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers);
                    return response.json();
                })
                .then(data => {
                    console.log('Category data received:', data);
                    console.log('Data type:', typeof data);
                    console.log('Data length:', data.length);
                    if (data && data.length > 0) {
                        console.log('First item:', data[0]);
                        initializeCategoryChart(data);
                    } else {
                        console.warn('No category data received or empty array');
                        // Initialize empty chart
                        initializeCategoryChart([]);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    console.error('Error details:', {
                        message: error.message,
                        stack: error.stack,
                        name: error.name
                    });
                    // Initialize empty chart on error
                    initializeCategoryChart([]);
                    alert('Terjadi kesalahan saat memuat data kategori. Silakan coba lagi.');
                })
                .finally(() => {
                    // Hide loading indicator
                    loadingIndicator.classList.add('hidden');
                });
        }

        // Category Month Filter Handler
        document.getElementById('categoryMonthFilter').addEventListener('change', function() {
            loadCategoryData(this.value);
        });

        // Load initial category data on page load
        console.log('Loading initial category data...');
        loadCategoryData();



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
                .then(response => {
                    console.log('Year filter response status:', response.status);
                    console.log('Year filter response headers:', response.headers);
                    return response.json();
                })
                .then(data => {
                    console.log('Year filter data received:', data);
                    console.log('Yearly data:', data.yearlyData);

                    if (data.yearlyData && data.yearlyData.series && data.yearlyData.categories) {
                        console.log('Updating yearly chart with:', {
                            series: data.yearlyData.series,
                            categories: data.yearlyData.categories
                        });

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
                    } else {
                        console.warn('Invalid yearly data structure:', data);
                    }
                })
                .catch(error => {
                    console.error('Year filter error:', error);
                    console.error('Year filter error details:', {
                        message: error.message,
                        stack: error.stack,
                        name: error.name
                    });
                    alert('Terjadi kesalahan saat memuat data tahun. Silakan coba lagi.');
                })
                .finally(() => {
                    // Hide loading indicator
                    loadingIndicator.classList.add('hidden');
                });
        });
    </script>
</x-app-layout>
