<div >
    @foreach ($notifications as $notif)
        <div class="flex items-center justify-between mb-3 p-2 border rounded">
            <div class="flex items-center space-x-3">
                <span>⚠️</span>
                <div>
                    <p class="font-semibold">{{ $notif->batch }} ({{ $notif->nama_obat }})</p>
                    <p class="text-sm">
                        Sisa {{ $notif->sisa_hari }} hari. Segera keluarkan/diskon.
                    </p>
                </div>
            </div>
            <div class="text-sm font-semibold">
                Exp: <span class="text-red-500">{{ $notif->exp_date->format('d M Y') }}</span>
            </div>
        </div>
    @endforeach
</div>
