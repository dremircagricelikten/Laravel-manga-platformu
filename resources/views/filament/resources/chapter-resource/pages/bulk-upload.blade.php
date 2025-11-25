<div>
    <x-filament-panels::page>
        <form wire:submit="upload">
            {{ $this->form }}
            
            <div class="mt-6">
                <x-filament::button type="submit" color="success" icon="heroicon-o-arrow-up-tray" size="lg">
                    Upload All Chapters
                </x-filament::button>
            </div>
        </form>
    </x-filament-panels::page>
</div>
