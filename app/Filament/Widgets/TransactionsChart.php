<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class TransactionsChart extends ChartWidget
{

    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {

        $data = $this->getProductsPerMonth();
        return [
            'datasets' => [
                [
                    'label' => 'Transaksi masuk',
                    'data' => $data['productsPerMonth']
                ]
            ],
            'labels' => $data['months']
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    private function getProductsPerMonth(): array
    {
        $now = Carbon::now();

        $productsPerMonth = [];
        $months = collect(range(1, 12))->map(function ($month) use ($now, &$productsPerMonth) {
            $formattedMonth = Carbon::parse($now->month($month)->format('Y-m'));
            $count = Transaction::whereMonth('date', $formattedMonth)->count();

            $productsPerMonth[] = $count;

            return $now->month($month)->format('M');
        })->toArray();

        // Debugging: Dump the contents of $productsPerMonth to see what it contains
        // dd($productsPerMonth);

        return [
            'productsPerMonth' => $productsPerMonth,
            'months' => $months
        ];
    }
}
