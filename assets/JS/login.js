import {showError} from "./snackbar.js";
import {
  attachEventHandler,
  attachOnEnterHandler,
  redirectToDashboard
} from "./util.js";

(function () {

  const userNameField = document.getElementById("username");
  const passwordField = document.getElementById("password");
  const loginButton = document.getElementById("login-button");

  const init = () => {
    loginButton.disabled = true;
    resetFields();
    attachEventHandler([userNameField, passwordField], validateButton, 'keyup');
    attachOnEnterHandler(passwordField, loginButton);
  }

  const validateButton = () => {
    const userName = userNameField.value;
    const password = passwordField.value;
    loginButton.disabled = isEmpty(userName) || isEmpty(password);
  }

  const resetFields = () => {
    userNameField.value = '';
    passwordField.value = '';
    userNameField.focus();
  }

  const isEmpty = (value) => {
    return value === '';
  }

  const login = () => {
    $.ajax({
      url: 'index.php?UserController=login',
      method: 'POST',
      cache: false,
      data: {'userName': userNameField.value, 'password': passwordField.value},
      success: function (response) {
        redirectToDashboard();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError('Empty credentials submitted!');
        } else if (xhr.status === 401) {
          showError('Username or Password incorrect!');
        }
        init();
      }
    })
  }

  init();
  window.login = login;

})();

