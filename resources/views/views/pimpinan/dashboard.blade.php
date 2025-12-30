@extends('views.layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white mb-6">
        <h2 class="text-2xl font-bold">Dashboard</h2>
        <p class="text-sm opacity-90">Dashboard Pimpinan</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        <x-dashboard-box>
            <div
                class="flex items-center p-5 bg-white rounded-xl shadow border-l-4 border-blue-500 hover:shadow-lg transition">
                <div class="p-3 bg-blue-100 rounded-full mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M3 3h18v6H3zM3 13h18v8H3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Batch Aktif</p>
                    <p class="text-2xl font-bold">{{ $totalBatch }}</p>

                </div>
            </div>
        </x-dashboard-box>

        <x-dashboard-box>
            <div
                class="flex items-center p-5 bg-white rounded-xl shadow border-l-4 border-green-500 hover:shadow-lg transition">
                <div class="p-3 bg-green-100 rounded-full mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Stok Sekarang</p>
                    <p class="text-2xl font-bold">{{ $totalStok }}</p>

                </div>
            </div>
        </x-dashboard-box>

        <x-dashboard-box>
            <div
                class="flex items-center p-5 bg-white rounded-xl shadow border-l-4 border-yellow-500 hover:shadow-lg transition">
                <div class="p-3 bg-yellow-100 rounded-full mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path d="M20 12H4" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Stok Keluar</p>
                    <p class="text-2xl font-bold">{{ $totalKeluar }}</p>

                </div>
            </div>
        </x-dashboard-box>

        <x-dashboard-box>
            <div
                class="flex items-center p-5 bg-white rounded-xl shadow border-l-4 border-red-500 hover:shadow-lg transition">
                <div class="p-3 bg-red-100 rounded-full mr-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-triangle-alert-icon lucide-triangle-alert text-red-600">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Akan Kadaluarsa</p>
                    <p class="text-2xl font-bold text-red-600"> {{ $notifications->count() }}</p>
                </div>
            </div>
        </x-dashboard-box>

    </div>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-1">Analisis Kinerja Stok (FEFO)</h3>
        <p class="text-sm text-gray-500 mb-4">
            Grafik menunjukkan kecepatan pergerakan stok untuk mencegah kadaluarsa.
        </p>

        <div class="w-full overflow-x-auto">
            <canvas id="fefoChart" class="w-full" style="height: 300px;"></canvas>
        </div>


        <div class="text-center mt-4">
            <a href="{{ route('pimpinan.laporan-audit.index') }}" class="text-blue-600 text-sm hover:underline">
                Lihat Laporan Detail
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const stokKeluarRaw = @json(isset($stokKeluarBulanan) ? $stokKeluarBulanan : []);
        const stokExpiredRaw = @json(isset($stokExpiredBulanan) ? $stokExpiredBulanan : []);

        const periodeSet = new Set(
            Object.keys(stokKeluarRaw).concat(Object.keys(stokExpiredRaw))
        );

        if (periodeSet.size === 0) {
            const now = new Date();
            const y = now.getFullYear();
            const m = String(now.getMonth() + 1).padStart(2, '0');
            periodeSet.add(y + '-' + m);
        }


        const periodeList = Array.from(periodeSet).sort(function(a, b) {
            return new Date(a + '-01') - new Date(b + '-01');
        });


        const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

        const labels = periodeList.map(function(p) {
            const parts = p.split('-');
            return monthNames[parseInt(parts[1]) - 1] + ' ' + parts[0];
        });


        const stokKeluarData = periodeList.map(function(p) {
            return stokKeluarRaw[p] || 0;
        });

        const stokExpiredData = periodeList.map(function(p) {
            return stokExpiredRaw[p] || 0;
        });


        const ctx = document.getElementById('fefoChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                        label: 'Stok Keluar',
                        data: stokKeluarData,
                        borderWidth: 3,
                        tension: 0.4,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59,130,246,0.15)',
                        fill: true
                    },
                    {
                        label: 'Stok Hampir Kadaluarsa (≤30 hari)',
                        data: stokExpiredData,
                        borderDash: [5, 5],
                        borderWidth: 2,
                        tension: 0.4,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239,68,68,0.15)',
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
@endsection
