<?php 
interface CartManagementInterface {
    public function addToCart($user_id, $product_id, $name, $price, $quantity, $image);
    public function updateQuantity($user_id, $product_id, $quantity);
    public function deleteItem($user_id, $product_id);
    public function getCartItems($user_id);
}
?>
