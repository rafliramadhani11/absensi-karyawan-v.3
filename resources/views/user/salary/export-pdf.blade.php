<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Total Penggajian Seluruh karyawan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            @import url('https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&family=Lato:wght@400;700&display=swap');

            body {
                font-family: 'Lato', sans-serif;
            }

            .font-tinos {
                font-family: 'Tinos', serif;
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

    <body class="">
        <div class="w-full">
            <header class="mb-6 border-b-4 border-black p-4 pb-4">
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

            <h2 class="mb-5 text-center text-xl font-bold">
                LAPORAN PENGGAJIAN KARYAWAN <br>
                Periode {{ \Carbon\Carbon::parse($start)->translatedFormat('j F Y') }} -
                {{ \Carbon\Carbon::parse($end)->translatedFormat('j F Y') }}
            </h2>

            <div class="mb-6 p-4">
                <h3 class="mb-2 text-left text-xl font-bold tracking-wider">
                    LAPORAN PENGGAJIAN KARYAWAN {{ Carbon\Carbon::parse($start)->translatedFormat('j F Y') }} -
                    {{ Carbon\Carbon::parse($end)->translatedFormat('j F Y') }}
                </h3>
            </div>

            @php
                $attendances = \App\Models\Attendance::with('user')
                    ->whereBetween('created_at', [$start, $end])
                    ->get();

                $groupedByUser = $attendances->groupBy('user_id');

                $grandTotalHadir = 0;
                $grandTotalIzin = 0;
                $grandTotalAlpha = 0;
                $grandTotalGajiHadir = 0;
                $grandTotalPotonganIzin = 0;
                $grandTotalPotonganAlpha = 0;
                $grandTotalGajiBersih = 0;

                $gajiPerHadir = 200000;
                $potonganIzin = 10000;
                $potonganAlpha = 100000;
            @endphp

            <table class="w-full border-collapse border border-gray-400 text-left">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-400 px-4 py-2">Bulan</th>
                        <th class="border border-gray-400 px-4 py-2">Nama Karyawan</th>
                        <th class="border border-gray-400 px-4 py-2">Hadir</th>
                        <th class="border border-gray-400 px-4 py-2">Izin</th>
                        <th class="border border-gray-400 px-4 py-2">Tidak Hadir</th>
                        <th class="border border-gray-400 px-4 py-2">Gaji Hadir</th>
                        <th class="border border-gray-400 px-4 py-2">Potongan Izin</th>
                        <th class="border border-gray-400 px-4 py-2">Potongan Tdk Hadir</th>
                        <th class="border border-gray-400 px-4 py-2">Total Gaji</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($groupedByUser as $userId => $userAttendances)
                        @php
                            $user = $userAttendances->first()->user;

                            // ====================================================== //
                            // BAGIAN PERBAIKAN: TAMBAHKAN ->sortKeys() DI SINI        //
                            // ====================================================== //
                            $monthlyRecords = $userAttendances
                                ->groupBy(function ($item) {
                                    return \Carbon\Carbon::parse($item->created_at)->format('Y-m');
                                })
                                ->sortKeys(); // <-- Mengurutkan bulan secara kronologis

                        @endphp

                        @foreach ($monthlyRecords as $month => $records)
                        @php
                            $hadir = $records->where('status', 'hadir')->count();
                            $izin = $records->where('status', 'izin')->count();
                            $alpha = $records->where('status', 'tidak hadir')->count();
                            $gajiHadir = $hadir * $gajiPerHadir;
                            $potIzin = $izin * $potonganIzin;
                            $potAlpha = $alpha * $potonganAlpha;
                            $totalGaji = $gajiHadir - $potIzin - $potAlpha;
                            $grandTotalHadir += $hadir;
                            $grandTotalIzin += $izin;
                            $grandTotalAlpha += $alpha;
                            $grandTotalGajiHadir += $gajiHadir;
                            $grandTotalPotonganIzin += $potIzin;
                            $grandTotalPotonganAlpha += $potAlpha;
                            $grandTotalGajiBersih += $totalGaji;
                        @endphp
                        <tr>
                            <td class="px-4 py-2 border border-gray-400">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->translatedFormat('F Y') }}</td>
                            <td class="px-4 py-2 border border-gray-400">{{ $user->name }}</td>
                            <td class="px-4 py-2 border border-gray-400">{{ $hadir }}</td>
                            <td class="px-4 py-2 border border-gray-400">{{ $izin }}</td>
                            <td class="px-4 py-2 border border-gray-400">{{ $alpha }}</td>
                            <td class="px-4 py-2 border border-gray-400">Rp{{ number_format($gajiHadir, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border border-gray-400">Rp{{ number_format($potIzin, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border border-gray-400">Rp{{ number_format($potAlpha, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 border border-gray-400">Rp{{ number_format($totalGaji, 0, ',', '.') }}</td>
                        </tr> @endforeach
                    @empty
                        <tr>
                            <td colspan="10">Tidak ada data absensi pada rentang tanggal ini.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-200 font-bold">
                        <td colspan="2" class="text-right">TOTAL KESELURUHAN</td>
                        <td>{{ $grandTotalHadir }}</td>
                        <td>{{ $grandTotalIzin }}</td>
                        <td>{{ $grandTotalAlpha }}</td>
                        <td class="text-right">Rp{{ number_format($grandTotalGajiHadir, 0, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($grandTotalPotonganIzin, 0, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($grandTotalPotonganAlpha, 0, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($grandTotalGajiBersih, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>

            <div class="mt-8">
                <div class="flex justify-end space-x-8">
                    <div class="text-center">
                        <p>Mengetahui,</p>
                        <div class="relative h-16 w-48">
                        </div>
                        <p class="font-bold underline">Erlin Usnaharoh</p>
                        <p class="font-semibold">HRD</p>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>
