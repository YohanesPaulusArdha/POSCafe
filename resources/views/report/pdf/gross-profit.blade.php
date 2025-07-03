<!DOCTYPE html>
<html>

<head>
    <title>Laporan Gross Profit</title>
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
    <h1>Laporan Gross Profit</h1>
    <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} -
        {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
    </p>
    <hr>
    <table>
        <thead>
            <tr>
                <th>Invoice</th>
                <th>Tanggal</th>
                <th>Subtotal (Nett)</th>
                <th>Pajak & Servis</th>
                <th>Total (Gross)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->invoice_number }}</td>
                    <td>{{ $order->created_at->format('d M Y, H:i') }}</td>
                    @php
                        $subtotal = $order->details->sum('subtotal');
                        $taxAndService = $order->total_amount - $subtotal;
                    @endphp
                    <td>Rp. {{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($taxAndService, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
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