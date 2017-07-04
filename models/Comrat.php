<?php


class Comrat extends ActiveRecord\Model
{
    public static $table_name = 'comrat';

    public static $primary_key = 'id';


    public static function getComments($userID)
    {
        $userID = (int)$userID;

        $comAttr = \Comrat::find('all', ['conditions' => ['user_id = ? AND status_c = ?', $userID, 1]]);
        $array = [];
        if (!empty($comAttr)) {
            foreach ($comAttr as $item) {
                $res = $item->to_array(['only' => ['comment', 'user_id', 'product_id']]);
                $array[] = $res;
            }
        } else {
            $array = null;
        }

        return $array;
    }


    public function insertComment($contentComm, $userId, $productId)
    {

        $contentComm = (string)$contentComm;
        $userId = (int)$userId;
        $productId = (int)$productId;

        if ($userId === $_SESSION['id']) {
            $exist_data = \Comrat::find(['conditions' => ['user_id = ? AND product_id = ?', $userId, $productId]]);

            if (empty($exist_data)) {
                $comment = new Comrat();
                $comment->comment = $contentComm;
                $comment->user_id = $userId;
                $comment->product_id = $productId;
                $comment->status_c = 1;
                $comment->save();
            } else {
                $attr = ['comment' => $contentComm, 'status_c' => 1];
                $exist_data->update_attributes($attr);
            }
        }
    }


    public static function getRatings($userID)
    {
        $userID = (int)$userID;

        $ratAttr = \Comrat::find('all', ['conditions' => ['user_id = ? AND status_r = ?', $userID, 1]]);
        $array = [];
        if (!empty($ratAttr)) {
            foreach ($ratAttr as $item) {
                $res = $item->to_array(['only' => ['rating', 'user_id', 'product_id']]);
                $array[] = $res;
            }
        } else {
            $ratAttr = null;
        }

        return $array;
    }


    public function insertRating($ratingValue, $userId, $productId)
    {
        $ratingValue = (int)$ratingValue;
        $userId = (int)$userId;
        $productId = (int)$productId;

        if ($userId === $_SESSION['id']) {
            $exist_data = \Comrat::find(['conditions' => ['user_id = ? AND product_id = ?', $userId, $productId]]);

            if (empty($exist_data)) {
                $comment = new Comrat();
                $comment->rating = $ratingValue;
                $comment->user_id = $userId;
                $comment->product_id = $productId;
                $comment->status_r = 1;
                $comment->save();
            } else {
                $attr = ['rating' => $ratingValue, 'status_r' => 1];
                $exist_data->update_attributes($attr);
            }
        }
    }

    public static function getAllCommRatingsByIdProduct($itemId) {
        $itemId = (int)$itemId;

        $model = \Comrat::find_by_sql("SELECT users.username, comrat.comment, comrat.rating
            FROM comrat
            LEFT JOIN users ON users.id = comrat.user_id
            WHERE
            comrat.product_id = {$itemId}
            AND(comrat.comment IS NOT NULL OR comrat.rating IS NOT NULL)");


        $array = [];
        if (!empty($model)) {
            foreach ($model as $item) {
                $res = $item->to_array(['only' => ['username', 'comment', 'rating']]);
                $array[] = $res;
            }
        } else {
            $array = null;
        }

        return $array;
    }

    /*
     * The calculation of the sum and average ratings by product ID
     *
     * @return array $model
     * */
    public static function avgValue($itemId)
    {
        $itemId = (int)$itemId;

        $model = \Comrat::find_by_sql("SELECT SUM(rating), AVG(rating)
                                    FROM comrat
                                    WHERE `status_r` = 1 AND `product_id` = {$itemId}");
        $array = [];
        if (!empty($model)) {
            foreach ($model as $item) {
                $res = $item->to_array(['only' => ['sum(rating)', 'avg(rating)']]);
                $array[] = $res;
            }
        } else {
            $array = null;
        }

        return $array;
    }
}