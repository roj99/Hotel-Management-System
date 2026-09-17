<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use UnitEnum;
use App\Filament\Concerns\RestrictedPageToRoles;

class SettingsPage extends Page implements HasForms
{
    use InteractsWithForms,RestrictedPageToRoles;

    protected static string|UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'General Settings';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Settings';

    protected string $view = 'filament.pages.settings-page';

    /**
     * Backing state for the form (Filament reads/writes this via
     * ->statePath('data') below). Keys here are the setting "key" column
     * values that get persisted to the settings table.
     */
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'hotel_name' => Setting::get('hotel_name'),
            'hotel_logo' => Setting::get('hotel_logo'),
            'hotel_address' => Setting::get('hotel_address'),
            'hotel_currency' => Setting::get('hotel_currency', 'USD'),

            'notification_email' => Setting::get('notification_email'),
            'mail_from_name' => Setting::get('mail_from_name'),
            'mail_from_email' => Setting::get('mail_from_email'),
            'send_booking_confirmation' => (bool) Setting::get('send_booking_confirmation', true),
            'send_cancellation_notice' => (bool) Setting::get('send_cancellation_notice', true),
            'send_checkin_reminder' => (bool) Setting::get('send_checkin_reminder', true),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Hotel Information')
                    ->description('General details shown across the admin panel and on guest-facing documents (invoices, emails).')
                    ->columns(2)
                    ->schema([
                        TextInput::make('hotel_name')
                            ->label('Hotel Name')
                            ->required()
                            ->maxLength(255),

                        Select::make('hotel_currency')
                            ->label('Currency')
                            ->options([
                                'USD' => 'USD — US Dollar',
                                'EUR' => 'EUR — Euro',
                                'JOD' => 'JOD — Jordanian Dinar',
                                'SAR' => 'SAR — Saudi Riyal',
                                'AED' => 'AED — UAE Dirham',
                            ])
                            ->required()
                            ->native(false),

                        Textarea::make('hotel_address')
                            ->label('Address')
                            ->rows(2)
                            ->columnSpanFull(),

                        FileUpload::make('hotel_logo')
                            ->label('Logo')
                            ->image()
                            ->avatar()
                            ->disk('public')
                            ->directory('settings')
                            ->columnSpanFull(),
                    ]),

                Section::make('Email & Notifications')
                    ->description('Where system emails come from, and which automatic notifications are active.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('mail_from_name')
                            ->label('From Name')
                            ->maxLength(255),

                        TextInput::make('mail_from_email')
                            ->label('From Email')
                            ->email()
                            ->maxLength(255),

                        TextInput::make('notification_email')
                            ->label('Notification Email')
                            ->helperText('Where internal alerts (e.g. new cancellations) are sent.')
                            ->email()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Toggle::make('send_booking_confirmation')
                            ->label('Send booking confirmation emails')
                            ->columnSpanFull(),

                        Toggle::make('send_cancellation_notice')
                            ->label('Send cancellation notice emails')
                            ->columnSpanFull(),

                        Toggle::make('send_checkin_reminder')
                            ->label('Send check-in reminder emails')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, is_bool($value) ? ($value ? '1' : '0') : $value);
        }

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->action('save'),
        ];
    }
}
