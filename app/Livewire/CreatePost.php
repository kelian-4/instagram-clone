<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\PostMedia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class CreatePost extends Component
{
    use WithFileUploads;

    public bool   $open              = false;
    public string $caption           = '';
    public string $location          = '';
    public array  $media             = [];
    public int    $step              = 1;
    public array  $altTexts          = [];   // texte alt par média
    public bool   $disableComments   = false;
    public bool   $showAltSection    = false;
    public bool   $showAdvanced      = false;
    public bool $hideLikes = false;

    protected $listeners = ['openCreatePost' => 'openModal'];

    public function openModal(): void
    {
        $this->reset(['caption','location','media','step',
                      'altTexts','disableComments','showAltSection','showAdvanced']);
        $this->step = 1;
        $this->open = true;
    }

    public function closeModal(): void
    {
        $this->reset(['caption','location','media','step',
                      'altTexts','disableComments','showAltSection','showAdvanced']);
        $this->step = 1;
        $this->open = false;
    }

    public function nextStep(): void
    {
        if (empty($this->media)) {
            $this->addError('media', 'Sélectionne au moins une photo ou vidéo.');
            return;
        }
        $this->clearValidation('media');
        $this->step = 2;
    }

    public function publish(): void
{
    // Validation sans media.* pour éviter le bug Livewire WithFileUploads
    if (empty($this->media)) {
        $this->addError('media', 'Sélectionne au moins une photo.');
        return;
    }

    $post = Post::create([
        'user_id'          => Auth::id(),
        'caption'          => $this->caption ?: null,
        'location'         => $this->location ?: null,
        'comments_enabled' => !$this->disableComments,
    ]);

    foreach ($this->media as $index => $file) {
        try {
            $path = $file->store('posts', 'public');
        } catch (\Exception $e) {
            $this->addError('media', 'Erreur upload : ' . $e->getMessage());
            $post->delete();
            return;
        }

        PostMedia::create([
            'post_id'  => $post->id,
            'path'     => $path,
            'type'     => str_starts_with($file->getMimeType(), 'video') ? 'video' : 'image',
            'order'    => $index,
            'alt_text' => $this->altTexts[$index] ?? null,
        ]);
    }

    $this->closeModal();
    $this->redirectRoute('feed');
}

    public function render()
    {
        return view('livewire.create-post');
    }
}
