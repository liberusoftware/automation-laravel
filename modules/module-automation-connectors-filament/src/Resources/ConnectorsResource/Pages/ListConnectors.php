<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\Connectors\Filament\Resources\ConnectorsResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Liberu\Modules\Automation\Connectors\Filament\Resources\ConnectorsResource;

final class ListConnectors extends ListRecords
{
    protected static string $resource = ConnectorsResource::class;
}
