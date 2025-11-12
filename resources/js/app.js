import './bootstrap';

document.addEventListener('livewire:init', () => {
    Livewire.on('notify', (event) => {
        const message = event.message;
        const type = event.type || 'info'; // default to info if type is not provided

        alert(`${type.toUpperCase()}: ${message}`);
    });
});
