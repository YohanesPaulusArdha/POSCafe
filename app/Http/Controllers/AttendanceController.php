<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Attendance;
use Carbon\Carbon;

class AttendanceController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $lastAttendance = Attendance::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->latest('clock_in')
            ->first();

        $attendanceHistory = Attendance::where('user_id', $user->id)
            ->orderBy('clock_in', 'desc')
            ->paginate(10);

        return view('Attendance', [
            'hasClockedIn' => !is_null($lastAttendance),
            'lastClockInTime' => $lastAttendance ? Carbon::parse($lastAttendance->clock_in)->isoFormat('dddd, D MMMM YYYY, HH:mm:ss') : null,
            'attendanceHistory' => $attendanceHistory
        ]);
    }

    public function clockIn(Request $request)
    {
        $request->validate(['password' => 'required']);
        $user = Auth::user();

        if (!Hash::check($request->password, (string) $user->password)) {
            return redirect()->back()->with('error', 'Password yang Anda masukkan salah.');
        }

        $existing = Attendance::where('user_id', $user->id)->whereNull('clock_out')->first();
        if ($existing) {
            return redirect()->back()->with('error', 'Anda sudah melakukan clock-in dan belum clock-out.');
        }

        Attendance::create([
            'user_id' => $user->id,
            'clock_in' => Carbon::now('Asia/Jakarta'),
        ]);

        return redirect()->route('attendance.index')->with('success', 'Absensi masuk (Clock-In) berhasil!');
    }


    public function clockOut(Request $request)
    {
        $request->validate(['password' => 'required']);
        $user = Auth::user();

        if (!Hash::check($request->password, (string) $user->password)) {
            return redirect()->back()->with('error', 'Password yang Anda masukkan salah.');
        }

        $attendance = Attendance::where('user_id', $user->id)
            ->whereNull('clock_out')
            ->latest('clock_in')
            ->first();

        if (!$attendance) {
            return redirect()->back()->with('error', 'Tidak ditemukan catatan clock-in yang aktif.');
        }

        $attendance->update(['clock_out' => Carbon::now('Asia/Jakarta')]);

        return redirect()->route('attendance.index')->with('success', 'Absensi keluar (Clock-Out) berhasil!');
    }
}