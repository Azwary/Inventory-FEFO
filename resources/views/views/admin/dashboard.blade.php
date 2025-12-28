@extends('views.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Dashboard Admin</h2>
    </div>

    <!-- Quick Boxes -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-dashboard-box>Box 1</x-dashboard-box>
        <x-dashboard-box>Box 2</x-dashboard-box>
        <x-dashboard-box>Box 3</x-dashboard-box>
        <x-dashboard-box>Box 4</x-dashboard-box>
    </div>

    <!-- Notifikasi & Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
        <!-- Notifikasi: full-width mobile, 8/12 desktop -->
        <div class="bg-white p-4 rounded shadow lg:col-span-8 flex flex-col">
            <h2 class="font-bold mb-4">Notifikasi Kekadaluarsaan Terbaru (FEFO)</h2>
            <x-notifications :notifications="$notifications" class="flex-1" />
        </div>

        <!-- Quick Actions: full-width mobile, 4/12 desktop -->
        <div class="bg-white p-4 rounded shadow lg:col-span-4 flex flex-col">
            <h2 class="font-bold mb-4">Quick Actions</h2>
            <x-quick-actions class="flex-1" />
        </div>
    </div>
@endsection
