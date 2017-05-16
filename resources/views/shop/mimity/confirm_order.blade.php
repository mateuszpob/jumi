@extends('shop.mimity.index')

@section('content')

<div class="col-lg-9 col-md-9 col-sm-12">
    <div class="col-lg-12 col-sm-12">
        <span class="title">TWOJE ZAMÓWIENIE</span>
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
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
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
                        <td class="cart-item-table-text">{{$item->quantity}}</td>
                        <!--Unit price-->
                        <td class="cart-item-table-text" class="price">{{ number_format($item->price, 2, '.', ' ') }} PLN</td>
                        <!--Total price-->
                        <td class="cart-item-table-text" data-item_id="{{$item->id}}">{{number_format(($item->price * $item->quantity), 2, '.', ' ') }} PLN</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
<!--    <div class="col-lg-12 col-sm-12">
        <span class="title">DOSTAWA I PŁATNOŚĆ</span>
    </div>-->
    <div class="col-lg-12 col-sm-12 hero-feature">
        <table class="table table-bordered tbl-cart">
            <tbody>
                <tr>
                    <td>Wartość produktów</td>
                    <td>{{ number_format(($total_amount-$shipment_amount), 2, '.', ' ') }} PLN</td>
                </tr>
                <!-- <tr>
                    <td>Podatek VAT</td>
                    <td>{{ number_format(($total_amount-$shipment_amount)*0.01*config('shop.vat'), 2, '.', ' ') }} PLN</td>
                </tr> -->
                <tr>
                    <td class="">Koszt dostawy</td>
                    <td>{{ number_format($shipment_amount, 2, '.', ' ') }} PLN</td>
                </tr>
                <tr>
                    <td class=""><strong>Razem</strong></td>
                    <td><strong>{{ number_format($total_amount, 2, '.', ' ') }} PLN</strong></td>
                </tr>
            </tbody>
        </table>
    </div>
 
    <div class="col-lg-5 col-sm-6">
        <span class="title">ADRES DOSTAWY</span>
   </div>
 <!--    <div class="col-lg-4 col-sm-4">
        <span class="title">ADRES NA FAKTURZE</span>
    </div>-->
    <div class="col-lg-7 col-sm-6">
        <span class="title">INFORMACJE</span>
    </div>
    <div class="col-lg-5 col-sm-6 hero-feature">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <span class="confirm-order-user-data">
                            {{ $data['first_name'] }} {{ $data['last_name'] }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="confirm-order-user-data">
                            {{ $data['email'] }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="confirm-order-user-data">
                            {{ $data['telephone'] }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="confirm-order-user-data">
                           {{ $data['address'] }}
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <span class="confirm-order-user-data">
                            {{ $data['city'] }} {{ $data['postcode'] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7 col-sm-6 hero-feature">
        <div class="row">
            <div class="col-md-12">
                <span class="confirm-order-user-data">
                   {{ $data['comment'] }}
                </span>
            </div>
        </div>
    </div>

<div class="col-lg-12 col-sm-12 hero-feature">
    <div>
            <form id="order_checkout_last_form" style="float:right;" action="/create-order" method="POST">
                    <p><sub>Zlecenie realizacji płatności:
                           
                           Zlecenie wykonuje PayU SA;Dane odbiorcy, tytuł oraz kwota płatności dostarczane są
                           PayU SA przez odbiorcę;Zlecenie jest przekazywane do realizacji po otrzymaniu przez
                           PayU SA Państwa wpłaty. Płatność udostępniana jest odbiorcy w ciągu 1 godziny, nie
                           później niż do końca następnego dnia roboczego;PayU SA nie pobiera opłaty od realizacji
                           usługi.</sub>
                    </p>
                     
                     <p><sub><input id="payu_terms_check" name="payu_terms" type="checkbox"> Akceptuję <a href="http://static.payu.com/sites/terms/files/payu_terms_of_service_single_transaction_pl_pl.pdf">"Regulamin pojedynczej transakcji płatniczej PayU"</a>.
                           </sub></p> 
                     
                     <p><sub>Administratorem Twoich danych osobowych w rozumieniu ustawy z dnia 29 sierpnia 1997
                           r. o ochronie danych osobowych (tj. Dz.U. z 2002 roku, nr 101, poz. 926 z późn. zm.)
                           jest PayU SA z siedzibą w Poznaniu (60-166), przy ul. Grunwaldzkiej 182. Twoje dane
                           osobowe będą przetwarzane zgodnie z obowiązującymi przepisami prawa, w celu świadczenia
                           usług i archiwizacji. Twoje dane nie będą udostępniane innym podmiotom, z wyjątkiem
                           podmiotów upoważnionych na podstawie przepisów prawa. Przysługuje Tobie prawo dostępu
                           do treści swoich danych oraz ich poprawiania. Podanie danych jest dobrowolne, ale
                           niezbędne do realizacji ww. celu.
                    </sub></p>
                     
                     
            
            <input type="hidden" name="first_name" value="{{ $data['first_name'] }}" />
            <input type="hidden" name="last_name" value="{{ $data['last_name'] }}" />
            <input type="hidden" name="email" value="{{ $data['email'] }}" />
            <input type="hidden" name="telephone" value="{{ $data['telephone'] }}" />
            <input type="hidden" name="address" value="{{ $data['address'] }}" />
            <input type="hidden" name="postcode" value="{{ $data['postcode'] }}" />
            <input type="hidden" name="city" value="{{ $data['city'] }}" />
            <input type="hidden" name="comment" value="{{ $data['comment'] }}" />
            <input type="hidden" name="payment_type_id" value="{{ $data['payment_type_id'] }}" />

            <div class="row">
            <br><br>
            <div class="col-lg-12 col-sm-12 hero-feature">
                <p id="payu_terms_error" style="display:none;color:red;float:left;"><sub style="font-size: 14px;">Należy zaakceptować "Regulamin pojedynczej transakcji płatniczej PayU"</sub></p>
                <span class="sum-label">Do zapłaty:&nbsp;<span>{{ number_format($total_amount, 2, '.', ' ') }} PLN</span></span>
            </div>
            </div>

            @if($data['payment_type_id']==1)
                <input style="float:right;" class="btn btn-success" type="submit" value="Potwierdzam i płacę"/>
            @elseif($data['payment_type_id']==2)
                <div id="payu_submit" class="payu_button" >
                    <img src="/img/payu_pay_button.png">
                </div>
            @endif
        </form>            
    </div>
</div>

</div>
<script>

</script>



@endsection