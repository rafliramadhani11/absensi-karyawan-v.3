@section('title', 'Employee Daily Absent')

<x-app-layout>
    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Employee Daily Absent
        </h1>
    </div>

    @if (!Auth::user()->is_hrd && !Auth::user()->is_admin)
        <div wire:ignore class="mt-10">
            <div id="reader" width="10px" class="md:w-1/2 md:translate-x-1/2"></div>
        </div>
    @endif

    {{-- Session --}}
    <div class="mt-10">
        @if (session('status') === 'absen-gagal')
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400"
                role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <span class="font-medium">Qr Code Tidak Sesuai !</span>
            </div>
        @endif
        @if (session('status') === 'code-kadaluarsa')
            <div class="mb-4 rounded-lg bg-red-50 p-4 text-sm text-red-800 dark:bg-gray-800 dark:text-red-400"
                role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <span class="font-medium">Qr Code Sudah Kedaluwarsa.</span>
            </div>
        @endif
        @if (session('status') === 'absen-already')
            <div class="mb-4 rounded-lg bg-yellow-50 p-4 text-sm text-yellow-800 dark:bg-gray-800 dark:text-yellow-300"
                role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <span class="font-medium">Anda Sudah Melakukan Absensi .</span>
            </div>
        @endif
        @if (session('status') === 'absen-updated')
            <div class="mb-4 rounded-lg bg-green-50 p-4 text-sm text-green-800 dark:bg-gray-800 dark:text-green-400"
                role="alert" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <span class="font-medium">Berhasil Melakukan Absensi .</span>
            </div>
        @endif
    </div>
    {{-- ----------------------------------------------------------------- --}}

    <livewire:user.user-qr-absent-daily-table />

    <script src="{{ asset('js/html5-qrcode.min.js') }}" type="text/javascript"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            window.location.href = decodedText;
        }

        function onScanFailure(error) {
            // handle scan failure, usually better to ignore and keep scanning.
            // for example:
            console.warn(`Code scan error = ${error}`);
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", {
            fps: 10,
            qrbox: {
                width: 250,
                height: 250
            }
        }, false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
    </script>

</x-app-layout>
