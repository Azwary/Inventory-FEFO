<div>
    @foreach ($notifications as $notif)
        <div class="flex items-center justify-between mb-3 p-2 border rounded">
            <div class="flex items-center space-x-3">
                <span> <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-triangle-alert-icon lucide-triangle-alert text-yellow-500">
                        <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3" />
                        <path d="M12 9v4" />
                        <path d="M12 17h.01" />
                    </svg></span>
                <div>
                    <p class="font-semibold">{{ $notif->nomor_batch ?? '-' }}
                        ({{ $notif->barang->obat?->nama_obat ?? '-' }})
                    </p>
                    <p class="text-sm">
                        Sisa {{ $notif->sisa_hari }} hari. Segera keluarkan/diskon.
                    </p>
                </div>
            </div>
            <div class="text-sm font-semibold">
                Exp: <span class="text-red-500">{{ $notif->tanggal_kadaluarsa ?? '-' }}
                </span>
            </div>
        </div>
    @endforeach
</div>
