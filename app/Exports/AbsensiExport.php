<?php

namespace App\Exports;

use App\Models\Attendance;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class AbsensiExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Attendance::whereBetween('clock_in', [$this->startDate, $this->endDate . ' 23:59:59'])
            ->with('user')->orderBy('clock_in', 'asc')->get();
    }

    public function headings(): array
    {
        return [
            'Nama Kasir',
            'Tanggal',
            'Jam Masuk',
            'Jam Pulang',
            'Total Jam Kerja',
            'Status',
        ];
    }

    public function map($attendance): array
    {
        $clockIn = Carbon::parse($attendance->clock_in);
        $clockOut = $attendance->clock_out ? Carbon::parse($attendance->clock_out) : null;

        $totalKerja = 'N/A';
        if ($clockOut) {
            $totalKerja = $clockIn->diff($clockOut)->format('%H jam %i menit');
        }

        //Masuk tepat waktu jika sebelum jam 09:00
        $status = $clockIn->hour < 9 ? 'On Time' : 'Late';

        return [
            $attendance->user->name ?? 'N/A',
            $clockIn->format('Y-m-d'),
            $clockIn->format('H:i:s'),
            $clockOut ? $clockOut->format('H:i:s') : 'N/A',
            $totalKerja,
            $status,
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A1:F1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1:F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            },
        ];
    }
}