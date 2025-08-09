import { showErrorAlert, showSuccessAlert } from '../../alerts.js';

export function setupCreateDriverModal() {
    const photoInput = document.getElementById('photo');
    const preview = document.getElementById('photo-preview');
    const form = document.querySelector('#modalCreateDriver form');
    if (!form) return;

    if (photoInput && preview) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                if (file.size > 2 * 1024 * 1024) {
                    showErrorAlert('La imagen debe ser menor a 2 MB.');
                    this.value = '';
                    preview.style.display = 'none';
                    preview.src = '#';
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                preview.src = '#';
            }
        });
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = form.name.value.trim();
        const email = form.email.value.trim();
        const password = form.password.value;
        const passwordConfirmation = form.password_confirmation.value;
        const license = form.license.value.trim();
        const photoFile = photoInput.files[0];

        const errors = [];
        const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=<>?{}[\]~]).{8,}$/;

        if (!passwordRegex.test(password)) errors.push('La contraseña debe tener mínimo 8 caracteres, incluir letras, números y símbolos.');
        if (password !== passwordConfirmation) errors.push('Las contraseñas no coinciden.');
        if (!/^\d{12}$/.test(license)) errors.push('La licencia debe contener exactamente 12 dígitos numéricos.');
        if (photoFile) {
            if (!photoFile.type.startsWith('image/')) errors.push('El archivo de foto debe ser una imagen válida.');
            if (photoFile.size > 2 * 1024 * 1024) errors.push('La imagen debe ser menor a 2 MB.');
        }

        if (errors.length) {
            showErrorAlert(errors.join('<br>'));
            return;
        }

        const validateImageDimensions = file => new Promise((resolve, reject) => {
            if (!file) return resolve(true);
            const img = new Image();
            img.onload = () => (img.width < 100 || img.height < 100) ? reject('La imagen debe tener un tamaño mínimo de 100x100 píxeles.') : resolve(true);
            img.onerror = () => reject('No se pudo cargar la imagen para validar dimensiones.');
            img.src = URL.createObjectURL(file);
        });

        validateImageDimensions(photoFile)
            .then(() => {
                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(async response => {
                    if (!response.ok) {
                        const data = await response.json().catch(() => null);
                        if (data && data.errors) {
                            const serverErrors = Object.values(data.errors).flat();
                            showErrorAlert(serverErrors.join('<br>'));
                        } else {
                            showErrorAlert('Error desconocido al guardar el conductor.');
                        }
                        throw new Error('Error al guardar');
                    }
                    return response.json();
                })
                .then(data => {
                    showSuccessAlert('Conductor creado correctamente.');

                    // Cerrar modal y limpiar foco para evitar warnings aria-hidden
                    const modalElement = document.getElementById('modalCreateDriver');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) modalInstance.hide();

                    // Limpiar formulario y preview
                    form.reset();
                    preview.style.display = 'none';
                    preview.src = '#';

                    // Pequeño timeout para que el modal se cierre antes de recargar
                    setTimeout(() => {
                        location.reload();  // recarga para ver cambios reflejados
                    }, 300);
                })
                .catch(err => {
                    console.error(err);
                });
            })
            .catch(errMsg => {
                showErrorAlert(errMsg);
            });
    });
}
