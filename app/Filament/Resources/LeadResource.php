<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Customer;
use App\Models\MarketingSource;
use App\Models\User;
use Filament\Resources\Forms\Components;
use Filament\Resources\Forms\Form;
use Filament\Resources\Resource;
use Filament\Resources\Tables\Columns;
use Filament\Resources\Tables\Filter;
use Filament\Resources\Tables\Table;

class LeadResource extends Resource
{
    public static $icon = 'heroicon-o-user-add';
    public static $label = 'Khách tiềm năng';
    public static $navigationLabel = 'Khách tiềm năng';
    public static $navigationSort = 11;

    public static function statusLabels()
    {
        return [
            'new' => 'Mới',
            'contacted' => 'Đã liên hệ',
            'qualified' => 'Tiềm năng',
            'converted' => 'Đã chuyển đổi',
            'lost' => 'Không thành công',
        ];
    }

    public static function form(Form $form)
    {
        return $form->schema([
            Components\TextInput::make('full_name')->label('Họ tên'),
            Components\TextInput::make('phone')->label('Điện thoại')->required(),
            Components\TextInput::make('email')->label('Email')->email(),
            Components\Select::make('source_id')->label('Nguồn marketing')
                ->options(MarketingSource::where('status', 'active')->pluck('name', 'id')->toArray()),
            Components\TextInput::make('campaign')->label('Chiến dịch'),
            Components\TextInput::make('medium')->label('Phương thức'),
            Components\TextInput::make('keyword')->label('Từ khóa'),
            Components\Textarea::make('requirement')->label('Nhu cầu'),
            Components\Select::make('status')->label('Trạng thái')
                ->options(static::statusLabels())->default('new')->required(),
            Components\Select::make('assigned_to')->label('Nhân viên phụ trách')
                ->options(User::pluck('name', 'id')->toArray()),
            Components\Select::make('customer_id')->label('Khách hàng đã chuyển đổi')
                ->options(Customer::pluck('full_name', 'id')->toArray()),
            Components\DateTimePicker::make('contacted_at')->label('Thời gian liên hệ'),
            Components\DateTimePicker::make('converted_at')->label('Thời gian chuyển đổi'),
        ]);
    }

    public static function table(Table $table)
    {
        return $table
            ->columns([
                Columns\Text::make('full_name')->label('Họ tên')->primary()->searchable()->sortable(),
                Columns\Text::make('phone')->label('Điện thoại')->searchable(),
                Columns\Text::make('marketingSource.name')->label('Nguồn'),
                Columns\Text::make('campaign')->label('Chiến dịch')->limit(25),
                Columns\Text::make('requirement')->label('Nhu cầu')->limit(35),
                Columns\Text::make('assignedTo.name')->label('Phụ trách'),
                Columns\Text::make('status')->label('Trạng thái')->options(static::statusLabels()),
                Columns\Text::make('created_at')->label('Ngày tạo')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([
                Filter::make('new', function ($query) {
                    return $query->where('status', 'new');
                })->label('Lead mới'),
                Filter::make('contacted', function ($query) {
                    return $query->where('status', 'contacted');
                })->label('Đã liên hệ'),
                Filter::make('qualified', function ($query) {
                    return $query->where('status', 'qualified');
                })->label('Tiềm năng'),
                Filter::make('converted', function ($query) {
                    return $query->where('status', 'converted');
                })->label('Đã chuyển đổi'),
            ]);
    }

    public static function routes()
    {
        return [
            Pages\ListLeads::routeTo('/', 'index'),
            Pages\CreateLead::routeTo('/create', 'create'),
            Pages\EditLead::routeTo('/{record}/edit', 'edit'),
        ];
    }
}
