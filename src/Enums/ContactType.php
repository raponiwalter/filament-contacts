<?php

namespace Wraps\FilamentContacts\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum ContactType: string implements HasLabel, HasColor
{
    case Work = 'work';
    case Home = 'home';
    case Mobile = 'mobile';
    case Other = 'other';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Work => __('filament-contacts::enums.work'),
            self::Home => __('filament-contacts::enums.home'),
            self::Mobile => __('filament-contacts::enums.mobile'),
            self::Other => __('filament-contacts::enums.other'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Work => 'info',
            self::Home => 'success',
            self::Mobile => 'warning',
            self::Other => 'gray',
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::Work => 'heroicon-o-briefcase',
            self::Home => 'heroicon-o-home',
            self::Mobile => 'heroicon-o-phone',
            self::Other => 'heroicon-o-question-mark-circle',
        };
    }
}
