<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instagram — Connexion</title>
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
    </style>
</head>
<body>
<div style="width:100%;max-width:350px">
    <div class="auth-box" style="text-align:center">
        <div style="font-family:'Dancing Script',cursive;font-size:56px;font-weight:700;margin-bottom:28px">
            Instagram
        </div>

        @if(session('status'))
        <div style="background:#1a3a1a;border:1px solid #2d5a2d;border-radius:6px;
                    padding:10px;margin-bottom:16px;font-size:13px;color:#4ade80">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <input class="auth-input" type="text" name="email"
                   placeholder="Numéro de téléphone, nom d'utilisateur ou email"
                   value="{{ old('email') }}" required autofocus>

            <input class="auth-input" type="password" name="password"
                   placeholder="Mot de passe" required>

            @if($errors->any())
            <p style="color:#ff3040;font-size:13px;margin-bottom:8px">
                Identifiants incorrects. Veuillez réessayer.
            </p>
            @endif

            <label style="display:flex;align-items:center;gap:8px;margin-bottom:12px;
                          font-size:13px;color:#a8a8a8;cursor:pointer;text-align:left">
                <input type="checkbox" name="remember"
                       style="accent-color:#0095f6">
                Se souvenir de moi
            </label>

            <button type="submit" class="auth-btn">Se connecter</button>
        </form>

        <div class="auth-divider">OU</div>

        <a href="#" style="color:#385185;font-size:14px;font-weight:600;
                           display:flex;align-items:center;gap:8px;justify-content:center;
                           margin-bottom:16px">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="#385185">
                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
            Se connecter avec Facebook
        </a>

        <a href="{{ route('password.request') }}"
           style="color:#a8a8a8;font-size:12px">Mot de passe oublié ?</a>
    </div>

    <div class="auth-box" style="text-align:center;margin-top:10px;padding:20px 40px">
        Vous n'avez pas de compte ?
        <a href="{{ route('register') }}" style="color:#0095f6;font-weight:600;margin-left:4px">
            Inscrivez-vous
        </a>
    </div>
</div>
</body>
</html>
