<?php

namespace Wraps\FilamentContacts\Filament\Resources\RelationManagers;

use BackedEnum;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Wraps\FilamentContacts\Support\ContactColumn;
use Wraps\FilamentContacts\Support\ContactOptions;
use Filament\Resources\RelationManagers\RelationManager;

class ContactsRelationManager extends RelationManager
{
    protected static string $relationship = 'contacts';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('filament-contacts::resources.contacts.title');
    }

    public function getContactOptions(): ContactOptions
    {
        $record = $this->getOwnerRecord();

        if (method_exists($record, 'getFilamentContactOptions')) {
            return $record->getFilamentContactOptions();
        }
        return ContactOptions::make();
    }

    public function isReadOnly(): bool
    {
        $options = $this->getContactOptions();

        if ($options->isReadonly !== null) {
            return $options->isReadonly;
        }

        return parent::isReadOnly();
    }

    public function form(Schema $schema): Schema
    {
        $options = $this->getContactOptions();

        $standardComponents = [
            Select::make(ContactColumn::TYPE)
                ->translateLabel(__('filament-contacts::resources.contacts.fields.type'))
                ->options($options->types)
                ->required()
                ->columnSpan(1),

            Toggle::make(ContactColumn::IS_PRIMARY)
                ->translateLabel(__('filament-contacts::resources.contacts.fields.is_primary'))
                ->inline(false)
                ->helperText(__('filament-contacts::resources.contacts.fields.is_primary_helper'))
                ->columnSpan(1),

            TextInput::make(ContactColumn::EMAIL)
                ->translateLabel(__('filament-contacts::resources.contacts.fields.email'))
                ->email()
                ->hidden(in_array(ContactColumn::EMAIL, $options->hiddenFields))
                ->columnSpanFull(),

            TextInput::make(ContactColumn::PHONE)
                ->translateLabel(__('filament-contacts::resources.contacts.fields.phone'))
                ->tel()
                ->hidden(in_array(ContactColumn::PHONE, $options->hiddenFields))
                ->columnSpanFull(),

            Textarea::make(ContactColumn::ADDRESS)
                ->translateLabel(__('filament-contacts::resources.contacts.fields.address'))
                ->rows(3)
                ->hidden(in_array(ContactColumn::ADDRESS, $options->hiddenFields))
                ->columnSpanFull(),
        ];

        $components = array_merge($standardComponents, $options->appendedSchema);

        return $schema
            ->components($components)
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        $options = $this->getContactOptions();

        return $table
            ->recordTitleAttribute(ContactColumn::TYPE)
            ->columns([
                TextColumn::make(ContactColumn::TYPE)
                    ->label(__('filament-contacts::resources.contacts.fields.type'))
                    ->badge()
                    ->sortable(),

                IconColumn::make(ContactColumn::IS_PRIMARY)
                    ->label(__('filament-contacts::resources.contacts.fields.is_primary'))
                    ->boolean()
                    ->trueIcon('heroicon-s-star')
                    ->falseIcon('heroicon-o-star')
                    ->color(fn (string $state): string => $state ? 'warning' : 'gray')
                    ->sortable()
                    ->action(function (Model $record) {
                        if ($record->is_primary) {
                            return;
                        }
                        $this->getOwnerRecord()->contacts()->update(['is_primary' => false]);
                        $record->update(['is_primary' => true]);
                    }),

                TextColumn::make(ContactColumn::EMAIL)
                    ->label(__('filament-contacts::resources.contacts.fields.email'))
                    ->icon('heroicon-m-envelope')
                    ->copyable()
                    ->searchable($options->isSearchable)
                    ->toggleable(isToggledHiddenByDefault: in_array(ContactColumn::EMAIL, $options->hiddenFields)),

                TextColumn::make(ContactColumn::PHONE)
                    ->label(__('filament-contacts::resources.contacts.fields.phone'))
                    ->icon('heroicon-m-phone')
                    ->copyable()
                    ->searchable($options->isSearchable)
                    ->toggleable(isToggledHiddenByDefault: in_array(ContactColumn::PHONE, $options->hiddenFields)),

                TextColumn::make(ContactColumn::ADDRESS)
                    ->label(__('filament-contacts::resources.contacts.fields.address'))
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->{ContactColumn::ADDRESS})
                    ->toggleable(isToggledHiddenByDefault: in_array(ContactColumn::ADDRESS, $options->hiddenFields)),
            ])
            ->filters([
                // Filtri eventuali
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('filament-contacts::resources.contacts.actions.add_contact'))
                    ->icon($options->icons['create'] ?? 'heroicon-m-plus')
                    ->slideOver($options->useSlideOver)
                    ->visible(! $options->isReadonly),
            ])
            ->actions([
                EditAction::make()
                    ->icon($options->icons['edit'] ?? 'heroicon-m-pencil-square')
                    ->slideOver($options->useSlideOver)
                    ->visible(! $options->isReadonly),

                DeleteAction::make()
                    ->icon($options->icons['delete'] ?? 'heroicon-m-trash')
                    ->visible(! $options->isReadonly),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(! $options->isReadonly),
                ]),
            ]);
    }
}