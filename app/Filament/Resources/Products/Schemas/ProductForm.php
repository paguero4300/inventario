<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\ConfigurationOption;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
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
                            ->afterStateUpdated(function (Set $set) {
                                // Reset cascade when category changes
                                $set('config_level_0', null);
                                $set('config_level_1', null);
                                $set('config_level_2', null);
                                $set('config_level_3', null);
                                $set('config_level_4', null);
                                $set('sku', '');
                            }),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-m-qr-code')
                            ->readOnly()
                            ->helperText('Auto-generated from configuration'),
                        TextInput::make('name')
                            ->required()
                            ->prefixIcon('heroicon-m-cube'),
                    ])->columns(2),

                Section::make('Product Configuration')
                    ->description('Configure product using cascade selectors')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        // Level 0 - Root
                        Select::make('config_level_0')
                            ->label(fn(Get $get) => static::getSelectLabel($get, 0))
                            ->options(fn(Get $get) => static::getOptions($get, 0))
                            ->visible(fn(Get $get) => static::hasOptions($get, 0))
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                static::handleSelectionChange($set, $get, 0, $state);
                            }),

                        // Level 1
                        Select::make('config_level_1')
                            ->label(fn(Get $get) => static::getSelectLabel($get, 1))
                            ->options(fn(Get $get) => static::getOptions($get, 1))
                            ->visible(fn(Get $get) => static::hasOptions($get, 1))
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                static::handleSelectionChange($set, $get, 1, $state);
                            }),

                        // Level 2
                        Select::make('config_level_2')
                            ->label(fn(Get $get) => static::getSelectLabel($get, 2))
                            ->options(fn(Get $get) => static::getOptions($get, 2))
                            ->visible(fn(Get $get) => static::hasOptions($get, 2))
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                static::handleSelectionChange($set, $get, 2, $state);
                            }),

                        // Level 3
                        Select::make('config_level_3')
                            ->label(fn(Get $get) => static::getSelectLabel($get, 3))
                            ->options(fn(Get $get) => static::getOptions($get, 3))
                            ->visible(fn(Get $get) => static::hasOptions($get, 3))
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                static::handleSelectionChange($set, $get, 3, $state);
                            }),

                        // Level 4
                        Select::make('config_level_4')
                            ->label(fn(Get $get) => static::getSelectLabel($get, 4))
                            ->options(fn(Get $get) => static::getOptions($get, 4))
                            ->visible(fn(Get $get) => static::hasOptions($get, 4))
                            ->live()
                            ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                static::handleSelectionChange($set, $get, 4, $state);
                            }),

                        Placeholder::make('config_complete')
                            ->label('✅ Configuration Complete')
                            ->content(fn(Get $get) => 'SKU: ' . $get('sku'))
                            ->visible(fn(Get $get) => !empty($get('sku')) && static::isConfigComplete($get))
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->collapsible()
                    ->visible(fn(Get $get) => $get('category_id') !== null),

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

    protected static function getOptions(Get $get, int $level): array
    {
        if ($level === 0) {
            // Root level - get by category
            $categoryId = $get('category_id');
            if (!$categoryId) {
                return [];
            }

            return ConfigurationOption::query()
                ->whereNull('parent_id')
                ->where('category_id', $categoryId)
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->pluck('name', 'id')
                ->toArray();
        }

        // Child levels - get by parent
        $parentLevel = $level - 1;
        $parentId = $get("config_level_{$parentLevel}");

        if (!$parentId) {
            return [];
        }

        return ConfigurationOption::query()
            ->where('parent_id', $parentId)
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->pluck('name', 'id')
            ->toArray();
    }

    protected static function hasOptions(Get $get, int $level): bool
    {
        return count(static::getOptions($get, $level)) > 0;
    }

    protected static function getSelectLabel(Get $get, int $level): string
    {
        if ($level === 0) {
            return 'Select Material';
        }

        $parentLevel = $level - 1;
        $parentId = $get("config_level_{$parentLevel}");

        if (!$parentId) {
            return 'Next Step';
        }

        $parent = ConfigurationOption::find($parentId);
        return $parent?->next_step_label ?? 'Next Step';
    }

    protected static function handleSelectionChange(Set $set, Get $get, int $level, $optionId): void
    {
        // Reset subsequent levels
        for ($i = $level + 1; $i <= 4; $i++) {
            $set("config_level_{$i}", null);
        }

        // Generate SKU
        $skuParts = [];
        for ($i = 0; $i <= 4; $i++) {
            $selectedId = $get("config_level_{$i}");
            if ($selectedId) {
                $option = ConfigurationOption::find($selectedId);
                if ($option && $option->sku_part) {
                    $skuParts[] = $option->sku_part;
                }
            }
        }

        $sku = !empty($skuParts) ? implode('-', $skuParts) : '';
        $set('sku', $sku);
    }

    protected static function isConfigComplete(Get $get): bool
    {
        // Check if last selected option has no children
        for ($level = 4; $level >= 0; $level--) {
            $selectedId = $get("config_level_{$level}");
            if ($selectedId) {
                $option = ConfigurationOption::find($selectedId);
                return $option && !$option->hasChildren();
            }
        }
        return false;
    }
}
