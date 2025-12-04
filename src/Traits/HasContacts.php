<?php

namespace Wraps\FilamentContacts\Traits;

use Wraps\FilamentContacts\Models\Contact;
use Wraps\FilamentContacts\Support\ContactColumn;
use Wraps\FilamentContacts\Support\ContactOptions;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasContacts
{
    /**
     * Relazione polimorfica.
     */
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, ContactColumn::MORPH_NAME);
    }

    public function primaryContact(): MorphOne
    {
        return $this->morphOne(Contact::class, ContactColumn::MORPH_NAME)
                    ->where('is_primary', true);
    }

    /**
     * Metodo di configurazione.
     * L'utente può sovrascriverlo nel suo Modello per personalizzare il comportamento.
     */
    public function getFilamentContactOptions(): ContactOptions
    {
        return ContactOptions::make();
    }
}