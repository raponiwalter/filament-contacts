<?php

namespace Wraps\FilamentContacts\Filament\Tables\Columns;

use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Wraps\FilamentContacts\Support\ContactColumn as SupportContactColumn;

class ContactColumn extends TextColumn
{
    protected string $contactType = 'email';
    protected bool $isPrimaryOnly = false;
    protected string $relationName = 'contacts';
    protected string $valueAttribute = 'value';

    protected function setUp(): void
    {
        parent::setUp();

        $this->getStateUsing(function (Model $record) {
            $relName = $this->getRelationName();

            if (! $record->relationLoaded($relName) && ! isset($record->$relName)) {
                return null;
            }

            $contacts = $record->$relName;

            if (! $contacts instanceof Collection) {
                return null;
            }

            $contact = $contacts->first(function ($item) {
                $typeMatch = $item->type === $this->getContactType();
                $primaryMatch = $this->isPrimaryOnly() ? $item->is_primary === true : true;
                return $typeMatch && $primaryMatch;
            });

            return $contact ? $contact->{$this->getValueAttribute()} : null;
        });
    }

    public function asPhone(): static
    {
        $this->contactType = SupportContactColumn::PHONE;
        $this->icon('heroicon-m-phone');
        return $this;
    }

    public function asEmail(): static
    {
        $this->contactType = SupportContactColumn::EMAIL;
        $this->icon('heroicon-m-envelope');
        return $this;
    }

    public function type(string $type): static
    {
        $this->contactType = $type;
        return $this;
    }

    public function primaryOnly(bool $condition = true): static
    {
        $this->isPrimaryOnly = $condition;
        return $this;
    }

    public function relation(string $relationName): static
    {
        $this->relationName = $relationName;
        return $this;
    }

    public function valueAttribute(string $attribute): static
    {
        $this->valueAttribute = $attribute;
        return $this;
    }

    public function getContactType(): string
    {
        return $this->contactType;
    }

    public function isPrimaryOnly(): bool
    {
        return $this->isPrimaryOnly;
    }

    public function getRelationName(): string
    {
        return $this->relationName;
    }

    public function getValueAttribute(): string
    {
        return $this->valueAttribute;
    }
}