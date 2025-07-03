@extends('sidebar.SideBar')

@section('title', 'Absensi Kehadiran')

@push('styles')
    <style>
        .status-badge {
            font-size: 0.8rem;
            padding: 0.4em 0.7em;
            border-radius: 15px;
        }

        .status-ontime {
            background-color: #d1fae5;
            color: #067647;
        }

        .status-late {
            background-color: #fee2e2;
            color: #b91c1c;
        }

        .top-header-bar {
            background-color: #ffffff;
            padding: 0.75rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .075);
        }

        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }
    </style>
@endpush

@section('content')
    <nav class="top-header-bar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="fa-solid fa-bars me-3" id="sidebar-toggler" style="cursor: pointer;"></i>
            <span>Absensi Kehadiran</span>
        </div>
        <div class="d-flex align-items-center">
            <i class="fa-regular fa-bell me-3"></i>
            <div class="user-profile">
                <img src="https://i.pravatar.cc/150?u={{ auth()->id() }}" alt="User Avatar">
            </div>
        </div>
    </nav>


    <main class="main-content">
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                    <div class="card shadow-sm">
                        <div class="card-body p-5 text-center">
                            <i class="fa-solid fa-fingerprint fa-4x text-primary mb-4"></i>
                            <h3 class="card-title">Selamat Datang, {{ auth()->user()->name }}!</h3>

                            @if(session('success'))
                                <div class="alert alert-success mt-3">{{ session('success') }}</div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger mt-3">{{ session('error') }}</div>
                            @endif

                            @if ($hasClockedIn)
                                <p class="text-muted mt-3">Anda sudah melakukan absensi masuk pada:</p>
                                <p class="lead fw-bold">{{ $lastClockInTime }}</p>
                                <button class="btn btn-danger btn-lg mt-3" data-bs-toggle="modal"
                                    data-bs-target="#passwordModal">
                                    <i class="fa-solid fa-right-from-bracket me-2"></i> Clock Out
                                </button>
                            @else
                                <p class="text-muted mt-3">Silakan lakukan absensi untuk memulai sesi kerja Anda.</p>
                                <button class="btn btn-primary btn-lg mt-3" data-bs-toggle="modal"
                                    data-bs-target="#passwordModal">
                                    <i class="fa-solid fa-right-to-bracket me-2"></i> Clock In
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-custom mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Riwayat Absensi Anda</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4" style="width: 5%;">No</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Total Jam Kerja</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendanceHistory as $item)
                                    @php
                                        
                                        $clockInTime = \Carbon\Carbon::parse($item->clock_in);
                                        $clockOutTime = $item->clock_out ? \Carbon\Carbon::parse($item->clock_out) : null;

                                      
                                        $isLate = $clockInTime->hour >= 9;

                                      
                                        $workDuration = '-';
                                        if ($clockOutTime) {
                                            $totalMinutes = $clockInTime->diffInMinutes($clockOutTime);
                                            $hours = floor($totalMinutes / 60);
                                            $minutes = $totalMinutes % 60;
                                            $workDuration = sprintf('%02d jam %02d menit', $hours, $minutes);
                                        }
                                    @endphp
                                    <tr>
                                        <td class="ps-4">{{ $loop->iteration + $attendanceHistory->firstItem() - 1 }}</td>
                                        <td>{{ $clockInTime->format('d M Y') }}</td>
                                        <td>{{ $clockInTime->format('H:i:s') }}</td>
                                        <td>
                                            @if($clockOutTime)
                                                {{ $clockOutTime->format('H:i:s') }}
                                            @else
                                                <span class="text-muted">Belum Clock Out</span>
                                            @endif
                                        </td>
                                        <td>{{ $workDuration }}</td>
                                        <td class="text-center">
                                            @if($isLate)
                                                <span class="badge status-late">Late</span>
                                            @else
                                                <span class="badge status-ontime">On Time</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Belum ada riwayat absensi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    @if($attendanceHistory->hasPages())
                        <div class="card-footer bg-white">
                            {{ $attendanceHistory->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordModalLabel">Konfirmasi Absensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ $hasClockedIn ? route('attendance.clock-out') : route('attendance.clock-in') }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <p>Untuk melanjutkan, silakan masukkan password Anda.</p>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection