document.addEventListener('DOMContentLoaded', () => {
  const showPasswordCheckbox = document.getElementById('show-password');
  if (showPasswordCheckbox) {
    showPasswordCheckbox.addEventListener('change', () => {
      const passwordFields = document.querySelectorAll('input[type="password"], input[data-password-toggle]');
      passwordFields.forEach(input => {
        input.type = showPasswordCheckbox.checked ? 'text' : 'password';
      });
    });
  }

  const authForm = document.querySelector('.auth-form');
  if (authForm) {
    authForm.addEventListener('submit', e => {
      const inputs = authForm.querySelectorAll('input[required]');
      let valid = true;
      let firstInvalid = null;

      inputs.forEach(input => {
        // Clear errors
        input.classList.remove('input-error');

        // Basic field check
        if (!input.value.trim()) {
          valid = false;
          input.classList.add('input-error');
          if (!firstInvalid) firstInvalid = input;
        }

        // Email validation
        if (input.type === 'email' && input.value.trim()) {
          const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
          if (!emailPattern.test(input.value.trim())) {
            valid = false;
            input.classList.add('input-error');
            if (!firstInvalid) firstInvalid = input;
          }
        }

        // Password length check
        if (input.type === 'password' && input.value.trim()) {
          if (input.value.length < 6) {
            valid = false;
            input.classList.add('input-error');
            if (!firstInvalid) firstInvalid = input;
          }
        }
      });

      if (!valid) {
        e.preventDefault();
        alert('Please fill in all required fields correctly. Passwords must be at least 6 characters, and emails must be valid.');
        firstInvalid.focus();
      }
    });
  }
});
