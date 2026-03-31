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
