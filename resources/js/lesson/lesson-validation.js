window.lessonValidation = function (formElement) {
    // Get all input elements
    let titleInput = formElement.querySelector('[name="title"]');
    let courseIdInput = formElement.querySelector('[name="course_id"]');
    let statusInput = formElement.querySelector('[name="status"]');
    let typeInput = formElement.querySelector('[name="type"]');
    let descriptionInput = formElement.querySelector('[name="description"]');
    let contentInput = formElement.querySelector('[name="content"]');

    // Get all input errors
    let titleError = formElement.querySelector('.js-title-error');
    let courseIdError = formElement.querySelector('.js-course-id-error');
    let statusError = formElement.querySelector('.js-status-error');
    let typeError = formElement.querySelector('.js-type-error');
    let descriptionError = formElement.querySelector('.js-description-error');
    let contentError = formElement.querySelector('.js-content-error');

    // Instantly clear old messages
    if (titleError) titleError.innerText = "";
    if (courseIdError) courseIdError.innerText = "";
    if (statusError) statusError.innerText = "";
    if (typeError) typeError.innerText = "";
    if (descriptionError) descriptionError.innerText = "";
    if (contentError) contentError.innerText = "";

    // Validate Title
    if (titleInput) {
        let titleValue = (titleInput.value || '').trim();

        if (titleValue === '') {
            if (titleError) titleError.innerText = 'The title field is required';
            titleInput.focus();
            return false;
        } else if (titleValue.length > 255) {
            if (titleError) titleError.innerText = 'The title field length must be less than 255 characters.';
            titleInput.focus();
            return false;
        }
    }

    // Validate Course id
    if (courseIdInput) {
        let courseIdValue = (courseIdInput.value || '').trim();

        if (courseIdValue === '') {
            if (courseIdError) courseIdError.innerText = "The course id field is required.";
            courseIdInput.focus();
            return false;
        }
        // Checks if it is not a number, not an integer, or less than 1
        else if (isNaN(courseIdValue) || !Number.isInteger(Number(courseIdValue)) || Number(courseIdValue) <= 0) {
            if (courseIdError) courseIdError.innerText = "The course id must be a positive integer.";
            courseIdInput.focus();
            return false;
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

    // Validate Type
    if (typeInput) {
        let typeValue = (typeInput.value || '').trim();

        if (typeValue === "") {
            if (typeError) typeError.innerText = "The type field is required.";
            typeInput.focus();
            return false;
        } else if (!['Default', 'Survey', 'Quiz'].includes(typeValue)) {
            if (typeError) typeError.innerText = "The selected type is invalid.";
            typeInput.focus();
            return false;
        }
    }

    // Validate Description
    if (descriptionInput) {
        let descriptionValue = (descriptionInput.value || '').trim();

        if (descriptionValue !== '') {
            // Check if the value is just a bunch of numbers (optional safety check)
            if (!isNaN(descriptionValue) && descriptionValue.length > 0) {
                if (descriptionError) descriptionError.innerText = "The description value must be a valid text string.";
                descriptionInput.focus();
                return false;
            }
        }
    }

    // Validate Content
    if (contentInput) {
        let contentValue = (contentInput.value || '').trim();

        if (contentValue === '') {
            if (contentError) contentError.innerText = 'The content field is required.';
            contentInput.focus();
            return false;
        }
    }

    return true;

};
