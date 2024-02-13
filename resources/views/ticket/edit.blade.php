    <x-app-layout>
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
            <h1 class="text-white text-lg font-bold">Update {{ $ticket->title }}</h1>
            <div
                class="w-full sm:max-w-xl mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
                <form method="POST" action="{{ route('ticket.update', $ticket->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')
                    <!-- Email Address -->
                    <div class="mt-4">
                        <x-input-label for="title" :value="__('Title')" />
                        <x-text-input value='{{ $ticket->title }}' id="title" class="block mt-1 w-full" type="text"
                            name="title" autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="description" :value="__('Description')" />
                        <x-textarea default='{{ $ticket->description }}' name="description" id="description"
                            value="" />
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="attachment" :value="__('Attachment (if any)')" />
                        <x-file-input name="attachment" id="attachment" />
                        @if ($ticket->attachment)
                            <a class="text-white" href="{{ '/storage/' . $ticket->attachment }}" target="_blank">Open
                                Attachment</a>
                        @endif
                        <x-input-error :messages="$errors->get('attachment')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button class="ml-3">
                            Update
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </x-app-layout>
