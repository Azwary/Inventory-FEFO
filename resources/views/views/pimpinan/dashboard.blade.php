@extends('views.layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Dashboard Utama (Tampilan Pimpinan)</h2>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <x-views.components.dashboard-box>Box 1</x-views.components.dashboard-box>
        <x-views.components.dashboard-box>Box 2</x-views.components.dashboard-box>
        <x-views.components.dashboard-box>Box 3</x-views.components.dashboard-box>
        <x-views.components.dashboard-box>Box 4</x-views.components.dashboard-box>
    </div>

    <div class="bg-white p-4 rounded shadow">
        <h3 class="font-semibold mb-1">Analisis Kinerja Stok (FEFO)</h3>
        <p class="text-sm text-gray-500 mb-4">
            Grafik menunjukkan kecepatan pergerakan stok untuk mencegah kadaluarsa.
        </p>

        <canvas id="fefoChart" height="80"></canvas>

        <div class="text-center mt-4">
            <a href="#" class="text-blue-600 text-sm hover:underline">
                Lihat Laporan Detail
            </a>
        </div>
    </div>


@endsection
@section('scripts')
    <script>
        const ctx = document.getElementById('fefoChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                datasets: [{
                        label: 'Stok Keluar',
                        data: [30, 45, 40, 60, 55, 70],
                        borderWidth: 3,
                        tension: 0.4
                    },
                    {
                        label: 'Stok Hampir Expired',
                        data: [20, 30, 35, 25, 40, 30],
                        borderDash: [5, 5],
                        borderWidth: 2,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
