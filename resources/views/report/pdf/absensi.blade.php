<!DOCTYPE html>
<html>

<head>
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
            font-size: 12px;
        }

        th {
            background-color: #f2f2f2;
        }

        h2 {
            text-align: center;
        }

        .date-range {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <h2>Laporan Absensi Karyawan</h2>
    <div class="date-range">
        Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </div>
    <table>
        <thead>
            <tr>
                <th>Nama Kasir</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Total Jam Kerja</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $attendance)
                <tr>
                    <td>{{ $attendance->user->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->clock_in)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->clock_in)->format('H:i:s') }}</td>
                    <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_out)->format('H:i:s') : 'N/A' }}
                    </td>
                    <td>{{ $attendance->clock_out ? \Carbon\Carbon::parse($attendance->clock_in)->diff(\Carbon\Carbon::parse($attendance->clock_out))->format('%H jam %i menit') : 'N/A' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>