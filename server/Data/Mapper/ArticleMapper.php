<?php


namespace Data\Mapper;

use Core\Domain\Article\Article;

class ArticleMapper {

    public static function map($articleRow): ?Article {
        if ($articleRow) {
            return new Article($articleRow['id'], $articleRow['name'], $articleRow['quantity'], $articleRow['max_price'], null, $articleRow['checked']);
        }
        return null;
    }

}
