const checkValidation = (
  type = null,
  email = null,
  name = null,
  password = null,
  description = null,
  img = null,
  tags = null
) => {
  console.log("type", email, name, password, description, img, tags);
  switch (type.toLowerCase()) {
    case "auth":
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (email !== null && !emailRegex.test(email)) {
        showToast("Invalid email format", "error");
        return false;
      }

      if (name !== null && name.length <= 1) {
        showToast("Name must be at least 2 characters", "error");
        return false;
      }

      if (password.length < 6) {
        showToast("Password must be at least 6 characters", "error");
        return false;
      }
      break;

    case "post":
      if (name !== null && name.length <= 1) {
        showToast("Name must be at least 2 characters", "error");
        return false;
      }

      if (!description.length) {
        showToast("Description can not be blank", "error");
        return false;
      }

      if (!img || img.length < 1) {
        showToast("Image can not be blank", "error");
        return false;
      }

      if (tags.length < 1) {
        showToast("Tags can not be blank", "error");
        return false;
      }
      break;

    default:
      showToast("Invalid validation type", "error");
      return false;
  }

  return true;
};

// Example usage:
// console.log(checkValidation('test@example.com', 'John', 'pass123', '', [], [], 'auth'));
// console.log(checkValidation(null, 'John', '', 'Some description', ['img.jpg'], ['tag1'], 'post'));
