import './bootstrap';

// Global dark mode toggle function
window.toggleDarkMode = function() {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('dark', isDark);
};

// Initialize dark mode on page load AND on every Livewire navigation
function applyDarkMode() {
    if (localStorage.getItem('dark') === 'true') {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

applyDarkMode();
document.addEventListener('livewire:navigated', applyDarkMode);

// Toast notification handler
window.toastHandler = function() {
    return {
        toasts: [],
        nextId: 1,
        timeouts: {},

        init() {
            const flash = document.getElementById('__toastFlash');
            if (flash && flash.textContent) {
                try {
                    const data = JSON.parse(flash.textContent);
                    if (data && data.message) {
                        this.addToast(data);
                    }
                } catch (e) {}
            }
        },

        addToast({ type = 'default', title = '', message, duration = 5000 }) {
            const id = this.nextId++;
            const toast = {
                id,
                type,
                title,
                message,
                duration,
                visible: true,
            };

            this.toasts.push(toast);

            if (duration > 0) {
                this.timeouts[id] = setTimeout(() => {
                    this.removeToast(id);
                }, duration);
            }
        },

        removeToast(id) {
            if (this.timeouts[id]) {
                clearTimeout(this.timeouts[id]);
                delete this.timeouts[id];
            }

            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts[index].visible = false;
                setTimeout(() => {
                    this.toasts = this.toasts.filter(t => t.id !== id);
                }, 200);
            }
        },
    };
};
