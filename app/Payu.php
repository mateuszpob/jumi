<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payu extends Model
{
	private static $access_token;
	private static $client_id = 223499;
	/*
	 * Tworzy zamowienie do Payu - przekierowuje na ich stronę
	 */
    public static function createOrder(\App\Order $order){
            $url = config('shop.payu_url');

	    $ch = curl_init();

	    // Dodaje produktu do zamowienia.
            $products = [];
            foreach($order->getOrderItems() as $item){
                    $products[] = [
                        "name" => $item->item->name,
                        "unitPrice" => $item->price*100,
                        "quantity" => $item->quantity
                    ];
            }
            // Dodaje wysylke do zamowienia
            $products[] = [
                "name" => 'Transport',
                "unitPrice" => $order->shipment_amount*100,
                "quantity" => "1"
            ];

            $postfields = [
                'notifyUrl' => url('payu-listener'),
                'customerIp' => '127.0.0.1',
                'merchantPosId' => config('shop.payu_merchant_pos_id'),
                'description' => 'mojachata order '.$order->id,
                'currencyCode' => 'PLN',
                'totalAmount' => (string)$order->total_amount*100,
                'continueUrl' => url('potwierdzenie-platnosci/'.$order->id),
                'buyer' => [
                    "email" => $order->email,
                    "phone" => $order->telephone,
                    "firstName" => $order->first_name,
                    "lastName" => $order->last_name,
                    "language" => "pl"
                ],
                'products' => $products
            ];

            $tkn = base64_encode(config('shop.payu_merchant_pos_id').':'.config('shop.payu_key_2'));


	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);

	    curl_setopt($ch, CURLOPT_POST, TRUE);

	    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postfields));

	    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
	      "Content-Type: application/json",
	      "Authorization: Basic ".$tkn
	    ));// "Authorization: Bearer 3e5cac39-7e38-4139-8fd6-30adc06a61bd"

	    $response = curl_exec($ch);
	    curl_close($ch);//dd($response);
	    return json_decode($response)->redirectUri;



    }
/*
private static function getAccessToken(){


	$ch = curl_init();

	$url_0 = 'https://secure.payu.com/pl/standard/user/oauth/authorize';

	curl_setopt($ch, CURLOPT_URL, $url_0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);

	$a = 'grant_type=trusted_merchant&client_id=300127&client_secret=df292495cfa141324ce3123865fe51f1&email=qwert45%40op.pl&ext_customer_id=extCustId6667';

	$c = 'grant_type=client_credentials&client_id=300127&client_secret=df292495cfa141324ce3123865fe51f1';	

	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $c);

    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
      "Content-Type: application/x-www-form-urlencoded"
    ));

	$response = json_decode(curl_exec($ch));
	dd($response);
	//self::$access_token = $response->access_token;
	curl_close($ch);
	return $response->access_token;
}
*/





}
