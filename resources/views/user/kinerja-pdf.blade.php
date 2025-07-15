<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Kinerja Absensi Karyawan</title>
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
                <h3 class="mb-2 text-left text-xl font-bold tracking-wider">
                    LAPORAN ABSENSI {{ Carbon\Carbon::parse($start)->translatedFormat('j F Y') }} -
                    {{ Carbon\Carbon::parse($end)->translatedFormat('j F Y') }}
                </h3>
                <p>Nama : {{ $user->name }}</p>
                <p>Divisi : {{ $user->division->name }}</p>
            </div>

            <?php
            use App\Models\Attendance;
            use Carbon\Carbon;
            
            $attendances = Attendance::whereBetween('created_at', [$start, $end])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'asc')
                ->get();
            
            $totalHadir = $attendances->where('status', 'hadir')->count();
            $totalTidakHadir = $attendances->where('status', 'tidak hadir')->count();
            $totalIzin = $attendances->where('status', 'izin')->count();
            
            $totalTelat = $attendances->where('status', 'hadir')->where('absen_datang', '>', '08:00:00')->count();
            ?>

            <table class="w-full border-collapse border border-gray-400 text-left">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-400 px-4 py-2">No</th>
                        <th class="border border-gray-400 px-4 py-2">Tanggal</th>
                        <th class="border border-gray-400 px-4 py-2">Hari</th>
                        <th class="border border-gray-400 px-4 py-2">Jam Masuk</th>
                        <th class="border border-gray-400 px-4 py-2">Jam Pulang</th>
                        <th class="border border-gray-400 px-4 py-2">Keterangan</th>
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
                            <td class="border border-gray-400 px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="border border-gray-400 px-4 py-2">{{ $tanggal }}</td>
                            <td class="border border-gray-400 px-4 py-2">{{ $hari }}</td>
                            <td class="border border-gray-400 px-4 py-2">
                                @if ($attendance->absen_datang > '08:00:00')
                                    <span class="font-semibold text-red-600">{{ $attendance->absen_datang }}</span>
                                @else
                                    <span>{{ $attendance->absen_datang ?? '-' }}</span>
                                @endif
                            </td>
                            <td class="border border-gray-400 px-4 py-2">{{ $attendance->absen_pulang ?? '-' }}</td>
                            <td class="border border-gray-400 px-4 py-2">
                                <span>{{ ucwords($attendance->status) }}</span>
                                @if ($attendance->status === 'hadir' && $attendance->absen_datang > '08:00:00')
                                    <span class="font-semibold text-red-600"> (telat)</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

            <div class="mt-8">
                <div class="flex justify-between space-x-8">
                    <div>
                        <div class="text-right font-semibold">
                            <p>HADIR: {{ $totalHadir }}</p>
                            <p>TIDAK HADIR: {{ $totalTidakHadir }}</p>
                            <p>IZIN: {{ $totalIzin }}</p>
                            <p class="text-red-600">TOTAL TELAT: {{ $totalTelat }}</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p>Mengetahui,</p>
                        <div class="relative h-24 w-48">
                        </div>
                        <p class="font-bold underline">Erlin Usnaharoh</p>
                        <p class="font-semibold">HRD</p>
                    </div>
                </div>
            </div>
        </div>



    </body>

</html>
