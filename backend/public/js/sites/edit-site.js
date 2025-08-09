export function initEditSite() {
    const modalEditSite = new bootstrap.Modal(document.getElementById('editSiteModal'));
    const form = $('#editSiteForm');

    document.querySelectorAll('.btn-edit-site').forEach(button => {
        button.addEventListener('click', function() {
            const site = this.dataset;

            form.find('select[name="company_id"]').val(site.company_id);
            form.find('input[name="name"]').val(site.name);
            form.find('input[name="route_name"]').val(site.route_name);
            form.find('select[name="locality_id"]').val(site.locality_id);
            form.find('input[name="address"]').val(site.address);
            form.find('input[name="phone"]').val(site.phone);
            form.find('select[name="status"]').val(site.status);

            form.attr('action', `/clients/${site.id}`);

            modalEditSite.show();
        });
    });
}
