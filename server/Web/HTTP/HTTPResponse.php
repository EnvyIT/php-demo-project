<?php


namespace Web\HTTP;


class HTTPResponse {

    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const NOT_FOUND = 404;

    private static function throwHTTPErrorResponse(int $statusCode): void {
        http_response_code($statusCode);
        die($statusCode);
    }

    /**
     * Sets HTTP response code 400 and calls die.
     */
    public static function badRequest() {
        self::throwHTTPErrorResponse(self::BAD_REQUEST);
    }


    /**
     * Sets HTTP response code 401 and calls die.
     */
    public static function unauthorized() {
        self::throwHTTPErrorResponse(self::UNAUTHORIZED);
    }

    /**
     * Sets HTTP response code 404 and calls die.
     */
    public static function notFound() {
        self::throwHTTPErrorResponse(self::NOT_FOUND);
    }

}
