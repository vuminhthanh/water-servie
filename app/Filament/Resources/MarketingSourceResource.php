<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarketingSourceResource\Pages;
use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\Table;

class MarketingSourceResource extends Resource
{
    public static $icon = 'heroicon-o-speakerphone';
    public static $label = 'Nguồn marketing';
    public static $navigationLabel = 'Nguồn marketing';
    public static $navigationSort = 11;

    public static function channelLabels()
    {
        return [
            'google' => 'Google',
            'facebook' => 'Facebook',
            'tiktok' => 'TikTok',
            'zalo' => 'Zalo',
            'seo' => 'SEO',
            'referral' => 'Khách giới thiệu',
            'offline' => 'Offline',
            'other' => 'Khác',
        ];
    }

    public static function form(Form $form)
    {
        return $form->schema([
            Components\TextInput::make('name')
                ->label('Tên nguồn')
                ->placeholder('Ví dụ: Facebook Ads')
                ->required()
                ->maxLength(255),
            Components\TextInput::make('code')
                ->label('Mã nguồn')
                ->placeholder('Ví dụ: FACEBOOK')
                ->required()
                ->maxLength(50)
                ->unique(static::getModel(), 'code', true),
            Components\Select::make('channel')
                ->label('Kênh')
                ->options(static::channelLabels())
                ->required(),
            Components\Select::make('status')
                ->label('Trạng thái')
                ->options(['active' => 'Hoạt động', 'inactive' => 'Ngừng hoạt động'])
                ->default('active')
                ->required(),
        ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('code')->label('Mã')->primary()->searchable()->sortable(),
                Columns\Text::make('name')->label('Tên nguồn')->searchable()->sortable(),
                Columns\Text::make('channel')->label('Kênh')->options(static::channelLabels()),
                Columns\Text::make('leads_count')->label('Số lead')->sortable(),
                Columns\Text::make('status')->label('Trạng thái')->options([
                    'active' => 'Hoạt động',
                    'inactive' => 'Ngừng hoạt động',
                ]),
                Columns\Text::make('created_at')->label('Ngày tạo')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Filter::make('active', function ($query) {
                    return $query->where('status', 'active');
                })->label('Đang hoạt động'),
                Filter::make('google', function ($query) {
                    return $query->where('channel', 'google');
                })->label('Google'),
                Filter::make('facebook', function ($query) {
                    return $query->where('channel', 'facebook');
                })->label('Facebook'),
            ]);
    }

    public static function routes()
    {
        return [
            Pages\ListMarketingSources::routeTo('/', 'index'),
            Pages\CreateMarketingSource::routeTo('/create', 'create'),
            Pages\EditMarketingSource::routeTo('/{record}/edit', 'edit'),
        ];
    }
}
