<?php

namespace Web\Controller;

use Common\Router\Param;

class BaseController implements Controller {

    /**
     * GET parameter "page" adds current page to action so that a redirect
     * back to this page is possible after successful execution of POST action
     * if "page" has been set before then just keep the current value (to avoid
     * problem with "growing URLs" when a POST form is rendered "a second time"
     * e.g. during a forward after an unsuccessful POST action)
     *
     * Be sure to check for invalid / insecure page redirects!!
     *
     * @param string $action uri optional
     * @param string $controller
     * @param array $params array key/value pairs
     * @return string
     */
    public static function action(string $action, string $controller, array $params = null): string {
        $page = isset($_REQUEST[Param::PAGE]) ? $_REQUEST[Param::PAGE] : $_SERVER['REQUEST_URI'];
        $res = 'index.php?' . $controller . '=' . rawurlencode($action) . '&' . Param::PAGE . '=' . rawurlencode($page);
        if (is_array($params)) {
            foreach ($params as $name => $value) {
                $res .= '&' . rawurlencode($name) . '=' . rawurlencode($value);
            }
        }
        return $res;
    }

}
