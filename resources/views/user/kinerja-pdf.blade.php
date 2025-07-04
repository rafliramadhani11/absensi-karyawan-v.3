<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Kinerja Absensi Karyawan</title>
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

        <h2>ABSENSI KARYAWAN
            {{ Carbon\Carbon::parse($start)->translatedFormat('j F Y') }} -
            {{ Carbon\Carbon::parse($end)->translatedFormat('j F Y') }}
        </h2>

        <div class="info">
            <div class="employee-details">
                <p><strong>Nama Karyawan:</strong> {{ $user->name }} </p>
                <p><strong>Divisi:</strong> {{ $user->division->name }} </p>
            </div>
            <div class="logo">
                <img src="{{ asset('img/logo-birdie-hexagon-light.png') }}" alt="Logo Perusahaan">
            </div>
        </div>

        <?php
        use App\Models\Attendance;
        use Carbon\Carbon;
        
        $attendances = Attendance::where('user_id', $user->id)
            ->whereBetween('created_at', [$start, $end])
            ->orderBy('created_at', 'asc')
            ->get();
        ?>

        <table>
            <thead>
                <tr>
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
                        $hari = Carbon::parse($attendance->created_at)->locale('id')->isoFormat('dddd');
                        $hari = ucfirst($hari);
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tanggal }}</td>
                        <td>{{ $hari }}</td>
                        <td>{{ $attendance->absen_datang ?? '-' }}</td>
                        <td>{{ $attendance->absen_pulang ?? '-' }}</td>
                        <td>{{ $attendance->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="ttd">
            <div class="kanan">
                <p>Mengetahui</p>
                <p style="margin-top: 100px;">Andika</p>
            </div>
        </div>

    </body>

</html>
