// public/js/validation.js

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');
    const form = document.querySelector('form');

    if (emailInput && emailError && form) {
        emailInput.addEventListener('input', function() {
            validateEmail();
        });

        form.addEventListener('submit', function(event) {
            if (!validateEmail()) {
                event.preventDefault(); // Formularübermittlung verhindern
            }
        });
    }

    /**
     * Validiert das E-Mail-Format und prüft, ob es auf '@edu.bbq.de' endet.
     * @returns {boolean} True, wenn die E-Mail gültig ist, False sonst.
     */
    function validateEmail() {
        const email = emailInput.value;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/; // Grundlegendes E-Mail-Format
        const requiredDomain = '@edu.bbq.de';

        if (!emailPattern.test(email)) {
            emailError.textContent = 'Ungültiges E-Mail-Format.';
            emailInput.classList.add('border-red-500');
            return false;
        } else if (!email.endsWith(requiredDomain)) {
            emailError.textContent = `E-Mail muss auf '${requiredDomain}' enden.`;
            emailInput.classList.add('border-red-500');
            return false;
        } else {
            emailError.textContent = '';
            emailInput.classList.remove('border-red-500');
            return true;
        }
    }
});