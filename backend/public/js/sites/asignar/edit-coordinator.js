// edit-coordinator.js
export function initEditCoordinator() {
    const btnsEditar = document.querySelectorAll('.btnEditarCoordinador');
    const modalElement = document.getElementById('editCoordinatorModal');
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('editCoordinatorForm');
    if (!form || btnsEditar.length === 0) return;

    btnsEditar.forEach(btnEditar => {
        btnEditar.addEventListener('click', () => {
            let user = {};
            try {
                user = JSON.parse(btnEditar.dataset.user);
            } catch (e) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al cargar datos del coordinador',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            if (!user.coordinate_id) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'No se encontró el ID del coordinador',
                    confirmButtonColor: '#dc3545'
                });
                return;
            }

            form.action = `/coordinates/${user.coordinate_id}`;

            form.querySelector('#edit_name').value = user.name || '';
            form.querySelector('#edit_email').value = user.email || '';
            form.querySelector('#edit_address').value = user.address || '';
            form.querySelector('#edit_phone_number').value = user.phone_number || '';
            form.querySelector('#edit_site_id').value = user.site_id || '';

            const preview = form.querySelector('#edit_photoPreview');
            if (user.photo) {
                preview.src = `/storage/${user.photo}`;
                preview.style.display = 'block';
            } else {
                preview.style.display = 'none';
                preview.src = '#';
            }

            modal.show();
        });
    });

    form.addEventListener('submit', function handleSubmit(e) {
        e.preventDefault();

        // Aquí podrías agregar validaciones propias si quieres

        Swal.fire({
            title: 'Guardando cambios...',
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

    const photoInput = form.querySelector('#edit_photo');
    const photoPreview = form.querySelector('#edit_photoPreview');

    if (photoInput) {
        photoInput.addEventListener('change', function () {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    photoPreview.src = e.target.result;
                    photoPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.style.display = 'none';
                photoPreview.src = '#';
            }
        });
    }
}
