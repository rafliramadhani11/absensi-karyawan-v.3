<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Kinerja Divisi</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&family=Lato:wght@400;700&display=swap');

            body {
                font-family: 'Lato', sans-serif;
            }

            .font-tinos {
                font-family: 'Tinos', serif;
            }

            .header-yellow {
                background-color: #FFFF00;
                font-weight: bold;
            }

            h2 {
                text-align: center;
                margin: 0 0 20px 0;
            }

            .info {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .logo {
                flex: 0 0 auto;
            }

            .logo img {
                height: 60px;
                width: auto;
            }

            .employee-details {
                text-align: left;
            }

            .employee-details p {
                margin: 4px 0;
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

            .ttd {
                width: 100%;
                margin-top: 80px;
            }

            .ttd .kanan {
                float: right;
                text-align: center;
            }
        </style>
    </head>

    <body class="p-4">

        @php
            use Carbon\Carbon;
            use Carbon\CarbonPeriod;
            use App\Models\Attendance;

            $userIds = $division->users->pluck('id');

            $attendances = Attendance::whereIn('user_id', $userIds)
                ->whereBetween('created_at', [Carbon::parse($start)->startOfDay(), Carbon::parse($end)->endOfDay()])
                ->get();

            // Total realisasi agregat untuk seluruh divisi
            $realisasiHadir = $attendances->where('status', 'hadir')->count();
            $realisasiIzin = $attendances->where('status', 'izin')->count();
            $realisasiTelat = $attendances
                ->where('status', 'hadir')
                ->filter(function ($item) {
                    return $item->absen_datang > '08:00:00';
                })
                ->count();
            $realisasiAlpha = $attendances->where('status', 'tidak hadir')->count();

            // Total hari kerja
            $period = CarbonPeriod::create($start, $end);
            $totalWorkingDays = collect($period)->reject(fn($d) => $d->isWeekend())->count();
            $jumlahKaryawan = $division->users->count();
            $maksimumHadir = $totalWorkingDays * $jumlahKaryawan;
            $maksimumIzin = 5 * $jumlahKaryawan;
            $maksimumTelat = 3 * $jumlahKaryawan;
            $maksimumAlpha = 0;

            $bobotHadir = 40;
            $bobotIzin = 15;
            $bobotTelat = 15;
            $bobotAlpha = 30;

            $nilaiHadir = $maksimumHadir > 0 ? ($realisasiHadir / $maksimumHadir) * $bobotHadir : 0;
            $nilaiIzin =
                $realisasiIzin <= $maksimumIzin ? (($maksimumIzin - $realisasiIzin) / $maksimumIzin) * $bobotIzin : 0;
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
                            <img src="{{ asset('img/logo-birdie-hexagon-light.png') }}" alt="Logo Perusahaan">
                        </div>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-600">Alamat: Jl. Kav. Polri No.21 Blok D, Jagakarsa, Kec. Jagakarsa,
                    Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12550</p>
            </header>

            <div class="mb-6">
                <h2 class="mb-4 text-center text-xl font-bold">PENILAIAN KINERJA DIVISI</h2>
                <div class="mt-4">
                    <p><strong>Divisi:</strong> {{ $division->name }}</p>
                    <p><strong>Jumlah Karyawan:</strong> {{ $jumlahKaryawan }}</p>
                    <p><strong>Periode:</strong> {{ Carbon::parse($start)->translatedFormat('j F Y') }} s/d
                        {{ Carbon::parse($end)->translatedFormat('j F Y') }}</p>
                </div>
            </div>

            <table class="w-full border-collapse border border-gray-400 text-left">
                <thead>
                    <tr class="bg-gray-200 text-center">
                        <th class="border border-gray-400 px-4 py-2">No</th>
                        <th class="border border-gray-400 px-4 py-2">Indikator</th>
                        <th class="border border-gray-400 px-4 py-2">Maksimum</th>
                        <th class="border border-gray-400 px-4 py-2">Realisasi</th>
                        <th class="border border-gray-400 px-4 py-2">Bobot (%)</th>
                        <th class="border border-gray-400 px-4 py-2">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">1</td>
                        <td class="border border-gray-400 px-4 py-2">Jumlah Hari Hadir</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $maksimumHadir }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $realisasiHadir }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $bobotHadir }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($nilaiHadir, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">2</td>
                        <td class="border border-gray-400 px-4 py-2">Izin Resmi</td>
                        <td class="border border-gray-400 px-4 py-2">&le; {{ $maksimumIzin }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $realisasiIzin }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $bobotIzin }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($nilaiIzin, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">3</td>
                        <td class="border border-gray-400 px-4 py-2">Terlambat Masuk</td>
                        <td class="border border-gray-400 px-4 py-2">&le; {{ $maksimumTelat }} Kali</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $realisasiTelat }} Kali</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $bobotTelat }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($nilaiTelat, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 px-4 py-2">4</td>
                        <td class="border border-gray-400 px-4 py-2">Tidak Hadir (Alpha)</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $maksimumAlpha }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $realisasiAlpha }} Hari</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $bobotAlpha }}</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($nilaiAlpha, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="text-center font-bold">
                        <td colspan="4" class="border-none"></td>
                        <td class="border border-gray-400 px-4 py-2">Total Nilai Akhir</td>
                        <td class="border border-gray-400 px-4 py-2">{{ number_format($totalNilaiAkhir, 2, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="text-center font-bold">
                        <td colspan="4" class="border-none"></td>
                        <td class="border border-gray-400 px-4 py-2">Kategori</td>
                        <td class="border border-gray-400 px-4 py-2">{{ $kategoriPenilaian }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-12 text-right">
                <p>Mengetahui,</p>
                <div class="h-24"></div>
                <p class="font-bold underline">Erlin Usnaharoh</p>
                <p class="font-semibold">HRD</p>
            </div>
        </div>

    </body>

</html>
