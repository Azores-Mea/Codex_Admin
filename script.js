const email = document.getElementById('email');
const password = document.getElementById('password');
const submitBtn = document.getElementById('submitBtn');

const clearStates = (group) => group.classList.remove('error', 'success');

const validateField = (input) => {
    const group = input.closest('.input-group');
    const val = input.value.trim();
    clearStates(group);

    if (val.length === 0) return;

    if (input.type === 'email') {
        const isValid = val.includes('@') && val.includes('.');
        isValid ? group.classList.add('success') : group.classList.add('error');
    } else {
        val.length >= 6 ? group.classList.add('success') : group.classList.add('error');
    }
};

[email, password].forEach(input => {
    input.addEventListener('input', () => {
        clearStates(input.closest('.input-group'));
        const isReady = email.value.trim() !== "" && password.value.trim() !== "";
        submitBtn.disabled = !isReady;
        isReady ? submitBtn.classList.add('active') : submitBtn.classList.remove('active');
    });

    input.addEventListener('blur', () => validateField(input));
});

document.getElementById('eyeToggle').addEventListener('click', function() {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
});