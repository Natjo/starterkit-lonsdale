/* eslint-disable */

/**
 * formValidate
 */

const FormValidate = function (form, onSend) {
    const fields = form.querySelectorAll(':required');
    const mandatory = form.getAttribute('data-mandatory');
    let validity = true;
    let init = true;

    this.reset = () => {
        init = true;
        for (let field of fields) {
            field.removeAttribute('aria-invalid');
            field.parentNode.querySelector('.invalid-msg').remove();
        }
    };

    const validate = () => {
        if (init) return;
        validity = true;
        for (let field of fields) {
            const dataTypeMismatch = field.dataset.typemismatch;
            const dataPatternMismatch = field.dataset.patternmismatch;
            const typeMismatch = field.validity.typeMismatch;
            const tooShort = field.validity.tooShort;
            const tooLong = field.validity.tooLong;
            const stepMismatch = field.validity.stepMismatch;
            const patternMismatch = field.validity.patternMismatch;
            const valueMissing = field.validity.valueMissing;
            const group = field.closest('[role="group"]');
            let invalid_msg = group ? group.querySelector('.invalid-msg'): field.parentNode.querySelector('.invalid-msg');

            if (!invalid_msg) {
                invalid_msg = document.createElement('div');
                invalid_msg.className = 'invalid-msg';
                invalid_msg.id = field.getAttribute('aria-describedby').split(' ')[0];
                if (group) {
                    group.insertAdjacentElement('beforeend', invalid_msg);
                } else {
                    field.insertAdjacentElement('afterend', invalid_msg);
                }
            }

            if (!field.checkValidity()) {
                field.setAttribute('aria-invalid', true);
                let msg = '';
                if ((typeMismatch || stepMismatch || tooShort || tooLong) && dataTypeMismatch) {
                    msg = dataTypeMismatch;
                }
                if (patternMismatch && dataPatternMismatch) {
                    msg = dataPatternMismatch;
                }
                if (valueMissing && mandatory) {
                    msg = mandatory;
                }
                field.setCustomValidity(msg);
                invalid_msg.innerHTML = field.validationMessage;
                validity = false;
            } else {
                field.removeAttribute('aria-invalid');
                invalid_msg.innerHTML = '';
            }
        }
        return validity;
    };

    for (let field of fields) {
        field.addEventListener('input', () => validate());
        field.addEventListener('change', () => validate());
    }

    form.onsubmit = (e) => {
        e.preventDefault();
        init = false;
        validate() && onSend(this);
    };
};

export default FormValidate;
