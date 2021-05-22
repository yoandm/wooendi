<?php

class Wooendi_admin_view_wooendiconfig {


	public function __construct(){
		
	}

	public function show(){

        $wooendi_first_installed_version = get_option('wooendi_first_installed_version');

        $wooendi_config = json_decode(get_option('wooendi_config'), 1);

        if($wooendi_config){
            $wooendi_config_url = $wooendi_config['url'];
            $wooendi_config_login = $wooendi_config['login'];
            $wooendi_config_password = $wooendi_config['password'];
            $wooendi_config_id = $wooendi_config['id'];            
            $wooendi_config_project = $wooendi_config['project'];            
        } else {
            $wooendi_config_url = '';
            $wooendi_config_login = '';
            $wooendi_config_password = '';
            $wooendi_config_id = '';    
            $wooendi_config_project = '';                 
        }


        
?>

    <div class="wrap">
    <h1 class="wp-heading-inline">Configuration</h1>

        <div id="poststuff">

                <div id="post-body" class="metabox-holder columns-2">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">

                            <form action="?page=wooendi_config" method="post">
                            <input type="hidden" name="wooendi_action" value="wooendi_admin_config_save" />
                            <table class="wp-list-table widefat fixed striped posts">

                                <tbody>
                                        <tr>
                                            <td>Endi URL</td>
                                            <td><input type="text" name="wooendi_config_url" value="<?php echo $wooendi_config_url; ?>" /></td>
                                        </tr>        
                                         <tr>
                                            <td>E-Mail de connexion</td>
                                            <td><input type="text" name="wooendi_config_login" value="<?php echo $wooendi_config_login; ?>" /></td>
                                        </tr> 
                                         <tr>
                                            <td>Mot de passe</td>
                                            <td><input type="password" name="wooendi_config_password" value="<?php echo $wooendi_config_password; ?>"  /></td>
                                        </tr> 
                                         <tr>
                                            <td>ID structure</td>
                                            <td><input type="text" name="wooendi_config_id" value="<?php echo $wooendi_config_id; ?>"  /></td>
                                        </tr>                                                                                   
                                        <tr>
                                            <td>ID projet</td>   
                                            <td><input type="text" name="wooendi_config_project" value="<?php echo $wooendi_config_project; ?>"  /></td>
                                        </tr>                                                                   
                                </tbody>
                            </table>

                            <br />

                            <input type="submit" class="button button-primary button-large" value="Enregistrer" /> 
                            </div>
                            </form>

                           
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
<?php
    }
			
}
?>
