<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Spatie\LaravelPdf\Support\pdf;

class UserController extends Controller
{
    public function exportAttendances($start, $end)
    {
        return pdf()
            ->view('user.attendance.export-pdf', compact('start', 'end'))
            ->name('Kinerja Absensi ' . Auth::user()->name);
    }

    public function exportSalaries($start, $end)
    {
        return pdf()
            ->view('user.salary.export-pdf', compact('start', 'end'))
            ->name('Gaji ' . Auth::user()->name);
    }
}
