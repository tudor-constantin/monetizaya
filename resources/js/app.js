import './bootstrap';

const TOAST_STORAGE_KEY = '__toastNext';
const AUTH_STATUS_INTERVAL_MS = 5000;

window.toggleDarkMode = function () {
    const isDark = document.documentElement.classList.toggle('dark');
    localStorage.setItem('dark', isDark);
};

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

function clearAuthStatusPolling() {
    if (!authStatusInterval) {
        return;
    }

    clearInterval(authStatusInterval);
    authStatusInterval = null;
}

function getAuthStatusUrl() {
    return document.body?.dataset?.authStatusUrl ?? '';
}

async function checkAuthStatus(statusUrl) {
    try {
        const response = await fetch(statusUrl, {
            method: 'GET',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

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
                sessionStorage.setItem(TOAST_STORAGE_KEY, JSON.stringify(data.toast));
                window.dispatchEvent(new CustomEvent('toast', { detail: data.toast }));
            }

            clearAuthStatusPolling();

            if (data.redirect) {
                window.location.href = data.redirect;
            }
        }
    } catch {
        // Ignore transient network failures.
    }
}

function startAuthStatusPolling() {
    const statusUrl = getAuthStatusUrl();
    if (!statusUrl) {
        clearAuthStatusPolling();
        return;
    }

    if (authStatusInterval) {
        return;
    }

    void checkAuthStatus(statusUrl);
    authStatusInterval = setInterval(() => {
        void checkAuthStatus(statusUrl);
    }, AUTH_STATUS_INTERVAL_MS);
}

startAuthStatusPolling();
document.addEventListener('livewire:navigated', startAuthStatusPolling);

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
            const stored = sessionStorage.getItem(TOAST_STORAGE_KEY);

            if (!stored) {
                return;
            }

            try {
                const data = JSON.parse(stored);

                if (data && data.message) {
                    this.addToast(data);
                }
            } catch (e) {}

            sessionStorage.removeItem(TOAST_STORAGE_KEY);
        },

        addToast({ type = 'default', title = '', message, duration = 5000, persist = false }) {
            if (!message) {
                return;
            }

            if (persist) {
                sessionStorage.setItem(TOAST_STORAGE_KEY, JSON.stringify({ type, title, message, duration }));
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
