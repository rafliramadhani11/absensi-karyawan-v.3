<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Gaji</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 40px;
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

    <body>

        <h2>Penggajian
            {{ Carbon\Carbon::parse($start)->translatedFormat('j F Y') }} -
            {{ Carbon\Carbon::parse($end)->translatedFormat('j F Y') }}
        </h2>

        <div class="info">
            <div class="employee-details">
                <p><strong>Nama Karyawan:</strong> {{ Auth::user()->name }} </p>
                <p><strong>Divisi:</strong> {{ Auth::user()->division->name }} </p>
            </div>
            <div class="logo">
                <img src="{{ asset('img/logo-birdie-hexagon-light.png') }}" alt="Logo Perusahaan">
            </div>
        </div>

        @php
            use Carbon\Carbon;
            use App\Models\Attendance;
            use Illuminate\Support\Facades\Auth;

            $user = Auth::user();

            // Ambil semua data dari rentang yang diminta
            $attendances = Attendance::where('user_id', $user->id)
                ->whereBetween('created_at', [$start, $end])
                ->get();

            // Group by bulan (format: 2025-01, 2025-02, dst)
            $groupedAttendances = $attendances->groupBy(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m');
            });

            // Rate gaji dan potongan
            $gajiPerHadir = 200000;
            $potonganIzin = 10000;
            $potonganAlpha = 20000;
        @endphp

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Bulan</th>
                    <th>Hadir</th>
                    <th>Izin</th>
                    <th>Tidak Hadir</th>
                    <th>Gaji Hadir</th>
                    <th>Potongan Izin</th>
                    <th>Potongan Tidak Hadir</th>
                    <th>Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $grandTotal = 0;
                @endphp
                @foreach ($groupedAttendances as $month => $records)
                    @php
                        $carbon = Carbon::createFromFormat('Y-m', $month);
                        $bulan = $carbon->translatedFormat('F Y');

                        $hadir = $records->where('status', 'hadir')->count();
                        $izin = $records->where('status', 'izin')->count();
                        $alpha = $records->where('status', 'tidak hadir')->count();

                        $gajiHadir = $hadir * $gajiPerHadir;
                        $potIzin = $izin * $potonganIzin;
                        $potAlpha = $alpha * $potonganAlpha;

                        $totalGaji = $gajiHadir - $potIzin - $potAlpha;
                        $grandTotal += $totalGaji;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $bulan }}</td>
                        <td>{{ $hadir }}</td>
                        <td>{{ $izin }}</td>
                        <td>{{ $alpha }}</td>
                        <td>Rp{{ number_format($gajiHadir, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($potIzin, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($potAlpha, 0, ',', '.') }}</td>
                        <td>Rp{{ number_format($totalGaji, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="font-weight: bold;">
                    <td colspan="8" style="text-align: right;">Total Keseluruhan</td>
                    <td>Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>



        <div class="ttd">
            <div class="kanan">
                <p>Mengetahui</p>
                <p style="margin-top: 100px;">Andika</p>
            </div>
        </div>

    </body>

</html>
