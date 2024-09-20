document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll('.validate-form');
    forms.forEach(form => {
        form.addEventListener('submit', function (e) {
            let valid = true;
            const inputs = form.querySelectorAll('input[required]');
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    valid = false;
                    input.classList.add('error');
                    input.nextElementSibling.textContent = 'This field is required.';
                } else {
                    input.classList.remove('error');
                    input.nextElementSibling.textContent = '';
                }
            });
            if (!valid) {
                e.preventDefault();
            }
        });
    });
});
