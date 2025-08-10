export function initEditAdmin() {
    const modalElement = document.getElementById('editAdminModal');
    if (!modalElement) return;

    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('editAdminForm');
    if (!form) return;

    document.querySelectorAll('.btn-edit-admin').forEach(button => {
        button.addEventListener('click', () => {
            const adminData = button.dataset;

            form.action = `/companies/${adminData.companyId}/admin`;

            form.querySelector('#edit_admin_name').value = adminData.adminName || '';
            form.querySelector('#edit_admin_email').value = adminData.adminEmail || '';
            form.querySelector('#edit_admin_id').value = adminData.adminId || '';

            modal.show();
        });
    });

    form.addEventListener('submit', function handleSubmit(e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(async response => {
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Error al actualizar el administrador');
            }

            return response.json();
        })
        .then(data => {
            Swal.fire({
                title: '¡Éxito!',
                text: 'El administrador se ha actualizado correctamente',
                icon: 'success',
                confirmButtonColor: '#28a745'
            }).then(() => {
                modal.hide();
                window.location.reload();
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error',
                text: error.message,
                icon: 'error',
                confirmButtonColor: '#28a745'
            });
        });
    });
}
