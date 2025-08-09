export function initDeleteSite() {
    function deleteSite(siteId, siteName) {
        Swal.fire({
            title: '¿Estás seguro?',
            html: `¿Deseas eliminar el sitio/ruta <strong>${escapeHtml(siteName)}</strong>?<br><br>
                   <div class="alert alert-warning">
                       <i class="fas fa-exclamation-triangle"></i>
                       <strong>Advertencia:</strong> Esta acción también eliminará todos los usuarios asociados 
                       exclusivamente a este sitio y es irreversible.
                   </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = $('<form>', {
                    method: 'POST',
                    action: `/clients/${siteId}`
                });

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_token',
                    value: $('meta[name="csrf-token"]').attr('content')
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: '_method',
                    value: 'DELETE'
                }));

                $('body').append(form);
                form.submit();
            }
        });
    }

    window.deleteSite = deleteSite;
}

function escapeHtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
