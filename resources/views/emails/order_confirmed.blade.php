<!DOCTYPE html>
<html>
    
</html>
    <body>


        <!-- START DEFAULT DATATABLE -->
        <div class="panel panel-default">
            <div class="panel-heading">                                
                <h3 class="panel-title">Dane zamówienia  {{ $data->id }}</h3>

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
                                {{ $data->id }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Wartość przedmiotów:
                            </td>
                            <td>
                                {{ $data->cart_amount }} zł
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Data potwierdzenia email:
                            </td>
                            <td>
                                {{ $data->confirm_date }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Data złożenia zamówienia:
                            </td>
                            <td>
                                {{ $data->created_at }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Zamówienie wysłano:
                            </td>
                            <td>
                                {{ $data->sent ? 'TAK' : 'NIE' }}
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Wysyłka - nazwa:
                            </td>
                            <td>
                                <a href="/admin/shipment/show/{{$$data->shipment_id}}">{{ $data->shipment_name }}</a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Wysyłka - cena:
                            </td>
                            <td>
                                {{ $data->shipment_price }} zł
                            </td>
                        </tr>

                        <tr>
                            <td>
                                Waga całości zamówienia
                            </td>
                            <td>
                                {{ $data->weight_total }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Całkowita liczba produktów:
                            </td>
                            <td>
                                {{ $data->getTotalQuantity() }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Imię:
                            </td>
                            <td>
                                {{ $data->first_name }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Nazwisko:
                            </td>
                            <td>
                                {{ $data->last_name }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Adres:
                            </td>
                            <td>
                                {{ $data->address }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Miasto:
                            </td>
                            <td>
                                {{ $data->city }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Kod pocztowy:
                            </td>
                            <td>
                                {{ $data->postcode }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Adres e-mail:
                            </td>
                            <td>
                                {{ $data->email }} 
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Telefon
                            </td>
                            <td>
                                {{ $data->telephone }} 
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
                            <td class="td-qty">Liczba</td>
                            <td>Cena</td>
                            <td>Wartość</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data->items as $data_item)
                        <?php $item = $data_item->item; ?>
                            <tr class="cart-item" data-item_id="{{$item->id}}">
                                <!--Image--> 
                                <td class="hidden-xs">
                                    <a href="detail.html">
                                        <img src="{{url('image/'.$item->image_path)}}" alt="{{$item->name}}" title="" width="47" height="47">
                                    </a>
                                </td>
                                <!--Name-->
                                <td><a href="detail.html">{{$item->name}}</a>
                                </td>
                                <!--Quantity-->
                                <td>{{$data_item->quantity}}</td>
                                <!--Unit price-->
                                <td class="price">{{$item->price}}zł</td>
                                <!--Total price-->
                                <td class="cart-item-total-price" data-item_id="{{$item->id}}">{{($item->price * $data_item->quantity)}}zł</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </body>
</html>