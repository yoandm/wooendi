<?php

class Wooendi_admin_view_order {


	public function __construct(){
		add_action('woocommerce_order_item_add_action_buttons', array($this, 'view_invoice'));

	}

	public function view_invoice(){

		global $wpdb;

        if(! strstr($_SERVER['SCRIPT_NAME'], 'post.php') || ! (int) $_REQUEST['post'])
            return 0;
        
		$order = wc_get_order((int) $_REQUEST['post']);

    }
			
}
?>