@php
    $data = isset($state) ? $state : (isset($getState) ? $getState() : []);
    $record = isset($getRecord) ? $getRecord() : null;
@endphp

<div class="my-2 text-sm font-medium tracking-tight">
    @if($record && method_exists($record, 'trashed') && $record->trashed())
        <x-filament::button
            color="primary"
            icon="heroicon-o-arrow-path"
            wire:click="restore({{ $record->id }})"
            class="mb-2"
        >
            {{ __('filament-auditing::filament-auditing.action.restore') }}
        </x-filament::button>
    @endif
    <ul>
        @foreach($data ?? [] as $key => $value)
            <li>
                <span class="inline-block rounded-md whitespace-normal text-gray-700 dark:text-gray-200 bg-gray-500/10">
                    {{ Str::title($key) }}:
                </span>
                <span class="font-semibold">
                    @unless(is_array($value))
                        {{ $value }}
                    @else
                        <span class="divide-x divide-solid divide-gray-200 dark:divide-gray-700">
                            @foreach ($value as $nestedValue)
                                {{$nestedValue['id']}}
                            @endforeach
                        </span>
                    @endunless
                </span>
            </li>
        @endforeach
    </ul>
</div>
