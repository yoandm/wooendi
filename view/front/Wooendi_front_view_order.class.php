<?php

class Wooendi_front_view_order {


    public function __construct(){
        add_action( 'woocommerce_order_details_after_order_table', array($this, 'view_invoice'), 10, 1);
      
    }
    
    public function view_invoice($order){
       

    }


}
?>
