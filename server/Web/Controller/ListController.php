<?php


namespace Web\Controller;

use Common\Logger\Logger;
use Common\Logger\LoggerFactory;
use Common\Router\Route;
use Common\Router\Router;
use Common\Util\StringUtils;
use Common\Util\ValidationUtils;
use Core\Domain\ShoppingList\ShoppingList;
use Core\Domain\ShoppingList\ShoppingListState;
use Core\Domain\User\User;
use Exception;
use Service\ShoppingListService;
use Service\UserService;
use Web\HTTP\HTTPResponse;


class ListController extends BaseController {

  const CONTROLLER = "ListController";
  const ADD_ARTICLE = "add";
  const TAKE_OVER = "takeOverList";
  const NAME = "name";
  const QUANTITY = "quantity";
  const MAX_PRICE = "maxPrice";
  const SHOPPING_LIST_ID = "shoppingListId";
  const DELETE_ARTICLE = "deleteArticle";
  const PUBLISH_LIST = "publish";
  const ARTICLE_ID = "articleId";
  const DUE_DATE = "dueDate";
  const LIST_NAME = "listName";
  const DELETE_LIST = "deleteList";
  const EDIT_LIST = "editList";
  const VOLUNTEER_ID = "volunteerId";
  const SET_LIST_DONE = "setListDone";
  const PROCESS_LIST = "processList";
  const CHECK_ARTICLE = "checkArticle";
  const CHECKED = "checked";
  const UPDATE_TOTAL_PRICE = "updateTotalPrice";
  const TOTAL_PRICE = "totalPrice";
  const UPDATE_DUE_DATE = "updateDueDate";
  const UPDATE_LIST_NAME = "updateListName";
  const SHOPPING_LIST_WITH_ID = "Shopping list with ID ";
  const NEW_LIST = "newList";

  private static $instance = false;
  private ShoppingListService $shoppingListService;
  private UserService $userService;
  private Logger $logger;

  private function __construct() {
    $this->userService = UserService::getInstance();
    $this->shoppingListService = ShoppingListService::getInstance();
    $this->logger = LoggerFactory::createLogger('ListController');
  }

  public static function getInstance() {
    if (!self::$instance) {
      self::$instance = new ListController();
    }
    return self::$instance;
  }

  private function validateEditId(int $editId, string $caller) {
    if ($editId == 0) {
      $this->logger->error($caller . "No list id was found for editing list!");
      HTTPResponse::notFound();
    }
  }

  private function validateUser(?User $user, string $caller) {
    if ($user == null) {
      $this->logger->error($caller . " failed - User was unauthorized!");
      HTTPResponse::unauthorized();
    }
  }

  public function getAllShoppingLists(): array {
    $user = $this->userService->getAuthenticatedUser();
    $this->validateUser($user, 'getAllShoppingLists()');
    $user->clearStore();
    $this->logger->info("Get all shopping lists for owner");
    return $this->shoppingListService->getAllListsByOwnerId($user->getId());
  }

  public function getAllNewShoppingLists(): array {
    $this->logger->info("Get all shopping lists with state NEW");
    return $this->shoppingListService->getAllShoppingListsByState(ShoppingListState::NEW);
  }

  public function getAllInProgressShoppingLists(): array {
    $this->logger->info("Get all shopping lists with state IN PROGRESS!");
    $volunteer = $this->userService->getAuthenticatedUser();
    $this->validateUser($volunteer, 'getAllInProgressShoppingLists()');
    return $this->shoppingListService->getAllVolunteerListsByState($volunteer->getId(), ShoppingListState::IN_PROGRESS);
  }

  public function getAllDoneShoppingLists(): array {
    $this->logger->info("Get all shopping lists with state DONE!");
    $volunteer = $this->userService->getAuthenticatedUser();
    $this->validateUser($volunteer, 'getAllDoneShoppingLists()');
    return $this->shoppingListService->getAllVolunteerListsByState($volunteer->getId(), ShoppingListState::DONE);
  }

  public function getHelpSeekerEditList(): ShoppingList {
    $user = $this->userService->getAuthenticatedUser();
    $this->validateUser($user, 'getHelpSeekerEditList()');
    $editId = $user->getEditList();
    try {
      return $this->shoppingListService->getShoppingListById($editId);
    } catch (Exception $exception) {
      $this->logger->error($exception->getMessage());
      HTTPResponse::notFound();
    }
  }

  public function getVolunteerProcessList(): ShoppingList {
    $user = $this->userService->getAuthenticatedUser();
    $this->validateUser($user, 'getVolunteerProcessList()');
    $editId = $user->getEditList();
    $this->validateEditId($editId, 'getEditList()');
    try {
      return $this->shoppingListService->getShoppingListById($editId);
    } catch (Exception $exception) {
      $this->logger->error($exception->getMessage());
      HTTPResponse::notFound();
    }
  }

  public function post(): ?bool {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      throw new Exception('ListController can only handle POST requests.');
      return null;
    } elseif (!isset($_REQUEST[self::CONTROLLER])) {
      throw new Exception(self::CONTROLLER . 'not specified.');
      return null;
    }

    $action = $_REQUEST[self::CONTROLLER];
    switch ($action) {

      case self::NEW_LIST:
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'NEW_LIST');
        $id = $this->shoppingListService->newShoppingList($user->getId());
        $user->storeEditList($id);
        $this->logger->info("New list with ID: " . $id . " for User with ID: " . $user->getId() . " created!");
        exit(200);

      case self::ADD_ARTICLE:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::NAME], $_REQUEST[self::QUANTITY], $_REQUEST[self::MAX_PRICE], $_REQUEST[self::SHOPPING_LIST_ID]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'ADD_ARTICLE');
        $this->shoppingListService->addArticle(StringUtils::escape($_REQUEST[self::NAME]), intval($_REQUEST[self::QUANTITY]),
            floatval($_REQUEST[self::MAX_PRICE]), intval($_REQUEST[self::SHOPPING_LIST_ID]));
        try {
          $shoppingList = $this->shoppingListService->getShoppingListById(intval($_REQUEST[self::SHOPPING_LIST_ID]));
        } catch (Exception $exception) {
          $this->logger->error($exception->getMessage());
          HTTPResponse::notFound();
        }
        $this->logger->info("Add new article");
        echo json_encode($shoppingList->getArticles());
        exit(200);

      case self::DELETE_ARTICLE:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::ARTICLE_ID], $_REQUEST[self::SHOPPING_LIST_ID]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'DELETE_ARTICLE');
        $this->shoppingListService->deleteArticle(intval($_REQUEST[self::ARTICLE_ID]));
        try {
          $shoppingList = $this->shoppingListService->getShoppingListById(intval($_REQUEST[self::SHOPPING_LIST_ID]));
        } catch (Exception $exception) {
          $this->logger->error($exception->getMessage());
          HTTPResponse::notFound();
        }
        $this->logger->info("Delete article with ID " . intval($_REQUEST[self::ARTICLE_ID]));
        echo json_encode($shoppingList->getArticles());
        exit(200);

      case self::TAKE_OVER:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::VOLUNTEER_ID], $_REQUEST[self::SHOPPING_LIST_ID]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'TAKE_OVER');
        try {
          $this->shoppingListService->takeOverList(intval($_REQUEST[self::SHOPPING_LIST_ID]), intval($_REQUEST[self::VOLUNTEER_ID]));
        } catch (Exception $exception) {
          $this->logger->error($exception->getMessage());
          HTTPResponse::notFound();
        }
        $this->logger->info(self::SHOPPING_LIST_WITH_ID . $_REQUEST[self::SHOPPING_LIST_ID] . " taken over by volunteer with ID " . $_REQUEST[self::VOLUNTEER_ID] . "!");
        exit(200);
        break;

      case self::PROCESS_LIST:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::SHOPPING_LIST_ID]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'PROCESS_LIST');
        $user->storeEditList(intval($_REQUEST[self::SHOPPING_LIST_ID]));
        $this->logger->info(self::SHOPPING_LIST_WITH_ID . $_REQUEST[self::SHOPPING_LIST_ID] . " processed!");
        exit(200);
        break;

      case self::CHECK_ARTICLE:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::ARTICLE_ID]))) {
          HTTPResponse::badRequest();
        }
        $checkedState = isset($_REQUEST[self::CHECKED]) ? boolval($_REQUEST[self::CHECKED]) : "0";
        $this->shoppingListService->toggleArticleCheck(intval($_REQUEST[self::ARTICLE_ID]), $checkedState);
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'CHECK_ARTICLE');
        try {
          $shoppingList = $this->shoppingListService->getShoppingListById($user->getEditList());
        } catch (Exception $exception) {
          $this->logger->error($exception->getMessage());
          HTTPResponse::notFound();
        }
        $this->logger->info("Article with ID " . $_REQUEST[self::ARTICLE_ID] . " checked!");
        echo json_encode($shoppingList->getArticles());
        exit(200);
        break;

      case self::UPDATE_TOTAL_PRICE:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::SHOPPING_LIST_ID], $_REQUEST[self::TOTAL_PRICE]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'SET_LIST_DONE');
        $totalPrice = $_REQUEST[self::TOTAL_PRICE] == "" ? null : floatval(StringUtils::normalizeFloatInput($_REQUEST[self::TOTAL_PRICE]));
        $this->shoppingListService->setTotalPrice(intval($_REQUEST[self::SHOPPING_LIST_ID]), $totalPrice);
        $this->logger->info(self::SHOPPING_LIST_WITH_ID . $_REQUEST[self::SHOPPING_LIST_ID] . " total price updated to " . $_REQUEST[self::TOTAL_PRICE] . "!");
        exit(200);
        break;

      case self::SET_LIST_DONE:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::SHOPPING_LIST_ID]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'SET_LIST_DONE');
        $this->shoppingListService->updateListState(intval($_REQUEST[self::SHOPPING_LIST_ID]), ShoppingListState::DONE);
        $this->logger->info(self::SHOPPING_LIST_WITH_ID . $_REQUEST[self::SHOPPING_LIST_ID] . " set to state DONE!");
        exit(200);
        break;

      case self::UPDATE_LIST_NAME:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::SHOPPING_LIST_ID], $_REQUEST[self::LIST_NAME]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'UPDATE_LIST_NAME');
        $this->shoppingListService->updateListName(intval($_REQUEST[self::SHOPPING_LIST_ID]), StringUtils::escape($_REQUEST[self::LIST_NAME]));
        $this->logger->info(self::SHOPPING_LIST_WITH_ID . $_REQUEST[self::SHOPPING_LIST_ID] . " list name updated to " . StringUtils::escape($_REQUEST[self::LIST_NAME]) . "!");
        break;

      case self::UPDATE_DUE_DATE:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::SHOPPING_LIST_ID], $_REQUEST[self::DUE_DATE]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'UPDATE_DUE_DATE');
        $this->shoppingListService->updateDueDate(intval($_REQUEST[self::SHOPPING_LIST_ID]), StringUtils::escape($_REQUEST[self::DUE_DATE]));
        $this->logger->info(self::SHOPPING_LIST_WITH_ID . $_REQUEST[self::SHOPPING_LIST_ID] . " list due date updated to " . StringUtils::escape($_REQUEST[self::DUE_DATE]) . "!");
        break;

      case self::PUBLISH_LIST:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::SHOPPING_LIST_ID], $_REQUEST[self::DUE_DATE], $_REQUEST[self::LIST_NAME]))) {
          HTTPResponse::badRequest();
        }
        $this->shoppingListService->publish(intval($_REQUEST[self::SHOPPING_LIST_ID]), StringUtils::escape($_REQUEST[self::DUE_DATE]), StringUtils::escape($_REQUEST[self::LIST_NAME]));
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'PUBLISH_LIST');
        $user->clearStore();
        $this->logger->info(self::SHOPPING_LIST_WITH_ID . $_REQUEST[self::SHOPPING_LIST_ID] . " published and set to state NEW!");
        break;

      case self::DELETE_LIST:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::SHOPPING_LIST_ID]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'DELETE_LIST');
        $this->shoppingListService->deleteList(intval($_REQUEST[self::SHOPPING_LIST_ID]));
        $user->clearStore();
        $this->logger->info(self::SHOPPING_LIST_WITH_ID . $_REQUEST[self::SHOPPING_LIST_ID] . " deleted!");
        exit(200);
        break;

      case self::EDIT_LIST:
        if (ValidationUtils::anyNotSet(array($_REQUEST[self::SHOPPING_LIST_ID]))) {
          HTTPResponse::badRequest();
        }
        $user = $this->userService->getAuthenticatedUser();
        $this->validateUser($user, 'EDIT_LIST');
        $shoppingListId = intval($_REQUEST[self::SHOPPING_LIST_ID]);
        $user->storeEditList($shoppingListId);
        $this->shoppingListService->updateListState($shoppingListId, ShoppingListState::UNPUBLISHED);
        $this->logger->info(self::SHOPPING_LIST_WITH_ID . $_REQUEST[self::SHOPPING_LIST_ID] . " editing started and state set to UNPUBLISHED!");
        exit(200);

      default:
        $this->logger->warning("Post method does not exist!");
        break;
    }
    return false;
  }

}
