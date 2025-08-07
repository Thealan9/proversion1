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
                    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- previsualización de imagen -->
                        <div class="mb-4 flex flex-col items-center">
                            <div
                                class="relative w-32 h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden mb-2">
                                <img id="preview" src="#" alt="Previsualización"
                                    class="absolute inset-0 w-full h-full object-cover hidden">
                                <div id="emptyPreview" class="flex items-center justify-center h-full text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">Previsualización</span>
                        </div>

                        <!-- area mensaje -->
                        <textarea name="message" placeholder="{{ __('What\'s do you think?') }}"
                            class="block w-full rounded-md bg-white shadow-sm
                                @error('message') border-red-600 @enderror
                                focus:border-indigo-200 focus:ring-yellow-200 focus:ring-opacity-50
                                dark:bg-gray-800 dark:text-white
                                dark:focus:border-blue-300 dark:focus:ring-blue-200 dark:focus:ring-opacity-50 "></textarea>

                        <x-input-error :messages="$errors->get('message')" />

                            <!-- subir imagen -->
                        <div class="mt-4">
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fotografía</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="image"
                                    class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                        </svg>
                                        <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-semibold">Click para subir</span> o arrastra una imagen
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG o GIF (MAX. 2MB)
                                        </p>
                                    </div>
                                    <input id="image" name="image" type="file" class="hidden"
                                        onchange="previewImage(event)" />
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('image')" class="mt-2" />
                        </div>

                        <x-primary-button class="mt-6 w-full justify-center">
                            {{ __('Posting') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>


            {{-- aqui empieza la lista de publicaciones --}}
            <div class="mt-8 space-y-6">
                @foreach ($posts as $post)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 transition-all hover:shadow-lg">
                        <div class="p-6">
                            <div class="flex items-start space-x-4">
                                {{-- Avatar/icono del usuario --}}
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-gray-400 dark:text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        {{-- Información del usuario --}}
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                                {{ $post->user->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $post->created_at->diffForHumans() }}
                                            </p>
                                        </div>

                                        {{-- Botones de acción (solo para el creador) --}}
                                        @if (auth()->check() && auth()->user()->id === $post->user_id)
                                            <div class="flex space-x-2">
                                                <button onclick="openEditModal({{ $post->id }})"
                                                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors"
                                                    title="Editar">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path
                                                            d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                                    </svg>
                                                </button>

                                                <form method="POST" action="{{ route('posts.destroy', $post->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-gray-400 hover:text-red-500 dark:hover:text-red-400 transition-colors"
                                                        title="Eliminar"
                                                        onclick="return confirm('¿Estás seguro de querer borrar este post?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                                            viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Contenido del post --}}
                                    <div class="mt-4">
                                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">
                                            {{ $post->message }}
                                        </p>

                                        {{-- Imagen del post --}}
                                        @if ($post->image)
                                            <div
                                                class="mt-4 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 max-w-xs">
                                                <img src="/images/{{ $post->image }}" alt="Imagen del post"
                                                    class="w-full h-auto max-h-64 object-cover">
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Modal para editar --}}
    <div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md mx-4 shadow-xl">
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="mb-4">
                    <textarea name="message" id="editMessage" class="w-full rounded-md bg-white dark:bg-gray-700 shadow-sm"></textarea>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 dark:text-gray-300 mb-2">Nueva imagen (opcional)</label>
                    <input type="file" name="image" class="w-full">
                    <img id="currentImage" class="mt-2 max-w-full h-auto rounded-lg hidden">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('editModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const emptyPreview = document.getElementById('emptyPreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    preview.src = reader.result;
                    preview.classList.remove('hidden');
                    emptyPreview.classList.add('hidden');
                }
                reader.readAsDataURL(file);
            } else {
                preview.src = "#";
                preview.classList.add('hidden');
                emptyPreview.classList.remove('hidden');
            }
        }

        function openEditModal(postId) {
            fetch(`/post/${postId}/edit`) // Actualizado para coincidir con tu ruta
                .then(response => {
                    if (!response.ok) throw new Error('Error fetching post');
                    return response.json();
                })
                .then(data => {
                    document.getElementById('editMessage').value = data.message;
                    if (data.image) {
                        const imgElement = document.getElementById('currentImage');
                        imgElement.src = `/images/${data.image}`;
                        imgElement.classList.remove('hidden');
                    }

                    document.getElementById('editForm').action = `/post/${postId}`;
                    document.getElementById('editModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('No se pudo cargar el post para editar');
                });
        }
    </script>
</x-app-layout>
