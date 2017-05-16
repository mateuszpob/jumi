@extends('shop.mimity.left_side_bestsellers')


@section('items')

<div class="col-lg-9 col-md-9 col-sm-12">
    <div class="col-lg-12 col-sm-12">
        <span class="title">KOSZYK</span>
    </div>
    <div class="col-lg-12 col-sm-12 hero-feature">
        <table class="table table-bordered tbl-cart">
            <thead>
                <tr>
                    <td class="hidden-xs">Zdjęcie</td>
                    <td>Nazwa</td>
                    <td class="td-qty">Liczba</td>
                    <td>Cena</td>
                    <td>Wartość</td>
                    <td>Usuń</td>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                <?php
                    $item_price = $item->price;
                    if($item->price_discounted)
                        $item_price = $item->price_discounted;

                ?>
                    <tr class="cart-item" data-item_id="{{$item->id}}">
                        <!--Image--> 
                        <td class="hidden-xs">
                            <a href="{{ url('produkt/'.$item->item_id) }}">
                                <img src="{{url('image/'.$item->image_path)}}" alt="{{$item->name}}" title="" width="47" height="47">
                            </a>
                        </td>
                        <!--Name-->
                        <td><a href="{{ url('produkt/'.$item->item_id) }}">{{$item->name}}</a>
                        </td>
                        <!--Quantity-->
                        <td>
                            <div class="input-group bootstrap-touchspin cart-quantity-btns">
                                <span class="input-group-btn">
                                    <button class="btn btn-default bootstrap-touchspin-down cart-quantity-down" data-item_id="{{$item->id}}" type="button">-</button>
                                </span>
                                <span class="input-group-addon bootstrap-touchspin-prefix" style="display: none;">
                                </span>
                                <input type="text" name="" value="{{$item->quantity}}" data-item_id="{{$item->id}}" class="cart-item-quantiy input-qty form-control text-center" style="display: block;"/>
                                <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span>
                                <span class="input-group-btn">
                                    <button class="btn btn-default bootstrap-touchspin-up cart-quantity-up" data-item_id="{{$item->id}}" type="button">+</button>
                                </span>
                            </div>
                        </td>
                        <!--Unit price-->
                        <td class="price cart-item-table-text">{{ number_format($item_price, 2, '.', ' ') }} PLN</td>
                        <!--Total price-->
                        <td class="cart-item-total-price cart-item-table-text" data-item_id="{{$item->id}}">{{ number_format($item_price * $item->quantity, 2, '.', ' ') }} PLN</td>
                        <!--Remove-->
                        <td class="text-center">
                            <a href="#" class="remove_cart" data-item_id="{{$item->id}}" rel="1">
                                <i style="margin-top: 15px;" class="glyphicon glyphicon-trash"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5" align="right">Razem:</td>
                    <td class="total" colspan="1" style="text-align: center"><b id="total-cart-amount" data-total_amount="{{$total_amount}}">{{ number_format($total_amount, 2, '.', ' ') }} PLN</b>
                    </td>
                </tr>
            </tbody>
        </table>


    </div>
    <div class="col-lg-12 col-sm-12">
        <span class="title">KOSZTY DOSTAWY</span>
    </div>
    <?php $shipment_amount = $cart->calculateShipmentPrice(); ?>
    <div class="col-lg-12 col-sm-12">
        <p style="float:left">Wszystkie produkty wysyłąne są przez producenta.</p>
        <p id="shipment-amount-cart" style="float:right;font-size:21px">{{ number_format(  $shipment_amount, 2, '.', ' ') }} PLN</p>
    </div>
    <div style="height:100px;float: left;"></div>
    <!--<div class="row">-->
        <div class="col-md-4">
            <div class="btn-group btns-cart" style="padding-top:15px;">
                <a class="btn btn-primary" href="{{redirect()->getUrlGenerator()->previous()}}">Kontynuuj zakupy</a>
            </div>
        </div>
        <div class="col-md-8 do-zaplaty">
            <form action="/checkout" method="POST">
            <input id="drop-cart" type="submit" class="btn btn-success" value="Złóż zamówienie" />
            Do zapłaty:&nbsp;<span id="total-amount">{{ number_format($shipment_amount + $total_amount, 2, '.', ' ') }} PLN</span> 
            </form>
        </div>
    <!--</div>-->
</div>

<script>
    
    
    
$(document).ready(function(){
    // odswierz strone po dodaniu do koszyka jesli jestes w koszyku
    $('.add-to-cart').on('click', function () {
        setTimeout(function(){
            location.reload();
        }, 1000);
    });

});
//    // ============= CHECKOUT ================== //
//    $('#checkout-button').on('click', function(){
//        var shipment_id = parseInt($('input[name="shipment_id"]:checked').data('shipment_id'));
//        console.log('checkout click');
//        $.ajax({
//            type: 'POST',
//            url: '/checkout',
//            data: {'shipment_id':shipment_id},
//            success: function(res){
//                if(parseInt(res.success) == 1){
//                    
//                }
//            }
//        });
//    });
//});    
</script>

@endsection