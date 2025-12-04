<?php

namespace Wraps\FilamentContacts;

use Filament\Contracts\Plugin;
use Filament\Panel;

class ContactsPlugin implements Plugin
{
    public function getId(): string
    {
        return 'filament-contacts';
    }

    public function register(Panel $panel): void
    {
        // to be implemented if needed
    }

    public function boot(Panel $panel): void
    {
        // Boot logic if needed
    }

    public static function make(): static
    {
        return new static();
    }
}