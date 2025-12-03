@extends('layouts.app')

@section('content')
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Daftar Teknisi</h1>

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
                    <option value="5" {{ request('entries', $entries) == 5 ? 'selected' : '' }}>5</option>
                    <option value="10" {{ request('entries', $entries) == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('entries', $entries) == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('entries', $entries) == 50 ? 'selected' : '' }}>50</option>
                </select>

                <!-- Icon panah (Custom Dropdown Icon) -->
                <div class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-gray-500">
                    ▼
                </div>
            </div>


            {{-- Search --}}
            <div class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari..."
                    class="border border-gray-300 rounded-lg text-sm px-3 py-2 w-[180px]">

                {{-- TIDAK DIPAKAI untuk autosubmit --}}
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm">
                    Cari
                </button>
            </div>

        </form>




        <div id="tableWrapper" class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600">
                    <thead
                        class="bg-gray-50 border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500 font-bold">
                        <tr>
                            <th class="px-6 py-4">No</th>
                            <th class="px-6 py-4">Profil Teknisi</th>
                            <th class="px-6 py-4">Kontak</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($teknisis as $index => $teknisi)
                            <tr class="hover:bg-blue-50/50 transition duration-200 ease-in-out">

                                {{-- Nomor auto sesuai pagination --}}
                                <td class="px-6 py-4 font-medium text-gray-900">
                                    {{ $teknisis->firstItem() + $index }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-base font-semibold text-gray-800">{{ $teknisi->user->nama }}</span>
                                        <span class="text-xs text-gray-500">{{ $teknisi->user->email }}</span>
                                    </div>
                                </td>

                                <td class="px-6 py-4 font-medium text-gray-700">
                                    {{ $teknisi->user->no_hp ?? '-' }}
                                </td>

                                <td class="px-6 py-4 text-center">
                                    @if($teknisi->is_verified)
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-600 ring-1 ring-inset ring-green-600/20">
                                            Verified
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600 ring-1 ring-inset ring-red-600/20">
                                            Pending
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 text-center flex justify-center gap-2">

                                    @if(!$teknisi->is_verified)
                                        <a href="javascript:void(0);"
                                            data-url="{{ route('admin.teknisi.verify', $teknisi->id_teknisi) }}"
                                            class="verify-teknisi inline-flex items-center gap-1 rounded-lg bg-blue-600 px-4 py-2 text-xs font-medium text-white shadow-sm hover:bg-blue-700 transition-all duration-200">

                                            <!-- Ikon centang -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                            </svg>

                                            Verifikasi
                                        </a>
                                    @endif

                                    <a href="javascript:void(0);"
                                        data-url="{{ route('admin.teknisi.destroy', $teknisi->id_teknisi) }}"
                                        class="delete-teknisi inline-flex items-center gap-1 rounded-lg bg-red-600 px-4 py-2 text-xs font-medium text-white shadow-sm hover:bg-red-700 transition-all duration-200">

                                        <!-- Ikon hapus -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                        </svg>

                                        Hapus
                                    </a>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500 bg-gray-50">
                                    Belum ada data teknisi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $teknisis->links() }}
        </div>
    </div>
@endsection



@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // Tombol Verifikasi
            document.querySelectorAll('.verify-teknisi').forEach(button => {
                button.addEventListener('click', function () {
                    const url = this.dataset.url;
                    Swal.fire({
                        title: 'Verifikasi teknisi?',
                        text: "Teknisi ini akan diverifikasi dan bisa melakukan pekerjaan.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, verifikasi',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });

            // Tombol Hapus
            document.querySelectorAll('.delete-teknisi').forEach(button => {
                button.addEventListener('click', function () {
                    const url = this.dataset.url;
                    Swal.fire({
                        title: 'Hapus teknisi?',
                        text: "Teknisi ini akan dihapus dan tidak bisa login lagi!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });

            // ------------------------------
            // AUTO SEARCH (Ketik langsung cari)
            // ------------------------------
            document.addEventListener('DOMContentLoaded', function () {

                const searchInput = document.querySelector("input[name='search']");
                let typingDelay;

                if (searchInput) {
                    searchInput.addEventListener("keyup", function () {
                        clearTimeout(typingDelay);
                        typingDelay = setTimeout(() => {
                            this.form.submit(); // otomatis reload dengan keyword baru
                        }, 400);
                    });
                }
            });


        });
    </script>
@endsection