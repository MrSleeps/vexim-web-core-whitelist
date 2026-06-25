<?php

namespace VEximweb\Core\Whitelist\Filament\Resources\Pages;

use VEximweb\Core\Whitelist\Filament\Resources\WhitelistResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWhitelists extends ListRecords
{
    protected static string $resource = WhitelistResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
