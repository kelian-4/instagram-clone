# Instagram Clone — Laravel + Livewire

Un clone complet d’Instagram développé avec Laravel et Livewire, reproduisant les principales fonctionnalités d’un réseau social moderne : publication de contenu, interactions sociales et messagerie en temps réel.

> **Avertissement :** Ce projet est un clone éducatif. Il n’est pas affilié à Instagram ni à Meta.

-----

## Fonctionnalités

### Utilisateurs & Social

- Authentification (inscription / connexion)
- Profils utilisateurs personnalisés
- Système de follow / unfollow
- Suggestions et recherche d’utilisateurs

### Contenu

- Création de posts (images / médias)
- Likes et commentaires
- Hashtags
- Enregistrement de posts (bookmarks)

### Stories & Reels

- Stories avec compteur de vues
- Visionneuse de stories
- Support des reels via médias

### Communication

- Messagerie privée (conversations)
- Messages en temps réel (Livewire)
- Gestion des conversations

### Notifications

- Nouveaux abonnés
- Likes sur les posts
- Commentaires

-----

## Stack technique

|Couche             |Technologies                                        |
|-------------------|----------------------------------------------------|
|**Backend**        |PHP, Laravel, Eloquent ORM, Policies & Authorization|
|**Frontend**       |Blade, Livewire, Alpine.js, TailwindCSS             |
|**Base de données**|SQLite (par défaut), compatible MySQL / PostgreSQL  |
|**Outils**         |Vite, Laravel Breeze (auth), PHPUnit (tests)        |

-----

## Installation

### 1. Cloner le projet

```bash
git clone https://github.com/kelian-4/instagram-clone.git
cd instagram-clone
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configuration

Copier le fichier d’environnement et générer la clé applicative :

```bash
cp .env.example .env
php artisan key:generate
```

Configurer la base de données dans `.env` si nécessaire (SQLite est utilisé par défaut).

### 4. Base de données

```bash
php artisan migrate --seed
```

### 5. Lancer le projet

```bash
php artisan serve
npm run dev
```

-----

## Structure du projet

```
app/
├── Livewire/        # Composants interactifs (like, follow, messages...)
├── Models/          # Modèles (User, Post, Story, Message...)
├── Notifications/   # Système de notifications
├── Policies/        # Autorisations
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
```

-----

## Points notables

- Architecture Laravel propre (Models, Policies, Notifications)
- Utilisation de Livewire pour éviter un frontend JavaScript complexe
- Gestion complète des relations sociales, de la messagerie et du contenu média
- Simulation réaliste d’un réseau social moderne

-----

## Tests

```bash
php artisan test
```

-----

## Aperçu

Feed 
<img width="1317" height="673" alt="Copie d&#39;écran_20260522_160639" src="https://github.com/user-attachments/assets/d37b2e28-af8e-4a3c-8d3e-64d94ebda0fc" />

Reels

<img width="1306" height="672" alt="Copie d&#39;écran_20260522_160706" src="https://github.com/user-attachments/assets/1390706b-74b1-4f2a-a52f-870046c7aa43" />

Dm 
<img width="1317" height="675" alt="Copie d&#39;écran_20260522_160729" src="https://github.com/user-attachments/assets/59069ceb-e809-4778-88ba-9101f4fbd633" />

Profil

<img width="1314" height="659" alt="Copie d&#39;écran_20260522_160748" src="https://github.com/user-attachments/assets/f413ac1a-1026-4b33-a866-fd0439579bff" />

-----

## Améliorations possibles

- Notifications en temps réel (WebSockets)
- Upload optimisé (CDN / stockage cloud)
- Système de recommandations
- Stories avancées (stickers, réactions)
- Application mobile (API + Flutter / React Native)

-----

## Licence

Ce projet est distribué sous licence **GNU Affero General Public License (AGPL)**.  
Voir le fichier <LICENSE> pour plus de détails.

-----

## Auteur

**Kelian**  
GitHub : [github.com/kelian-4](https://github.com/kelian-4)
