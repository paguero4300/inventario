<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ProductInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('category.name')
                    ->label('Category'),
                TextEntry::make('sku')
                    ->label('SKU')
                    ->copyable()
                    ->weight('bold'),
                TextEntry::make('name')
                    ->label('Name'),

                // Dynamic configuration levels display
                TextEntry::make('config_level_0')
                    ->label('Level 0')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 0);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 0)),

                TextEntry::make('config_level_1')
                    ->label('Level 1')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 1);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 1)),

                TextEntry::make('config_level_2')
                    ->label('Level 2')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 2);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 2)),

                TextEntry::make('config_level_3')
                    ->label('Level 3')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 3);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 3)),

                TextEntry::make('config_level_4')
                    ->label('Level 4')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 4);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 4)),

                TextEntry::make('config_level_5')
                    ->label('Level 5')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 5);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 5)),

                TextEntry::make('config_level_6')
                    ->label('Level 6')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 6);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 6)),

                TextEntry::make('config_level_7')
                    ->label('Level 7')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 7);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 7)),

                TextEntry::make('config_level_8')
                    ->label('Level 8')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 8);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 8)),

                TextEntry::make('config_level_9')
                    ->label('Level 9')
                    ->state(function ($record) {
                        return static::getConfigLevelName($record, 9);
                    })
                    ->visible(fn($record) => static::hasConfigLevel($record, 9)),

                TextEntry::make('dimensions')
                    ->label('Dimensions'),
                TextEntry::make('color')
                    ->label('Color'),
                TextEntry::make('notes')
                    ->label('Notes')
                    ->columnSpanFull(),
                IconEntry::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label('Updated At')
                    ->dateTime(),
            ]);
    }

    /**
     * Check if a configuration level exists in specifications
     */
    protected static function hasConfigLevel($record, int $level): bool
    {
        if (!$record || !isset($record->specifications)) {
            return false;
        }

        $specifications = is_array($record->specifications)
            ? $record->specifications
            : json_decode($record->specifications, true);

        return isset($specifications["config_level_{$level}"]);
    }

    /**
     * Get the name of the configuration option for a given level
     */
    protected static function getConfigLevelName($record, int $level): ?string
    {
        if (!static::hasConfigLevel($record, $level)) {
            return null;
        }

        $specifications = is_array($record->specifications)
            ? $record->specifications
            : json_decode($record->specifications, true);

        $optionId = $specifications["config_level_{$level}"] ?? null;

        if (!$optionId) {
            return null;
        }

        $option = \App\Models\ConfigurationOption::find($optionId);

        return $option ? $option->name : "ID: {$optionId}";
    }
}
