<?php
/*
Plugin Name: Wooendi
Plugin URI:
Description: Communication avec le logiciel de facturation enDI
Version: 1.0
Author: Yoan De Macedo
Author URI: http://yoandemacedo.com
License: GPL3
*/

/*  
	Copyright (C) 2021	Yoan De Macedo  <mail@yoandm.com>                       
	web : http://yoandm.com

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

	if ( ! defined( 'ABSPATH' ) ) {
	        exit;
	}

	require(plugin_dir_path( __FILE__ ) . 'lib/phpEndi/autoload.php');

	require(plugin_dir_path( __FILE__ ) . 'view/front/Wooendi_front_view_order.class.php');
		require(plugin_dir_path( __FILE__ ) . 'controllers/admin/Wooendi_admin_controller_order.class.php');

	if(is_admin()){
		require(plugin_dir_path( __FILE__ ) . 'view/admin/Wooendi_admin_view_order.class.php');
		require(plugin_dir_path( __FILE__ ) . 'view/admin/Wooendi_admin_view_wooendiconfig.class.php');
		require(plugin_dir_path( __FILE__ ) . 'controllers/admin/Wooendi_admin_controller_wooendiconfig.class.php');
	}

	class Wooendi {
		const PLUGIN_VERSION = '1.0';
		public $instanceFrontViewOrder;
		public $instanceAdminViewOrder;
		public $instanceAdminControllerOrder;
		public $instanceAdminControllerWooendiconfig;


		public function __construct(){

			register_activation_hook( __FILE__, array($this, 'install'));
			add_action('admin_menu', array($this, 'plugin_setup_admin_menu'));

			$this->instanceFrontViewOrder = new Wooendi_front_view_order();
			$this->instanceAdminControllerOrder = new Wooendi_admin_controller_order();

			if(is_admin()){
				$this->instanceAdminViewOrder = new Wooendi_admin_view_order();
				$this->instanceAdminControllerWooendiconfig = new Wooendi_admin_controller_wooendiconfig();
			}
			
		}

		public function install(){

			if(get_option('wooendi_version'))
				return 0;

			add_option('wooendi_version', self::PLUGIN_VERSION);
			add_option('wooendi_first_installed_version', self::PLUGIN_VERSION);
		}


		function plugin_setup_admin_menu(){
		        add_menu_page( 'Wooendi', 'Wooendi', 'manage_options', 'wooendi_config', array(new Wooendi_admin_view_wooendiconfig(),'show'));
		}
	 

	}

	$wooendi = new Wooendi();

?>