<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use function Spatie\LaravelPdf\Support\pdf;

class PdfHRDSalaryController extends Controller
{
    public function usersSalaries($start, $end)
    {
        return pdf()
            ->view('user.salary.export-pdf', compact('start', 'end'))
            ->name('Penggajian seluruh karyawan ');
    }

    public function userSalaries(User $user, $start, $end)
    {
        return pdf()
            ->view('user.user-salary.export.pdf', compact('user', 'start', 'end'))
            ->name('Penggajian karyawan ');
    }
}
