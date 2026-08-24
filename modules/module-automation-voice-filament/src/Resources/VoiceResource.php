<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Voice\Filament\Resources;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource\Pages\CreateVoice;
use Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource\Pages\EditVoice;
use Liberu\Modules\Automation\Voice\Filament\Resources\VoiceResource\Pages\ListVoice;
use Liberu\Modules\Automation\Voice\Models\VoiceResource as VoiceRecord;

final class VoiceResource extends Resource
{
    protected static ?string $model = VoiceRecord::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-bolt';

    protected static string|\UnitEnum|null $navigationGroup = 'Automation';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(255),
            Select::make('status')
                ->options([
                    'draft' => 'Draft',
                    'active' => 'Active',
                    'paused' => 'Paused',
                    'completed' => 'Completed',
                    'failed' => 'Failed',
                    'cancelled' => 'Cancelled',
                    'published' => 'Published',
                ])
                ->default('draft')
                ->required(),
            Textarea::make('payload')
                ->formatStateUsing(static fn (?array $state): string => json_encode($state ?? [], JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR))
                ->dehydrateStateUsing(static fn (?string $state): array => is_string($state) && trim($state) !== '' ? (json_decode($state, true, 512, JSON_THROW_ON_ERROR) ?: []) : [])
                ->json(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('status')->badge()->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVoice::route('/'),
            'create' => CreateVoice::route('/create'),
            'edit' => EditVoice::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $teamId = auth()->user()?->currentTeam?->getKey();

        return parent::getEloquentQuery()->where('team_id', $teamId);
    }
}
