<?php

use Common\Util\StringUtils;
use Web\Controller\ListController;
use Web\Controller\UserController;

require_once('Partials/volunteerHeader.php');
UserController::getInstance()->routingGuardVolunteer();
$shoppingList = ListController::getInstance()->getVolunteerProcessList();
?>

  <div class="mdl-grid">
    <input id="shopping-list-id" type="hidden" value="<?php echo $shoppingList->getId(); ?>">
    <div class="mdl-cell mdl-cell--12-col-desktop mdl-grid mdl-cell--12-col-phone">
      <div
          class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-cell mdl-cell--2-col-desktop mdl-cell--12-col-phone">
        <input class="mdl-textfield__input" type="number" id="totalPrice" step="0.01"
               value="<?php echo $shoppingList->getTotal() ?>">
        <label class="mdl-textfield__label" for="list-name">Total</label>
        <span class="mdl-textfield__error">Is required and must be an integer or float!</span>
      </div>
      <div
          class="mdl-textfield mdl-js-textfield mdl-textfield--floating-label mdl-cell mdl-cell--2-col-desktop mdl-cell--12-col-phone mdl-cell--8-offset">
        <button id="done-button"
                class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--primary full-width"
                onclick="setListDone()" disabled
                title="Set shopping list to done"
        >Done
        </button>
      </div>
    </div>
    <table id="process-table" class="mdl-data-table mdl-js-data-table mdl-shadow--2dp mdl-cell mdl-cell--12-col-desktop">
      <thead>
      <tr>
        <th class="mdl-data-table__cell--non-numeric">
          <label id="master-checkbox" class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-data-table__select"
                 for="table-header">
            <input type="checkbox" id="table-header"
                   class="mdl-checkbox__input" <?php if ($shoppingList->areAllArticlesChecked()) { ?> checked <?php } ?>/>
          </label>
        </th>
        <th class="mdl-data-table__cell--non-numeric">Name</th>
        <th class="mdl-data-table__cell--non-numeric">Quantity</th>
        <th class="mdl-data-table__cell--non-numeric">Max Price</th>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($shoppingList->getArticles() as $article): ?>
        <tr>
          <td class="mdl-data-table__cell--non-numeric">
            <label class="mdl-checkbox mdl-js-checkbox mdl-js-ripple-effect mdl-data-table__select"
                   for="<?php echo $article->getId() ?>">
              <input id="<?php echo $article->getId() ?>" type="checkbox"
                     class="mdl-checkbox__input" <?php if ($article->isChecked()) { ?> checked <?php } ?>>
            </label>
          </td>
          <td class="mdl-data-table__cell--non-numeric"><?php echo StringUtils::escape($article->getName()); ?></td>
          <td class="mdl-data-table__cell--non-numeric"><?php echo StringUtils::escape($article->getQuantity()); ?></td>
          <td class="mdl-data-table__cell--non-numeric"><?php echo StringUtils::escape($article->getMaxPrice()); ?></td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>


  <script type="module" src="assets/JS/process-list.js"></script>
<?php
require_once('Partials/footer.php');
