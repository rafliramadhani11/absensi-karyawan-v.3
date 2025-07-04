<?php

namespace App\Http\Controllers;

use App\Models\Division;
use Illuminate\Http\Request;

use function Spatie\LaravelPdf\Support\pdf;

class DivisionPdfController extends Controller
{
    public function __invoke(Division $division, $start, $end)
    {
        return pdf()
            ->view('division.kinerja-pdf', compact('division', 'start', 'end'))
            ->name('Kinerja Divisi ' . $division->name);
    }
}
