<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GrossProfitExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $nettSales;
    protected $grossSales;

    public function __construct($startDate, $endDate, $nettSales, $grossSales)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->nettSales = $nettSales;
        $this->grossSales = $grossSales;
    }

    public function collection()
    {
        return Order::whereBetween('created_at', [$this->startDate, $this->endDate . ' 23:59:59'])
            ->with(['details.product', 'user'])->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'No Invoice',
            'Waktu Order',
            'Nama Kasir',
            'Menu',
            'Total Qty',
            'Metode Pembayaran',
            'Sub Total',
            'Service',
            'PB01',
            'Total',
        ];
    }

    public function map($order): array
    {
        $subtotal = $order->details->sum('subtotal');
        $service = $subtotal * 0.05;
        $pb01 = $subtotal * 0.10;

        $menuItems = $order->details->map(function ($detail) {
            return ($detail->product->name ?? 'Produk Dihapus') . ' (' . $detail->quantity . ')';
        })->implode(', ');

        return [
            $order->invoice_number,
            $order->created_at->format('Y-m-d H:i:s'),
            $order->user->name ?? 'N/A',
            $menuItems,
            $order->details->sum('quantity'),
            $order->payment_method,
            $subtotal,
            $service,
            $pb01,
            $order->total_amount,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cellRange = 'A3:J3';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setBold(true);

                $event->sheet->getDelegate()->insertNewRowBefore(1, 2);
                $event->sheet->getDelegate()->mergeCells('A1:J1');
                $event->sheet->getDelegate()->setCellValue('A1', 'LAPORAN LABA KOTOR (GROSS PROFIT)');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                $lastRow = $event->sheet->getDelegate()->getHighestRow() + 2;
                $event->sheet->getDelegate()->setCellValue("I{$lastRow}", 'TOTAL NETT SALES:');
                $event->sheet->getDelegate()->setCellValue("J{$lastRow}", $this->nettSales);
                $event->sheet->getDelegate()->getStyle("I{$lastRow}:J{$lastRow}")->getFont()->setBold(true);

                $lastRow++;
                $event->sheet->getDelegate()->setCellValue("I{$lastRow}", 'TOTAL GROSS SALES:');
                $event->sheet->getDelegate()->setCellValue("J{$lastRow}", $this->grossSales);
                $event->sheet->getDelegate()->getStyle("I{$lastRow}:J{$lastRow}")->getFont()->setBold(true);
            },
        ];
    }
}