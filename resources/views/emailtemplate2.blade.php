<?php
echo "<pre>";
print_r($order);
echo "</pre>";
foreach($order->products as $pro){
    echo "<pre>";
    print_r($pro->order_item_id);
    echo "</pre>";
}
?>