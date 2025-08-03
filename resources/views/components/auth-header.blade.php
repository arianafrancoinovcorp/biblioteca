@props([
    'title',
    'description',
])

<div {{ $attributes->merge(['class' => 'flex w-full flex-col text-center']) }}>
    <flux:heading size="xl" class="text-black">{{ $title }}</flux:heading>
    <flux:subheading class="text-black">{{ $description }}</flux:subheading>
</div>
