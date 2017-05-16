@extends('shop.mimity.index')

@section('content')

@if($order->payment_received_in_full)

    <br><br><br><br><br><br>
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            <h1>Twoje zamówienie zostało przyjęte do realizacji</h1>
            <br><br><br>
            <h4>Numer Twojego zamówienia: {{ $order->id }}</h2>
        </div>
    </div>
    <br><br><br><br>
    <div class="row">
        <div class="col-md-6 col-md-offset-3" style="text-align: center">
            <p>
                Dziękujemy za skorzystanie z naszych usług. W razie jakich kolwiek pytań prosimy o kaontakt telefoniczny: <strong>501 246 654</strong>, lub mailowy <a href="mailto:sklep@mojachata.eu">sklep@mojachata.eu</a>.
            </p>
        </div>
    </div>
    <br><br><br><br><br><br><br><br><br>
    
@else

    <br><br><br><br><br><br>
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            <h1>Płatność nie powiodła się.</h1>
        </div>
    </div>
    <br><br><br><br><br><br>
    
@endif

@stop