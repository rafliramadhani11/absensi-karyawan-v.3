<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use function Spatie\LaravelPdf\Support\pdf;

class PdfControllerAllEmployee extends Controller
{
    public function __invoke($start, $end)
    {
        return pdf()
            ->view('hrd.kinerja-all-employee', compact('start', 'end'))
            ->name('Kinerja Absensi Semua Karyawan');
    }
}
