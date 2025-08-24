<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Laporan Absensi & Kinerja</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&family=Lato:wght@400;700&display=swap');

            body {
                font-family: 'Lato', sans-serif;
            }

            .font-tinos {
                font-family: 'Tinos', serif;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 40px;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 8px;
                text-align: center;
            }

            .page-break {
                page-break-before: always;
            }

            /* <<< kunci pemisah halaman */
        </style>
    </head>

    <body class="p-4">

        {{-- ============================= --}}
        {{-- BAGIAN 1: LAPORAN ABSENSI --}}
        {{-- ============================= --}}
        <div class="mx-auto w-full max-w-4xl p-8">

            <header class="mb-6 border-b-4 border-black pb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="font-tinos text-4xl font-bold italic">Birdie</h1>
                        <p class="font-tinos text-lg italic">It's Time</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <h2 class="text-right text-2xl font-bold">PT BIRDIE INDONESIA</h2>
                        <div class="logo">
                            <img src="{{ asset('img/logo-birdie-hexagon-light.png') }}" alt="Logo Perusahaan"
                                class="h-14">
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-600">
                    Alamat: Jl. Kav. Polri No.21 Blok D, Jagakarsa, Jakarta Selatan 12550
                </p>
            </header>

            <div class="mb-6">
                <h3 class="mb-2 text-left text-xl font-bold tracking-wider">
                    LAPORAN ABSENSI {{ \Carbon\Carbon::parse($start)->translatedFormat('j F Y') }}
                    - {{ \Carbon\Carbon::parse($end)->translatedFormat('j F Y') }}
                </h3>
                <p>Nama : {{ $user->name }}</p>
                <p>Divisi : {{ $user->division->name }}</p>
            </div>

            @php
                use App\Models\Attendance;
                use Carbon\Carbon;
                use Carbon\CarbonPeriod;

                $attendances = Attendance::whereBetween('created_at', [$start, $end])
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'asc')
                    ->get();

                $totalHadir = $attendances->where('status', 'hadir')->count();
                $totalTidakHadir = $attendances->where('status', 'tidak hadir')->count();
                $totalIzin = $attendances->where('status', 'izin')->count();
                $totalTelat = $attendances->where('status', 'hadir')->where('absen_datang', '>', '08:00:00')->count();
            @endphp

            <table>
                <thead>
                    <tr class="bg-gray-200">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Hari</th>
                        <th>Jam Masuk</th>
                        <th>Jam Pulang</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($attendances as $attendance)
                        @php
                            $tanggal = Carbon::parse($attendance->created_at)->format('d/m');
                            $hari = ucfirst(Carbon::parse($attendance->created_at)->locale('id')->isoFormat('dddd'));
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $tanggal }}</td>
                            <td>{{ $hari }}</td>
                            <td>
                                @if ($attendance->absen_datang > '08:00:00')
                                    <span class="font-semibold text-red-600">{{ $attendance->absen_datang }}</span>
                                @else
                                    <span>{{ $attendance->absen_datang ?? '-' }}</span>
                                @endif
                            </td>
                            <td>{{ $attendance->absen_pulang ?? '-' }}</td>
                            <td>
                                <span>{{ ucwords($attendance->status) }}</span>
                                @if ($attendance->status === 'hadir' && $attendance->absen_datang > '08:00:00')
                                    <span class="font-semibold text-red-600">(telat)</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-8 flex justify-between">
                <div class="text-right font-semibold">
                    <p>HADIR: {{ $totalHadir }}</p>
                    <p>TIDAK HADIR: {{ $totalTidakHadir }}</p>
                    <p>IZIN: {{ $totalIzin }}</p>
                    <p class="text-red-600">TOTAL TELAT: {{ $totalTelat }}</p>
                </div>
                <div class="text-center">
                    <p>Mengetahui,</p>
                    <div class="relative h-24 w-48"></div>
                    <p class="font-bold underline">Erlin Usnaharoh</p>
                    <p class="font-semibold">HRD</p>
                </div>
            </div>
        </div>

        {{-- ============================= --}}
        {{-- BAGIAN 2: KINERJA (halaman baru) --}}
        {{-- ============================= --}}
        <div class="page-break"></div>

        <div class="mx-auto w-full max-w-4xl p-8">
            <header class="mb-6 border-b-4 border-black pb-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="font-tinos text-4xl font-bold italic">Birdie</h1>
                        <p class="font-tinos text-lg italic">It's Time</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <h2 class="text-right text-2xl font-bold">PT BIRDIE INDONESIA</h2>
                        <div class="logo">
                            <img src="{{ asset('img/logo-birdie-hexagon-light.png') }}" alt="Logo Perusahaan"
                                class="h-14">
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-600">Alamat: Jl. Kav. Polri No.21 Blok D, Jagakarsa, Jakarta Selatan
                    12550</p>
            </header>

            @php
                // hitung KPI dsb (logika sama seperti file kinerja sebelumnya)
                $realisasiHadir = $totalHadir;
                $realisasiIzin = $totalIzin;
                $realisasiTelat = $totalTelat;
                $realisasiAlpha = $totalTidakHadir;

                $period = CarbonPeriod::create($start, $end);
                $totalWorkingDays = 0;
                foreach ($period as $date) {
                    if (!$date->isWeekend()) {
                        $totalWorkingDays++;
                    }
                }

                $maksimumHadir = $totalWorkingDays;
                $maksimumIzin = 5;
                $maksimumTelat = 3;
                $maksimumAlpha = 0;

                $bobotHadir = 40;
                $bobotIzin = 15;
                $bobotTelat = 15;
                $bobotAlpha = 30;

                $nilaiHadir = $maksimumHadir > 0 ? ($realisasiHadir / $maksimumHadir) * $bobotHadir : 0;
                $nilaiIzin =
                    $realisasiIzin <= $maksimumIzin
                        ? (($maksimumIzin - $realisasiIzin) / $maksimumIzin) * $bobotIzin
                        : 0;
                $nilaiTelat = $maksimumTelat > 0 ? max(0, (1 - $realisasiTelat / $maksimumTelat) * $bobotTelat) : 0;
                $nilaiAlpha = $maksimumHadir > 0 ? max(0, (1 - $realisasiAlpha / $maksimumHadir) * $bobotAlpha) : 0;

                $totalNilaiAkhir = $nilaiHadir + $nilaiIzin + $nilaiTelat + $nilaiAlpha;
                $kategoriPenilaian = match (true) {
                    $totalNilaiAkhir >= 95 => 'Sangat Disiplin',
                    $totalNilaiAkhir >= 80 => 'Disiplin',
                    $totalNilaiAkhir >= 60 => 'Kurang Disiplin',
                    default => 'Tidak Disiplin',
                };
            @endphp

            <h2 class="mb-5 text-center text-xl font-bold">PENILAIAN KINERJA KARYAWAN</h2>
            <p><strong>Nama Karyawan:</strong> {{ $user->name }}</p>
            <p><strong>Divisi:</strong> {{ $user->division->name ?? 'N/A' }}</p>
            <p><strong>Periode:</strong> {{ Carbon::parse($start)->translatedFormat('j F Y') }} s/d
                {{ Carbon::parse($end)->translatedFormat('j F Y') }}</p>

            <table class="w-full border-collapse border border-gray-400 text-left">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-400 px-4 py-2">No</th>
                        <th class="border border-gray-400 px-4 py-2">Indikator Kinerja</th>
                        <th class="border border-gray-400 px-4 py-2">Nilai Maksimum</th>
                        <th class="border border-gray-400 px-4 py-2">Realisasi</th>
                        <th class="border border-gray-400 px-4 py-2">Bobot (%)</th>
                        <th class="border border-gray-400 px-4 py-2">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- KPI 1: Jumlah Hari Hadir --}}
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">1</td>
                        <td class="border border-gray-400 px-4 py-2">Jumlah Hari Hadir</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $maksimumHadir }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $realisasiHadir }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $bobotHadir }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($nilaiHadir, 2, ',', '.') }}</td>
                    </tr>
                    {{-- KPI 2: Jumlah Izin Resmi --}}
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">2</td>
                        <td class="border border-gray-400 px-4 py-2">Jumlah Izin Resmi (Sakit/Cuti)</td>
                        <td class="border border-gray-400 px-4 py-2">&le; {{ $maksimumIzin }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $realisasiIzin }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $bobotIzin }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($nilaiIzin, 2, ',', '.') }}</td>
                    </tr>
                    {{-- KPI 3: Jumlah Terlambat Masuk --}}
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">3</td>
                        <td class="border border-gray-400 px-4 py-2">Jumlah Terlambat Masuk</td>
                        <td class="border border-gray-400 px-4 py-2">&le; {{ $maksimumTelat }} Kali</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $realisasiTelat }} Kali</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $bobotTelat }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($nilaiTelat, 2, ',', '.') }}</td>
                    </tr>
                    {{-- KPI 4: Tidak Hadir Tanpa Keterangan --}}
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">4</td>
                        <td class="border border-gray-400 px-4 py-2">Tidak Hadir Tanpa Keterangan</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $maksimumAlpha }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $realisasiAlpha }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $bobotAlpha }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($nilaiAlpha, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="font-bold">
                        <td colspan="4" class="border-none"></td>
                        <td class="border border-gray-400 px-4 py-2">Total Nilai Akhir</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($totalNilaiAkhir, 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="font-bold">
                        <td colspan="4" class="border-none"></td>
                        <td class="border border-gray-400 px-4 py-2">Kategori Penilaian</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $kategoriPenilaian }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-8 flex justify-between">
                <div>
                    <p>Sangat Disiplin ≥ 95</p>
                    <p>Disiplin ≥ 80</p>
                    <p>Kurang Disiplin ≥ 60</p>
                    <p>Tidak Disiplin < 60</p>
                </div>
                <div class="text-center">
                    <p>Mengetahui,</p>
                    <div class="relative h-24 w-48"></div>
                    <p class="font-bold underline">Erlin Usnaharoh</p>
                    <p class="font-semibold">HRD</p>
                </div>
            </div>
        </div>
    </body>

</html>
