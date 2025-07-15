@section('title', 'Employee Daily Absent')

<x-app-layout>
    {{-- Heading --}}
    <div class="flex items-center justify-between">
        <h1 class="mt-3 text-2xl font-semibold sm:text-3xl">
            Employee Daily Absent
        </h1>
    </div>

    <?php
    $existingAttendance = \App\Models\Attendance::where('user_id', Auth::id())->whereDate('created_at', today())->first();
    
    $now = now();
    $isMorningWindow = $now->between(today()->setTime(7, 0), today()->setTime(10, 0));
    $isAfternoonWindow = $now->between(today()->setTime(17, 0), today()->setTime(18, 0));
    
    $canScanMorning = !$existingAttendance && $isMorningWindow;
    
    $canScanAfternoon = $existingAttendance && !$existingAttendance->absen_pulang && $isAfternoonWindow;
    
    $showScanner = false;
    $showScanner = $canScanMorning || $canScanAfternoon;
    ?>


    @if ($showScanner)
        {{-- Jika waktu scan, tampilkan QR Scanner --}}
        <div wire:ignore class="mt-10">
            <div id="reader" width="10px" class="md:w-1/2 md:translate-x-1/2"></div>
        </div>
    @else
        @if ($existingAttendance && $existingAttendance->status === 'izin')
            {{-- Kasus BARU: Pengguna tercatat sedang izin hari ini --}}
            <div class="mt-10 rounded-lg border border-orange-400 bg-orange-100 p-4 text-center text-orange-700">
                <p class="font-semibold">Anda tercatat sedang izin hari ini. 📝</p>
                <p class="text-sm">Anda tidak perlu melakukan absensi datang atau pulang.</p>
            </div>
        @elseif ($existingAttendance && $existingAttendance->absen_pulang)
            {{-- Kasus 1: Absensi hari ini sudah selesai (datang dan pulang) --}}
            <div class="mt-10 rounded-lg border border-green-400 bg-green-100 p-4 text-center text-green-700">
                <p class="font-semibold">Absensi hari ini sudah selesai. ✅</p>
                <p class="text-sm">Terima kasih dan selamat beristirahat.</p>
            </div>
        @elseif($existingAttendance && !$existingAttendance->absen_pulang)
            {{-- Kasus 2: Sudah absen datang, tapi belum waktunya absen pulang --}}
            <div class="mt-10 rounded-lg border border-blue-400 bg-blue-100 p-4 text-center text-blue-700">
                <p class="font-semibold">Anda sudah melakukan absensi datang.</p>
                <p class="text-sm">Silakan kembali pada jadwal absen pulang (17:00 - 18:00).</p>
            </div>
        @else
            {{-- Kasus 3: Belum absen sama sekali dan sekarang di luar jadwal --}}
            <div class="mt-10 rounded-lg border border-yellow-400 bg-yellow-100 p-4 text-center text-yellow-700">
                <p class="font-semibold">Saat ini di luar waktu absensi.</p>
                <p class="text-sm">Jadwal absen: Pagi (07:00 - 10:00) dan Sore (17:00 - 18:00).</p>
            </div>
        @endif
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
