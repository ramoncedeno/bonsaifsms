<div class="container mx-auto p-4">
    {{-- Livewire Import Form --}}
    <form wire:submit.prevent="importSms" enctype="multipart/form-data" class="mb-4 bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <div class="flex flex-col md:flex-row items-center md:justify-between">
            <div class="flex items-center space-x-4">
                <label for="file-upload" class="cursor-pointer px-4 py-2 rounded text-sm font-semibold bg-blue-500 text-white hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">
                    Choose File
                </label>
                <input id="file-upload" type="file" wire:model="file" required class="hidden">
                <div wire:loading wire:target="file" class="text-sm text-gray-500">Uploading...</div>
                @if ($file)
                    <span class="text-sm text-gray-500">{{ $file->getClientOriginalName() }}</span>
                @endif
            </div>

            <button type="submit" wire:loading.attr="disabled" wire:target="importSms" class="mt-4 md:mt-0 w-full md:w-auto px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500">
                <span wire:loading.remove wire:target="importSms">Import</span>
                <span wire:loading wire:target="importSms">Processing...</span>
            </button>
        </div>
        @error('file') <span class="text-red-500 text-xs mt-2">{{ $message }}</span> @enderror
    </form>
</div>
