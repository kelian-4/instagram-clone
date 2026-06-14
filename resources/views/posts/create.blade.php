@extends('layouts.app')
@section('title', 'Nouveau post')

@section('content')
<div class="max-w-xl mx-auto px-4 py-8" x-data="{
    previews: [],
    handleFiles(e) {
        const files = Array.from(e.target.files);
        this.previews = files.map(f => URL.createObjectURL(f));
    }
}">
    <h1 class="text-xl font-bold mb-6 text-center">Créer un nouveau post</h1>

    @if ($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
        <ul class="list-disc list-inside text-red-600 text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data"
          class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
        @csrf

        {{-- Zone d'upload --}}
        <div class="border-b border-gray-200">
            <label for="images"
                   class="flex flex-col items-center justify-center p-12 cursor-pointer hover:bg-gray-50 transition"
                   x-show="previews.length === 0">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                </svg>
                <p class="font-semibold text-lg mb-1">Glissez vos photos ici</p>
                <p class="text-gray-500 text-sm mb-4">ou cliquez pour sélectionner</p>
                <span class="bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-600 transition">
                    Sélectionner depuis l'ordinateur
                </span>
            </label>

            {{-- Prévisualisation --}}
            <div x-show="previews.length > 0" class="relative">
                <div class="grid gap-1" :class="previews.length === 1 ? 'grid-cols-1' : previews.length <= 4 ? 'grid-cols-2' : 'grid-cols-3'">
                    <template x-for="(src, i) in previews" :key="i">
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            <img :src="src" class="w-full h-full object-cover">
                        </div>
                    </template>
                </div>
                <label for="images" class="absolute top-2 right-2 bg-white/80 rounded-full p-1 cursor-pointer hover:bg-white">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Z"/></svg>
                </label>
            </div>

            <input type="file" id="images" name="images[]"
                   accept="image/*" multiple required
                   @change="handleFiles($event)"
                   class="hidden">
        </div>

        {{-- Caption + options --}}
        <div class="p-4">
            <div class="flex items-start gap-3 mb-4">
                <img src="{{ auth()->user()->avatarUrl() }}" alt="" class="w-8 h-8 rounded-full object-cover flex-shrink-0 mt-1">
                <textarea name="caption"
                          placeholder="Rédigez une légende... #hashtag"
                          rows="4"
                          maxlength="2200"
                          class="flex-1 resize-none border-none outline-none text-sm placeholder-gray-400">{{ old('caption') }}</textarea>
            </div>

            <div class="border-t border-gray-100 pt-4">
                <input type="text" name="location"
                       placeholder="📍 Ajouter un lieu"
                       value="{{ old('location') }}"
                       class="w-full border-none outline-none text-sm placeholder-gray-400 bg-transparent">
            </div>

            <div class="mt-4">
                <button type="submit"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded-lg transition">
                    Publier
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
