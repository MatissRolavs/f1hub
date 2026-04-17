@props(['user', 'class' => 'font-semibold'])

@php
    $favDriver = $user?->favoriteDriver;
    $favConstructor = $user?->favoriteConstructor;
    $favNumber = $favDriver?->permanent_number;
    $favPillColor = $favConstructor
        ? config('f1.team_colors.'.$favConstructor->name, config('f1.default_team_color'))
        : null;
@endphp

<span {{ $attributes->merge(['class' => $class]) }}>{{ $user?->name }}</span>
@if($favNumber && $favPillColor)
    <span class="ml-2 inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold text-white align-middle"
          style="background-color: {{ $favPillColor }}; text-shadow: 0 1px 1px rgba(0,0,0,0.6);">
        #{{ $favNumber }}
    </span>
@endif
