<!DOCTYPE html>
<html>

<head>
    <title>Laporan Metode Pembayaran</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        h1 {
            text-align: center;
            margin-bottom: 0;
        }

        p {
            text-align: center;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <h1>Laporan Metode Pembayaran</h1>
    <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Metode Pembayaran</th>
                <th style="text-align: center;">Jumlah Transaksi</th>
                <th style="text-align: right;">Total Uang Masuk</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paymentMethods as $method)
                <tr>
                    <td>{{ $method->payment_method }}</td>
                    <td style="text-align: center;">{{ $method->transaction_count }}</td>
                    <td style="text-align: right;">Rp. {{ number_format($method->total_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>