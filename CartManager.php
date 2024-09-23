<?php
include 'CartManagementInterface.php';

class CartManager implements CartManagementInterface {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addToCart($user_id, $product_id, $name, $price, $quantity, $image) {
        $query = "INSERT INTO `cart` (`user_id`, `pid`, `name`, `price`, `quantity`, `image`)
                  VALUES ('$user_id', '$product_id', '$name', '$price', '$quantity', '$image')";
        return mysqli_query($this->db, $query);
    }

    public function updateQuantity($user_id, $product_id, $quantity) {
        $query = "UPDATE `cart` SET `quantity` = '$quantity' WHERE `user_id` = '$user_id' AND `pid` = '$product_id'";
        return mysqli_query($this->db, $query);
    }

    public function deleteItem($user_id, $product_id) {
        $query = "DELETE FROM `cart` WHERE `user_id` = '$user_id' AND `pid` = '$product_id'";
        return mysqli_query($this->db, $query);
    }

    public function getCartItems($user_id) {
        $query = "SELECT * FROM `cart` WHERE `user_id` = '$user_id'";
        return mysqli_query($this->db, $query);
    }
}
?>
