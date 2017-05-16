@extends('shop.mimity.index')

@section('content')

<form class="checkout-data-form" action="/order-checkout" method="POST">
    <div class="col-lg-8 col-md-8 col-sm-12">
        <div class="col-lg-12 col-sm-12">
            <span class="title">TWOJE DANE - zakupy bez logowania</span>
        </div>
        <div class="col-lg-12 col-sm-12 hero-feature">
                <table class="checkout-form table table-bordered tbl-checkout">
                    <tbody>
                        <tr>
                            <td>Imię</td>
                            <td>
                                <input type="text" name="first_name" value="{{ isset($address_data['first_name']) ? $address_data['first_name'] : old('first_name') }}" class="form-control">
                            </td>
                            <td>Nazwisko</td>
                            <td>
                                <input type="text" name="last_name" value="{{ isset($address_data['last_name']) ? $address_data['last_name'] : old('last_name') }}" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>
                                <input type="text" name="email" value="{{ isset($address_data['email']) ? $address_data['email'] : old('email') }}" class="form-control">
                            </td>
                            <td>Numer telefonu</td>
                            <td>
                                <input type="text" name="telephone" value="{{ isset($address_data['telephone']) ? $address_data['telephone'] : old('telephone') }}" class="form-control">
                            </td>
                        </tr>
                        <tr>
                            <td>Adres</td>
                            <td colspan="3">
                                <input type="text" name="address" value="{{ isset($address_data['address']) ? $address_data['address'] : old('address') }}" class="form-control" />
                            </td>
                        </tr>
                        <tr>
                            <td>Miasto</td>
                            <td>
                                <input type="text" name="city" value="{{ isset($address_data['city']) ? $address_data['city'] : old('city') }}" class="form-control">
                            </td>
                            <td>Kod pocztowy</td>
                            <td>
                                <input type="text" name="postcode" value="{{ isset($address_data['postcode']) ? $address_data['postcode'] : old('postcode') }}" class="form-control">
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td>Komentarz do zamówienia</td>
                            <td colspan="3">
                                <textarea name="comment" class="form-control">{{ old('comment') }}</textarea>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input class="btn btn-success" value="Potwierdź" type="submit" />
                @if (count($errors) > 0)
                    <p class="error-label error-label-checkout">Uzupełnij lub popraw zaznaczone pola.</p>
                @endif
        </div>
    </div>

    <div class="col-md-4">
        <div class="col-lg-12 col-sm-12">
            <span class="title">PŁATOŚCI</span>
        </div>
        <div class="col-lg-12 col-sm-12" style="border: 1px solid #ddd;">
            <div class="col-lg-8 col-sm-10 ">
                <img style="width:100%" src="/img/payu_logo.jpg" >
            </div>
            <div class="col-lg-4 col-sm-2 ">
                <input type="radio" value="2" name="payment_type_id" checked style="margin: 70% 50%;">
            </div>
        </div>
        <div class="col-lg-12 col-sm-12" style="border: 1px solid #ddd;margin-top: 28px;">
            <div class="col-lg-8 col-sm-10 ">
                <p style="font-size: 22px;margin-top: 24%;" >Zwykły przelew</p>
            </div>
            <div class="col-lg-4 col-sm-2 ">
                <input type="radio" value="1" name="payment_type_id" style="margin: 70% 50%;">
            </div>
        </div>
    </div>
</form>
<script>
    @if (count($errors->getBag('default')->keys()) > 0)
        $(document).ready(function(){
            $('input[name="{!! implode('"], input[name="', $errors->getBag('default')->keys()) !!}"]').addClass('error-input-thin');
        });
    @endif
    
    
//$(document).ready(function(){
//    $('#checkout-button').on('click', function(){
//        $('.checkout-form input').removeClass('error-input-thin');
//        $('.checkout-form textarea').removeClass('error-input-thin');
//        var data = [];
//        var error_counter = 0;
//        var first_name = $('input[name="first_name"]').val();
//        if(first_name == '' || first_name == null){
//            $('input[name="first_name"]').addClass('error-input-thin');
//            error_counter ++;
//        }else{
//            data['first_name'] = first_name;
//        }
//        var last_name = $('input[name="last_name"]').val();
//        if(last_name == '' || last_name == null){
//            $('input[name="last_name"]').addClass('error-input-thin');
//            error_counter ++;
//        }else{
//            data['last_name'] = last_name;
//        }
//        var email = $('input[name="email"]').val();
//        if(email == '' || email == null){
//            $('input[name="email"]').addClass('error-input-thin');
//            error_counter ++;
//        }else{
//            data['email'] = email;
//        }
//        var telephone = $('input[name="telephone"]').val();
//        if(telephone == '' || telephone == null){
//            $('input[name="telephone"]').addClass('error-input-thin');
//            error_counter ++;
//        }else{
//            data['telephone'] = telephone;
//        }
//        var address = $('textarea[name="address"]').val();
//        if(address == '' || address == null){
//            $('textarea[name="address"]').addClass('error-input-thin');
//            error_counter ++;
//        }else{
//            data['address'] = address;
//        }
//        var city = $('input[name="city"]').val();
//        if(city == '' || city == null){
//            $('input[name="city"]').addClass('error-input-thin');
//            error_counter ++;
//        }else{
//            data['city'] = city;
//        }
//        var postcode = $('input[name="postcode"]').val();
//        if(postcode == '' || postcode == null){
//            $('input[name="postcode"]').addClass('error-input-thin');
//            error_counter ++;
//        }else{
//            data['postcode'] = postcode;
//        }
//        var comment = $('textarea[name="comment"]').val();
//        
//        if(error_counter == 0){
//            $.ajax({
//                type: 'POST',
//                url: '/order-checkout',
//                data: data,
//                function(res){
//                    if(parseInt(res.success) == 1){
//                    
//                    
//                    }
//                }
//            })
//        }else{
//            
//        }
//    });
//        
//});
</script>

@endsection