// view-company.js

export function initViewCompany() {
    window.viewCompany = function (companyId) {
        $.get(`/companies/${companyId}`, function (data) {
            Swal.fire({
                title: '<i class="fas fa-industry"></i> Detalles de la Empresa/Sindicato',
                html: `
                    <div class="row text-left">
                        <div class="col-md-6">
                            <h6><strong>Información General</strong></h6>
                            <p><strong>Nombre:</strong> ${escapeHtml(data.company.name)}</p>
                            <p><strong>Razón Social:</strong> ${escapeHtml(data.company.business_name || 'N/A')}</p>
                            <p><strong>RFC:</strong> ${escapeHtml(data.company.rfc || 'N/A')}</p>
                            <p><strong>Localidad:</strong> ${escapeHtml(data.company.locality ? data.company.locality.locality : 'N/A')}</p>
                            <p><strong>Dirección:</strong> ${escapeHtml(data.company.address)}</p>
                            <p><strong>Teléfono:</strong> ${escapeHtml(data.company.phone)}</p>
                            <p><strong>Email:</strong> ${escapeHtml(data.company.email || 'N/A')}</p>
                            <p><strong>Estado:</strong> 
                                <span class="badge ${data.company.status === 'active' ? 'bg-success' : 'bg-danger'}">
                                    ${data.company.status === 'active' ? 'Activo' : 'Inactivo'}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6><strong>Estadísticas</strong></h6>
                            <p><strong>Total de Sitios/Rutas:</strong> ${data.stats.sites}</p>
                            <p><strong>Sitios Activos:</strong> ${data.stats.active_sites}</p>
                            <p><strong>Total de Usuarios:</strong> ${data.stats.total_users}</p>
                            ${data.company.notes ? `<p><strong>Notas:</strong> ${escapeHtml(data.company.notes)}</p>` : ''}
                        </div>
                    </div>
                `,
                width: 700,
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#28a745'
            });
        }).fail(function () {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la información de la empresa/sindicato',
                icon: 'error',
                confirmButtonColor: '#28a745'
            });
        });
    }
}

function escapeHtml(text) {
    return text
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
