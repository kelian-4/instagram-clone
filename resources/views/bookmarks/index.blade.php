<x-app-layout>
    <div class="max-w-4xl mx-auto mt-8">
        <h1 class="text-2xl font-bold mb-4">Publications enregistrées</h1>
        
        <!-- Ta boucle pour afficher les éléments sauvegardés -->
        <div class="grid grid-cols-3 gap-4">
            @foreach($bookmarks as $bookmark)
                <!-- Affichage du post lié au bookmark -->
                <div class="aspect-square bg-gray-200">
                    <!-- exemple: <img src="{{ $bookmark->post->image_url }}"> -->
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
