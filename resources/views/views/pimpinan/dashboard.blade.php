@extends('views.layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Dashboard Utama (Tampilan Pimpinan)</h2>
    </div>

    <!-- Grid dashboard boxes responsif -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-6">
        <x-dashboard-box>Box 1</x-dashboard-box>
        <x-dashboard-box>Box 2</x-dashboard-box>
        <x-dashboard-box>Box 3</x-dashboard-box>
        <x-dashboard-box>Box 4</x-dashboard-box>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-1">Analisis Kinerja Stok (FEFO)</h3>
        <p class="text-sm text-gray-500 mb-4">
            Grafik menunjukkan kecepatan pergerakan stok untuk mencegah kadaluarsa.
        </p>

        <!-- Container chart responsif -->
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
        const ctx = document.getElementById('fefoChart').getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                        label: 'Stok Keluar',
                        data: [30, 45, 40, 60, 55, 70],
                        borderWidth: 3,
                        tension: 0.4,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    },
                    {
                        label: 'Stok Hampir Expired',
                        data: [20, 30, 35, 25, 40, 30],
                        borderDash: [5, 5],
                        borderWidth: 2,
                        tension: 0.4,
                        borderColor: '#EF4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // penting
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        max: 100,
                        ticks: {
                            stepSize: 20
                        }
                    }
                }
            }
        });
    </script>
@endsection
