<?php

use Common\Util\StringUtils;
use Web\Controller\ListController;
use Web\Controller\UserController;

require_once('Partials/header.php');
UserController::getInstance()->routingGuardHelpSeeker();
$dateNow = new DateTime();
$shoppingList = ListController::getInstance()->getHelpSeekerEditList();
?>

  <div class="list-control__buttons">
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
      <input id="shoppingListId" type="hidden" name="<?php echo ListController::SHOPPING_LIST_ID; ?>"
             value="<?php echo $shoppingList->getId(); ?>">
      <input class="mdl-textfield__input" type="date" id="dueDate" placeholder="Due Date"
             name="<?php echo ListController::DUE_DATE; ?>"
             min="<?php echo $dateNow->format('Y-m-d') ?>"
             value="<?php if ($shoppingList->getDueDate() != null) {
                 echo $shoppingList->getDueDate()->format('Y-m-d');
             } else {
                 echo $dateNow->format('Y-m-d');
             } ?>" required>
      <label class="mdl-textfield__label" for="dueDate">Due Date</label>
    </div>
    <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
      <input class="mdl-textfield__input" type="text" id="list-name" name="<?php echo ListController::LIST_NAME ?>"
             value="<?php echo $shoppingList->getName(); ?>">
      <label class="mdl-textfield__label" for="list-name">List Name</label>
    </div>
    <div class="button">
      <button type="submit" id="publish-button"
              class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary"
              title="Publish Shopping List"
              onclick="publishList()"
      >
        Publish List
      </button>
    </div>
  </div>

  <div class="mdl-grid">
    <table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp full-width mdl-cell mdl-cell--12-col">
      <thead>
      <tr>
        <th class="mdl-data-table__cell--non-numeric">
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="text" id="name" pattern="^(?!\s*$).+" required>
            <label class="mdl-textfield__label" for="name">Article Name</label>
            <span class="mdl-textfield__error">Is required and must not be empty!</span>
          </div>
        </th>
        <th class="mdl-data-table__cell--non-numeric">
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="number" step="1" id="quantity" required>
            <label class="mdl-textfield__label" for="quantity">Quantity</label>
            <span class="mdl-textfield__error">Is required and must be an integer!</span>
          </div>
        </th>
        <th class="mdl-data-table__cell--non-numeric">
          <div class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label">
            <input class="mdl-textfield__input" type="number" step="0.01" id="maxPrice" required>
            <label class="mdl-textfield__label" for="maxPrice">Max Price</label>
            <span class="mdl-textfield__error">Is required and must be an integer or float!</span>
          </div>
        </th>
        <th>
          <button id="addButton"
                  class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary mb-08"
                  title="Add Article"
                  onclick="addArticle()"
          ><i class="material-icons">add</i></button>
        </th>
      </tr>
      </thead>
      <tbody id="table-body">
      <?php foreach ($shoppingList->getArticles() as $article): ?>
        <tr>
          <td class="mdl-data-table__cell--non-numeric"><?php echo StringUtils::escape($article->getName()); ?></td>
          <td class="mdl-data-table__cell--non-numeric"><?php echo StringUtils::escape($article->getQuantity()); ?></td>
          <td class="mdl-data-table__cell--non-numeric"><?php echo StringUtils::escape($article->getMaxPrice()); ?></td>
          <td>
            <button onclick="deleteArticle(<?php echo $article->getId() ?>, <?php echo $article->getShoppingListId() ?>)"
                    class="mdl-button mdl-js-button mdl-button--icon mdl-color-text--red-500" title="Delete Article">
              <i class="material-icons">delete</i>
            </button>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <script type="module" src="assets/JS/create-list.js"></script>
<?php
require_once('Partials/footer.php');
