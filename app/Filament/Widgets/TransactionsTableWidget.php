<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Range;
use App\Filament\Resources\TransactionResource;
use Filament\Widgets\TableWidget as BaseWidget;

class TransactionsTableWidget extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(TransactionResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn($state) => $state == 'cash' ? 'Tunai' : 'QRIS')
                    ->searchable()
                    ->badge()
                    ->sortable()
                    ->color(fn(string $state): string => match ($state) {
                        'cash' => 'warning',
                        'qris' => 'success'
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric(
                        decimalPlaces: 0,
                        decimalSeparator: '.',
                        thousandsSeparator: ',',
                    )
                    ->money('IDR')
                    ->sortable()
                    ->summarize(Sum::make()->money("IDR")),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->summarize(Range::make()
                        ->minimalDateTimeDifference()),
                Tables\Columns\ImageColumn::make('proof')
                    ->size(75)
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ]);
    }
}
