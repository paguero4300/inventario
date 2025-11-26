<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                    ->description('Core product details')
                    ->icon('heroicon-o-cube')
                    ->schema([
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->live()
                            ->prefixIcon('heroicon-m-tag')
                            ->afterStateUpdated(fn(Set $set) => $set('specifications', [])),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-m-qr-code'),
                        TextInput::make('name')
                            ->required()
                            ->prefixIcon('heroicon-m-cube'),
                    ])->columns(2),

                Section::make('Specifications')
                    ->description('Category-specific attributes')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema(function (Get $get) {
                        $categoryId = $get('category_id');
                        if (! $categoryId) {
                            return [];
                        }
                        $category = Category::find($categoryId);
                        if (! $category || ! $category->specifications_schema) {
                            return [];
                        }

                        $fields = [];
                        foreach ($category->specifications_schema as $spec) {
                            $name = "specifications.{$spec['name']}";
                            $label = $spec['label'];
                            $type = $spec['type'];

                            if ($type === 'select') {
                                $fields[] = Select::make($name)
                                    ->label($label)
                                    ->options(array_combine($spec['options'], $spec['options']))
                                    ->prefixIcon('heroicon-m-check-circle');
                            } elseif ($type === 'text') {
                                $fields[] = TextInput::make($name)
                                    ->label($label)
                                    ->prefixIcon('heroicon-m-pencil');
                            }
                        }
                        return $fields;
                    })
                    ->columns(2),

                Section::make('Additional Details')
                    ->description('Extra information and status')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        TextInput::make('dimensions')
                            ->prefixIcon('heroicon-m-arrows-pointing-out'),
                        TextInput::make('color')
                            ->prefixIcon('heroicon-m-swatch'),
                        Textarea::make('notes')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->required()
                            ->default(true)
                            ->inline(false),
                    ])->columns(2),
            ]);
    }
}
