const error = {
  background: 'mdl-color--red-600',
  font: 'mdl-color-text--white'
};

const info = {
  background: 'mdl-color--yellow-600',
  font: 'mdl-color-text--white'
};

const success = {
  background: 'mdl-color--green-600',
  font: 'mdl-color-text--white'
}

const notification = document.querySelector('#snackbar-container');
const text = document.querySelector('#snackbar-text');

const set = (theme) => {
  notification.classList.add(theme.background);
  text.classList.add(theme.font);
}

export const showError = (message) => {
  set(error);
  notification.MaterialSnackbar.showSnackbar({message: message, timeout: 4000});
}

export const showInfo = (message) => {
  set(info);
  notification.MaterialSnackbar.showSnackbar({message: message, timeout: 4000});
}

export const showSuccess = (message) => {
  set(success);
  notification.MaterialSnackbar.showSnackbar({message: message, timeout: 4000});
}


