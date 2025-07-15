<!DOCTYPE html>
<html lang="id">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Detail Pribadi Karyawan</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            /* Menambahkan font kustom jika diperlukan, contoh menggunakan Google Fonts */
            @import url('https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&family=Lato:wght@400;700&display=swap');

            body {
                font-family: 'Lato', sans-serif;
            }

            .font-tinos {
                font-family: 'Tinos', serif;
            }

            .logo {
                flex: 0 0 auto;
            }

            .logo img {
                height: 60px;
                width: auto;
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

            <h3 class="mb-8 text-center text-xl font-bold tracking-wider">DETAIL PRIBADI KARYAWAN</h3>

            <div class="grid grid-cols-[max-content,1fr] gap-x-6 gap-y-3 text-base">
                <div class="font-semibold">NAMA</div>
                <div>: {{ $user->name }}</div>
                <div class="font-semibold">NIK</div>
                <div>: {{ $user->nik }}</div>
                <div class="font-semibold">EMAIL</div>
                <div>: {{ $user->email }}</div>
                <div class="font-semibold">NO. TELEPON</div>
                <div>: {{ $user->phone }}</div>
                <div class="font-semibold">JENIS KELAMIN</div>
                <div>: {{ $user->gender }}</div>
                <div class="font-semibold">TANGGAL LAHIR</div>
                <div>: {{ $user->birth_date }}</div>
                <div class="font-semibold">ALAMAT</div>
                <div>: {{ $user->address }}</div>
                <div class="font-semibold">DIVISI</div>
                <div>: {{ $user->division->name }}</div>
            </div>

            <div class="mt-16 flex justify-end">
                <div class="text-center">
                    <p>Mengetahui,</p>
                    <div class="relative h-24 w-48">

                    </div>
                    <p class="font-bold underline">Erlin Usnaharoh</p>
                    <p class="font-semibold">HRD</p>
                </div>
            </div>
        </div>
    </body>

</html>
