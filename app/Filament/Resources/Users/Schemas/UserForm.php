<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('User Profile')
                    ->description('Account details and access')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->prefixIcon('heroicon-m-user'),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->prefixIcon('heroicon-m-envelope'),
                        TextInput::make('password')
                            ->password()
                            ->required()
                            ->prefixIcon('heroicon-m-lock-closed'),
                        TextInput::make('avatar_url')
                            ->prefixIcon('heroicon-m-photo'),
                        DateTimePicker::make('email_verified_at')
                            ->prefixIcon('heroicon-m-check-badge'),
                        Toggle::make('is_active')
                            ->required()
                            ->inline(false),
                    ])->columns(2),
            ]);
    }
}
