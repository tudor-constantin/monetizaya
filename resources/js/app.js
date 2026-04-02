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

let authStatusInterval = null;

function startAuthStatusPolling() {
    const statusMeta = document.querySelector('meta[name="auth-status-url"]');

    if (!statusMeta) {
        if (authStatusInterval) {
            clearInterval(authStatusInterval);
            authStatusInterval = null;
        }

        return;
    }

    const statusUrl = statusMeta.content;

    const checkStatus = async () => {
        try {
            const response = await fetch(statusUrl, {
                method: 'GET',
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });

            if (response.status === 401) {
                if (authStatusInterval) {
                    clearInterval(authStatusInterval);
                    authStatusInterval = null;
                }

                return;
            }

            if (!response.ok && response.status !== 423) {
                return;
            }

            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                return;
            }

            const data = await response.json();

            if (data?.active === false) {
                if (data.toast?.message) {
                    sessionStorage.setItem('__toastNext', JSON.stringify(data.toast));
                    window.dispatchEvent(new CustomEvent('toast', { detail: data.toast }));
                }

                if (authStatusInterval) {
                    clearInterval(authStatusInterval);
                    authStatusInterval = null;
                }

                window.location.href = data.redirect || '/login';
            }
        } catch (e) {
            // Ignore transient network failures.
        }
    };

    if (authStatusInterval) {
        return;
    }

    checkStatus();
    authStatusInterval = setInterval(checkStatus, 5000);
}

startAuthStatusPolling();
document.addEventListener('livewire:navigated', startAuthStatusPolling);

// Toast notification handler
window.toastHandler = function() {
    return {
        toasts: [],
        nextId: 1,
        timeouts: {},

        init() {
            this.checkFlashToast();
            this.checkStoredToast();
            document.addEventListener('livewire:navigated', () => this.checkFlashToast());
            document.addEventListener('livewire:navigated', () => this.checkStoredToast());
        },

        checkFlashToast() {
            const flash = document.getElementById('__toastFlash');
            if (flash && flash.textContent) {
                try {
                    const data = JSON.parse(flash.textContent);
                    if (data && data.message) {
                        this.addToast(data);
                    }
                } catch (e) {}

                flash.remove();
            }
        },

        checkStoredToast() {
            const stored = sessionStorage.getItem('__toastNext');

            if (!stored) {
                return;
            }

            try {
                const data = JSON.parse(stored);

                if (data && data.message) {
                    this.addToast(data);
                }
            } catch (e) {}

            sessionStorage.removeItem('__toastNext');
        },

        addToast({ type = 'default', title = '', message, duration = 5000, persist = false }) {
            if (!message) {
                return;
            }

            if (persist) {
                sessionStorage.setItem('__toastNext', JSON.stringify({ type, title, message, duration }));
            }

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
