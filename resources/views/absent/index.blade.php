@section('title', 'Qr Code Absent')

<x-app-layout>
    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Absent Daily
        </h1>
        {{-- <livewire:attendance.attendance-create /> --}}
    </div>

    <div wire:ignore>
        <div id="reader" width="10px" class="md:w-1/2 md:translate-x-1/2"></div>
    </div>

    {{-- Session --}}
    <div class="mt-10">
        @if (session('status') === 'absen-gagal')
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <span class="font-medium">Qr Code Tidak Sesuai !</span>
            </div>
        @endif
        @if (session('status') === 'code-kadaluarsa')
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400"
                role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <span class="font-medium">Qr Code Sudah Kedaluwarsa.</span>
            </div>
        @endif
        @if (session('status') === 'absen-already')
            <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
                role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <span class="font-medium">Anda Sudah Melakukan Absensi .</span>
            </div>
        @endif
        @if (session('status') === 'absen-updated')
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <span class="font-medium">Berhasil Melakukan Absensi .</span>
            </div>
        @endif
    </div>
    {{-- ----------------------------------------------------------------- --}}

    <livewire:user.user-qr-absent-daily-table lazy />

</x-app-layout>
