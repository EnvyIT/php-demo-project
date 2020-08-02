import {
  debounce,
  isEmpty,
  redirectToDoneLists,
  redirectToLogin,
  attachOnEnterHandler
} from "./util.js";
import {showError} from "./snackbar.js";

$(document).ready(function () {
  const listId = document.getElementById('shopping-list-id');

  const generateInput = (id, checked) => {
    let input = document.createElement("input");
    input.setAttribute("id", id);
    input.setAttribute("type", "checkbox");
    input.className = "mdl-checkbox__input";
    input.checked = checked;
    return input;
  }

  const generateLabel = (forValue) => {
    let label = document.createElement("label");
    label.setAttribute("for", forValue);
    label.className = "mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-data-table__select";
    return label;
  }

  const createTableData = () => {
    let td = document.createElement("td");
    td.className = "mdl-data-table__cell--non-numeric";
    return td;
  }

  const setTD = (td, text, tr) => {
    td = createTableData();
    td.innerText = text;
    tr.appendChild(td)
  }

  const createTableBody = (data) => {
    let articles = JSON.parse(data);
    let tbody = document.createElement("tbody");
    articles.forEach(article => {
      let tr = document.createElement("tr");
      let td = createTableData();
      let input = generateInput(article.id, article.checked);
      let label = generateLabel(article.id);
      label.appendChild(input);
      td.appendChild(label);
      tr.appendChild(td);
      setTD(td, article.name, tr);
      setTD(td, article.quantity, tr);
      setTD(td, article.maxPrice, tr);
      componentHandler.upgradeElement(input);
      componentHandler.upgradeElement(label);
      tbody.appendChild(tr);
    });
    return tbody;
  }

  const createTH = () => {
    let th = document.createElement("th");
    th.className = "mdl-data-table__cell--non-numeric";
    return th;
  }

  const setTH = (th, text, tr) => {
    th = createTH();
    th.innerText = text;
    tr.appendChild(th);
  }

  const areAllChecked = () => {
    const checkboxes = document.querySelectorAll(
        'tbody .mdl-data-table__select');
    return !isAnyUnchecked(checkboxes);
  }

  const getMasterCheckbox = () => {
    let label = generateLabel("table-header");
    label.setAttribute("id", "master-checkbox");
    let input = generateInput("table-header", areAllChecked());
    label.appendChild(input);
    componentHandler.upgradeElement(input);
    componentHandler.upgradeElement(label);
    return label;
  }

  const createTableHead = () => {
    let thead = document.createElement("thead");
    let tr = document.createElement("tr");
    let th = createTH();
    th.appendChild(getMasterCheckbox());
    tr.appendChild(th);
    setTH(th, "Name", tr);
    setTH(th, "Quantity", tr);
    setTH(th, "Max Price", tr);
    thead.appendChild(tr);
    return thead;
  }

  const createTable = (data) => {
    let table = document.createElement("table");
    table.className = 'mdl-data-table mdl-js-data-table mdl-shadow--2dp mdl-cell mdl-cell--12-col-desktop';
    table.setAttribute('id', 'process-table');
    table.appendChild(createTableHead());
    let tbody = createTableBody(data);
    table.appendChild(tbody);
    componentHandler.upgradeElement(table);
    return table;
  }

  const attachCheckBoxHandlers = (checkboxes, callback) => {
    checkboxes.forEach(
        checkbox => checkbox.addEventListener('change', (event) => {
          const isCheckedPHPString = event.target.checked ? "1" : "0"; //PHP does not parse false" correctly -> always 1 (true)
          callback(checkbox.getAttribute("for"), isCheckedPHPString);
        }));
  }

  const attachMasterCheckBoxHandler = (masterCheckbox, checkboxes,
      totalPriceField) => {
    let state;
    masterCheckbox.addEventListener('change', (event) => {
      checkboxes.forEach(box => {
        state = box.getElementsByTagName("input")[0].checked;
        if (!state && event.target.checked || state && !event.target.checked) {
          box.click();
        }
      });
      totalPriceField.focus();
    });
  }

  const isAnyUnchecked = (checkboxes) => {
    let anyUnchecked = false;
    checkboxes.forEach(checkbox => {
      if (!checkbox.getElementsByTagName("input")[0].checked) {
        anyUnchecked = true;
      }
    });
    return anyUnchecked;
  }

  const attachValidationHandler = (totalPriceField, doneButton, checkboxes) => {
    totalPriceField.addEventListener('keyup', (e) => {
      doneButton.disabled = isAnyUnchecked(checkboxes) || isEmpty(
          totalPriceField.value);
    });
    checkboxes.forEach(
        checkbox => checkbox.addEventListener('input', (event) => {
          doneButton.disabled = !event.target.checked || isEmpty(
              totalPriceField.value);
        }));
  }

  const attachPriceHandler = (totalPriceField, callback) => {
    totalPriceField.addEventListener('keyup', (event) => {
      const price = isEmpty(event.target.value) ? null : event.target.value;
      callback(listId.value, price);
    });
  }

  const attachOnFocusHandler = (totalPriceField, checkboxes, doneButton) => {
    totalPriceField.addEventListener('focus', (event) => {
      doneButton.disabled = isAnyUnchecked(checkboxes) || isEmpty(
          totalPriceField.value);
    });
  }

  const init = () => {
    const masterCheckbox = document.getElementById('master-checkbox');
    const checkboxes = document.querySelectorAll(
        'tbody .mdl-data-table__select');
    const doneButton = document.getElementById('done-button');
    const totalPriceField = document.getElementById('totalPrice');
    attachCheckBoxHandlers(checkboxes, checkArticle);
    attachMasterCheckBoxHandler(masterCheckbox, checkboxes, totalPriceField);
    attachValidationHandler(totalPriceField, doneButton, checkboxes);
    attachPriceHandler(totalPriceField, debounce(updateTotalPrice, 780));
    attachOnFocusHandler(totalPriceField, checkboxes, doneButton);
    attachOnEnterHandler(totalPriceField, doneButton);
    totalPriceField.focus();
  }

  const checkArticle = (articleId, checked) => {
    $.ajax({
      url: `index.php?ListController=checkArticle`,
      method: 'POST',
      data: {"articleId": articleId, "checked": checked},
      success: function (response) {
        $('#process-table').replaceWith(createTable(response));
        init();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError("No article specified to set checked state!")
        } else if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("Shopping list not found!")
        }
      }
    })
  }

  const updateTotalPrice = (shoppingListId, totalPrice) => {
    $.ajax({
      url: `index.php?ListController=updateTotalPrice`,
      method: 'POST',
      data: {"shoppingListId": shoppingListId, "totalPrice": totalPrice},
      success: function (response) {
        console.log(response);
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("Shopping list not found!")
        }
      }
    })
  }

  const setListDone = () => {
    $.ajax({
      url: `index.php?ListController=setListDone`,
      method: 'POST',
      data: {"shoppingListId": listId.value},
      success: function (response) {
        console.log(response);
        redirectToDoneLists();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError("All fields must be set list to done state!")
        } else if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("Shopping list not found!")
        }
      }
    })
  }

  init();
  window.setListDone = setListDone;
});


