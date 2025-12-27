@extends('views.layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow mb-4">
        <h2 class="text-xl font-semibold">Dashboard Admin</h2>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
        <x-dashboard-box>Box 1</x-dashboard-box>
        <x-dashboard-box>Box 2</x-dashboard-box>
        <x-dashboard-box>Box 3</x-dashboard-box>
        <x-dashboard-box>Box 4</x-dashboard-box>
    </div>

    <div class="grid grid-cols-12 gap-6 items-stretch">
        <!-- Notifikasi: lebih lebar, misal 8/12 -->
        <div class="bg-white p-4 rounded shadow col-span-8 flex flex-col">
            <h2 class="font-bold mb-4">Notifikasi Kekadaluarsaan Terbaru (FEFO)</h2>
            <x-notifications="$notifications" class="flex-1" />

        </div>

        <!-- Quick Actions: lebih sempit, misal 4/12 -->
        <div class="col-span-4 bg-white p-4 rounded shadow flex flex-col">
            <h2 class="font-bold mb-8">Quick Actions</h2>
            <x-quick-actions class="flex-1" />
        </div>

    </div>



@endsection
