<?php

namespace Wraps\FilamentContacts\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Wraps\FilamentContacts\Enums\ContactType;
use Wraps\FilamentContacts\Support\ContactColumn;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        ContactColumn::IS_PRIMARY => 'boolean',
        ContactColumn::TYPE => ContactType::class
    ];

    public function getTable()
    {
        return config('filament-contacts.table_name', parent::getTable());
    }

    public function contactable(): MorphTo
    {
        return $this->morphTo(ContactColumn::MORPH_NAME);
    }

    protected static function booted(): void
    {
        static::saving(function (Contact $contact) {
            // primary setup
            if ($contact->getAttribute(ContactColumn::IS_PRIMARY)) {
                //search for other primary contacts of the same type and set them to false
                static::where('contactable_type', $contact->contactable_type)
                    ->where('contactable_id', $contact->contactable_id)
                    ->where('id', '!=', $contact->id)
                    ->update([ContactColumn::IS_PRIMARY => false]);
            }
        });
    }
}
