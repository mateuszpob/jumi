@extends('admin.index')


@section('content')

<!-- START DEFAULT DATATABLE -->
<div class="panel panel-default">
    <div class="panel-heading">                                
        <h3 class="panel-title">Dane zamówienia  {{ $order->id }}</h3>

    </div>


    <div class="panel-body">
        <div class="table-responsive">
            <table class="table datatable2 showTable">
                <tbody>
                    <tr>
                        <td>
                            Numer zamówienia:
                        </td>
                        <td>
                            {{ $order->id }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Wartość przedmiotów:
                        </td>
                        <td>
                            {{ $order->cart_amount }} zł
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Data potwierdzenia email:
                        </td>
                        <td>
                            {{ $order->confirm_date }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Data złożenia zamówienia:
                        </td>
                        <td>
                            {{ $order->created_at }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Zamówienie wysłano:
                        </td>
                        <td>
                            {{ $order->sent ? 'TAK' : 'NIE' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Wysyłka - cena:
                        </td>
                        <td>
                            {{ $order->shipment_amount }} zł
                        </td>
                    </tr>

                    <tr>
                        <td>
                            Waga całości zamówienia
                        </td>
                        <td>
                            {{ $order->weight_total }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Całkowita liczba produktów:
                        </td>
                        <td>
                            {{ $order->getTotalQuantity() }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Imię:
                        </td>
                        <td>
                            {{ $order->first_name }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Nazwisko:
                        </td>
                        <td>
                            {{ $order->last_name }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Adres:
                        </td>
                        <td>
                            {{ $order->address }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Miasto:
                        </td>
                        <td>
                            {{ $order->city }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Kod pocztowy:
                        </td>
                        <td>
                            {{ $order->postcode }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Adres e-mail:
                        </td>
                        <td>
                            {{ $order->email }} 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Telefon
                        </td>
                        <td>
                            {{ $order->telephone }} 
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>


</div>

<div class="panel panel-default">
    <div class="panel-heading">                                
        <h3 class="panel-title">Przedmity</h3>

    </div>


    <div class="panel-body">
        <table class="table table-bordered tbl-cart">
            <thead>
                <tr>
                    <td class="hidden-xs">Zdjęcie</td>
                    <td>Nazwa</td>
                    <td>EAN</td>
                    <td>Cena</td>
                    <td>Wartość</td>
                    <td class="td-qty">Liczba</td>
                </tr>
            </thead>
            <tbody>
                @foreach($order_items as $order_item)
                <?php
                $item = $order_item->item;
                $variant = $order_item->variant;
                ?>
                <tr class="cart-item" data-item_id="{{$item->id}}">
                    <!--Image--> 
                    <td class="hidden-xs">
                        <a href="detail.html">
                            <img src="{{url('image/'.$item->image_path)}}" alt="{{$item->name}}" title="" width="47" height="47">
                        </a>
                    </td>
                    <!--Name-->
                    <td><a href="{{$item->getUrl()}}">{{$item->name}}</a></td>
                    @if($variant)
                    <!--EAN-->
                    <td>{{$variant->ean}}</td>
                    <!--Unit price-->
                    <td class="price">{{$variant->price}}zł</td>
                    <!--Total price-->
                    <td class="cart-item-total-price" data-item_id="{{$item->id}}">{{($variant->price * $order_item->quantity)}}zł</td>
                    @else
                    <!--EAN-->
                    <td>{{$item->ean}}</td>
                    <!--Unit price-->
                    <td class="price">{{$item->price}}zł</td>
                    <!--Total price-->
                    <td class="cart-item-total-price" data-item_id="{{$item->id}}">{{($item->price * $order_item->quantity)}}zł</td>
                    @endif
                    <!--Quantity-->
                    <td>{{$order_item->quantity}}</td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection