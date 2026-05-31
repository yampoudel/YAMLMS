window.userValidation = async function (formElement, routes) {
    // Get all input elements
    let roleInput = formElement.querySelector('[name="role"]');
    let loginInput = formElement.querySelector('[name="login"]');
    let firstNameInput = formElement.querySelector('[name="first_name"]');
    let lastNameInput = formElement.querySelector('[name="last_name"]');
    let emailInput = formElement.querySelector('[name="email"]');
    let passwordInput = formElement.querySelector('[name="password"]');
    let statusInput = formElement.querySelector('[name="status"]');
    let dobInput = formElement.querySelector('[name="birth_date"]');
    let phoneInput = formElement.querySelector('[name="phone"]');
    let mobileInput = formElement.querySelector('[name="mobile"]');
    let countryInput = formElement.querySelector('[name="country"]');
    let cityInput = formElement.querySelector('[name="city"]');
    let postCodeInput = formElement.querySelector('[name="postcode"]');
    let suburbInput = formElement.querySelector('[name="suburb"]');
    let imageInput = formElement.querySelector('[name="image_path"]');

    // Get all input errors
    let roleError = formElement.querySelector('.js-role-error');
    let loginError = formElement.querySelector('.js-login-error');
    let firstNameError = formElement.querySelector('.js-firstname-error');
    let lastNameError = formElement.querySelector('.js-lastname-error');
    let emailError = formElement.querySelector('.js-email-error');
    let passwordError = formElement.querySelector('.js-password-error');
    let statusError = formElement.querySelector('.js-status-error');
    let dobError = formElement.querySelector('.js-birthdate-error');
    let phoneError = formElement.querySelector('.js-phone-error');
    let mobileError = formElement.querySelector('.js-mobile-error');
    let countryError = formElement.querySelector('.js-country-error');
    let cityError = formElement.querySelector('.js-city-error');
    let postCodeError = formElement.querySelector('.js-postcode-error');
    let suburbError = formElement.querySelector('.js-suburb-error');
    let imageError = formElement.querySelector('.js-image-error');

    // Instantly clear old messages (clears previous Laravel errors for all the fields)
    if (roleError) roleError.innerText = "";
    if (loginError) loginError.innerText = "";
    if (firstNameError) firstNameError.innerText = "";
    if (lastNameError) lastNameError.innerText = "";
    if (emailError) emailError.innerText = "";
    if (passwordError) passwordError.innerText = "";
    if (statusError) statusError.innerText = "";
    if (dobError) dobError.innerText = "";
    if (phoneError) phoneError.innerText = "";
    if (mobileError) mobileError.innerText = "";
    if (countryError) countryError.innerText = "";
    if (cityError) cityError.innerText = "";
    if (postCodeError) postCodeError.innerText = "";
    if (suburbError) suburbError.innerText = "";
    if (imageError) imageError.innerText = "";

    // Validate Role
    if (roleInput) {
        let roleValue = (roleInput.value || '').trim();

        if (roleValue === "") {
            if (roleError) roleError.innerText = "The role field is required.";
            roleInput.focus();
            return false;
        } else if (!['Admin', 'Teacher', 'Learner'].includes(roleValue)) {
            if (roleError) roleError.innerText = "The selected role is invalid.";
            roleInput.focus();
            return false;
        }
    }

    // Validate login
    if (loginInput) {
        let loginValue = (loginInput.value || '').trim();

        if (loginValue === "") {
            if (loginError) loginError.innerText = "The login field is required.";
            loginInput.focus();
            return false;
        }

        // Await the AJAX database check before allowing the form to submit
        let uniqueLogin = await isLoginUnique(loginValue, routes.login);

        if (!uniqueLogin) {
            if (loginError) loginError.innerText = "The login has already been taken.";
            loginInput.focus();
            return false;
        }
    }

    // Validate First Name
    if (firstNameInput) {
        let firstNameValue = (firstNameInput.value || '').trim();

        if (firstNameValue === "") {
            if (firstNameError) firstNameError.innerText = "The first name field is required.";
            firstNameInput.focus();
            return false;
        } else if (firstNameValue.length > 255) {
            if (firstNameError) firstNameError.innerText = "The first name must not be greater than 255 characters.";
            firstNameInput.focus();
            return false;
        }
    }

    // Validate Last Name
    if (lastNameInput) {
        let lastNameValue = (lastNameInput.value || '').trim();

        if (lastNameValue === "") {
            if (lastNameError) lastNameError.innerText = "The last name field is required.";
            lastNameInput.focus();
            return false;
        } else if (lastNameValue.length > 255) {
            if (lastNameError) lastNameError.innerText = "The last name must not be greater than 255 characters.";
            lastNameInput.focus();
            return false;
        }
    }

    // Validate Email
    if (emailInput) {
        // Force the value to a string layout and trim it
        let emailValue = (emailInput.value || '').trim();

        // Check empty value
        if (emailValue === "") {
            if (emailError) emailError.innerText = "The email field is required.";
            emailInput.focus();
            return false;
        }

        // Check if the format is invalid (Regex test)
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!emailPattern.test(emailValue)) {
            if (emailError) emailError.innerText = "The email field must be a valid email address.";
            emailInput.focus();
            return false;
        }

        // Check Unique Email
        let uniqueEmail = await isEmailUnique(emailValue, routes.email);

        if (!uniqueEmail) {
            if (emailError) emailError.innerText = "The email has already been taken";
            emailInput.focus();
            return false;
        }

    }

    // Validate Password (Unified for Create & Edit)
    if (passwordInput) {
        let passwordValue = (passwordInput.value || '').trim();

        // Safe check: look for Laravel's hidden PUT/PATCH method element
        let methodInput = formElement.querySelector('input[name="_method"]');
        let isEditForm = methodInput && (methodInput.value === 'PUT' || methodInput.value === 'PATCH');

        if (isEditForm) {
            // EDIT PAGE LOGIC: Only validate if they typed a new password
            if (passwordValue !== "") {
                if (passwordValue.length < 8) {
                    if (passwordError) passwordError.innerText = "The password must be at least 8 characters.";
                    passwordInput.focus();
                    return false;
                }
            }
        } else {
            // CREATE PAGE LOGIC: Password is strictly required
            if (passwordValue === "") {
                if (passwordError) passwordError.innerText = "The password field is required.";
                passwordInput.focus();
                return false;
            } else if (passwordValue.length < 8) {
                if (passwordError) passwordError.innerText = "The password must be at least 8 characters.";
                passwordInput.focus();
                return false;
            }
        }
    }

    // Validate Status
    if (statusInput) {
        let statusValue = (statusInput.value || '').trim();

        if (statusValue === "") {
            if (statusError) statusError.innerText = "The status field is required.";
            statusInput.focus();
            return false;
        } else if (!['Active', 'Disabled'].includes(statusValue)) {
            if (statusError) statusError.innerText = "The selected status is invalid.";
            statusInput.focus();
            return false;
        }
    }

    // Validate Date of Birth (Continued & Completed)
    if (dobInput) {
        let dobValue = (dobInput.value || '').trim();

        if (dobValue === "") {
            if (dobError) dobError.innerText = "The birth date field is required.";
            dobInput.focus();
            return false;
        }

        const parsedDate = Date.parse(dobValue);
        if (isNaN(parsedDate)) {
            if (dobError) dobError.innerText = "The birth date field must be a valid date.";
            dobInput.focus();
            return false;
        }
    }

    // Validate Phone
    if (phoneInput) {
        let phoneValue = (phoneInput.value || '').trim();

        if (phoneValue === "") {
            if (phoneError) phoneError.innerText = "The phone field is required.";
            phoneInput.focus();
            return false;
        }
    }

    // Validate Mobile
    if (mobileInput) {
        let mobileValue = (mobileInput.value || '').trim();

        if (mobileValue === "") {
            if (mobileError) mobileError.innerText = "The mobile field is required.";
            mobileInput.focus();
            return false;
        }
    }

    // Validate Country
    if (countryInput) {
        let countryValue = (countryInput.value || '').trim();

        if (countryValue === "") {
            if (countryError) countryError.innerText = "The country field is required.";
            countryInput.focus();
            return false;
        }
    }

    // Validate City
    if (cityInput) {
        let cityValue = (cityInput.value || '').trim();

        if (cityValue === "") {
            if (cityError) cityError.innerText = "The city field is required.";
            cityInput.focus();
            return false;
        }
    }

    // Validate Post Code
    if (postCodeInput) {
        let postCodeValue = (postCodeInput.value || '').trim();

        if (postCodeValue === "") {
            if (postCodeError) postCodeError.innerText = "The postcode field is required.";
            postCodeInput.focus();
            return false;
        }
    }

    // Validate Suburb
    if (suburbInput) {
        let suburbValue = (suburbInput.value || '').trim();

        if (suburbValue === "") {
            if (suburbError) suburbError.innerText = "The suburb field is required.";
            suburbInput.focus();
            return false;
        }
    }

    // Validate Image (Nullable, Image Type, Extension Mimes: png, jpg, jpeg, webp, Max Size: 2MB)
    if (imageInput && imageInput.files && imageInput.files.length > 0) {
        let file = imageInput.files[0]; // Targeted index 0 to reference the file object safely

        // Enforce actual Image mime root type
        if (!file.type.startsWith('image/')) {
            if (imageError) imageError.innerText = "The file must be an image.";
            imageInput.focus();
            return false;
        }

        // Validate Allowed Extensions (mimes: png, jpg, jpeg, webp)
        let fileName = file.name.toLowerCase();
        let allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];
        let fileExtension = fileName.split('.').pop();

        if (!allowedExtensions.includes(fileExtension)) {
            if (imageError) imageError.innerText = "The image must be a file of type: png, jpg, jpeg, webp.";
            imageInput.focus();
            return false;
        }

        // Enforce 2MB restriction threshold size (max: 2048 KB)
        let maxSizeInBytes = 2048 * 1024;

        if (file.size > maxSizeInBytes) {
            if (imageError) imageError.innerText = "The image must not be greater than 2MB.";
            imageInput.focus();
            return false;
        }
    }

    return true;
}

/**
 * Check User Login
 */
async function isLoginUnique(loginValue, checkUrl) {
    try {
        const targetUrl = `${checkUrl}?login=${encodeURIComponent(loginValue)}`;

        // Keep user session active
        const response = await fetch(targetUrl, {
            method: 'GET',
            credentials: 'include', // <-- THIS HOLDS SESSION ALIVE
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            return false;
        }

        const data = await response.json();
        return data.isUnique;
    } catch (error) {
        return false;
    }
}

/**
 * Check User Email
 */
async function isEmailUnique(emailValue, checkUrl) {
    try {
        const targetUrl = `${checkUrl}?email=${encodeURIComponent(emailValue)}`;

        // Keep user session active
        const response = await fetch(targetUrl, {
            method: 'GET',
            credentials: 'include', // <-- THIS HOLDS SESSION ALIVE
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) {
            return false;
        }

        const data = await response.json();
        return data.isUnique;

    } catch (error) {
        return false;
    }
}
