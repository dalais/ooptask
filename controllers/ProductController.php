<?php

class ProductController extends MyClasses\components\Controller {



    public function actionIndex()
    {
        $products = \Products::find('all', ['limit' => 10]);

        $this->loadTwig();
        if (isset($_SESSION['username'])) {
            $this->twig->addGlobal('auth', $_SESSION['username']);
        }
        echo $this->twig->render('products.html', ['products' => $products]);

        return true;
    }



    public function actionView($id)
    {

        $product = \Products::find($id);

        $this->loadTwig();

        echo $this->twig->render('view.html', ['item' => $product]);

        return true;
    }
}