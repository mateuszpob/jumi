
function dataTablesDelete(addr, token) {

    var html = '<form action="' + addr + '" method="POST" accept-charset="UTF-8">\n\
                <input name="_method" type="hidden" value="DELETE">\n\
                <input name="_token" type="hidden" value="' + token + '">\n\
<button type="submit" class="btn btn-danger btn-rounded btn-condensed btn-sm delete"></form>';

    return html;
}
/*
 * Załadowanie koszyka z id ktore bedzie w sesji. 
 * Powinno być odpalane zawsze po załadowaniu strony, odświerzeniu, przejściu na inną.
 * Odświerzy wartości w koszyku w menu (liczbę itemów i total_amount)
 */
function loadCart(callback) {
    callback = callback || null;
    $.ajax({
        url: '/refresh-cart',
        type: 'POST',
        success: function (res) {
            if (parseInt(res.success) == 1) {
                $('.cart-counter').html(res.total_quantity);
                $('.total_amount').html('Suma: ' + parseFloat(res.total_amount).toFixed(2) + '  PLN');

                if (callback === null) {

                } else {
                    callback(res.total_amount, res.shipment_amount);
                }
                return res.total_amount;
            }
        }
    });
}
/*
 * Ładuje itemy do widżetu koszyka
 * @returns {undefined}
 */
function loadItemsToCartList(cart_opened) {
    $.ajax({
        url: '/load-items-to-cart-list',
        type: 'POST',
        success: function (res) {
            
            if(res.items && res.items.length > 0){
                var count = res.items.length;
                $('#fixed-cart').show();
            }else{
                $('#fixed-cart').hide();
            }
            if (parseInt(res.success) == 1) {

                $('#cart-list-content').remove();
                if (res.total_amount > 0) {
                    
                    var html = '<span class="cart-counter">'+count+'</span><div class="overflowhid"><div class="fixed-cart-header"><strong>KOSZYK</strong></div><div id="col-md-12">';
                    res.items.forEach(function (item) {
                        var item_price = item.price;
                        if (item.price_discounted)
                            item_price = item.price_discounted;
                        
                        
                        html += '<div class="row cart-item-menu-list">';
                            html += '<div class="col-md-4">';
                                html += '<div class="crop"><img src="/image/' + item.image_path + '"></div>';
                            html += '</div>';
                            html += '<div class="col-md-8">';
                                html += '<a class="aincart" href="/produkt/' + item.item_id + '"><b>' + item.name + '</b></a><br>';
                                html += '<span>' + parseFloat(item_price).toFixed(2) + ' PLN&nbsp;&nbsp;&nbsp;&nbsp;x' + item.quantity + ' </span>';
                            html += '</div>';
                        html += '</div>';
                        
                    });
                    var style_close = '';
                    if(cart_opened){
                        style_close = 'style="position:static"';
                    }
                    html += '<div style="height:74px"></div></div><div class="cart-fixed-footer"><div class="shadower"></div>';
                            html += '<div class="col-md-8 checkout-button"><a href="/koszyk"><div class="">KOSZYK - '+parseFloat(res.total_amount + res.shipment_amount).toFixed(2)+' PLN</div></a></div><div id="open-close-fixed-cart" '+style_close+' class="col-md-4 " ><img src="/img/fixed_cart_icon.png"></div>';
                    html += '</div></div>';
                    $('#fixed-cart').html(html);
                } 
            }
        }
    });
}
/*
 * Ustawia liczby na stronie koszyka
 * 
 * @param {float} total_cart_amount
 * @param {float} shipment_amount
 * @returns {void}
 */
function setCartTotalAmount(total_cart_amount, shipment_amount) {
    if(total_cart_amount <= 0)
        window.location = '/'
    $('#shipment-amount-cart').html(parseFloat(shipment_amount).toFixed(2) + ' PLN');
    $('#total-cart-amount').html(parseFloat(total_cart_amount).toFixed(2) + ' PLN');
    $('#total-cart-amount').attr('data-total_amount', total_cart_amount);
//
//    var shipment_amount = parseInt($('input[name="shipment_id"]:checked').attr('data-shipment_price'));
    $('#shipment-amount').html(parseFloat(shipment_amount).toFixed(2) + ' PLN');
    $('#shipment-amount').attr('data-shipment_amount', shipment_amount)
//
    $('#total-amount').html(parseFloat(total_cart_amount + shipment_amount).toFixed(2) + ' PLN');

}

//function recountCartAndShipment(){
//        
//        var total_cart_amount = parseInt($('#total-cart-amount').data('total_amount'));
//        var shipment_amount = parseInt($('input[name="shipment_id"][checked]').data('shipment_price'));
//
//        $('#shipment-amount').html(shipment_amount+'zł');
//        $('#total-amount').html(parseInt(total_cart_amount + shipment_amount) +'zł');
//}

$(document).ready(function () {
    var fixed_cart_opened = false;
    
    loadCart();
    loadItemsToCartList(fixed_cart_opened);






    /************************** START SHOP SCRIPTS *********************************/

    /*
     * Stworz zamówienie i zapłać przez payu
     */
    $('#payu_submit').on('click', function () {
        if (document.getElementById('payu_terms_check').checked) {
            $("#payu_terms_error").hide();
            $("#payu_terms_check").css({'outline': 'none'});
            $("#order_checkout_last_form").submit();
        } else {
            $("#payu_terms_check").css({'outline': '3px solid red'});
            $("#payu_terms_error").show();
        }
    });

    /*
     * Dodaj przedmiot do koszyka.
     */
    $('.add-to-cart').on('click', function () {
        var clicked_btn = $(this);
        var item_id = parseInt(this.dataset.item_id);
        var variant_id = $(this).attr('data-variant_id');
        var data = {};
        if (variant_id == 'not_select_variants') {
            $('.show-variants').trigger('click');
            return;
        } else if (variant_id == 'no_variants') {
            data = {'item_id': item_id};
        } else if (parseInt(variant_id) > 0) {

            data = {'item_id': item_id, 'variant_id': parseInt(variant_id)};
        }
        $.ajax({
            type: 'POST',
            url: '/add-to-cart',
            data: data,
            success: function (res) {
                if (parseInt(res.success) == 1) {
                    $('.cart-counter').html(res.result);
                    clicked_btn.html('Przedmiot dodany!');
                    clicked_btn.animate({'opacity': '0.4'}, 200);
                }
                loadCart();
                loadItemsToCartList(fixed_cart_opened);
                if (window.location.pathname == '/cart' || window.location.pathname == '/cart#' || window.location.pathname == 'cart' || window.location.pathname == 'cart#') {
                    location.reload();
                }

            }
        });
    });

    /*
     * Zwieksz liczbe danego przedmiotu w koszyku o 1 (na stronie koszyka).
     */
    $('.cart-quantity-up').on('click', function () {
        var item_id = parseInt($(this).data('item_id'));

        $.ajax({
            type: 'POST',
            url: '/cart-quantity-up',
            data: {'item_id': item_id},
            success: function (res) {
                if (parseInt(res.success) == 1) {
                    $('.cart-item-quantiy[data-item_id="' + item_id + '"]').val(res.quantity);
                    $('.cart-item-total-price[data-item_id="' + item_id + '"]').html(parseFloat(res.price * res.quantity).toFixed(2) + ' PLN');
                    loadCart(setCartTotalAmount);
                    loadItemsToCartList(fixed_cart_opened);
                } else if (parseInt(res.success) == 1) {

                }
            }
        });
    });
    /*
     * Zmniejsz liczbe danego przedmiotu w koszyku o 1 (na stronie koszyka).
     */
    $('.cart-quantity-down').on('click', function () {
        var item_id = parseInt($(this).data('item_id'));
        $.ajax({
            type: 'POST',
            url: '/cart-quantity-down',
            data: {'item_id': item_id},
            success: function (res) {
                if (parseInt(res.success) == 1) {
                    $('.cart-item-quantiy[data-item_id="' + item_id + '"]').val(res.quantity);
                    $('.cart-item-total-price[data-item_id="' + item_id + '"]').html(parseFloat(res.price * res.quantity).toFixed(2) + ' PLN');
                    loadCart(setCartTotalAmount);
                    loadItemsToCartList(fixed_cart_opened);

                    if (total_cart_amount <= 0) {
                        window.location('')
                    }
                }
            }
        });

    });
    /*
     * Aktualizuje cene wysylki podczas zmiany sposobu wysylki (na stronie koszyka)
     */
    $('input[name="shipment_id"]').on('change', function () {
        loadCart(setCartTotalAmount);
    });

    /*
     * Usuwa item z carta  (na stronie koszyka)
     */
    $('.remove_cart').on('click', function () {
        var item_id = parseInt($(this).data('item_id'));
        $.ajax({
            type: 'POST',
            url: '/cart-remove-item',
            data: {'item_id': item_id},
            success: function (res) {
                if (parseInt(res.success) == 1) {
                    $('.cart-item[data-item_id="' + item_id + '"]').remove();
                    total_cart_amount = loadCart(setCartTotalAmount);
                    loadItemsToCartList(fixed_cart_opened);
                    if (total_cart_amount <= 0) {
                        window.location('')
                    }
                }
            }
        })
    });

    /*
     * Pokaż wariantyproduktu na stronie produktu
     */
    $('.show-variants').on('click', function () {
        $('.variants-tab-btn').trigger('click');

        var scrollTop = $(window).scrollTop();
        var from_top = 120;
        if (scrollTop < 100) {
            from_top = 136;
        } else {
            from_top = 100;
        }


        $('html, body').animate({
            scrollTop: $('.variants-tab-btn').offset().top - from_top
        }, 400);

        $('.variant-table-header').animate({'background-color': '#000'}, 600, function () {
            $('.variant-table-header').animate({'background-color': '#ddd'}, 400);
        });
        $('.variant-table-header th').animate({'color': '#fff'}, 600, function () {
            $('.variant-table-header th').animate({'color': '#000'}, 400);
        });

    });
    /*
     * Zmiana wariantu na stronie produktu, zmienia variant_id na buttonie add-to-cart i cene 
     */
    $('input.variant-id-on-details').on('change', function () {
        var selected_radio = $('input.variant-id-on-details:checked');
        var val = selected_radio.val();
        var item_id = selected_radio.data('item_id');
        var price = $('.price-on-variant-list[data-variant_id="' + val + '"]').html();
        $('.add-to-cart[data-item_id="' + item_id + '"]').attr('data-variant_id', val);
        $('.price-on-wide-list[data-item_id="' + item_id + '"]').html(price);
    });


    /*
     * Strona produktu, Ustaw cene produktu taką jak w wariancie
     */

    /************************** var  END SHOP SCRIPTS **********************************/
    item_id = ""
    
    // edycja itemow dla admina na zwyklych podstronach - otworz popup
    $(document).on('click', '.edit-for-admin', function () {
        var item_id = $(this).data('item_id');
        // pobierz dane itemu
        $.ajax({
            url: '/edit-item-data',
            data: {item_id: item_id},
            method: 'POST',
            success: function (res) {
                if (res.Success) {
//                    $('#popup-bck > .popup-panel > input[name="item_name"]').val(res.item.name);
//                    $('#popup-bck > .popup-panel > input[name="item_id"]').val(res.item.id);
//                    $('#popup-bck > .popup-panel > textarea[name="item_description"]').html(res.item.description);
//                    $('#popup-bck > .popup-panel > textarea[name="item_description"]').val(res.item.description);
                    $('#popup-bck').html(res.html);
                    $('#popup-bck').fadeIn(50);
                }
            }
        });
    });
    // edycja itemow dla admina na zwyklych podstronach - submit popupa (zapis itemu)
    $('.popup-submit').on('click', function () {
        var item_id = $('#popup-bck > .popup-panel > input[name="item_id"]').val();
        var item_name = $('#popup-bck > .popup-panel > input[name="item_name"]').val();
        var item_description = $('#popup-bck > .popup-panel > textarea[name="item_description"]').val();console.log(item_id);
        $.ajax({
            url: '/save-edited-item',
            data: {item_id: item_id, item_name: item_name, item_description: item_description},
            method: 'POST',
            success: function (res) {
                if (res.Success) {
                    $('.item-name-on-main-flow[data-item_id="' + item_id + '"]').html(item_name);
                    $('.shop-short-desc[data-item_id="' + item_id + '"]').html(item_description);
                    $('#popup-bck').fadeOut(50);
                }
            }
        });
    });
//    // edycja itemow dla admina na zwyklych podstronach - zamkniecie popupa
//    $('.popup-close').on('click', function () {
//        $('#popup-bck').fadeOut(50);
//    });
    
    
    // fixed cart - zamknięcie
    
    $(document).on('click', '#open-close-fixed-cart', function(){
        if(fixed_cart_opened){
            $('#fixed-cart').animate({'width':'64px', 'height':'64px'}, 200);
            $('#open-close-fixed-cart').css({'position':'absolute'});
            $('#open-close-fixed-cart img').attr('src', '/img/fixed_cart_icon.png')
            $('.cart-counter').fadeIn();
            fixed_cart_opened = false;
        }else{
            $('.cart-counter').fadeOut(0);
            $('#fixed-cart').animate({'width':'440px', 'height':'400px'}, 200);
            $('#open-close-fixed-cart').css({'position':'static'});
            $('#open-close-fixed-cart img').attr('src', '/img/fixed_cart_icon_close.png')
            fixed_cart_opened = true;
        }
    });
//    // fixed cart - otwarcie
//    $(document).on('click', '#open-close-fixed-cart.open', function(){console.log('open')
//        $('#open-close-fixed').removeClass('open');
//        $('#open-close-fixed').addClass('close');
//        $('#fixed-cart').animate({'width':'440px', 'height':'400px'}, 200);
//        $('#open-close-fixed-cart').css({'position':'static'});
//    });


    


    /****************************** START MENU ************************************/



});