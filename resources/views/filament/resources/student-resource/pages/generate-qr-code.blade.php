<x-filament-panels::page>
    {!! QrCode::size(100)->generate($this->getRecord()); !!}
</x-filament-panels::page>
