<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use App\Models\ConfigurationOption;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
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
                Wizard::make([
                    // STEP 1: Basic Information
                    Step::make('Basic Information')
                        ->description('Select category and product name')
                        ->icon('heroicon-o-cube')
                        ->schema([
                            Select::make('category_id')
                                ->label('Category')
                                ->options(function () {
                                    return Category::query()
                                        ->where('is_active', true)
                                        ->orderBy('name')
                                        ->pluck('name', 'id')
                                        ->toArray();
                                })
                                ->searchable()
                                ->native(false)
                                ->required()
                                ->live()
                                ->prefixIcon('heroicon-m-tag')
                                ->placeholder('Choose a category first...')
                                ->helperText('Select the category to unlock product configuration')
                                ->afterStateUpdated(function (Set $set) {
                                    // Reset cascade when category changes
                                    for ($i = 0; $i <= 9; $i++) {
                                        $set("config_level_{$i}", null);
                                    }
                                    $set('sku', '');
                                })
                                ->columnSpanFull(),

                            TextInput::make('name')
                                ->label('Product Name')
                                ->required()
                                ->prefixIcon('heroicon-m-cube')
                                ->placeholder('Enter product name...')
                                ->helperText('Descriptive name for this product')
                                ->columnSpanFull(),


                            // SKU Field - ReadOnly but included in submit
                            TextInput::make('sku')
                                ->label('Generated SKU')
                                ->unique(ignoreRecord: true)
                                ->readOnly()
                                ->dehydrated()
                                ->prefixIcon('heroicon-m-qr-code')
                                ->placeholder('Will be generated from configuration')
                                ->helperText('Auto-generated based on your configuration choices')
                                ->default('')
                                ->columnSpanFull(),
                        ])
                        ->columns(1)
                        ->completedIcon('heroicon-m-check-badge'),

                    // STEP 2: Product Configuration
                    Step::make('Configuration')
                        ->description('Configure product using cascade selectors')
                        ->icon('heroicon-o-adjustments-horizontal')
                        ->schema([
                            Placeholder::make('config_help')
                                ->label('📋 Configuration Guide')
                                ->content('Select options step by step. Each choice unlocks the next level.')
                                ->columnSpanFull(),

                            // Level 0 - Root
                            Select::make('config_level_0')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 0))
                                ->options(fn(Get $get) => static::getOptions($get, 0))
                                ->visible(fn(Get $get) => static::hasOptions($get, 0))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Select material...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 0))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 0, $state);
                                })
                                ->columnSpanFull(),

                            // Level 1
                            Select::make('config_level_1')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 1))
                                ->options(fn(Get $get) => static::getOptions($get, 1))
                                ->visible(fn(Get $get) => static::hasOptions($get, 1))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Choose next option...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 1))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 1, $state);
                                })
                                ->columnSpanFull(),

                            // Level 2
                            Select::make('config_level_2')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 2))
                                ->options(fn(Get $get) => static::getOptions($get, 2))
                                ->visible(fn(Get $get) => static::hasOptions($get, 2))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Choose next option...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 2))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 2, $state);
                                })
                                ->columnSpanFull(),

                            // Level 3
                            Select::make('config_level_3')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 3))
                                ->options(fn(Get $get) => static::getOptions($get, 3))
                                ->visible(fn(Get $get) => static::hasOptions($get, 3))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Choose next option...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 3))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 3, $state);
                                })
                                ->columnSpanFull(),

                            // Level 4
                            Select::make('config_level_4')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 4))
                                ->options(fn(Get $get) => static::getOptions($get, 4))
                                ->visible(fn(Get $get) => static::hasOptions($get, 4))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Choose next option...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 4))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 4, $state);
                                })
                                ->columnSpanFull(),

                            // Level 5
                            Select::make('config_level_5')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 5))
                                ->options(fn(Get $get) => static::getOptions($get, 5))
                                ->visible(fn(Get $get) => static::hasOptions($get, 5))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Choose next option...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 5))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 5, $state);
                                })
                                ->columnSpanFull(),

                            // Level 6
                            Select::make('config_level_6')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 6))
                                ->options(fn(Get $get) => static::getOptions($get, 6))
                                ->visible(fn(Get $get) => static::hasOptions($get, 6))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Choose next option...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 6))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 6, $state);
                                })
                                ->columnSpanFull(),

                            // Level 7
                            Select::make('config_level_7')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 7))
                                ->options(fn(Get $get) => static::getOptions($get, 7))
                                ->visible(fn(Get $get) => static::hasOptions($get, 7))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Choose next option...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 7))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 7, $state);
                                })
                                ->columnSpanFull(),

                            // Level 8
                            Select::make('config_level_8')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 8))
                                ->options(fn(Get $get) => static::getOptions($get, 8))
                                ->visible(fn(Get $get) => static::hasOptions($get, 8))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Choose next option...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 8))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 8, $state);
                                })
                                ->columnSpanFull(),

                            // Level 9
                            Select::make('config_level_9')
                                ->label(fn(Get $get) => static::getSelectLabel($get, 9))
                                ->options(fn(Get $get) => static::getOptions($get, 9))
                                ->visible(fn(Get $get) => static::hasOptions($get, 9))
                                ->native(false)
                                ->searchable()
                                ->live()
                                ->placeholder('Choose next option...')
                                ->helperText(fn(Get $get) => static::getHelperText($get, 9))
                                ->afterStateUpdated(function (Set $set, $state, Get $get) {
                                    static::handleSelectionChange($set, $get, 9, $state);
                                })
                                ->columnSpanFull(),

                            // Completion Badge
                            Placeholder::make('config_complete')
                                ->label('✅ Configuration Complete')
                                ->content(fn(Get $get) => 'Generated SKU: **' . $get('sku') . '**')
                                ->visible(fn(Get $get) => !empty($get('sku')) && static::isConfigComplete($get))
                                ->columnSpanFull(),
                        ])
                        ->columns(1)
                        ->completedIcon('heroicon-m-check-badge'),

                    // STEP 3: Additional Details
                    Step::make('Details')
                        ->description('Extra information and product status')
                        ->icon('heroicon-o-clipboard-document-list')
                        ->schema([
                            TextInput::make('dimensions')
                                ->label('Dimensions')
                                ->prefixIcon('heroicon-m-arrows-pointing-out')
                                ->placeholder('e.g., 10x20x30 cm')
                                ->columnSpanFull(),

                            TextInput::make('color')
                                ->label('Color')
                                ->prefixIcon('heroicon-m-swatch')
                                ->placeholder('e.g., Blue, Red, Custom')
                                ->columnSpanFull(),

                            Textarea::make('notes')
                                ->label('Additional Notes')
                                ->placeholder('Any special instructions or details...')
                                ->rows(3)
                                ->columnSpanFull(),

                            Toggle::make('is_active')
                                ->label('Active Product')
                                ->required()
                                ->default(true)
                                ->inline(false)
                                ->helperText('Enable this to make the product visible in the system')
                                ->columnSpanFull(),
                        ])
                        ->columns(1)
                        ->completedIcon('heroicon-m-check-badge'),
                ])
                    ->columnSpanFull()
                    ->skippable(false)
                    ->persistStepInQueryString(),
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
        for ($i = $level + 1; $i <= 9; $i++) {
            $set("config_level_{$i}", null);
        }

        // Generate SKU
        $skuParts = [];
        for ($i = 0; $i <= 9; $i++) {
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
        for ($level = 9; $level >= 0; $level--) {
            $selectedId = $get("config_level_{$level}");
            if ($selectedId) {
                $option = ConfigurationOption::find($selectedId);
                return $option && !$option->hasChildren();
            }
        }
        return false;
    }

    protected static function getHelperText(Get $get, int $level): string
    {
        $selectedId = $get("config_level_{$level}");

        if ($selectedId) {
            $option = ConfigurationOption::find($selectedId);
            if ($option && $option->hasChildren()) {
                return "Step {$level}: Continue selecting to refine your configuration";
            }
            return "Step {$level}: Selection complete ✓";
        }

        return "Step {$level}: Make your selection";
    }
}
