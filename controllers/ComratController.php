<?php

class ComratController extends MyClasses\components\Controller
{
    public function actionAddcomm()
    {
        if (isset($_POST['send'])) {
            $content = isset($_POST['comment']) ? $_POST['comment'] : null;
            $content = htmlspecialchars($content);

            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
            $user_id = (int)$user_id;

            $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
            $product_id = (int)$product_id;

            if (! empty($content) && ! empty($user_id) && ! empty($product_id)) {
                $comment = new Comrat();
                // Запись данных в БД
                $comment->insertComment($content, $user_id, $product_id);
                header("Location: /");
                exit;
            }
        } else {
            return false;
        }

        return true;
    }

    public function actionAddrat()
    {
        if (isset($_POST['assess'])) {
            $ratingValue = isset($_POST['rating']) ? $_POST['rating'] : null;
            $ratingValue = (int) $ratingValue;

            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
            $user_id = (int) $user_id;

            $product_id = isset($_POST['product_id']) ? $_POST['product_id'] : null;
            $product_id = (int) $product_id;

            if (! empty($ratingValue) && ! empty($user_id) && ! empty($product_id)) {
                $comment = new Comrat();
                // Запись данных в БД
                $comment->insertRating($ratingValue, $user_id, $product_id);
                header("Location: /");
                exit;
            }
        } else {
            return false;
        }
        return true;
    }

    public function actionDelete()
    {
        $userId    = isset($_POST['user_id'])    ? $_POST['user_id']    : null;
        $userId = (int)$userId;

        $productId = isset($_POST['product_id']) ? $_POST['product_id'] : null;
        $productId = (int)$productId;

        if (isset($_POST['deleteC'])) {
            $attr = ['comment' => null, 'status_c' => null];
            $exist_data = \Comrat::first(['conditions' => ['user_id = ? AND product_id = ?', $userId, $productId]]);
            empty($exist_data)?:$exist_data->update_attributes($attr);
            header("Location: /");
            exit;

        } elseif (isset($_POST['deleteR'])) {
            $attr = ['rating' => null, 'status_r' => null];
            $exist_data = \Comrat::find(['conditions' => ['user_id = ? AND product_id = ?', $userId, $productId]]);
            empty($exist_data)?:$exist_data->update_attributes($attr);
            header("Location: /");
            exit;
        }
        return true;
    }
}