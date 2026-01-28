const buttonSave = document.getElementById("button-save");
const nameInputField = document.getElementById("article-name");
const contentInputField = document.getElementById("article-content");
const errorMessageContent = document.getElementById("error-message-content");
const errorMessageName = document.getElementById("error-message-name");

function validateName(name) {
  return name.trim().length <= 32 && name.length > 0;
}

function validateContent(content) {
  return content.trim().length <= 1024;
}

// Unvalid input data stops the request and displays the error message
buttonSave.addEventListener("click", (e) => {
  console.log(contentInputField.value.length);
  if (!validateName(nameInputField.value)) {
    e.preventDefault();
    errorMessageName.classList.remove("hide");
  }
  if (!validateContent(contentInputField.value)) {
    e.preventDefault();
    errorMessageContent.classList.remove("hide");
  }
});
