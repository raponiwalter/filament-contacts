<?php

namespace Wraps\FilamentContacts\Support;

use Wraps\FilamentContacts\Enums\ContactType;

class ContactOptions
{
    public bool $isSearchable = true;
    public bool $isReadonly = false;
    public bool $useSlideOver = false; // Default: Classic modal

    public array $hiddenFields = [];
    public array $appendedSchema = []; // Extra fields injected by the user

    // Default: use ContactType enum, user can override with custom types
    public string|array $types = ContactType::class;

    public array $icons = [
        'create' => 'heroicon-m-plus',
        'delete' => 'heroicon-m-trash',
        'edit'   => 'heroicon-m-pencil-square',
    ];

    public static function make(): static
    {
        return new static();
    }

    public function searchable(bool $condition = true): static
    {
        $this->isSearchable = $condition;
        return $this;
    }

    public function readonly(bool $condition = true): static
    {
        $this->isReadonly = $condition;
        return $this;
    }

    public function slideOver(bool $condition = true): static
    {
        $this->useSlideOver = $condition;
        return $this;
    }

    public function hide(array $fields): static
    {
        $this->hiddenFields = $fields;
        return $this;
    }

    public function append(array $schemaComponents): static
    {
        $this->appendedSchema = $schemaComponents;
        return $this;
    }

    public function types(string|array $types): static
    {
        $this->types = $types;
        return $this;
    }

    public function icons(array $icons): static
    {
        $this->icons = array_merge($this->icons, $icons);
        return $this;
    }
}