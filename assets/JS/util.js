const ENTER = 13;

export const debounce = (func, wait) => {
  let timeout;
  return function (...args) {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
      func.apply(this, args);
    }, wait);
  };
}

export const isEmpty = (value) => {
  return value === '';
}

export const redirectToLogin = () => {
  redirectTo("index.php?view=login");
}

export const redirectToInProgress = () => {
  redirectTo("index.php?view=inProgress");
}

export const redirectToDoneLists = () => {
  redirectTo("index.php?view=doneLists");
}

export const redirectToMyLists = () => {
  redirectTo("index.php?view=myLists");
}

export const redirectToDashboard = () => {
  redirectTo("index.php?view=dashboard");
}

export const redirectToProcess = () => {
  redirectTo("index.php?view=process");
}

export const redirectToEditList = () => {
  redirectTo("index.php?view=createList");
}

const redirectTo = (url) => {
  location.href = url;
}

export const attachOnEnterHandler = (control, action) => {
  control.onkeydown = (event) => {
    if (event.keyCode === ENTER) {
      action.click();
    }
  }
}

export const attachEventHandler = (fields, validationCallback, event) => {
  fields.forEach(field => field.addEventListener(event, () => {
    validationCallback();
  }));
}

export const dateToString = (date) => {
  const splitDate = date.toLocaleString().split('/');
  let day = splitDate[0];
  let month = splitDate [1];
  const year = splitDate[2].split(',')[0];
  day = fillWithZero(day);
  month = fillWithZero(month);
  return `${year}-${day}-${month}`;
}

const fillWithZero = (input) => {
  return input?.length === 1 ? `0${input}` : input;
}
