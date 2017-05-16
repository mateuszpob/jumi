<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    // public function __construct()   
    // {  
    //  $this->beforeFilter('csrf', array('on'=>'post')); 
    //  $this->beforeFilter('auth', array('only'=>array('getDashboard')));   
    // }  
    // public function listen(){
    //     var_dump(\Request::all());
    // }
    

    public function payuListener(Request $request){

        $payu_order = $request->all()['order'];
        try{
            // tworze loga płatności
            $log = new \App\PaymentLog();
            $log->transaction_id = $payu_order['orderId']; // PayU OrderId
            if(isset($payu_order['description']))
                $log->order_id = (int)ltrim($payu_order['description'], 'mojachata order ');
            $log->created_at = \Carbon\Carbon::parse($payu_order['orderCreateDate']);
            $log->updated_at = \Carbon\Carbon::parse($payu_order['orderCreateDate']);
            if(isset($payu_order['customerIp']))
                $log->customer_ip = $payu_order['customerIp'];
            if(isset($payu_order['merchantPosId']))
                $log->merchant_id = $payu_order['merchantPosId'];
            if(isset($payu_order['currencyCode']))
                $log->currency_code = $payu_order['currencyCode'];
            if(isset($payu_order['totalAmount']))
                $log->total_amount = ((double)$payu_order['totalAmount'])/100; // bo w PayU jest w groszach 
            if(isset($payu_order['buyer']['email']))
                $log->buyer_email = $payu_order['buyer']['email'];
            if(isset($payu_order['buyer']['phone']))
                $log->buyer_phone = $payu_order['buyer']['phone'];
            if(isset($payu_order['buyer']['firstName']))
                $log->buyer_first_name = $payu_order['buyer']['firstName'];
            if(isset($payu_order['buyer']['lastName']))
                $log->buyer_last_name = $payu_order['buyer']['lastName'];
            if(isset($payu_order['status']))
                $log->status = $payu_order['status'];
            if(isset($payu_order['properties'][0]['value']))
                $log->payment_id = $payu_order['properties'][0]['value']; // PayU
            $log->data = json_encode($payu_order);
            $log->save();

            // jesli status completed odznacz ordera jako oplacony
            if($payu_order['status'] === 'COMPLETED'){
                $order = $log->order;
                \Log::info('AMOUNT: '.$log->total_amount);
                if($order){
                    $payment_in_full = $order->markAsPaid($log->total_amount);
                }else{
                    \Log::error('[PAYMENT - PayU] nie znaleizono ordera pasujacego do odp Payu', $payu_order);
                }
            }
        }catch(\Exception $e){
            \Log::error('[PAYMENT - PayU] nie znaleizono ordera pasujacego do odp Payu', $payu_order);
            \Log::error($e);
            return 0;
        }
        return 0;
    }
}
