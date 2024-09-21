<?php
interface ProductManagementInterface {
    public function addProduct($name, $quantity, $price, $detail, $image);
    public function updateProduct($id, $name, $quantity, $price, $detail, $image);
    public function deleteProduct($id);
    public function getProductById($id);
    public function getAllProducts();
}
?>
