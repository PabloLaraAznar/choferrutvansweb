// edit-company.js

export function initEditCompany() {
    window.editCompany = function (companyId) {
        $.get(`/companies/${companyId}`, function (data) {
            const form = $('#editCompanyForm');

            form.find('input[name="name"]').val(data.company.name);
            form.find('input[name="business_name"]').val(data.company.business_name);
            form.find('input[name="rfc"]').val(data.company.rfc);
            form.find('select[name="locality_id"]').val(data.company.locality_id);
            form.find('input[name="address"]').val(data.company.address);
            form.find('input[name="phone"]').val(data.company.phone);
            form.find('input[name="email"]').val(data.company.email);
            form.find('select[name="status"]').val(data.company.status);
            form.find('textarea[name="notes"]').val(data.company.notes);

            form.attr('action', `/companies/${companyId}`);

            $('#editCompanyModal').modal('show');
        }).fail(function () {
            Swal.fire({
                title: 'Error',
                text: 'No se pudo cargar la información de la empresa/sindicato para editar',
                icon: 'error',
                confirmButtonColor: '#28a745'
            });
        });
    }
}
