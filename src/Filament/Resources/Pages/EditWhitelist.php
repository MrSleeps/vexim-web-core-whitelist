<?php

namespace VEximweb\Core\Whitelist\Filament\Resources\Pages;

use VEximweb\Core\Whitelist\Filament\Resources\WhitelistResource;
use VEximweb\Core\Data\Models\EximUser;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;
use Illuminate\Support\Facades\Log;

class EditWhitelist extends EditRecord
{
    protected static string $resource = WhitelistResource::class;
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        Log::info('=== mutateFormDataBeforeSave called ===');
        Log::info('Data received:', $data);
        
        // Check if domain_wide is set and true
        $isDomainWide = isset($data['domain_wide']) && $data['domain_wide'];
        
        // For domain-wide entries, ensure localpart is null and user_id is null
        if ($isDomainWide) {
            $data['localpart'] = null;
            $data['user_id'] = null;
            Log::info('Domain wide - setting localpart and user_id to null');
        } 
        // For non-domain-wide entries, handle user_id and localpart logic
        else {
            // If user_id is provided, look up the localpart
            if (isset($data['user_id']) && !empty($data['user_id'])) {
                $eximUser = EximUser::where('user_id', $data['user_id'])->first();
                if ($eximUser) {
                    $data['localpart'] = $eximUser->localpart;
                    Log::info('Looked up localpart from user_id', [
                        'user_id' => $data['user_id'],
                        'localpart' => $data['localpart']
                    ]);
                }
            }
            
            // For domain users, force localpart from their account
            if (auth()->user()->isDomainUser()) {
                $eximUser = EximUser::where('username', auth()->user()->email)->first();
                if ($eximUser) {
                    $data['localpart'] = $eximUser->localpart;
                    $data['user_id'] = $eximUser->user_id;
                    Log::info('Set localpart and user_id for domain user', [
                        'localpart' => $data['localpart'],
                        'user_id' => $data['user_id']
                    ]);
                }
            }
        }
        
        // Clean up form-only fields
        unset($data['domain_wide']);
        unset($data['user_id_auto']);
        unset($data['localpart_auto']);
        unset($data['localpart_display']);
        
        Log::info('Final data after mutation:', $data);
        
        return $data;
    }
    
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }    
    
protected function mutateFormDataBeforeFill(array $data): array
{
    // If localpart is null and domain_id is set, it's domain-wide
    if (isset($data['domain_id']) && $data['domain_id'] > 0 && is_null($data['localpart'])) {
        $data['domain_wide'] = true;
    } else {
        $data['domain_wide'] = false;
    }
    
    return $data;
}    
}