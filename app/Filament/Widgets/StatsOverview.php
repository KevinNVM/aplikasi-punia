<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Riskihajar\Terbilang\Facades\Terbilang;
use Illuminate\Support\Facades\Config;

class StatsOverview extends BaseWidget
{

    protected static ?string $pollingInterval = '15s';

    protected static bool $isLazy = true;

    protected function getStats(): array
    {


        return [
            Stat::make('Total Transactions', Transaction::count())
                ->description('Total transaksi yang telah diterima')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([1, 1, 3, 4, 2]),

            Stat::make(
                'Total Jumlah',
                'Rp ' . $this->shortenNumber(Transaction::sum('amount'))
            )
                ->description('Total jumlah uang yang diterima')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([1, 1, 3, 4, 2]),
        ];
    }

    private function shortenNumber($number)
    {
        $suffix = '';

        if ($number >= 1000000) {
            $number /= 1000000;
            $suffix = 'M';
        } elseif ($number >= 1000) {
            $number /= 1000;
            $suffix = 'K';
        }

        // Format the number to have at most two decimal places
        $formattedNumber = number_format($number, 2, ',');

        // Remove trailing zeros and the decimal point if there are no decimal places
        $formattedNumber = rtrim($formattedNumber, '0');
        $formattedNumber = rtrim($formattedNumber, '.');

        return $formattedNumber . $suffix;
    }
}
