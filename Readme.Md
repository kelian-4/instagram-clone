Instagram Clone (Laravel + Livewire)

Un clone complet d’Instagram développé avec Laravel et Livewire, reproduisant les principales fonctionnalités du réseau social moderne : publication de contenu, interactions sociales et messagerie en temps réel.

⚠️ Ce projet est un clone éducatif.
Je ne suis pas affilié à Instagram ni à Meta.

✨ Fonctionnalités
👤 Utilisateurs & Social
Authentification (inscription / connexion)
Profils utilisateurs personnalisés
Système de follow / unfollow
Suggestions et recherche d’utilisateurs
📸 Contenu
Création de posts (images / médias)
Likes & commentaires
Hashtags
Enregistrement de posts (bookmarks)
🎬 Stories & Reels
Stories avec vues
Visionneuse de stories
Support des reels (via médias)
💬 Communication
Messagerie privée (conversations)
Messages en temps réel (Livewire)
Gestion des conversations
🔔 Notifications
Nouveaux followers
Likes sur les posts
Commentaires
🛠️ Stack technique
Backend
PHP — Laravel
Eloquent ORM
Policies & Authorization
Frontend
Blade
Livewire
Alpine.js
TailwindCSS
Base de données
SQLite (par défaut)
Compatible MySQL / PostgreSQL
Outils
Vite
Laravel Breeze (auth)
PHPUnit (tests)
📦 Installation
1. Cloner le projet
git clone https://github.com/kelian-4/instagram-clone.git
cd instagram-clone
2. Installer les dépendances
composer install
npm install
3. Configuration

Copier le fichier .env :

cp .env.example .env

Générer la clé :

php artisan key:generate

Configurer la base de données dans .env si nécessaire.

4. Base de données
php artisan migrate --seed
5. Lancer le projet
php artisan serve
npm run dev
📁 Structure du projet
app/
 ├── Livewire/          # Composants interactifs (like, follow, messages...)
 ├── Models/            # Modèles (User, Post, Story, Message…)
 ├── Notifications/     # Système de notifications
 ├── Policies/          # Autorisations
 └── Http/

resources/views/
 ├── feed/
 ├── profile/
 ├── messages/
 ├── notifications/
 ├── reels/
 └── explore/

database/
 ├── migrations/
 ├── seeders/
 └── factories/
🔥 Points intéressants du projet
Architecture Laravel propre (Models / Policies / Notifications)
Utilisation de Livewire pour éviter un frontend JS complexe
Gestion complète :
relations sociales
messagerie
contenu média
Simulation réaliste d’un réseau social moderne
🧪 Tests
php artisan test
📸 Aperçu

(Ajoute ici des screenshots ou un GIF — très important pour GitHub)

🔮 Améliorations possibles
Notifications en temps réel (WebSockets)
Upload optimisé (CDN / stockage cloud)
Système de recommandations
Stories avancées (stickers, réactions)
Application mobile (API + Flutter / React Native)
📄 Licence

Ce projet est sous licence :

GNU AFFERO GENERAL PUBLIC LICENSE (AGPL)

👤 Auteur

Kelian
GitHub : https://github.com/kelian-4
