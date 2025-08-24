<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <title>Cuti {{ $user->name }}</title>
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
                <div>NIK : {{ $user->nik ?? '-' }}</div>
                <div>Nama : {{ $user->name }}</div>
                <div>Divisi : {{ $user->division->name ?? '-' }}</div>
            </div>

            <table>
                <tr>
                    <th colspan="12" style="background: red; color: white;">
                        Periode Cuti : 01 Januari - 31 Desember {{ now()->year }}
                    </th>
                </tr>
                <tr>
                    @foreach (range(1, 12) as $m)
                        <th>{{ $m }}</th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($months as $m => $isCuti)
                        <td style="background: {{ $isCuti ? '#90ee90' : '#f08080' }}">
                            {{ $isCuti ? '✔' : 'X' }}
                        </td>
                    @endforeach
                </tr>
            </table>

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
