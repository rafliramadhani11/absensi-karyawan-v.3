<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Slip Gaji Karyawan</title>
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

    <body class="p-4">
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

            <!-- Header Info -->
            <div class="mb-6 flex justify-between">
                <div>
                    <p>Nik &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: {{ $user->nik }}</p>
                    <p>Periode Gaji: {{ \Carbon\Carbon::parse($start)->translatedFormat('j F Y') }} -
                        {{ \Carbon\Carbon::parse($end)->translatedFormat('j F Y') }}</p>
                </div>
                <div>
                    <p>Nama &nbsp;: {{ $user->name }}</p>
                    <p>Divisi &nbsp;: {{ $user->division->name }}</p>
                </div>
            </div>

            @php
                use Carbon\Carbon;
                use Illuminate\Support\Facades\Auth;

                // User & periode
                $user = $user ?? Auth::user();
                $periodeLabel =
                    Carbon::parse($start)->isSameMonth(Carbon::parse($end)) &&
                    Carbon::parse($start)->isSameYear(Carbon::parse($end))
                        ? Carbon::parse($start)->translatedFormat('F Y')
                        : Carbon::parse($start)->translatedFormat('F Y') .
                            ' - ' .
                            Carbon::parse($end)->translatedFormat('F Y');

                // Ambil absensi dalam rentang
                $records = \App\Models\Attendance::where('user_id', $user->id)
                    ->whereBetween('created_at', [$start, $end])
                    ->get();

                // Hitung status
                $hadir = $records->where('status', 'hadir')->count();
                $izin = $records->where('status', 'izin')->count();
                $alpha = $records->where('status', 'tidak hadir')->count();

                // Rate (silakan ubah sesuai kebijakanmu)
                $rateHarian = 200000; // gaji per hadir
                $ratePotIzin = 20000; // potongan per izin
                $ratePotAlpha = 100000; // potongan per tidak hadir
                $bonus = $bonus ?? 0; // opsional dikirim dari controller

                // Perhitungan
                $gajiPokok = $hadir * $rateHarian;
                $totalPendapatan = $gajiPokok + $bonus;

                $totalPotonganIzin = $izin * $ratePotIzin;
                $totalPotonganAlpha = $alpha * $ratePotAlpha;
                $totalPengurangan = $totalPotonganIzin + $totalPotonganAlpha;

                $gajiBersih = $totalPendapatan - $totalPengurangan;

                // helper format
                $rp = fn($n) => 'Rp' . number_format($n, 0, ',', '.');
            @endphp

            <!-- Main Salary Table -->
            <table class="mb-10 w-full border-collapse border-black text-sm">
                <thead>
                    <tr>
                        <th class="w-1/4 border border-black p-2 text-center">PENDAPATAN</th>
                        <th class="w-1/4 border border-black p-2 text-center">AMOUNT</th>
                        <th class="w-1/4 border border-black p-2 text-center">PENGURANGAN</th>
                        <th class="w-1/4 border border-black p-2 text-center">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-black p-2">GAJI POKOK</td>
                        <td class="border border-black p-2 text-center">
                            Rp{{ number_format($gajiPokok ?? 0, 0, ',', '.') }}
                        </td>
                        <td class="border border-black p-2">IZIN ({{ $izin ?? 0 }}x)</td>
                        <td class="border border-black p-2 text-center">
                            Rp{{ number_format($totalPotonganIzin ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-black p-2">BONUS</td>
                        <td class="border border-black p-2 text-center">
                            {{ $bonus ?? 0 ? 'Rp' . number_format($bonus, 0, ',', '.') : '-' }}
                        </td>
                        <td class="border border-black p-2">TIDAK HADIR ({{ $alpha ?? 0 }}x)</td>
                        <td class="border border-black p-2 text-center">
                            Rp{{ number_format($totalPotonganAlpha ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="font-bold">
                        <td class="border border-black p-2 uppercase">TOTAL PENDAPATAN</td>
                        <td class="border border-black p-2 text-center">
                            Rp{{ number_format(($gajiPokok ?? 0) + ($bonus ?? 0), 0, ',', '.') }}
                        </td>
                        <td class="border border-black p-2 uppercase">TOTAL PENGURANGAN</td>
                        <td class="border border-black p-2 text-center">
                            Rp{{ number_format(($totalPotonganIzin ?? 0) + ($totalPotonganAlpha ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr class="font-bold">
                        <td class="border border-black p-2 uppercase">GAJI BERSIH</td>
                        <td class="p-2" style="border-right:none;"></td> {{-- kosong tanpa border --}}
                        <td class="p-2" style="border-left:none;"></td> {{-- kosong tanpa border --}}
                        <td class="border border-black p-2 text-center">
                            Rp{{ number_format(
                                ($gajiPokok ?? 0) + ($bonus ?? 0) - (($totalPotonganIzin ?? 0) + ($totalPotonganAlpha ?? 0)),
                                0,
                                ',',
                                '.',
                            ) }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Tanda Tangan -->
            <div class="mt-16 flex justify-between">
                <div class="text-center">
                    <p>MENGETAHUI</p>
                    <div class="h-20"></div>
                    <p class="font-bold">ERLIN USNAHAROH</p>
                    <p>HRD</p>
                </div>
                <div class="text-center">
                    <p>MENGETAHUI</p>
                    <div class="h-20"></div>
                    <p class="font-bold uppercase">{{ $user->name }}</p>
                </div>
            </div>
        </div>
    </body>

</html>
