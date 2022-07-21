import "toastify-js/src/toastify.css"
import Toastify from 'toastify-js'

document.addEventListener('DOMContentLoaded', () => {
    const flashes = document.querySelectorAll('.flash-message');
    flashes.forEach((flash) => {
        const type = flash.dataset.type
        const message = flash.textContent

        let background = '#cfe2ff'
        let color = '#084298'
        switch (type) {
            case 'success':
                background = '#d1e7dd'
                color = '#0f5132'
                break
            case 'warning':
                background = '#fff3cd'
                color = '#055160'
                break
            case 'danger':
                background = '#f8d7da'
                color = '#842029'
                break
            case 'info':
                background = '#cff4fc'
                color = '#055160'
                break
        }

        Toastify({
            text: message,
            duration: 4000,
            gravity: "top",
            position: 'right',
            close: true,
            style: {
                background,
                color
            }
        }).showToast();
    })
})