import {showError} from "./snackbar.js";
import {redirectToEditList, redirectToLogin, redirectToMyLists} from "./util.js";

$(document).ready(function () {

  const newList = () => {
    $.ajax({
      url: `index.php?ListController=newList`,
      method: 'POST',
      cache: false,
      success: function (response) {
        redirectToEditList();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 401) {
          redirectToLogin();
        } else {
          showError("An unexpected error occurred trying to create new list!")
        }
      }
    })
  }

  const editList = (listId) => {
    $.ajax({
      url: `index.php?ListController=editList`,
      method: 'POST',
      cache: false,
      data: {"shoppingListId": listId},
      success: function (response) {
        redirectToEditList();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 401) {
          redirectToLogin();
        } else {
          showError("An unexpected error occurred trying to create new list!")
        }
      }
    })
  }

  const deleteList = (listId) => {
    $.ajax({
      url: `index.php?ListController=deleteList`,
      method: 'POST',
      cache: false,
      data: {"shoppingListId": listId},
      success: function (response) {
        redirectToMyLists();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 401) {
          redirectToLogin();
        } else {
          showError("An unexpected error occurred trying to create new list!")
        }
      }
    })
  }

  window.newList = newList;
  window.editList = editList;
  window.deleteList = deleteList;

});


