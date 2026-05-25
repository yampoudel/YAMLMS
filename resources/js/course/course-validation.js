window.validateCourse = function (formElement) {
    // Grab all input
    let titleInput = formElement.querySelector('[name="title"]');
    let descriptionInput = formElement.querySelector('[name="description"]');
    let priceInput = formElement.querySelector('[name="price"]');
    let imageInput = formElement.querySelector('[name="image_path"]');

    // Grab all input errors
    let titleError = formElement.querySelector('.js-title-error');
    let descriptionError = formElement.querySelector('.js-description-error');
    let priceError = formElement.querySelector('.js-price-error');
    let imageInputError = formElement.querySelector('.js-image-error');

    // Instantly clear old messages (clears previous Laravel errors for all the fields)
    if (titleError) titleError.innerText = "";
    if (descriptionError) descriptionError.innerText = "";
    if (priceError) priceError.innerText = "";
    if (imageInputError) imageInputError.innerText = "";

    // Validate Title
    if (titleInput) {
        let titleValue = titleInput.value.trim();
        if (titleValue === "") {
            if (titleError) titleError.innerText = "The course title field is required.";
            titleInput.focus();
            return false; // Prevent Form submission
        }
        if (titleValue.length > 255) {
            if (titleError) titleError.innerText = "The course title must not be greater than 255 characters.";
            titleInput.focus();
            return false;
        }
    }

    // Validate Description
    if (descriptionInput && descriptionInput.value.trim() === "") {
        if (descriptionError) descriptionError.innerText = "The description field is required.";
        descriptionInput.focus();
        return false;
    }

    // Validate Price
    if (priceInput) {
        let priceValue = priceInput.value;
        if (priceValue === "") {
            if (priceError) priceError.innerText = "The price field is required.";
            priceInput.focus();
            return false;
        }
        if (parseFloat(priceValue) < 0) {
            if (priceError) priceError.innerText = "The course price should be at least 0";
            priceInput.focus();
            return false;
        }
    }

    // Validate Image
    if (imageInput && imageInput.files.length > 0) {
        let file = imageInput.files[0];
        let fileSizeInMB = file.size / 1024 / 1024; // Convert bytes to Megabytes

        if (fileSizeInMB > 2) {
            if (imageInputError) imageInputError.innerText = "The course image must not be greater than 2 megabytes.";
            imageInput.value = ""; // Reset the input field so they must choose a smaller one
            return false;
        }
    }

    return true; // Proceed to backend(form submission)
}
