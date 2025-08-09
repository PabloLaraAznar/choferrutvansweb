// create-coordinator.js
import { validateForm } from './validation.js'; // Si usas validación externa, sino comentar

export function initCreateCoordinator() {
    const form = document.getElementById('createCoordinatorForm');
    const modalElement = document.getElementById('createCoordinatorModal');
    const btnOpenModal = document.getElementById('btnCrearCoordinador');

    if (!form || !modalElement || !btnOpenModal) return;

    const bsModal = bootstrap.Modal.getOrCreateInstance(modalElement);

    btnOpenModal.addEventListener('click', () => {
        bsModal.show();
    });

    form.addEventListener('submit', function handleSubmit(e) {
        e.preventDefault();

        const getValue = id => document.getElementById(id)?.value.trim();

        const fields = {
            name: { value: getValue('name'), rules: ['required'] },
            email: { value: getValue('email'), rules: ['required', 'email'] },
            password: { value: getValue('password'), rules: ['required', 'minLength:8'] },
            address: { value: getValue('address'), rules: ['required'] },
            phone_number: { value: getValue('phone_number'), rules: ['required', 'phone'] },
            employee_code: { value: getValue('employee_code'), rules: ['required'] }
        };

        // Validar con tu función o dejar vacía si no usas
        const errors = validateForm ? validateForm(fields) : {};

        // Limpiar estados anteriores
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());

        if (Object.keys(errors).length > 0) {
            let errorList = Object.values(errors).map(msg => `<li>${msg}</li>`).join('');
            Swal.fire({
                icon: 'error',
                title: 'Errores en el formulario',
                html: `<ul style="text-align: left;">${errorList}</ul>`,
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        // Mostrar loading y enviar formulario
        Swal.fire({
            title: 'Guardando...',
            text: 'Por favor espera',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        form.removeEventListener('submit', handleSubmit);
        form.submit();
    });

    // Reset modal al abrir
    modalElement.addEventListener('show.bs.modal', () => {
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    });

    // Reset modal al cerrar con botones
    modalElement.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
        btn.addEventListener('click', () => {
            form.reset();
            form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        });
    });

    // Preview foto
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.src = '#';
                photoPreview.style.display = 'none';
            }
        });
    }
}
