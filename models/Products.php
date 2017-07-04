<?php


class Products extends ActiveRecord\Model
{
    const SHOW_BY_DEFAULT = 2;

    public static $table_name = 'products';

    public static $primary_key = 'id';


    public static function getProducts($page = 1)
    {
        $page = (int)$page;
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;

        $products = \Products::find('all', ['limit' => self::SHOW_BY_DEFAULT, 'offset' => $offset]);

        return $products;
    }
}