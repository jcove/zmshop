<?php
/**
 * Author: XiaoFei Zhai
 * Date: 2018/8/13
 * Time: 17:11
 */

namespace App\Payments;


use App\Models\Order;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;

class Paypal implements PaymentInterface
{

    /**
     * @param Order $order
     */
    function getPayUrl(Order $order)
    {
        $paypal                         =   new ApiContext(
                new OAuthTokenCredential( config('payment.paypal.client_id'),config('payment.paypal.secret'))
        );

        $shipping = $order->shipping_fee; //运费

        $total = $order->total_amount;
        $itemList                       =    new ItemList();
        $payer = new Payer();
        $payer->setPaymentMethod('credit_card');
        $items                          =   [];
        foreach ($order->order_goods as $goods){
            $item                       =   new Item();
            $item->setName($goods->goods_name)
                ->setCurrency('USD')
                ->setQuantity($goods->num)
                ->setPrice($goods->final_price);
            $items[]                    =   $item;

        }

        $itemList->setItems($items);

        $details                        =   new Details();
        $details->setShipping($shipping)
            ->setSubtotal($order->goods_amount);

        $amount                         =   new Amount();
        $amount->setCurrency('USD')
            ->setTotal($total)
            ->setDetails($details);

        $transaction                    =   new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("支付描述内容")
            ->setInvoiceNumber(uniqid());

        $redirectUrls                   =   new RedirectUrls();
        $redirectUrls->setReturnUrl(route('pay.success',['id'=>$order->id],true))
            ->setCancelUrl(route('pay.cancel',['id'=>$order->id],true));

        $payment                        =   new Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)
            ->setTransactions([$transaction]);

        try {
            $payment->create($paypal);
        } catch (PayPalConnectionException $e) {
            echo $e->getData();
            die();
        }

        $approvalUrl = $payment->getApprovalLink();
        return $approvalUrl;
    }

    function getPayCode()
    {
        // TODO: Implement getPayCode() method.
    }
}