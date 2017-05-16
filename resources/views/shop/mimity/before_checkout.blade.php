@extends('shop.mimity.index')

@section('content')



<div class="col-lg-4 col-md-4 col-sm-12">
    <div class="col-lg-12 col-sm-12">
        <span class="title">ZAKUPY BEZ REJESTRACJI</span>
    </div>
    <div class="col-lg-12 col-sm-12 hero-feature" style="height: 160px;">
        <p>Aby złożyć zamówienie nie musisz zakładać konta w naszym sklepie.
            Wybierz przycisk "Złóż zamówienie"
        </p>
    </div>
    <div class="col-lg-12 col-sm-12 hero-feature">
        <a href="/checkout">
           <button class="btn btn-primary" title="Rejestracja">Złóż zamówienie</button>
        </a>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-12">
    <div class="col-lg-12 col-sm-12">
        <span class="title">NIE MAM JESZCZE KONTA</span>
    </div>
    <div class="col-lg-12 col-sm-12 hero-feature" style="height: 160px;">
        <p>Załóż konto, aby skorzystać z przywilejów dla stałych klientów:</p>
        <ul>
            <li>podgląd statusu realizacji zamówień i historii zakupów</li>
            <li>brak konieczności wprowadzania swoich danych przy kolejnych zakupach</li>
            <li>możliwość otrzymania rabatów i kuponów promocyjnych</li>
        </ul>
    </div>
    <div class="col-lg-12 col-sm-12 hero-feature">
        <a href="/checkout/register"
           <button class="btn btn-primary" title="Rejestracja">Rejestracja</button>
        </a>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-12">
    <div class="col-lg-12 col-sm-12">
        <span class="title">MAM JUŻ KONTO</span>
    </div>
    <form class="checkout-data-form" action="/loginr/checkout" method="POST">
    <div class="col-lg-12 col-sm-12 hero-feature" style="height: 160px;">
        
            <table class="checkout-form table table-bordered tbl-checkout">
                <tbody>
                    <tr>
                        <td>E-mail</td>
                        <td><input type="text" name="email" class="form-control"></td>
                    </tr>
                    <tr>
                        <td>Hasło</td>
                        <td><input type="password" name="password" class="form-control"></td>
                    </tr>
                </tbody>
            </table>
    </div>
    <div class="col-lg-12 col-sm-12 hero-feature">
        <input class="btn btn-primary" type="submit" value="Zaloguj" />
    </div>
    </form>
</div>

<script>
    @if (count($errors->getBag('default')->keys()) > 0)
        $(document).ready(function(){
            $('input[name="{!! implode('"], input[name="', $errors->getBag('default')->keys()) !!}"]').addClass('error-input-thin');
        });
    @endif
    
</script>

@endsection