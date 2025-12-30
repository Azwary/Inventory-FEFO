@extends('views.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white mb-6">
        <h2 class="text-2xl font-bold">Dashboard</h2>
        <p class="text-sm opacity-90">Dashboard Admin</p>
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



    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">

        <div class="bg-white p-4 rounded shadow lg:col-span-8 flex flex-col">
            <h2 class="font-bold mb-4">Notifikasi Kekadaluarsaan Terbaru (FEFO)</h2>
            <x-notifications :notifications="$notifications" class="flex-1" />
        </div>

        <div class="bg-white p-4 rounded shadow lg:col-span-4 flex flex-col">
            <h2 class="font-bold mb-4">Quick Actions</h2>
            <x-quick-actions class="flex-1" />
        </div>
    </div>
@endsection
