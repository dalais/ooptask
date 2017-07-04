<?php

class ProductController extends MyClasses\components\Controller {


    public function actionIndex($page = 1)
    {
        $userID = isset($_SESSION['id']) ? $_SESSION['id'] : null;
        $auth = isset($_SESSION['username']) ? $_SESSION['username'] : null;

        $products = \Products::getProducts($page);

        $total = (int) \Products::count();

        $pagination = new MyClasses\components\Pagination($total, $page, \Products::SHOW_BY_DEFAULT, 'page-');

        $comments = \Comrat::getComments($userID);
        $ratings  = \Comrat::getRatings($userID);

        $array = [];
        foreach ($products as $item) {
            $prod = $item->to_array(['only' => ['id','product']]);
            $prod_id = $prod['id'];
            $array[$prod_id] = $prod;

            if (! empty($comments)) {
                foreach ($comments as $arr_id => $comt) {
                    if ($comt['product_id'] != $prod_id) continue;
                    $array[$prod_id]['comment'] = $comt;
                }
            }

            if (! empty($ratings)) {
                foreach ($ratings as $arr_id => $rait) {
                    if ($rait['product_id'] != $prod_id) continue;
                    $array[$prod_id]['rating'] = $rait;
                }
            }
        }
        $this->loadTwig();
        $this->twig->addGlobal('auth', $auth);
        $this->twig->addGlobal('user_id', $userID);

        echo $this->twig->render('products.html', ['array' => $array, 'pagination' => $pagination]);


        return true;
    }



    public function actionView($id)
    {

        $product = \Products::find($id);

        $com_and_rat = \Comrat::getAllCommRatingsByIdProduct($id);

        $avg = \Comrat::avgValue($id);

        $this->loadTwig();
        $auth = isset($_SESSION['username']) ? $_SESSION['username'] : null;
        $this->twig->addGlobal('auth', $auth);

        echo $this->twig->render('view.html', ['item' => $product,'c_and_r' => $com_and_rat, 'avg' => $avg]);

        return true;
    }
}