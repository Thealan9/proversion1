<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{-- Empieza diseño del formulario --}}

                    <form method="POST" action="{{route('posts.store')}}">
                    @csrf
                    <textarea name="message" placeholder="{{__('What\'s do you think?')}}"
                        class="block w-full rounded-md bg-white shadow-sm
                                @error('message') border-red-600 @enderror
                                focus:border-indigo-200 focus:ring-yellow-200 focus:ring-opacity-50
                                dark:bg-gray-800 dark:text-white        
                                dark:focus:border-blue-300 dark:focus:ring-blue-200 dark:focus:ring-opacity-50 "></textarea>
                    <x-primary-button class="mt-6">
                        {{__('Posting')}}
                    </x-primary-button>

                    </form>
                    {{-- Acaba diseño del formulario --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
