<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SpkExport implements FromCollection, WithHeadings
{
    protected $hasil;

    public function __construct(array $hasil)
    {
        $this->hasil = $hasil;
    }

    public function collection(): Collection
    {
        return collect($this->hasil)->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Nama' => $item->nama,
                'Nilai' => $item->nilai,
                'Status' => $item->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['No', 'Nama', 'Nilai', 'Status'];
    }
}
