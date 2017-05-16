@extends('shop.mimity.index')

@section('content')

<div class="col-lg-8 col-md-8 col-sm-12">
    <div class="col-lg-12 col-sm-12">
        <span class="title">TWOJE DANE - ZAMÓWIENIE Z REJESTRACJĄ</span>
    </div>
    <div class="col-lg-12 col-sm-12 hero-feature">
        <form class="checkout-data-form" action="/order-checkout/register" method="POST">
            <table class="checkout-form table table-bordered tbl-checkout">
                <tbody>
                    <tr>
                        <td>Imię</td>
                        <td>
                            <input type="text" name="first_name" value="{{ isset($address_data['first_name']) ? $address_data['first_name'] : old('first_name') }}" required="required" class="form-control">
                        </td>
                        <td>Nazwisko</td>
                        <td>
                            <input type="text" name="last_name" value="{{ isset($address_data['last_name']) ? $address_data['last_name'] : old('last_name') }}" required="required" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>
                            <input type="text" name="email" value="{{ isset($address_data['email']) ? $address_data['email'] : old('email') }}" required="required" class="form-control">
                        </td>
                        <td>Numer telefonu</td>
                        <td>
                            <input type="text" name="telephone" value="{{ isset($address_data['telephone']) ? $address_data['telephone'] : old('telephone') }}" required="required" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>Hasło</td>
                        <td>
                            <input type="password" name="password" required="required" class="form-control">
                        </td>
                        <td>Powtórz hasło</td>
                        <td>
                            <input type="password" name="password_confirmation" required="required" class="form-control">
                        </td>
                    </tr>
                </tbody>
            </table>
            
            <input style="width: 18px;float:left;margin: -8px 5px;"  type="radio" name="account_type" checked="checked" value="person" class="form-control" />
            <span  style="float:left;">Osoba prywatna&nbsp;&nbsp;&nbsp;&nbsp;</span> 
            <input style="width: 18px;float:left;margin: -8px 5px;"  type="radio" name="account_type" value="company" class="form-control"/>
            <span  style="float:left;">Firma</span> 
            
            <table class="checkout-form table table-bordered tbl-checkout">
                <tbody>
                    <tr class="company-fields" style="display:none">
                        <td>Nazwa firmy</td>
                        <td colspan="3">
                            <input type="text" name="company_name" value="{{ isset($address_data['company_name']) ? $address_data['company_name'] : old('company_name') }}" class="form-control" />
                        </td>
                    </tr>
                    <tr>
                        <td>Ulica i nr domu</td>
                        <td colspan="3">
                            <input type="text" name="address" value="{{ isset($address_data['address']) ? $address_data['address'] : old('address') }}" required="required" class="form-control" />
                        </td>
                    </tr>
                    <tr>
                        <td>Miasto</td>
                        <td>
                            <input type="text" name="city" value="{{ isset($address_data['city']) ? $address_data['city'] : old('city') }}" required="required" class="form-control">
                        </td>
                        <td>Kod pocztowy</td>
                        <td>
                            <input type="text" name="postcode" value="{{ isset($address_data['postcode']) ? $address_data['postcode'] : old('postcode') }}" required="required" class="form-control">
                        </td>
                    </tr>
                    <tr>
                </tbody>
            </table>
            <table class="checkout-form table table-bordered tbl-checkout">
                <tbody>
                    <tr>
                        <td>Komentarz do zamówienia</td>
                        <td colspan="3">
                            <textarea name="comment" class="form-control">{{ old('comment') }}</textarea>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input class="btn btn-primary" type="submit" />
            @if (count($errors) > 0)
                <p class="error-label error-label-checkout">Uzupełnij lub popraw zaznaczone pola.</p>
            @endif
        </form>
    </div>
</div>


<script>
    @if (count($errors->getBag('default')->keys()) > 0)
        $(document).ready(function(){
            $('input[name="{!! implode('"], input[name="', $errors->getBag('default')->keys()) !!}"]').addClass('error-input-thin');
        });
    @endif
    
    $(document).ready(function(){
        $('input[name="account_type"]').on('change', function(){
            if($(this).val() == 'company')
                $('.company-fields').fadeIn();
            else
                if($(this).val() == 'person')
                    $('.company-fields').fadeOut();
        });
    });
</script>

@endsection