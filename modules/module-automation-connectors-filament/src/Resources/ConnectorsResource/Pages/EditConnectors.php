<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Filament\Resources\ConnectorsResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Liberu\Modules\Automation\Connectors\Filament\Resources\ConnectorsResource;

final class EditConnectors extends EditRecord
{
    protected static string $resource = ConnectorsResource::class;
}
