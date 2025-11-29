<?php

namespace App\Filament\Resources\PaymentSettingResource\Pages;

use App\Filament\Resources\PaymentSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Cache;

class EditPaymentSetting extends EditRecord
{
    protected static string $resource = PaymentSettingResource::class;

    protected function afterSave(): void
    {
        // Clear cache after updating settings
        Cache::forget("payment_setting_{$this->record->key}");
    }
}
