document.addEventListener('livewire:init', () => {
    window.Livewire.on('transaction-saved', () => {
        window.location.reload();
    });
});
