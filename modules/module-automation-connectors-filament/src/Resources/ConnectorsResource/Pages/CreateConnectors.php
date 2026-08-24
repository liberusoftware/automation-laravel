<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Filament\Resources\ConnectorsResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\Connectors\Filament\Resources\ConnectorsResource;

final class CreateConnectors extends CreateRecord
{
    protected static string $resource = ConnectorsResource::class;
}
