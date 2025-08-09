import { validateForm } from './validation.js';

export function initCreateSite() {
    const form = document.getElementById('createSiteForm');
    if (!form) return;

    const modalElement = document.getElementById('createSiteModal');
    const bsModal = bootstrap.Modal.getOrCreateInstance(modalElement);

    form.addEventListener('submit', function handleSubmit(e) {
        e.preventDefault();

        const getValue = id => document.getElementById(id)?.value.trim();

        const fields = {
            company_id: { value: getValue('company_id'), rules: ['required'] },
            name: { value: getValue('name'), rules: ['required'] },
            route_name: { value: getValue('route_name'), rules: [] },
            locality_id: { value: getValue('locality_id'), rules: ['required'] },
            address: { value: getValue('address'), rules: ['required'] },
            phone: { value: getValue('phone'), rules: ['required', 'phone'] },
            status: { value: getValue('status'), rules: ['required'] }
        };

        const errors = validateForm(fields);

        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        if (Object.keys(errors).length > 0) {
            let errorMessages = [];
            for (const [field, message] of Object.entries(errors)) {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = message;
                    input.insertAdjacentElement('afterend', errorDiv);
                }
                errorMessages.push(message);
            }
            alert(errorMessages.join('\n'));
            return;
        }

        form.removeEventListener('submit', handleSubmit);
        form.submit();
    });

    modalElement.addEventListener('show.bs.modal', () => {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    });

    modalElement.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', () => {
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        });
    });
}
