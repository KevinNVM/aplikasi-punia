<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Filters\TextFilter;
use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;
use Rappasoft\LaravelLivewireTables\Views\Filters\SelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\DateRangeFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\MultiSelectFilter;
use Rappasoft\LaravelLivewireTables\Views\Filters\NumberRangeFilter;

class PublicTxTable extends DataTableComponent
{
    protected $model = Transaction::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Jenis", "type")
                ->format(fn($val) => $val == 'cash' ? 'Tunai' : 'QRIS')
                ->sortable(),
            Column::make("Nama", "name")
                ->searchable()
                ->sortable(),
            Column::make("Jumlah", "amount")
                ->format(fn($value) => 'Rp ' . number_format($value, '2', ',', '.'))
                ->sortable()
                ->secondaryHeader(function ($rows) {
                    return 'Subtotal: ' . 'Rp ' . number_format($rows->sum('amount'), '2', ',', '.');
                }),
            Column::make("Tanggal", "date")
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            SelectFilter::make('Jenis')
                ->options(
                    [
                        '' => 'Semua',
                        'qris' => 'QRIS',
                        'cash' => 'Tunai'
                    ]
                )
                ->filter(function (Builder $builder, $value) {
                    return $builder->where('type', $value);
                }),
            SelectFilter::make('Jumlah')
                ->options([
                    '' => 'Semua',
                    '0-50000' => 'Rp 0 - Rp 50.000',
                    '50000-100000' => 'Rp 50.000 - Rp 100.000',
                    '100000-200000' => 'Rp 100.000 - Rp 200.000',
                    '200000-500000' => 'Rp 200.000 - Rp 500.000',
                    '500000>' => 'Rp 500.000 >>'
                ])
                ->filter(function (Builder $builder, $value) {
                    if ($value === '') {
                        return;
                    }

                    $range = explode('-', $value);

                    if (count($range) === 2) {
                        $builder->whereBetween('amount', [$range[0], $range[1]]);
                    } elseif (strpos($value, '500000>') === 0) {
                        $builder->where('amount', '>', 500000);
                    }
                })
            ,
            DateRangeFilter::make('Waktu')
                ->setFilterPillValues([0 => 'minDate', 1 => 'maxDate'])
                ->filter(function (Builder $builder, array $dateRange) { // Expects an array.
                    $builder
                        ->whereDate('date', '>=', $dateRange['minDate']) // minDate is the start date selected
                        ->whereDate('date', '<=', $dateRange['maxDate']); // maxDate is the end date selected
                })
            ,
        ];
    }
}
