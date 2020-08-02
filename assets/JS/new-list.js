import {
  redirectToInProgress,
  redirectToLogin,
  redirectToProcess
} from "./util.js";
import {showError} from "./snackbar.js";

$(document).ready(function () {
  const takeOverList = (listId, userId) => {
    console.log('takeOverList', listId, userId);
    $.ajax({
      url: `index.php?ListController=takeOverList`,
      method: 'POST',
      cache: false,
      data: {"shoppingListId": listId, "volunteerId": userId},
      success: function (response) {
        redirectToInProgress();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError('All fields must be set to take over list!');
        } else if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("List is already taken over by another volunteer!")
        }
      }
    })
  }

  const processList = (listId) => {
    $.ajax({
      url: `index.php?ListController=processList`,
      method: 'POST',
      cache: false,
      data: {"shoppingListId": listId},
      success: function (response) {
        redirectToProcess();
      },
      error: (xhr, ajaxOptions, thrownError) => {
        if (xhr.status === 400) {
          showError("No Shopping list specified for editing!")
        } else if (xhr.status === 401) {
          redirectToLogin();
        } else if (xhr.status === 404) {
          showError("Shopping list not found!")
        }
      }
    })
  }

  window.takeOverList = takeOverList;
  window.processList = processList;
});
