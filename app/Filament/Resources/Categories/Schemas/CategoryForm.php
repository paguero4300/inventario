<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('General Information')
                    ->description('Basic details about the category')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->prefixIcon('heroicon-m-tag'),
                        TextInput::make('slug')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-m-link'),
                        TextInput::make('icon')
                            ->prefixIcon('heroicon-m-photo'),
                        Select::make('default_unit_id')
                            ->relationship('defaultUnit', 'name')
                            ->prefixIcon('heroicon-m-scale'),
                        Toggle::make('is_active')
                            ->required()
                            ->default(true)
                            ->inline(false),
                        TextInput::make('sort_order')
                            ->required()
                            ->numeric()
                            ->default(0)
                            ->prefixIcon('heroicon-m-arrows-up-down'),
                    ])->columns(2),
            ]);
    }
}
