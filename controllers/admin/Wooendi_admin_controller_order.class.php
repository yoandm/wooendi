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
                $type = 'company';
                $registration = 'SARL';
                $company = $order->get_billing_company();
            } else {
                $type = 'individual';
                $registration = '';
                $firstname = $order->get_billing_first_name();
                $lastname = $order->get_billing_last_name();
            }

            $client = Customer::add([
                        'code' => '',
                        'type' => $type,
                        'registration' => $registration,
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

            Customer::addToProject($client['id'], $wooendi_config['project']);

            $facture = Invoice::add($client['id'], $wooendi_config['project'], 'Commande ' . $order->get_order_number());
            update_post_meta($order_id, '_wooendi_invoice_id', $facture['id']);


            Invoice::setObject($facture['id'], 'Commande ' . $order->get_order_number());
            Invoice::setDisplayUnit($facture['id'], 1);

            $lgroups = Invoice::getTaskLineGroups($facture['id']);

             foreach ($order->get_items() as $item) {

                Invoice::addLine($facture['id'], [

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

            
            Invoice::save($facture['id'], [
                                        'name' => 'Commande ' . $order->get_order_number(),
                                        'financial_year' => date('Y')   
                                ]

            );



            phpEndi::destroySession();

        }
    }

}
?>