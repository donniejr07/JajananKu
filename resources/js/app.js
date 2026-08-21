import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('livewire:init', () => {
    window.Livewire.on('transaction-saved', () => {
        window.location.reload();
    });
});
