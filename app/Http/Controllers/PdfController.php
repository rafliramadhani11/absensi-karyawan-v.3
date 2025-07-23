<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

use function Spatie\LaravelPdf\Support\pdf;

class PdfController extends Controller
{
    public function kinerja(User $user, $start, $end)
    {
        return pdf()
            ->view('user.kinerja-pdf', compact('user', 'start', 'end'))
            ->name('Absensi ' . $user->name);
    }

    public function kinerjaAbsensi(User $user, $start, $end)
    {
        return pdf()
            ->view('user.kinerja-absensi-karaywan', compact('user', 'start', 'end'))
            ->name('Kinerja Absensi ' . $user->name);
    }
}
