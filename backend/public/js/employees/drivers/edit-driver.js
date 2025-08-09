import { showErrorAlert, showSuccessAlert } from '../../alerts.js';

export function setupEditDriverModal() {
    const modalEditDriver = new bootstrap.Modal(document.getElementById('modalEditDriver'));
    const formEditDriver = document.getElementById('formEditDriver');
    const photoInput = document.getElementById('edit_photo');
    const currentPhotoPreview = document.getElementById('current_photo_preview');

    if (!formEditDriver) return;

    // Abrir modal y llenar formulario con datos del botón
    document.querySelectorAll('.btn-edit-driver').forEach(button => {
        button.addEventListener('click', function () {
            const driver = this.dataset;

            document.getElementById('edit_driver_id').value = driver.id;
            document.getElementById('edit_name').value = driver.name;
            document.getElementById('edit_email').value = driver.email;
            document.getElementById('edit_license').value = driver.license;

            // Mostrar foto actual o ocultar si no hay
            if (driver.photo) {
                currentPhotoPreview.src = driver.photo;
                currentPhotoPreview.style.display = 'block';
            } else {
                currentPhotoPreview.src = '#';
                currentPhotoPreview.style.display = 'none';
            }

            formEditDriver.action = `/drivers/${driver.id}`;

            // Limpiar input file y reset preview para nueva foto
            if (photoInput) {
                photoInput.value = '';
            }

            modalEditDriver.show();
        });
    });

    if (photoInput && currentPhotoPreview) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file && file.type.startsWith('image/')) {
                if (file.size > 2 * 1024 * 1024) {
                    showErrorAlert('La imagen debe ser menor a 2 MB.');
                    this.value = '';
                    currentPhotoPreview.style.display = 'none';
                    currentPhotoPreview.src = '#';
                    return;
                }
                const reader = new FileReader();
                reader.onload = e => {
                    currentPhotoPreview.src = e.target.result;
                    currentPhotoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                // Si el archivo no es válido, ocultar preview
                currentPhotoPreview.style.display = 'none';
                currentPhotoPreview.src = '#';
            }
        });
    }

    formEditDriver.addEventListener('submit', function (e) {
        e.preventDefault();

        const name = formEditDriver.name.value.trim();
        const email = formEditDriver.email.value.trim();
        const password = formEditDriver.password.value;
        const passwordConfirmation = formEditDriver.password_confirmation.value;
        const license = formEditDriver.license.value.trim();
        const photoFile = photoInput.files[0];

        const errors = [];

        const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[!@#$%^&*()_\-+=<>?{}[\]~]).{8,}$/;

        if (password) {
            if (!passwordRegex.test(password)) errors.push('La contraseña debe tener mínimo 8 caracteres, incluir letras, números y símbolos.');
            if (password !== passwordConfirmation) errors.push('Las contraseñas no coinciden.');
        }

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
                const formData = new FormData(formEditDriver);

                fetch(formEditDriver.action, {
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
                            showErrorAlert('Error desconocido al actualizar el conductor.');
                        }
                        throw new Error('Error al guardar');
                    }
                    return response.json();
                })
                .then(data => {
                    showSuccessAlert('Conductor actualizado correctamente.');

                    modalEditDriver.hide();

                    formEditDriver.reset();
                    currentPhotoPreview.style.display = 'none';
                    currentPhotoPreview.src = '#';

                    setTimeout(() => {
                        location.reload();
                    }, 300);
                })
                .catch(err => console.error(err));
            })
            .catch(errMsg => {
                showErrorAlert(errMsg);
            });
    });
}
