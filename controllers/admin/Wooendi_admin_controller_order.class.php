<?php

use yoandm\phpEndi\phpEndi;
use yoandm\phpEndi\Customer;
use yoandm\phpEndi\Invoice;

class Wooendi_admin_controller_order {

    public function __construct(){
        add_action('woocommerce_order_status_processing', array($this, 'generate_invoice'), 10, 1);   
    }

    public function generate_invoice($order_id){

        $invoice_id = get_post_meta($order_id, '_wooendi_invoice_id', true);

        if(! $invoice_id){

            $order = wc_get_order($order_id);

            $wooendi_config = json_decode(get_option('wooendi_config'), 1);

            phpEndi::connect($wooendi_config['login'], $wooendi_config['password'], $wooendi_config['url'], $wooendi_config['id']); 

            $address = $order->get_billing_address_1();
            if(! empty($order->get_billing_address_2()))
                $address .= $order->get_billing_address_2();

            if(! empty($order->get_billing_company())){
                $company = $order->get_billing_company();
                $firstname = $order->get_billing_first_name();
                $lastname = $order->get_billing_last_name();
            } else {
                $company = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
                $firstname = '';
                $lastname = '';
            }

            $client = Customer::add([
                        'code' => '',
                        'company_name' => $company,
                        'civilite' => '',
                        'lastname' => $lastname,
                        'firstname' => $firstname,
                        'function' => '',
                        'address' => $address,
                        'zip_code' => $order->get_billing_postcode(),
                        'city' => $order-> get_billing_city(),
                        'country' => '',
                        'tva_intracomm' => '',
                        'registration' => '',
                        'email' => $order->get_billing_email(),
                        'mobile' => '',
                        'phone' => $order->get_billing_phone(),
                        'fax' => '',
                        'comments' => ''
            ]);
            
            /*
            
            get_billing_country()
            get_shipping_tax()
            get_shipping_total()
            */

            Customer::addToProject($client, $wooendi_config['project']);

            $facture = Invoice::add($client, $wooendi_config['project'], 'Commande ' . $order->get_order_number());
            update_post_meta($order_id, '_wooendi_invoice_id', $facture);


            Invoice::setObject($facture, 'Commande ' . $order->get_order_number());
            Invoice::setDisplayUnit($facture, 1);

            $lgroups = Invoice::getTaskLineGroups($facture);

             foreach ($order->get_items() as $item) {

                Invoice::addLine($facture, [

                                            'order' => 1,
                                            'description' => $item->get_name(),
                                            'cost' => $item->get_total(),
                                            "quantity" => $item->get_quantity(),
                                            'unity' => 'Unité(s)',
                                            'tva' => 20,
                                            'group_id' => $lgroups[0]['id'],
                                            'product_id' => 12,
                                            'mode' => 'ht'
                                    ]

                );

             }

            
            Invoice::save($facture, [
                                        'name' => 'Commande ' . $order->get_order_number(),
                                        'financial_year' => date('Y')   
                                ]

            );



            phpEndi::destroySession();

        }
    }

}
?>
