<?php

declare(strict_types=1);

namespace Liberu\Modules\Automation\AiGateway\Filament\Resources\AiGatewayResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liberu\Modules\Automation\AiGateway\Filament\Resources\AiGatewayResource;

final class CreateAiGateway extends CreateRecord
{
    protected static string $resource = AiGatewayResource::class;
}
