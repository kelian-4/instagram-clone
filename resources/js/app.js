import './bootstrap';
import Alpine from 'alpinejs';

// ─── AlpineJS global ──────────────────────────────────────────────────────────
window.Alpine = Alpine;
Alpine.start();

// ─── Double-tap like (mobile) ─────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    // Délégation d'événement pour le double-clic sur les images de posts
    document.addEventListener('dblclick', (e) => {
        const slide = e.target.closest('.ig-carousel-slide, .ig-post img');
        if (!slide) return;

        const post = slide.closest('.ig-post');
        if (!post) return;

        // Animation cœur flottant
        const heart = document.createElement('div');
        heart.innerHTML = `
            <svg width="80" height="80" viewBox="0 0 24 24" fill="#ff3040" style="filter:drop-shadow(0 2px 8px rgba(255,48,64,0.6))">
                <path d="M16.792 3.904A4.989 4.989 0 0 1 21.5 9.122c0 3.517-4.903 7.574-9.5 10.378-4.597-2.804-9.5-6.861-9.5-10.378a4.989 4.989 0 0 1 4.708-5.218 4.21 4.21 0 0 1 3.675 1.941c.84 1.175.98 1.763 1.12 1.763s.278-.588 1.11-1.766a4.17 4.17 0 0 1 3.679-1.938m0-2a6.04 6.04 0 0 0-4.797 2.127 6.052 6.052 0 0 0-4.787-2.127A6.985 6.985 0 0 0 .5 9.122c0 3.61 2.55 7.097 7.16 10.124a50.153 50.153 0 0 0 4.34 2.555 50.154 50.154 0 0 0 4.342-2.555C20.95 16.22 23.5 12.733 23.5 9.122a6.985 6.985 0 0 0-6.708-7.218Z"/>
            </svg>`;
        heart.style.cssText = `
            position:absolute;top:50%;left:50%;transform:translate(-50%,-50%) scale(0);
            pointer-events:none;z-index:100;
            animation:heart-float 0.8s ease forwards;`;

        const container = slide.closest('.ig-carousel') || slide.closest('.ig-post');
        if (container) {
            container.style.position = 'relative';
            container.appendChild(heart);
            setTimeout(() => heart.remove(), 900);
        }

        // Déclencher le like Livewire
        const likeBtn = post.querySelector('[wire\\:click="toggle"]');
        if (likeBtn) likeBtn.click();
    });
});

// ─── CSS animation cœur flottant ──────────────────────────────────────────────
const style = document.createElement('style');
style.textContent = `
@keyframes heart-float {
    0%   { transform: translate(-50%,-50%) scale(0); opacity:1; }
    50%  { transform: translate(-50%,-50%) scale(1.2); opacity:1; }
    100% { transform: translate(-50%,-60%) scale(1); opacity:0; }
}`;
document.head.appendChild(style);
