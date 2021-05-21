<?php

    if(isset($_POST['wooendi_action']) && $_POST['wooendi_action'] == 'wooendi_admin_config_save')
        Wooendi_admin_controller_wooendiconfig::save_config();

    class Wooendi_admin_controller_wooendiconfig {

        public static function save_config(){
            $wooendi_url = $_POST['wooendi_config_url'];
            $wooendi_login = $_POST['wooendi_config_login'];
            $wooendi_password = $_POST['wooendi_config_password'];
            $wooendi_id = (int) $_POST['wooendi_config_id'];
            $wooendi_project = (int) $_POST['wooendi_config_project'];

            $wooendi_config = array(
                'url' => $wooendi_url,
                'login' => $wooendi_login,
                'password' => $wooendi_password,
                'id' => $wooendi_id,
                'project' => $wooendi_project
            );

            update_option('wooendi_config', json_encode($wooendi_config));

        }
    }
?>