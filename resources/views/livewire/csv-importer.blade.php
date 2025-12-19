<div>
    {{-- A good traveler has no fixed plans and is not intent upon arriving. --}}

    <div class="p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">CSV Import</h2>

        <input type="file" class="mb-3" wire:model="csvFile" accept=".csv,.txt" />
        @error('csvFile')
            <div class="text-red-500">{{ $message }}</div>
        @enderror

        <button wire:click="import" class="px-4 py-2 bg-blue-600 text-white rounded">
            Start Import CSV
        </button>

        @if($message)
            <div class="mt-4 text-green-600">
                {{ $message }}
            </div>
        @endif
    </div>

</div>
