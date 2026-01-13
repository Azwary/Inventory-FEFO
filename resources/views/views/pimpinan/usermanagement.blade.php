@extends('views.layouts.app')

@section('title', 'User Management')

@section('content')
    {{-- HEADER --}}
    {{-- <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg shadow text-white mb-6">
        <h2 class="text-2xl font-bold">User Management</h2>
        <p class="text-sm opacity-90">Kelola data pengguna sistem</p>
    </div> --}}

    <div class="p-6 bg-white rounded-xl shadow">
        @if ($errors->any())
            <div class="mb-4 bg-red-50 border border-red-200 text-red-700 p-3 rounded-lg text-sm">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 p-3 rounded-lg text-sm">
                {{ session('success') }}
            </div>
        @endif

        {{-- TOOLBAR --}}
        <form method="GET"
            class="mb-5 p-4 bg-white rounded-xl shadow-sm border
            flex flex-col gap-3 sm:flex-row sm:items-center sm:gap-3">

            {{-- SEARCH --}}
            <div class="relative w-full sm:w-1/3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / username..."
                    class="w-full pl-4 pr-3 py-2 border rounded-lg
                    focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <button type="submit"
                class="flex items-center justify-center gap-2
                bg-blue-600 text-white px-4 py-2 rounded-lg
                hover:bg-blue-700 transition">
                Cari
            </button>

            <button type="button" onclick="openModal('createModal')"
                class="sm:ml-auto flex items-center justify-center gap-2
                bg-green-600 text-white px-5 py-2 rounded-lg
                hover:bg-green-700 transition shadow">
                + Tambah User
            </button>
        </form>

        {{-- TABLE --}}
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="min-w-full border border-gray-200 table-auto text-center">
                <thead class="bg-gray-100 text-gray-700 text-sm uppercase">
                    <tr>
                        <th class="border px-3 py-2">Nama</th>
                        <th class="border px-3 py-2">Role</th>
                        <th class="border px-3 py-2">Username</th>
                        <th class="border px-3 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($user as $users)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="border px-3 py-2 font-semibold">
                                {{ $users->nama }}
                            </td>
                            <td class="border px-3 py-2">
                                <span
                                    class="px-3 py-1 text-xs rounded-full
                                    {{ $users->role === 'admin'
                                        ? 'bg-red-100 text-red-700'
                                        : ($users->role === 'pimpinan'
                                            ? 'bg-blue-100 text-blue-700'
                                            : 'bg-green-100 text-green-700') }}">
                                    {{ ucfirst($users->role) }}
                                </span>
                            </td>
                            <td class="border px-3 py-2">
                                {{ $users->username }}
                            </td>
                            <td class="border px-3 py-2 space-x-2">
                                <button onclick="openModal('editModal-{{ $users->id }}')"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-1 rounded-lg">
                                    Edit
                                </button>

                                <form action="{{ route('pimpinan.user.destroy', $users->id) }}" method="POST"
                                    class="inline" onsubmit="return confirm('Yakin hapus user?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-1 rounded-lg">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-10 text-gray-500 italic">
                                Data user tidak ditemukan
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL CREATE --}}
    <div id="createModal" class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">

        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">

            {{-- HEADER --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4 text-white">
                <h3 class="text-lg font-bold">Tambah User</h3>
                <p class="text-sm opacity-90">Input data user baru</p>
            </div>

            {{-- BODY --}}
            <form action="{{ route('pimpinan.user.store') }}" method="POST" class="p-6 space-y-4">
                @csrf

                <input name="nama" class="w-full border rounded-lg p-2" placeholder="Nama" required>
                <input name="username" class="w-full border rounded-lg p-2" placeholder="Username" required>

                <select name="role" class="w-full border rounded-lg p-2" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="pimpinan">Pimpinan</option>
                    <option value="user">User</option>
                </select>
                <div class="relative">
                    <input type="password" id="create-password" name="password" class="w-full border rounded-lg p-2 pr-10"
                        placeholder="Password" required>

                    <button type="button" onclick="togglePassword('create-password', this)"
                        class="absolute right-3 top-2 text-gray-500 hover:text-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye">
                            <path
                                d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                    </button>
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" onclick="closeModal('createModal')"
                        class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Batal
                    </button>
                    <button class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    @foreach ($user as $users)
        <div id="editModal-{{ $users->id }}"
            class="hidden fixed inset-0 bg-black/60 flex items-center justify-center z-50">

            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">

                <div class="bg-gradient-to-r from-yellow-500 to-orange-500 px-6 py-4 text-white">
                    <h3 class="text-lg font-bold">Edit User</h3>
                    <p class="text-sm opacity-90">{{ $users->nama }}</p>
                </div>

                <form action="{{ route('pimpinan.user.update', $users->id) }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    @method('PUT')

                    <input name="nama" value="{{ $users->nama }}" class="w-full border rounded-lg p-2">
                    <input name="username" value="{{ $users->username }}" class="w-full border rounded-lg p-2">

                    <select name="role" class="w-full border rounded-lg p-2">
                        <option value="admin" {{ $users->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pimpinan" {{ $users->role == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        <option value="user" {{ $users->role == 'user' ? 'selected' : '' }}>User</option>
                    </select>

                    <div class="relative">
                        <input type="password" id="edit-password-{{ $users->id }}" name="password"
                            class="w-full border rounded-lg p-2 pr-10" placeholder="Kosongkan jika tidak diubah">

                        <button type="button" onclick="togglePassword('edit-password-{{ $users->id }}', this)"
                            class="absolute right-3 top-2 text-gray-500 hover:text-gray-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye">
                                <path
                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t">
                        <button type="button" onclick="closeModal('editModal-{{ $users->id }}')"
                            class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    {{-- SCRIPT --}}
    <script>
        const eyeIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2.062 12.348a1 1 0 0 1 0-.696
                10.75 10.75 0 0 1 19.876 0
                1 1 0 0 1 0 .696
                10.75 10.75 0 0 1-19.876 0"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>
    `;

        const eyeOffIcon = `
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
            viewBox="0 0 24 24" fill="none" stroke="currentColor"
            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M2 8a10.645 10.645 0 0 0 20 0"/>
            <path d="m15 18-.722-3.25"/>
            <path d="m20 15-1.726-2.05"/>
            <path d="m4 15 1.726-2.05"/>
            <path d="m9 18 .722-3.25"/>
        </svg>
    `;

        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                btn.innerHTML = eyeOffIcon;
            } else {
                input.type = "password";
                btn.innerHTML = eyeIcon;
            }
        }

        function openModal(id) {
            document.getElementById(id)?.classList.remove('hidden');
        }

        function closeModal(id) {
            document.getElementById(id)?.classList.add('hidden');
        }
    </script>



@endsection
