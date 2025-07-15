<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use function Spatie\LaravelPdf\Support\pdf;

class PdfHRDSalaryController extends Controller
{
    public function __invoke($start, $end)
    {
        return pdf()
            ->view('user.salary.export-pdf', compact('start', 'end'))
            ->name('Penggajian seluruh karyawan ');
    }
}
