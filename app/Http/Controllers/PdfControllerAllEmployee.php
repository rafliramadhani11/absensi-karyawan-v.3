<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use function Spatie\LaravelPdf\Support\pdf;

class PdfControllerAllEmployee extends Controller
{
    public function pdf($start, $end)
    {
        return pdf()
            ->view('hrd.kinerja-all-employee', compact('start', 'end'))
            ->name('Kinerja Absensi Semua Karyawan');
    }

    public function cutiPdf(User $user)
    {
        $cutis = $user->cutis()
            ->where('status', 'approved')
            ->get();

        // siapkan array bulan 1–12 dengan default false
        $months = collect(range(1, 12))
            ->mapWithKeys(fn($m) => [$m => false]);

        // tandai bulan yang ada cutinya
        foreach ($cutis as $cuti) {
            $start = \Carbon\Carbon::parse($cuti->start_date);
            $end   = \Carbon\Carbon::parse($cuti->end_date);

            // loop harian supaya tidak lompat bulan
            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $months[$date->month] = true;
            }
        }

        return pdf()
            ->view('hrd.user-cuti.pdf-export', compact('user', 'cutis', 'months'))
            ->name("Cuti-{$user->name}.pdf");
    }
}
