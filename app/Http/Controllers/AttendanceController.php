<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function absenDatang(Request $request)
    {
        $user = Auth::user();
        $time = $request->time;

        return DB::transaction(function () use ($user, $time, $request) {
            $currentInterval = Carbon::now()->startOfMinute()->floorMinutes(1)->format('H:i');

            $existingAttendance = Attendance::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->lockForUpdate()
                ->first();

            if ($request->userId != Auth::id()) {
                return redirect()->back()->with('status', 'absen-gagal');
            }

            if ($time !== $currentInterval) {
                return redirect()->back()->with('status', 'code-kadaluarsa');
            }

            if ($existingAttendance) {
                return back()->with('status', 'absen-already');
            }

            Attendance::create([
                'user_id' => $user->id,
                'absen_datang' => $time,
                'status' => 'proses',
            ]);

            return back()->with('status', 'absen-updated');
        });
    }

    public function absenPulang(Request $request)
    {
        $user = Auth::user();
        $time = $request->time;

        return DB::transaction(function () use ($user, $time, $request) {
            $currentInterval = Carbon::now()->startOfMinute()->floorMinutes(1)->format('H:i');
            $attendance = Attendance::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->lockForUpdate()
                ->first();

            if ($request->userId != Auth::id()) {
                return redirect()->back()->with('status', 'absen-gagal');
            }

            if ($time !== $currentInterval) {
                return redirect()->back()->with('status', 'code-kadaluarsa');
            }

            if (!$attendance) {
                return back()->with('status', 'absen-datang-not-yet');
            }

            if ($attendance->absen_pulang) {
                return back()->with('status', 'absen-already');
            }

            $attendance->update([
                'absen_pulang' => $time,
                'status' => 'hadir',
            ]);

            return back()->with('status', 'absen-updated');
        });
    }
}
