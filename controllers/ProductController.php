<?php

class ProductController extends MyClasses\components\Controller {


    public function actionIndex()
    {
        $products = \Products::find('all', ['limit' => 10]);

        $this->loadTwig();

        echo $this->twig->display('products.html', ['products' => $products]);

        return true;
    }



    public function actionView($id)
    {

        $product = \Products::find($id);

        $this->loadTwig();

        echo $this->twig->display('view.html', ['item' => $product]);

        return true;
    }
}