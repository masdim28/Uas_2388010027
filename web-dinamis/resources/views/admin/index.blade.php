@extends('layouts.admin')

@section('content')
    {{-- 1. Ringkasan Bisnis --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-emerald-400 hover:shadow-md transition">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Produk Ready</p>
            <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $readyStockCount }}</h3>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-red-400 hover:shadow-md transition">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Sold Out</p>
            <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $soldOutCount }}</h3>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-indigo-600 hover:shadow-md transition">
            <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total User</p>
            <h3 class="text-3xl font-black text-gray-800 mt-1">{{ $totalUser }}</h3>
        </div>
        
        <div class="bg-white p-6 rounded-2xl shadow-sm border-b-4 border-[#CFB53B] hover:shadow-md transition">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-xl font-black text-[#CFB53B] mt-2" id="revenueCardValue">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                </div>
                <select id="revenueYearFilter" class="bg-indigo-50/50 border border-indigo-100/50 text-indigo-950 px-2.5 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider focus:ring-1 focus:ring-[#CFB53B] outline-none cursor-pointer transition hover:border-[#CFB53B]">
                    <option value="all" selected>Semua</option>
                    @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
    </div>

    {{-- 2. Analisa Penjualan (Chart) --}}
    <div class="bg-white p-8 rounded-[2rem] border border-white shadow-[0_20px_50px_rgba(0,0,0,0.02)] mb-10">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-6">
            <div>
                <h2 class="text-2xl font-black text-indigo-950 tracking-tighter uppercase italic leading-none">Analisa Penjualan</h2>
                <div class="flex flex-wrap items-center gap-2 mt-1">
                    <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-widest">Grafik interaktif kinerja penjualan butik</p>
                    <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full uppercase tracking-wider border border-emerald-100/50 shadow-sm" id="periodRevenueLabel">Rp 0</span>
                </div>
            </div>
            
            {{-- Navigation and Filter Container --}}
            <div class="flex flex-wrap items-center gap-4">
                {{-- Period Navigator --}}
                <div class="flex items-center gap-3 bg-indigo-50/40 border border-indigo-100/50 px-4 py-2 rounded-2xl shadow-inner">
                    <button id="prevPeriodBtn" class="text-indigo-950 hover:text-[#CFB53B] transition-all p-1 hover:bg-white rounded-xl active:scale-90 shadow-sm border border-transparent hover:border-indigo-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <span id="activePeriodLabel" class="text-xs font-black text-indigo-950 uppercase tracking-widest min-w-[125px] text-center italic font-serif">
                        Loading...
                    </span>
                    <button id="nextPeriodBtn" class="text-indigo-950 hover:text-[#CFB53B] transition-all p-1 hover:bg-white rounded-xl active:scale-90 shadow-sm border border-transparent hover:border-indigo-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                {{-- Filter Buttons --}}
                <div class="flex bg-gray-100/70 p-1 rounded-2xl border border-gray-200/50 shadow-inner">
                    <button data-filter="1bln" class="filter-btn px-4 py-2 text-[10px] font-black uppercase rounded-xl transition duration-300">1 Bln</button>
                    <button data-filter="3bln" class="filter-btn px-4 py-2 text-[10px] font-black uppercase rounded-xl transition duration-300">3 Bln</button>
                    <button data-filter="1thn" class="filter-btn px-4 py-2 text-[10px] font-black uppercase rounded-xl transition duration-300">1 Thn</button>
                </div>
            </div>
        </div>
        <div class="h-72">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- 3. Statistik Produk --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        <div class="bg-white p-6 rounded-3xl shadow-sm">
            <h3 onclick="openReportModal('checkout')" class="text-lg font-bold text-gray-800 mb-6 border-l-4 border-indigo-600 pl-4 uppercase tracking-tighter cursor-pointer hover:text-[#CFB53B] hover:border-[#CFB53B] transition-all flex items-center justify-between group">
                <span>5 Terlaris (Checkout)</span>
                <span class="text-[10px] font-black text-indigo-400 group-hover:text-[#CFB53B] uppercase tracking-wider normal-case italic">Lihat Semua →</span>
            </h3>
            <div class="space-y-4">
                @foreach($topCheckout as $product)
                <div class="flex items-center justify-between p-3 hover:bg-[#F1FBFD] rounded-2xl transition">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/'.$product->image) }}" class="w-12 h-12 rounded-xl object-cover shadow-sm">
                        <div>
                            <p class="text-sm font-bold text-gray-800">{{ $product->name }}</p>
                            <p class="text-[10px] text-gray-400 uppercase tracking-widest">Total Terjual</p>
                        </div>
                    </div>
                    <span class="text-sm font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">{{ $product->total_sold }} Pcs</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl shadow-sm">
            <h3 onclick="openReportModal('clicks')" class="text-lg font-bold text-gray-800 mb-6 border-l-4 border-[#CFB53B] pl-4 uppercase tracking-tighter cursor-pointer hover:text-indigo-950 hover:border-indigo-950 transition-all flex items-center justify-between group">
                <span>3 Paling Sering Dilirik (Klik)</span>
                <span class="text-[10px] font-black text-[#CFB53B] group-hover:text-indigo-950 uppercase tracking-wider normal-case italic">Lihat Semua →</span>
            </h3>
            <div class="space-y-6">
                @foreach($mostClicked as $index => $product)
                <div class="relative group overflow-hidden rounded-2xl bg-gray-50 flex items-center">
                    <div class="bg-[#CFB53B] text-white text-[10px] font-bold px-3 py-10 rounded-r-xl">#{{ $index + 1 }}</div>
                    <img src="{{ asset('storage/'.$product->image) }}" class="w-20 h-20 object-cover ml-4">
                    <div class="p-4 flex-1">
                        <p class="text-sm font-bold text-gray-800 uppercase tracking-tight">{{ $product->name }}</p>
                        <p class="text-xs font-bold text-[#CFB53B] mt-1">{{ number_format($product->clicks) }} Pengunjung</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- 4. Laporan Produk Terjual Perbulannya --}}
    <div class="bg-white p-8 rounded-[2rem] border border-white shadow-[0_20px_50px_rgba(0,0,0,0.02)] mb-10">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-6">
            <div>
                <h2 class="text-2xl font-black text-indigo-950 tracking-tighter uppercase italic leading-none">Laporan Produk Terjual</h2>
                <p class="text-[10px] text-indigo-300 font-bold uppercase tracking-widest mt-1">Rincian penjualan dan pendapatan per produk bulanan</p>
            </div>
            
            <div class="flex items-center gap-2">
                <select id="reportMonthSelect" class="bg-indigo-50/50 border border-indigo-100/50 text-indigo-950 px-4 py-2.5 rounded-2xl text-xs font-bold focus:ring-1 focus:ring-[#CFB53B] outline-none">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ str_pad($m, 2, '0', STR_PAD_LEFT) }}" {{ $m == date('m') ? 'selected' : '' }}>
                            {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                        </option>
                    @endfor
                </select>
                <select id="reportYearSelect" class="bg-indigo-50/50 border border-indigo-100/50 text-indigo-950 px-4 py-2.5 rounded-2xl text-xs font-bold focus:ring-1 focus:ring-[#CFB53B] outline-none">
                    @for($y = date('Y') - 2; $y <= date('Y') + 2; $y++)
                        <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-indigo-50/30 text-indigo-900 uppercase text-[10px] font-black tracking-[0.25em]">
                        <th class="px-6 py-4 text-center w-16">No</th>
                        <th class="px-6 py-4">Produk</th>
                        <th class="px-6 py-4 text-center">Jumlah Clicks</th>
                        <th class="px-6 py-4 text-center">Total Terjual</th>
                        <th class="px-6 py-4 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody id="monthlyReportTableBody" class="divide-y divide-gray-50">
                    <!-- Injected dynamically via JS -->
                </tbody>
            </table>
        </div>
    </div>

    {{-- 5. Modal Report --}}
    <div id="reportModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-6">
            <div class="relative transform overflow-hidden rounded-[2rem] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-white">
                <div class="bg-indigo-950 px-8 py-6 text-white flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-serif font-black tracking-tight uppercase italic text-[#CFB53B]" id="modalTitle">Laporan Produk</h3>
                        <p class="text-[9px] text-indigo-300 font-bold uppercase tracking-widest mt-1" id="modalSubTitle">Daftar produk terurut</p>
                    </div>
                    <button type="button" onclick="closeReportModal()" class="text-indigo-200 hover:text-white hover:bg-indigo-900/50 p-2 rounded-xl transition duration-200 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="bg-[#F1FBFD]/20 px-8 py-6 max-h-[60vh] overflow-y-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-indigo-100 text-[10px] font-black uppercase tracking-wider text-indigo-900">
                                <th class="pb-3 text-center w-12">No</th>
                                <th class="pb-3 pl-4">Produk</th>
                                <th class="pb-3 text-center" id="modalColHeader">Metrik</th>
                            </tr>
                        </thead>
                        <tbody id="modalTableBody" class="divide-y divide-indigo-50/50">
                            <!-- Injected dynamically via JS -->
                        </tbody>
                    </table>
                </div>
                <div class="bg-indigo-50/30 px-8 py-4 flex justify-end">
                    <button type="button" onclick="closeReportModal()" class="bg-indigo-950 hover:bg-indigo-900 text-white px-6 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Premium Chart Gradient Fill
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0.00)');

        // Chart Initialization
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Pendapatan',
                    data: [],
                    borderColor: '#4f46e5',
                    backgroundColor: gradient,
                    borderWidth: 4,
                    tension: 0.45,
                    fill: true,
                    pointBackgroundColor: '#CFB53B',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#CFB53B',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e1b4b',
                        titleFont: { size: 10, weight: 'bold', family: 'Inter' },
                        bodyFont: { size: 12, weight: 'black', family: 'Inter' },
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 16,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(context.raw);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(243, 244, 246, 0.8)', drawBorder: false },
                        ticks: {
                            font: { size: 9, weight: 'bold' },
                            color: '#94a3b8',
                            callback: function(value) {
                                if (value >= 1000000) return (value / 1000000) + 'M';
                                if (value >= 1000) return (value / 1000) + 'k';
                                return value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { size: 9, weight: 'black' }, color: '#94a3b8' }
                    }
                }
            }
        });

        // Dynamic API State
        let currentFilter = '1bln';
        let currentDate = new Date().toISOString().slice(0, 10);

        // Fetch Data Function
        function updateChartData() {
            fetch(`{{ route('admin.dashboard.chart') }}?filter=${currentFilter}&date=${currentDate}`)
                .then(res => res.json())
                .then(data => {
                    // Update Period Label & Revenue Label
                    document.getElementById('activePeriodLabel').textContent = data.period_label;
                    document.getElementById('periodRevenueLabel').textContent = data.total_revenue_formatted;
                    currentDate = data.current_date;

                    // Update Active Button Styling
                    document.querySelectorAll('.filter-btn').forEach(btn => {
                        const isSelected = btn.getAttribute('data-filter') === currentFilter;
                        if (isSelected) {
                            btn.className = "filter-btn px-4 py-2 text-[10px] font-black uppercase rounded-xl bg-white text-indigo-950 shadow-sm border border-gray-200/30";
                        } else {
                            btn.className = "filter-btn px-4 py-2 text-[10px] font-black uppercase rounded-xl text-gray-400 hover:text-indigo-950 transition duration-300";
                        }
                    });

                    // Update Chart Data
                    salesChart.data.labels = data.labels;
                    salesChart.data.datasets[0].data = data.data;
                    salesChart.update();
                })
                .catch(err => console.error("Error fetching chart data:", err));
        }

        // Shift Date function
        function shiftPeriod(direction) {
            let date = new Date(currentDate);
            if (currentFilter === '1bln') {
                date.setMonth(date.getMonth() + (direction * 1));
            } else if (currentFilter === '3bln') {
                date.setMonth(date.getMonth() + (direction * 3));
            } else if (currentFilter === '1thn') {
                date.setFullYear(date.getFullYear() + (direction * 1));
            }
            let y = date.getFullYear();
            let m = String(date.getMonth() + 1).padStart(2, '0');
            let d = String(date.getDate()).padStart(2, '0');
            currentDate = `${y}-${m}-${d}`;
            updateChartData();
        }

        // Event Listeners for Filters
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                currentFilter = this.getAttribute('data-filter');
                updateChartData();
            });
        });

        // Event Listeners for Period Navigation
        document.getElementById('prevPeriodBtn').addEventListener('click', () => shiftPeriod(-1));
        document.getElementById('nextPeriodBtn').addEventListener('click', () => shiftPeriod(1));

        // Global Modal functions
        window.openReportModal = function(sortBy) {
            const modal = document.getElementById('reportModal');
            const title = document.getElementById('modalTitle');
            const subTitle = document.getElementById('modalSubTitle');
            const colHeader = document.getElementById('modalColHeader');
            const tbody = document.getElementById('modalTableBody');

            tbody.innerHTML = '<tr><td colspan="3" class="py-10 text-center text-xs font-bold text-gray-400 uppercase italic">Sedang memuat data...</td></tr>';
            modal.classList.remove('hidden');

            if (sortBy === 'clicks') {
                title.textContent = 'Semua Produk Sering Dilirik';
                subTitle.textContent = 'Daftar produk terurut berdasarkan jumlah klik pengunjung';
                colHeader.textContent = 'Jumlah Klik';
            } else {
                title.textContent = 'Semua Produk Terlaris';
                subTitle.textContent = 'Daftar produk terurut berdasarkan kuantitas checkout (terjual)';
                colHeader.textContent = 'Kuantitas Terjual';
            }

            fetch(`{{ route('admin.dashboard.products-report') }}?sort_by=${sortBy}`)
                .then(res => res.json())
                .then(products => {
                    tbody.innerHTML = '';
                    if (products.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="3" class="py-10 text-center text-xs text-gray-400">Tidak ada produk ditemukan.</td></tr>';
                        return;
                    }

                    products.forEach((prod, index) => {
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-indigo-50/20 transition-colors';
                        
                        const metric = sortBy === 'clicks' ? prod.clicks_formatted : prod.total_sold_formatted;
                        
                        tr.innerHTML = `
                            <td class="py-4 text-center text-xs font-bold text-indigo-950">${index + 1}</td>
                            <td class="py-4 pl-4 flex items-center gap-3">
                                <img src="${prod.image_url}" class="w-10 h-10 object-cover rounded-xl shadow-sm border border-white" onerror="this.src='/images/placeholder.png'">
                                <span class="text-xs font-black text-indigo-950 uppercase tracking-tight">${prod.name}</span>
                            </td>
                            <td class="py-4 text-center">
                                <span class="text-xs font-black px-3 py-1 rounded-full ${sortBy === 'clicks' ? 'bg-[#CFB53B]/10 text-[#CFB53B]' : 'bg-indigo-50 text-indigo-600'}">${metric}</span>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => {
                    console.error("Error loading products report:", err);
                    tbody.innerHTML = '<tr><td colspan="3" class="py-10 text-center text-xs text-red-500 font-bold">Gagal memuat data.</td></tr>';
                });
        };

        window.closeReportModal = function() {
            document.getElementById('reportModal').classList.add('hidden');
        };

        // Monthly Sales Report
        window.updateMonthlyReport = function() {
            const m = document.getElementById('reportMonthSelect').value;
            const y = document.getElementById('reportYearSelect').value;
            const tbody = document.getElementById('monthlyReportTableBody');

            tbody.innerHTML = '<tr><td colspan="5" class="py-10 text-center text-xs font-bold text-gray-400 uppercase italic">Sedang memuat data...</td></tr>';

            fetch(`{{ route('admin.dashboard.monthly-sales-report') }}?month=${m}&year=${y}`)
                .then(res => res.json())
                .then(sales => {
                    tbody.innerHTML = '';
                    if (sales.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="py-16 text-center"><p class="text-xs text-indigo-300 font-black uppercase tracking-widest italic">Tidak ada produk terjual pada periode ini</p></td></tr>';
                        return;
                    }

                    sales.forEach((item, index) => {
                        const tr = document.createElement('tr');
                        tr.className = 'hover:bg-[#F1FBFD]/40 transition-colors group';
                        tr.innerHTML = `
                            <td class="px-6 py-4 text-center">
                                <span class="font-black text-indigo-950 tracking-tighter text-sm">#${index + 1}</span>
                            </td>
                            <td class="px-6 py-4 flex items-center gap-3">
                                <img src="${item.image_url}" class="w-10 h-10 object-cover rounded-xl shadow-sm border border-white" onerror="this.src='/images/placeholder.png'">
                                <span class="text-xs font-black text-indigo-950 uppercase tracking-tight">${item.name}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-bold text-gray-400">${item.clicks} Klik</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-xs font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">${item.total_sold_formatted}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="text-sm font-black text-emerald-600">${item.total_revenue_formatted}</span>
                            </td>
                        `;
                        tbody.appendChild(tr);
                    });
                })
                .catch(err => {
                    console.error("Error loading monthly report:", err);
                    tbody.innerHTML = '<tr><td colspan="5" class="py-10 text-center text-xs text-red-500 font-bold">Gagal memuat data laporan.</td></tr>';
                });
        };

        // Event Listeners for Monthly Report selectors
        document.getElementById('reportMonthSelect').addEventListener('change', window.updateMonthlyReport);
        document.getElementById('reportYearSelect').addEventListener('change', window.updateMonthlyReport);

        // Yearly Revenue Filter dynamic AJAX
        const revenueYearFilter = document.getElementById('revenueYearFilter');
        const revenueCardValue = document.getElementById('revenueCardValue');

        function updateRevenueCard() {
            const year = revenueYearFilter.value;
            revenueCardValue.innerHTML = '<span class="text-xs font-bold text-gray-300 uppercase animate-pulse">Memuat...</span>';
            
            fetch(`{{ route('admin.dashboard.revenue-report') }}?year=${year}`)
                .then(res => res.json())
                .then(data => {
                    revenueCardValue.textContent = data.total_revenue_formatted;
                })
                .catch(err => {
                    console.error("Error fetching revenue:", err);
                    revenueCardValue.textContent = 'Gagal memuat';
                });
        }

        if (revenueYearFilter) {
            revenueYearFilter.addEventListener('change', updateRevenueCard);
        }

        // Initial Load
        updateChartData();
        window.updateMonthlyReport();
    });
</script>
@endpush