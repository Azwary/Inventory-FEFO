@extends('admin.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')

    <h1 class="text-xl font-bold mb-4">Dashboard Utama</h1>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <x-admin.components.dashboard-box>Box 1</x-admin.components.dashboard-box>
        <x-admin.components.dashboard-box>Box 2</x-admin.components.dashboard-box>
        <x-admin.components.dashboard-box>Box 3</x-admin.components.dashboard-box>
        <x-admin.components.dashboard-box>Box 4</x-admin.components.dashboard-box>
    </div>

    <div class="grid grid-cols-12 gap-6 items-stretch">
        <!-- Notifikasi: lebih lebar, misal 8/12 -->
        <div class="bg-white p-4 rounded shadow col-span-8 flex flex-col">
            <h2 class="font-bold mb-4">Notifikasi Kekadaluarsaan Terbaru (FEFO)</h2>
            <x-admin.components.notifications :notifications="$notifications" class="flex-1" />
        </div>

        <!-- Quick Actions: lebih sempit, misal 4/12 -->
        <div class="col-span-4 bg-white p-4 rounded shadow flex flex-col ">
            <h2 class="font-bold mb-4">Quick Actions</h2>
            <x-admin.components.quick-actions class="flex-1" />
        </div>
    </div>



@endsection
