<?php

class ProductController extends MyClasses\components\Controller {


    public function actionIndex()
    {

        $userID = isset($_SESSION['id']) ? $_SESSION['id'] : null;

        $products = \Products::find('all', ['limit' => 10]);


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
        $auth = isset($_SESSION['username']) ? $_SESSION['username'] : null;
        $this->twig->addGlobal('auth', $auth);

        echo $this->twig->render('products.html', ['array' => $array]);


        return true;
    }



    public function actionView($id)
    {

        $product = \Products::find($id);

        $com_and_rat = \Comrat::getAllCommRatingsByIdProduct($id);

        $avg = \Comrat::avgValue($id);

        $this->loadTwig();

        echo $this->twig->render('view.html', ['item' => $product,'c_and_r' => $com_and_rat, 'avg' => $avg]);

        return true;
    }
}