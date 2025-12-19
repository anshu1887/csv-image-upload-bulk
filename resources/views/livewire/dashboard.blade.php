{{-- Nothing in the world is as soft and yielding as water. --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}

                    {{-- CSV Import Section --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                CSV Bulk Import
                            </h3>
                            <button wire:click="csvImporter" class="px-4 py-2 bg-indigo-600 text-white rounded">
                                <img src="#" alt='CSV-Import' />
                            </button>
                        </div>
                    </div>
                    
                    {{-- Image Upload Section --}}
                    <div class="mt-8 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                                Image Upload
                            </h3>
                            
                            <button wire:click="imageUploader" class="px-4 py-2 bg-indigo-600 text-white rounded">
                                <img src="#" alt='Image-Upload' />
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>