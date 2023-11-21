<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\Transaction;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use TransactionResource\Widgets\StatsOverview;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->options([
                        'cash' => 'Tunai',
                        'qris' => 'QRIS'
                    ])
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\DatePicker::make('date'),
                Forms\Components\FileUpload::make('proof')
                    ->image()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->formatStateUsing(fn($state) => $state == 'cash' ? 'Tunai' : 'QRIS')
                    ->searchable()
                    ->sortable(),
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
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),
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
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'cash' => 'Tunai',
                        'qris' => 'QRIS'
                    ]),
                SelectFilter::make('amount')
                    ->form([
                        Select::make('amount')
                            ->options([
                                '0-50000' => 'Rp 0 - Rp 50.000',
                                '50000-100000' => 'Rp 50.000 - Rp 100.000',
                                '100000-200000' => 'Rp 100.000 - Rp 200.000',
                                '200000-500000' => 'Rp 200.000 - Rp 500.000',
                                '500000>' => 'Rp 500.000 >>'
                            ])
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // if ($data['amount'] !== '500000>') {
                        //     $query->whereBetween('amount', explode('-', $data['amount']));
                        // } else {
                        //     $query->where('amount', '>', str_replace('>', '', $data['amount']));
                        // }
                        return $query
                            ->when(
                                $data['amount'],
                                function (Builder $query, $amount) {
                            if ($amount !== '500000>')
                                return $query->whereBetween('amount', explode('-', $amount));
                            else
                                return $query->where('amount', '>', str_replace('>', '', $amount));
                        }
                            );
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name'),
                TextEntry::make('amount')
                    ->money('IDR'),
                TextEntry::make('date'),
                ImageEntry::make('proof')
                    ->size('100%')
                    ->columnSpanFull()
            ]);
    }
}
