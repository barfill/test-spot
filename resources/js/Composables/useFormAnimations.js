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
