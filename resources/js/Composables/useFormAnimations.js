function getInputLabel(inputId) {
    return document.querySelector(`label[for="${inputId}"]`)
}

function getSpanAbove(inputId) {
    const input = document.getElementById(inputId);

    const previousElement = input.previousElementSibling;

    if(previousElement && previousElement.tagName === 'SPAN') {
        return previousElement;
    }

    return null;
}

let isAnimating = {};
let globalAnimationLock = false;

export function labelTransition(event, formType = 'create') {
    const inputElement = event.target;

    if(inputElement.tagName === 'INPUT' && (inputElement.type === 'text' || inputElement.type === 'password' || inputElement.type === 'email')) {
        if(globalAnimationLock) {
            return;
        }

        if(isAnimating[inputElement.id]) {
            return;
        }

        const label = getInputLabel(inputElement.id);
        const span = getSpanAbove(inputElement.id);

        if (!inputElement.hasBlurListener) {

            inputElement.addEventListener('blur', function() {
                if (inputElement.value === '' || inputElement.value.trim() === '') {
                    span.style.transition = 'transform .5s ease';
                    span.style.transform = 'translate(0, 0)';

                    label.classList.remove('label-enabled');
                    label.classList.add('label-disabled');
                    span.classList.remove('span-disabled');
                    span.classList.add('span-enabled');
                }
            });
            inputElement.hasBlurListener = true;
        }

        if(inputElement.value !== '' && inputElement.value.trim() !== '') {
            return;
        }

        const labelRect = label.getBoundingClientRect();
        const spanRect = span.getBoundingClientRect();

        if(spanRect.left === labelRect.left && spanRect.top === labelRect.top) {
            return;
        }

        isAnimating[inputElement.id] = true;
        globalAnimationLock = true;

        const deltaX = labelRect.left - spanRect.left;
        const deltaY = labelRect.top - spanRect.top;

        span.style.transition = 'transform .5s ease';
        span.style.transform = `translate(${deltaX}px, ${deltaY}px)`;

        setTimeout(() => {
            isAnimating[inputElement.id] = false;
            globalAnimationLock = false;

            if (document.activeElement === inputElement && inputElement.value.trim() !== '') {
                label.classList.add('label-enabled');
                label.classList.remove('label-disabled');
                span.classList.add('span-disabled');
                span.classList.remove('span-enabled');
            }
        }, 500);
    }
}

export function initializeFormLabels() {
    const inputs = document.querySelectorAll('form input[type=text][id]');

    inputs.forEach((input) => {
        const label = getInputLabel(input.id);
        const span = getSpanAbove(input.id);

        if (input.value && input.value.trim() !== '') {
            label.classList.add('label-enabled');
            label.classList.remove('label-disabled');
            span.classList.add('span-disabled');

            const labelRect = label.getBoundingClientRect();
            const spanRect = span.getBoundingClientRect();

            const deltaX = labelRect.left - spanRect.left;
            const deltaY = labelRect.top - spanRect.top;
            span.style.transform = `translate(${deltaX}px, ${deltaY}px)`;
        }
    });
}

export function changeInputType(inputId, newType, dateISO = null, dateForTextInput = null) {
    const input = document.getElementById(inputId);

    if(!input) return;

    if(!input.classList.contains('input-type-transition')) {
        if (!dateISO) {
            input.classList.add('input-type-transition');
            input.style.opacity = '0';
        }
    }

    // if (input.dataset.partialDatetime) {
    //     input.value = input.dataset.partialDatetime;
    // }

    if (input._openPickerHandler) {
        input.removeEventListener('pointerdown', input._openPickerHandler);
        input._openPickerHandler = null;
    }

    setTimeout(() => {
        input.type = newType;

        if (dateISO) {
            input.value = dateISO;
        }

        if(!input.classList.contains('input-type-transition')) {
            input.style.opacity = '1';
        }

        if(newType === 'datetime-local') {
            if(!input.hasDatetimeBlurListener) {
                input.addEventListener('input', function() {
                    input.dataset.partialDatetime = formatDateTimeForInputForTextInputs(input.value);
                });

                input.addEventListener('blur', function() {
                    if (dateISO || dateForTextInput) {
                        changeInputType(inputId, 'text');
                        setTimeout(() => {
                            if (input.value && input.value.trim() !== '') {
                                input.value = formatDateTimeForInputForTextInputs(input.value);
                            }
                        }, 205);
                    } else if((!input.value || input.value.trim() === '')) {
                        changeInputType(inputId, 'text');
                    }
                });
                input.hasDatetimeBlurListener = true;
            }


        }

        if (typeof input.showPicker === 'function') {
            input._openPickerHandler = (e) => {
            if (input.type === 'datetime-local') {
                try {
                    input.showPicker();
                } catch (err) {
                    console.error('Error showing picker:', err);
                }
            }
            };

            input.addEventListener('pointerdown', input._openPickerHandler, { passive: true });
        }

        input.style.opacity = '1';
    }, 200)
}

export function formatDateTimeForInputToISO(dateString) {
    if (!dateString) return '';

    if (dateString.includes('T')) {
        return dateString.slice(0, 16);
    }

    if (dateString.includes('.') && dateString.includes(' ')) {
        const [datePart, timePart] = dateString.split(' ');
        const [day, month, year] = datePart.split('.');

        return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}T${timePart}`;
    }

    return dateString.slice(0, 16);
}

export function formatDateTimeForInputForTextInputs(dateString) {
    if (!dateString) return '';

    dateString = dateString.slice(0, 16);

    const day = dateString.match(/(\d{2})T/g);
    day[0] = day[0].replace('T', '');
    const year = dateString.match(/(\d{4})/g);

    dateString = dateString.replace(year[0], day[0]);
    dateString = dateString.replace(day[0] + 'T', year[0] + ' ');

    dateString = dateString.replaceAll('-', '.');

    return dateString;
}
