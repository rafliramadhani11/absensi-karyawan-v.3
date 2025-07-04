<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;

use function Spatie\LaravelPdf\Support\pdf;

class PdfController extends Controller
{
    public function __invoke(User $user, $start, $end)
    {
        return pdf()
            ->view('user.kinerja-pdf', compact('user', 'start', 'end'))
            ->name('Kinerja Absensi ' . $user->name);
    }
}
