import {
  attachEventHandler,
  attachOnEnterHandler,
  debounce,
  isEmpty,
  redirectToLogin,
  redirectToMyLists
} from "./util.js";
import {showError} from "./snackbar.js";

$(document).ready(function () {

  const listId = document.getElementById('shoppingListId');
  const nameField = document.getElementById("name");
  const quantityField = document.getElementById("quantity");
  const maxPriceField = document.getElementById("maxPrice");
  const addButton = document.getElementById("addButton");

  const dueDateField = document.getElementById("dueDate");
  const listNameField = document.getElementById("list-name");
  const publishButton = document.getElementById("publish-button");

  const validateAddButton = () => {
    const fields = [nameField, quantityField, maxPriceField];
    addButton.disabled = fields.some(f => isEmpty(f.value));
  }

  const validatePushButton = () => {
    const areFieldsValid = [dueDateField, listNameField].some(
        f => isEmpty(f.value));
    const body = document.getElementById('table-body');
    publishButton.disabled = body.children.length === 0 || areFieldsValid;
  }

  const resetFields = () => {
    [nameField, quantityField, maxPriceField].forEach(f => f.value = "");
  }

  const setNameFieldFocus = () => {
    nameField.focus();
  }

  const parseArticles = (data) => {
    let articles = JSON.parse(data);
    let html = "";
    articles.forEach(article => {
      html += `<tr>
      <td class="mdl-data-table__cell--non-numeric">${article.name}</td>
      <td class="mdl-data-table__cell--non-numeric">${article.quantity}</td>
      <td class="mdl-data-table__cell--non-numeric">${article.maxPrice}</td>
      <td>
       <button onclick="deleteArticle(${article.id}, ${article.shoppingListId})"
               class="mdl-button mdl-js-button mdl-button--icon mdl-color-text--red-500">
        <i class="material-icons">delete</i>
       </button>
      </td>
      </tr>`
    });
    return html;
  }

  const attachPostHandler = (formId, url, method, templateId,
      postCallbacks = null) => {
    $(formId).on('submit', function (event) {
      event.preventDefault();
      $.ajax({
        url,
        method,
        cache: false,
        data: $(this).serialize(),
        success: function (response) {
          if (postCallbacks) {
            postCallbacks.forEach(callback => callback());
          }
          $(templateId).html(parseArticles(response));
          validatePushButton();
        },
        error: (xhr, ajaxOptions, thrownError) => {
          if (xhr.status === 400) {
            showError("All fields must be set to add a new article!")
          } else if (xhr.status === 401) {
            redirectToLogin();
          } else if (xhr.status === 404) {
            showError("Shopping list not found!")
          }
        }
      })
    });
  }
  const attachListNameHandler = (callback) => {
    listNameField.addEventListener('keyup', (event) => {
      const listName = isEmpty(event.target.value) ? null : event.target.value;
      callback(listId.value, listName);
    });
  }

  const init = () => {
    attachEventHandler([nameField, quantityField, maxPriceField],
        validateAddButton, 'focus');
    attachEventHandler([nameField, quantityField, maxPriceField],
        validateAddButton, 'keyup');
    attachEventHandler([dueDateField, listNameField], validatePushButton,
        'focus');
    attachEventHandler([dueDateField, listNameField], validatePushButton,
        'keyup');
    attachOnEnterHandler(nameField, addButton);
    attachOnEnterHandler(quantityField, addButton);
    attachOnEnterHandler(maxPriceField, addButton);
    attachEventHandler([dueDateField], debounce(updateDueDate, 780), 'change');
    attachPostHandler('#article-form', 'index.php?ListController=add', 'POST',
        '#table-body', [resetFields, setNameFieldFocus])
    attachListNameHandler(debounce(updateListName, 780));
    nameField.focus();
  }

  const updateListName = (shoppingListId, listName) => {
    console.log(shoppingListId, listName);
    $.ajax({
      url: `index.php?ListController=updateListName`,
      method: 'POST',
      data: {"shoppingListId": shoppingListId, "listName": listName},
      success: function (response) {
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError("List cannot be empty - old value will be restored")
        } else if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("Shopping list not found!")
        }
      }
    })
  }

  const updateDueDate = () => {
    $.ajax({
      url: `index.php?ListController=updateDueDate`,
      method: 'POST',
      data: {"shoppingListId": listId.value, "dueDate": dueDateField.value},
      success: function (response) {
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError("List cannot be empty - old value will be restored")
        } else if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("Shopping list not found!")
        }
      }
    })
  }

  const deleteArticle = (articleId, shoppingListId) => {
    $.ajax({
      url: `index.php?ListController=deleteArticle`,
      method: 'POST',
      cache: false,
      data: {"articleId": articleId, "shoppingListId": shoppingListId},
      success: function (response) {
        $('#table-body').html(parseArticles(response));
        validatePushButton();
        nameField.focus();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError("All fields must be set to delete an article!")
        } else if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("Shopping list not found!")
        }
      }
    })
  }

  const addArticle = () => {
    $.ajax({
      url: `index.php?ListController=add`,
      method: 'POST',
      cache: false,
      data: {
        'name': nameField.value,
        'quantity': quantityField.value,
        'maxPrice': maxPriceField.value,
        'shoppingListId': listId.value
      },
      success: function (response) {
        $('#table-body').html(parseArticles(response));
        resetFields();
        validatePushButton();
        nameField.focus();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError("All fields must be set to add a new article!")
        } else if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("Shopping list not found!")
        }
      }
    })
  }

  const publishList = () => {
    $.ajax({
      url: `index.php?ListController=publish`,
      method: 'POST',
      cache: false,
      data: {
        'shoppingListId': listId.value,
        'dueDate': dueDateField.value,
        'listName': listNameField.value
      },
      success: function (response) {
        redirectToMyLists();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError("All fields must be set to publish!")
        } else if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("Shopping list not found!")
        }
      }
    })
  }

  window.deleteArticle = deleteArticle;
  window.publishList = publishList;
  window.addArticle = addArticle;
  init();
});


