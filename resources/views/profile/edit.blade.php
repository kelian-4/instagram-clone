<x-app-layout>
<div style="max-width:660px;margin:40px auto;padding:0 20px">
    <h2 style="font-size:20px;font-weight:700;margin-bottom:32px;
               border-bottom:1px solid #262626;padding-bottom:16px">
        Modifier le profil
    </h2>

    @if(session('success'))
    <div style="background:#1a3a1a;border:1px solid #2d5a2d;border-radius:8px;
                padding:12px 16px;margin-bottom:20px;font-size:14px;color:#4ade80">
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')

        <!-- Avatar -->
        <div style="display:flex;align-items:center;gap:20px;
                    background:#111;border-radius:12px;padding:16px;margin-bottom:24px">
            <img src="{{ auth()->user()->avatarUrl() }}" alt=""
                 style="width:56px;height:56px;border-radius:50%;object-fit:cover;flex-shrink:0">
            <div>
                <div style="font-weight:600;font-size:14px;margin-bottom:4px">
                    {{ auth()->user()->username }}
                </div>
                <label style="color:#0095f6;font-size:14px;font-weight:600;cursor:pointer">
                    Changer la photo de profil
                    <input type="file" name="avatar" accept="image/*" style="display:none"
                           onchange="previewAvatar(this)">
                </label>
            </div>
        </div>

        <!-- Champs -->
        @foreach([
            ['name' => 'name',      'label' => 'Nom',           'type' => 'text',  'value' => old('name', auth()->user()->name)],
            ['name' => 'username',  'label' => 'Nom d\'utilisateur', 'type' => 'text', 'value' => old('username', auth()->user()->username)],
            ['name' => 'full_name', 'label' => 'Nom complet',   'type' => 'text',  'value' => old('full_name', $profile->full_name ?? '')],
            ['name' => 'website',   'label' => 'Site web',      'type' => 'url',   'value' => old('website', $profile->website ?? '')],
            ['name' => 'email',     'label' => 'Email',         'type' => 'email', 'value' => old('email', auth()->user()->email)],
        ] as $field)
        <div style="margin-bottom:20px">
            <label style="display:block;font-weight:600;font-size:14px;margin-bottom:6px">
                {{ $field['label'] }}
            </label>
            <input type="{{ $field['type'] }}"
                   name="{{ $field['name'] }}"
                   value="{{ $field['value'] }}"
                   style="width:100%;background:#262626;border:1px solid #363636;
                          border-radius:8px;padding:10px 14px;color:#fff;
                          font-size:14px;outline:none;font-family:inherit"
                   onfocus="this.style.borderColor='#a8a8a8'"
                   onblur="this.style.borderColor='#363636'">
            @error($field['name'])
            <p style="color:#ff3040;font-size:12px;margin-top:4px">{{ $message }}</p>
            @enderror
        </div>
        @endforeach

        <!-- Bio -->
        <div style="margin-bottom:24px">
            <label style="display:block;font-weight:600;font-size:14px;margin-bottom:6px">
                Biographie
            </label>
            <textarea name="bio" rows="4"
                      maxlength="150"
                      style="width:100%;background:#262626;border:1px solid #363636;
                             border-radius:8px;padding:10px 14px;color:#fff;
                             font-size:14px;outline:none;resize:none;
                             font-family:inherit;line-height:1.5"
                      onfocus="this.style.borderColor='#a8a8a8'"
                      onblur="this.style.borderColor='#363636'">{{ old('bio', $profile->bio ?? '') }}</textarea>
            <p style="color:#a8a8a8;font-size:12px;margin-top:4px">
                <span id="bio-count">{{ strlen(old('bio', $profile->bio ?? '')) }}</span>/150
            </p>
        </div>

        <button type="submit"
                style="background:#0095f6;color:#fff;padding:10px 24px;
                       border-radius:8px;font-size:14px;font-weight:600;
                       border:none;cursor:pointer;transition:opacity 0.2s"
                onmouseover="this.style.opacity='0.85'"
                onmouseout="this.style.opacity='1'">
            Soumettre
        </button>
    </form>
</div>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            document.querySelector('img[alt=""]').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Compteur bio
const bioTextarea = document.querySelector('textarea[name="bio"]');
const bioCount = document.getElementById('bio-count');
if (bioTextarea) {
    bioTextarea.addEventListener('input', () => {
        bioCount.textContent = bioTextarea.value.length;
    });
}
</script>
</x-app-layout>
