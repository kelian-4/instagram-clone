<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram — Créer un compte</title>
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        body { background: #000; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
        .auth-box { background: #000; border: 1px solid #262626; border-radius: 4px; padding: 32px 40px; width: 100%; max-width: 350px; }
        .auth-input { width: 100%; background: #121212; border: 1px solid #363636; border-radius: 6px; padding: 9px 10px; color: #fff; font-size: 12px; outline: none; margin-bottom: 6px; box-sizing: border-box; font-family: inherit; }
        .auth-input:focus { border-color: #a8a8a8; }
        .auth-btn { width: 100%; background: #0095f6; color: #fff; padding: 8px; border-radius: 8px; font-size: 14px; font-weight: 600; border: none; cursor: pointer; margin-top: 4px; transition: opacity 0.2s; }
        .auth-btn:hover { opacity: 0.85; }
        .auth-divider { display: flex; align-items: center; gap: 12px; margin: 16px 0; color: #a8a8a8; font-size: 13px; font-weight: 600; }
        .auth-divider::before, .auth-divider::after { content: ''; flex: 1; height: 1px; background: #262626; }
        .auth-error { color: #ff3040; font-size: 12px; margin-bottom: 6px; }
    </style>
</head>
<body>
<div style="width:100%;max-width:350px">
    <div class="auth-box" style="text-align:center">
        <!-- Logo -->
        <div style="font-family:'Dancing Script',cursive;font-size:48px;font-weight:700;margin-bottom:4px">
            Instagram
        </div>
        <p style="color:#a8a8a8;font-size:17px;font-weight:600;margin-bottom:24px;line-height:1.4">
            Inscrivez-vous pour voir les photos et les vidéos de vos amis.
        </p>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <input class="auth-input" type="text" name="name"
                   placeholder="Nom et prénom" value="{{ old('name') }}" required>
            @error('name') <p class="auth-error">{{ $message }}</p> @enderror

            <input class="auth-input" type="text" name="username"
                   placeholder="Nom d'utilisateur" value="{{ old('username') }}" required>
            @error('username') <p class="auth-error">{{ $message }}</p> @enderror

            <input class="auth-input" type="email" name="email"
                   placeholder="Adresse email" value="{{ old('email') }}" required>
            @error('email') <p class="auth-error">{{ $message }}</p> @enderror

            <input class="auth-input" type="password" name="password"
                   placeholder="Mot de passe" required>
            @error('password') <p class="auth-error">{{ $message }}</p> @enderror

            <input class="auth-input" type="password" name="password_confirmation"
                   placeholder="Confirmer le mot de passe" required>

            <p style="color:#a8a8a8;font-size:11px;margin:10px 0 14px;line-height:1.4">
                Les personnes qui utilisent notre service ont pu télécharger vos informations de contact vers Instagram.
                <a href="#" style="color:#a8a8a8">En savoir plus</a>
            </p>

            <button type="submit" class="auth-btn">S'inscrire</button>
        </form>
    </div>

    <div class="auth-box" style="text-align:center;margin-top:10px;padding:20px 40px">
        Vous avez déjà un compte ?
        <a href="{{ route('login') }}" style="color:#0095f6;font-weight:600;margin-left:4px">
            Connectez-vous
        </a>
    </div>
</div>
</body>
</html>
