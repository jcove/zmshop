@extends('pc.layout.no_header')
@section('content')
    <div class="container message">
        {{--<p><i class="iconfont icon-right1"></i>{{$message}}</p>--}}
        {{--<go-pay-message id="{{$data->id}}"></go-pay-message>--}}
        <p style="padding: 25px">
          @lang('html.choose_payment')
        </p>
        <div id="paypal-button-container" style="text-align: center">

        </div>
    </div>


@endsection
@section('script')
    <script>
        $(function () {
            {{--$.get('{{route('order.pay',['id'=>$data->id])}}',--}}
                {{--{--}}

                {{--},--}}
                {{--function (response) {--}}
                    {{--if(response.pay_url){--}}
                        {{--location.href           =   response.pay_url;--}}
                    {{--}--}}
                {{--}--}}
            {{--)--}}
        })
    </script>
    <script src="https://www.paypalobjects.com/api/checkout.js"></script>
    <script>
        // Render the PayPal button
        paypal.Button.render({
// Set your environment
            env: '{{config('app.env')=='production' ? 'production' : 'sandbox'}}', // sandbox | production

// Specify the style of the button
            style: {
                layout: 'vertical',  // horizontal | vertical
                size:   'medium',    // medium | large | responsive
                shape:  'rect',      // pill | rect
                color:  'gold'       // gold | blue | silver | white | black
            },

// Specify allowed and disallowed funding sources
//
// Options:
// - paypal.FUNDING.CARD
// - paypal.FUNDING.CREDIT
// - paypal.FUNDING.ELV
            funding: {
                allowed: [
                    paypal.FUNDING.CREDIT
                ],
                disallowed: []
            },

// PayPal Client IDs - replace with your own
// Create a PayPal app: https://developer.paypal.com/developer/applications/create
            client: {
                sandbox: '{{config('payment.paypal.client_id')}}',
                production: '<insert production client id>'
            },

            payment: function (data, actions) {
                return actions.payment.create({
                    payment: {
                        transactions: [
                            {
                                amount: {
                                    total: '{{$data->total_amount}}',
                                    currency: "USD",
                                    details: {
                                        subtotal: '{{$data->goods_amount}}',
                                        shipping: '{{$data->shipping_fee}}'
                                    }
                                },
                                description: "The payment transaction description.",

                                invoice_number: "{{$data->order_sn}}",
                                item_list: {
                                    items: [
                                        @foreach($data->order_goods as $item)
                                        {
                                            name: "{{$item->goods_name}}",
                                            quantity: '{{$item->num}}',
                                            price: '{{$item->final_price}}',
                                            currency: "USD"
                                        },
                                        @endforeach
                                    ]
                                },
                                // payment_options: {
                                //     allowed_payment_method: 'credit_card'
                                // }
                            }
                        ],
                        redirect_urls: {
                            return_url: "{{route('pay.success',['id'=>$data->id],true)}}",
                            cancel_url: "{{route('pay.cancel',['id'=>$data->id],true)}}"
                        }
                    }
                });
            },

            onAuthorize: function (data, actions) {
                return actions.payment.execute()
                    .then(function (result) {
                        window.alert('Payment Complete!');
                        location.href =  "{{route('pay.success',['id'=>$data->id],true)}}"+ '?paymentId='+result.id+'&PayerID='+result.payer.payer_info.payer_id;
                    });
            }
        }, '#paypal-button-container');
    </script>
@endsection