<?php

namespace App\Filament\Resources\Units\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Unit Details')
                    ->description('Define measurement units')
                    ->icon('heroicon-o-scale')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->prefixIcon('heroicon-m-tag'),
                        TextInput::make('abbreviation')
                            ->required()
                            ->prefixIcon('heroicon-m-hashtag'),
                        Toggle::make('is_base_unit')
                            ->required()
                            ->inline(false),
                    ])->columns(2),
            ]);
    }
}
