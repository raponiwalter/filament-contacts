<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Wraps\FilamentContacts\Enums\ContactType;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = config('filament-contacts.table_name', 'contacts');

        Schema::create($tableName, function (Blueprint $table) {
            $table->id();
            $table->morphs('contactable');
            $table->string('type')->default(ContactType::Other->value);

            $table->string('address')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();


            if (config('filament-contacts.features.is_primary')) {
                $table->boolean('is_primary')->default(false);
            }

            $table->timestamps();

            if (config('filament-contacts.features.soft_deletes')) {
                $table->softDeletes();
            }
        });
    }

    public function down(): void
    {
        $tableName = config('filament-contacts.table_name', 'contacts');
        Schema::dropIfExists($tableName);
    }
};
