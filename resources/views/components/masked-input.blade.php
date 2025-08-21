@php
    $extraAttributes = $getExtraAttributes();
    if (is_array($extraAttributes)) {
        $extraAttributes = new \Illuminate\View\ComponentAttributeBag($extraAttributes);
    }

    $placeholder = $extraAttributes->get('placeholder', '');
    $label = $getLabel();
@endphp

<div x-data="{ show: false }" class="w-full">
    @if($label)
        <label for="{{ $getId() }}" class="block text-sm font-medium text-gray-700 dark:text-white mb-2">
            {{ $label }}
         </label>
    @endif

    <div class="relative w-full">
        <input
            id="{{ $getId() }}"
            wire:model.lazy="{{ $getStatePath() }}"
            x-bind:type="show ? 'text' : 'password'"
            placeholder="{{ $placeholder }}"
            {{ $extraAttributes->merge([
                'class' => '
                    block w-full rounded-md border-gray-300 pr-10
                    focus:border-primary-500 focus:ring-primary-500 sm:text-sm
                    bg-white text-gray-900
                    dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600
                    placeholder-gray-400 dark:placeholder-gray-500
                ',
                'style' => 'caret-color: auto;',
            ]) }}
        />

        <button
            type="button"
            class="absolute right-0 top-1/2 transform -translate-y-1/2 flex items-center pr-3 text-gray-400 hover:text-gray-600"
            x-on:click="show = !show"
        >
            <!-- Olho fechado -->
            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>

            <!-- Olho aberto -->
            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.955 9.955 0 012.005-3.368m3.737-2.9A9.956 9.956 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.958 9.958 0 01-4.043 4.882M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 3l18 18"/>
            </svg>
        </button>
    </div>
</div>
