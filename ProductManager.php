<?php
class ProductManager {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function addProduct($name, $quantity, $price, $detail, $image) {
        $query = "INSERT INTO `products` (`name`, `product_quantity`, `price`, `product_detail`, `image`)
                  VALUES ('$name', '$quantity', '$price', '$detail', '$image')";
        return mysqli_query($this->db, $query);
    }

    public function updateProduct($id, $name, $quantity, $price, $detail, $image) {
        $query = "UPDATE `products` SET 
                  `name` = '$name',
                  `product_quantity` = '$quantity',
                  `price` = '$price',
                  `product_detail` = '$detail',
                  `image` = '$image'
                  WHERE `id` = '$id'";
        return mysqli_query($this->db, $query);
    }

    public function deleteProduct($id) {
        $query = "DELETE FROM `products` WHERE `id` = '$id'";
        return mysqli_query($this->db, $query);
    }

    public function getProductById($id) {
        $query = "SELECT * FROM `products` WHERE `id` = '$id'";
        return mysqli_query($this->db, $query);
    }

    public function getAllProducts() {
        $query = "SELECT * FROM `products`";
        return mysqli_query($this->db, $query);
    }
}
?>
