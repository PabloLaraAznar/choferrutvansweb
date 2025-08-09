// create-company.js
import { isEmailValid, isPhoneValid, isRequired, passwordsMatch, isMinLength } from './validation.js';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('createCompanyForm');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        // Campos
        const name = form.name.value;
        const phone = form.phone.value;
        const email = form.email.value;
        const adminName = form.admin_name.value;
        const adminEmail = form.admin_email.value;
        const adminPassword = form.admin_password.value;
        const adminPasswordConfirm = form.admin_password_confirmation.value;

        // Validaciones
        let errors = [];

        if (!isRequired(name)) errors.push('El campo Nombre es obligatorio.');
        if (!isRequired(phone) || !isPhoneValid(phone)) errors.push('El teléfono no es válido.');
        if (email && !isEmailValid(email)) errors.push('El email no es válido.');

        if (!isRequired(adminName)) errors.push('El nombre del admin es obligatorio.');
        if (!isRequired(adminEmail) || !isEmailValid(adminEmail)) errors.push('El email del admin no es válido.');
        if (!isRequired(adminPassword) || !isMinLength(adminPassword, 6)) errors.push('La contraseña debe tener al menos 6 caracteres.');
        if (!passwordsMatch(adminPassword, adminPasswordConfirm)) errors.push('Las contraseñas no coinciden.');

        if (errors.length > 0) {
            alert(errors.join('\n')); // Cambia por Swal o mejor UI si quieres
            return false;
        }

        form.submit();
    });
});
