@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-0 flex items-center gap-2">
            <i class="fas fa-user-friends text-blue-500 text-xl"></i>
            Daftar Pengguna
        </h1>

        <p class="text-gray-600 text-xs tracking-wide mb-8">
            Manajemen data pengguna yang terdaftar dalam aplikasi.
        </p>


        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- FILTER BAR --}}
        <form method="GET" class="mb-4 flex items-center justify-between gap-3 flex-wrap">

            {{-- Entries --}}
            <div class="relative">
                <select name="entries" onchange="this.form.submit()" class="appearance-none border border-gray-300 rounded-xl bg-white text-sm px-4 py-2 pr-10
                               shadow-sm hover:border-blue-400 focus:border-blue-500 focus:ring focus:ring-blue-200/40
                               transition-all cursor-pointer font-medium">
                    <option value="5" {{ request('entries', $entries ?? 10) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries', $entries ?? 10) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries', $entries ?? 10) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('entries', $entries ?? 10) == 50 ? 'selected' : '' }}>50</option>
                </select>

                <!-- Icon panah (Custom Dropdown Icon) -->
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                    ▼
                </div>
            </div>

            {{-- Search --}}
            <div class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengguna..."
                    class="border border-gray-300 rounded-lg text-sm px-3 py-2 w-[200px]">

                {{-- Bisa dihapus kalau mau full auto-search --}}
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Cari
                </button>
            </div>
        </form>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead
                        class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Nama & Email</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($users as $index => $user)
                            <tr class="hover:bg-blue-50/50 transition duration-200 ease-in-out">
                                {{-- Nomor mengikuti pagination --}}
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ method_exists($users, 'firstItem') ? $users->firstItem() + $index : $index + 1 }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-base font-semibold text-gray-800">{{ $user->nama }}</span>
                                        <span class="text-xs text-gray-500">{{ $user->email }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 font-medium text-gray-700">{{ $user->no_hp ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <!-- Form DELETE untuk pengguna -->
                                    <form action="{{ route('admin.user.destroy', $user->id_user) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)"
                                            class="inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-red-500 to-red-600 px-4 py-2 text-xs font-medium text-white shadow-sm hover:from-red-600 hover:to-red-700 hover:shadow-md transition-all duration-200 ml-2">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-gray-500 bg-gray-50">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 10a2 2 0 11-4 0 2 2 0 014 0zM15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM17 20h5v-2a3 3 0 00-5.356-1.857M7 20H2v-2a3 3 0 015.356-1.857">
                                            </path>
                                        </svg>
                                        <p class="text-base font-semibold">Belum ada data pengguna</p>
                                        <p class="text-sm text-gray-400">Data pengguna akan muncul di sini.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Info entries + tombol Previous / Next --}}
        <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-sm text-gray-600">
            {{-- Info jumlah entri --}}
            <div>
                @if (method_exists($users, 'total') && $users->total() > 0)
                    Menampilkan
                    <span class="font-semibold">{{ $users->firstItem() }}</span>
                    sampai
                    <span class="font-semibold">{{ $users->lastItem() }}</span>
                    dari
                    <span class="font-semibold">{{ $users->total() }}</span>
                    entri
                @else
                    Tidak ada entri yang ditampilkan
                @endif
            </div>

            {{-- Previous / Next --}}
            <div class="flex items-center gap-2">
                @php
                    $paginator = method_exists($users, 'appends')
                        ? $users->appends(request()->except('page'))
                        : null;
                @endphp

                @if ($paginator)
                    <a href="{{ $paginator->previousPageUrl() ?: '#' }}"
                        class="px-3 py-1 rounded-lg border text-xs
                                          {{ $paginator->onFirstPage() ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white hover:bg-gray-100' }}">
                        Previous
                    </a>

                    <span class="text-xs">
                        Halaman
                        <span class="font-semibold">{{ $paginator->currentPage() }}</span>
                        /
                        <span class="font-semibold">{{ $paginator->lastPage() }}</span>
                    </span>

                    <a href="{{ $paginator->hasMorePages() ? $paginator->nextPageUrl() : '#' }}"
                        class="px-3 py-1 rounded-lg border text-xs
                                          {{ $paginator->hasMorePages() ? 'bg-white hover:bg-gray-100' : 'bg-gray-100 text-gray-400 cursor-not-allowed' }}">
                        Next
                    </a>
                @endif
            </div>
        </div>

        {{-- Link pagination standar Laravel (opsional) --}}
        <div class="mt-2">
            @if (method_exists($users, 'links'))
                {{ $users->links() }}
            @endif
        </div>

    </div>
@endsection

@section('scripts')
    <!-- SweetAlert -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(el) {
            Swal.fire({
                title: 'Hapus Pengguna?',
                text: "Data pengguna akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    el.closest('form').submit(); // submit form DELETE
                }
            });
        }

        // AUTO SEARCH (ketik langsung reload)
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.querySelector("input[name='search']");
            let typingDelay;

            if (searchInput && searchInput.form) {
                searchInput.addEventListener("keyup", function () {
                    clearTimeout(typingDelay);
                    typingDelay = setTimeout(() => {
                        this.form.submit(); // otomatis reload dengan keyword baru
                    }, 400); // delay 400ms setelah berhenti mengetik
                });
            }
        });
    </script>
@endsection