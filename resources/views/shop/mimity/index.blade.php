<!DOCTYPE html>
<html>
    <head>
        <title>{{ $page_title or config('shop.page_title') }}</title>
        <meta name="description" content="{{ $page_description or config('shop.page_description') }}">
        <meta http-equiv="Content-type" content="text/html; charset=utf-8">
        <!--Favicons-->
        <link rel="apple-touch-icon" sizes="57x57" href="/favicons/apple-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="/favicons/apple-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="/favicons/apple-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="/favicons/apple-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="/favicons/apple-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="/favicons/apple-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="/favicons/apple-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="/favicons/apple-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-icon-180x180.png">
        <link rel="icon" type="image/png" sizes="192x192"  href="/favicons/android-icon-192x192.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicons/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="96x96" href="/favicons/favicon-96x96.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicons/favicon-16x16.png">
        <link rel="manifest" href="/favicons/manifest.json">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="/favicons/ms-icon-144x144.png">
        <meta name="theme-color" content="#ffffff">


        <!-- Bootstrap 3.3.4 -->
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- Main page styles -->
        <link href="/css/main.css" type="text/css" rel="stylesheet" />
        <!-- Mimity page styles -->
        <link href="/css/mimity.css" type="text/css" rel="stylesheet" />
        <!-- jQuery Colorbox style -->
        <!--<link rel="stylesheet" href="/css/colorbox.css" />-->
        <link href="/plugins/owl-carousel/owl.carousel.css" type="text/css" rel="stylesheet" />
        <link href="/plugins/JQRangeSlider/iThing-min.css" type="text/css" rel="stylesheet" />
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">


       

        <!-- jQuery 2.2.0 -->
        <script src="/js/jquery-2.2.0.min.js" type="text/javascript"></script>
        <!-- jQuery UI 1.11.2 -->
        <script src="/js/jquery-ui.min.js" type="text/javascript"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
            $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <script src="/js/main.js" type="text/javascript"></script> 
        <script src="/js/wookmark.js" type="text/javascript"></script> 
        <!-- jQuery Colorbox -->
        <script src="/js/jquery.colorbox-min.js" type="text/javascript"></script> 
        <!-- OWL Carousel - slider -->
        <script src="/plugins/owl-carousel/owl.carousel.min.js" type="text/javascript"></script> 


        <!-- Latest compiled and minified JavaScript -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>


    </head> 
    <body>
        
        
        <div id="popup-bck" style="display:none">

        </div>
        <header>
            <div class="container">
                <div class="row">

                    <!-- Logo -->
                    <div class="col-lg-5 col-md-4 hidden-sm hidden-xs no-right-padding">
                        <div class="col-lg-6 col-md-6 hidden-sm hidden-xs">
                            <div class="well logo">
                                <a href="/">
                                    <!--Moja<span>Chata</span>-->
                                    <img style="width: 160px;" src="/img/logo-md.png" alt="logo moja chata" title="Moja Chata">
                                </a>
                                <div>Stylowe&nbsp;wyposażenie&nbsp;wnętrz</div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 hidden-sm hidden-xs no-right-padding" >
                            <div class="contact-data">
                                tel.:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="tel:501246654">501-246-654</a><br>
                                email:&nbsp;<a href="mailto:sklep@mojachata.eu">sklep@mojachata.eu</a>
                            </div>
                        </div>
                    </div>
                    <!-- End Logo -->

                    <!-- Search Form -->
                    <div class="col-lg-4 col-md-4 col-sm-7 col-xs-12">
                        <div class="well">
                            <form>
                                <div class="input-group">
                                    <input id="search-input" name="search" type="text" class="form-control input-search" placeholder="czego szukasz?">
                                    <span class="input-group-btn">
                                        <button id="search-button" class="btn btn-default no-border-left"><i class="glyphicon glyphicon-search"></i></button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <!-- End Search Form -->

                    <!-- Shopping Cart List -->
                    <div class="col-lg-3 col-md-4 col-sm-5">
                        <div class="well">
                            <span class="pull-right" style="line-height: 30px">
                                @if(\Auth::check())
                                {{\Auth::user()->email}}&nbsp;&nbsp;&nbsp;<a href="/auth/logout">Wyloguj</a>
                                @else
                                <a href="/auth/login">Zaloguj&nbsp;się</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/auth/register">Zarejestruj&nbsp;się</a>
                                @endif
                            </span>
                            <div style="float: right;margin-top: 14px" class="fb-like" data-href="http://mojachata.eu/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
                        </div>
                        <div class="row">
                            <!--                            <div class="well cart-list">
                                                            <div class="btn-group btn-group-cart koszyk-w-menu">
                                                                <button style="display:none;" type="button" class="btn btn-default dropdown-toggle cart-not-empty-button" data-toggle="dropdown">
                                                                    <span class="pull-left"><i class="fa fa-shopping-cart icon-cart"></i></span>
                                                                    <span class="pull-left cart-line">Koszyk: <span class="cart-counter"></span> przedmiot(y)</span>&nbsp;<span class="caret"></span>
                                                                    <span class="pull-right"><i class="fa fa-caret-down"></i></span>
                                                                </button>
                            
                                                                <button style="display:none;" disabled type="button" class="btn btn-default empty-cart-button" data-toggle="dropdown">
                                                                    <span class="pull-left"><i class="fa fa-shopping-cart icon-cart"></i></span>
                                                                    <span class="pull-left cart-line">Koszyk jest pusty</span>
                                                                    <span class="pull-right"><i class="fa fa-caret-down"></i></span>
                                                                </button>
                                                                
                                                            </div>
                                                        </div>-->
                        </div>
                    </div>
                    <!-- End Shopping Cart List -->
                </div>
            </div>
        </header>



        <!-- Navigation -->
        <nav id="menu-glowne" class="navbar navbar-inverse" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!-- text logo on mobile view -->
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav pozycje-w-menu-glownym">
                        <li><a class="{{isset($schema_id ) && 'start' == $schema_id ? 'active' : null}}" href="/">STRONA GŁÓWNA</a></li>
                        @foreach(\App\SchemaCategories::active()->get() as $schema)
                        <li><a id="lazienki" class="{{isset($schema_url) && $schema->url == $schema_url ? 'active' : null}}" href="/kategorie/{{$schema->url}}">{{$schema->name}}</a></li>
                        @endforeach
                        <!--                        <li><a id="lazienki" href="/{{config('shop.shop_link_prefix')}}/6">Łazienki</a></li>-->
                        <!--<li><a href="/{{config('shop.shop_link_prefix')}}">Dom</a></li>-->
                        <!--<li><a id="elektronika" href="/{{config('shop.shop_link_prefix')}}/elektronika">Elektronika</a></li>-->
                        <!--<li><a href="">Inne produkty</a></li>-->
                        <!--                        <li class="nav-dropdown">
                                                    <a href="http://demo.18maret.com/demo/mimity/v1.2/index.html#" class="dropdown-toggle" data-toggle="dropdown">
                                                        Kategorie <span class="caret"></span>
                                                    </a>
                                                    <ul class="dropdown-menu">
                                                        <li><a href="http://demo.18maret.com/demo/mimity/v1.2/login.html">Login</a></li>
                                                        <li><a href="http://demo.18maret.com/demo/mimity/v1.2/register.html">Register</a></li>
                                                    </ul>
                                                </li>-->
                        {{--<li class="nav-dropdown">
                            <a href="/producenci" class="dropdown-toggle" data-toggle="dropdown">
                                Producenci <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                @foreach(\App\Producer::active()->get() as $p)
                                <li><a href="{{$p->getUrl()}}">{{$p->name}}</a></li>
                        @endforeach
                    </ul>
                    </li>--}}
                    <li><a title="Sprawdź aktualne promocje" class="main-menu-option" href="{{url(config('shop.page_prefix').'/promocje')}}">PROMOCJE</a></li>
                    <li><a title="Obsługiwane w sklepie sposoby płatności." class="main-menu-option" href="{{url(config('shop.page_prefix').'/formy-platnosci')}}">PŁATNOŚCI</a></li>
                    <li><a title="Sposoby dostawy towarów" class="main-menu-option" href="{{url(config('shop.page_prefix').'/czas-i-koszty-dostawy')}}">DOSTAWA</a></li>
                    <li><a title="Przydatne teksty" class="main-menu-option" href="{{url('/artykuly')}}">ARTYKUŁY</a></li>
                    <li><a title="Sprawdź czym się zajmujemy" class="main-menu-option" href="{{url(config('shop.page_prefix').'/o-nas')}}">O NAS</a></li>
                    <li class="cart-link-in-menu-glowne"><a title="Twój koszyk" class="{{isset($schema_id ) && 'cart' == $schema_id ? 'active' : null}}" href="/koszyk">Koszyk</a></li>
                    <!--                        <li class="cart-in-menu-glowne">
                                                <span class="loginbar-menu-glowne pull-right">
                                                    @if(\Auth::check())
                                                    {{\Auth::user()->email}}&nbsp;&nbsp;&nbsp;<a href="/auth/logout">Wyloguj</a>
                                                    @else
                                                    <a href="/auth/login">Zaloguj&nbsp;się</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href="/auth/register">Zarejestruj&nbsp;się</a>
                                                    @endif
                                                </span>
                                                <div class="well cart-list pull-right">
                                                    <div class="btn-group btn-group-cart koszyk-w-menu">
                                                        <button style="display:none;" type="button" class="btn btn-default dropdown-toggle cart-not-empty-button" data-toggle="dropdown">
                                                            <span class="pull-left"><i class="fa fa-shopping-cart icon-cart"></i></span>
                                                            <span class="pull-left cart-line">Koszyk: <span class="cart-counter"></span> przedmiot(y)</span>&nbsp;<span class="caret"></span>
                                                            <span class="pull-right"><i class="fa fa-caret-down"></i></span>
                                                        </button>
                    
                                                        <button disabled style="display:none;background-color: #333;margin-left: 0px;" type="button" class="btn btn-default dropdown-toggle empty-cart-button" data-toggle="dropdown">
                                                            <span class="pull-left cart-line">Koszyk jest pusty</span>
                                                        </button>
                                                        
                                                    </div>
                                                </div>
                                                
                                            </li>-->

                    </ul>
                </div>

            </div>
        </nav>
        <!-- End Navigation -->
        <section>
            <div class="container main-container"> 
                @yield('content')
            </div>


            <div id="fixed-cart">
                <span class="cart-counter"></span>
            </div>


            <div class="sep-40"></div>
            <footer>
                <div class="container">
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="column">
                            <h4>Pomoc</h4>
                            <ul>
                                <li><a href="{{ url('mojachata/zwroty-i-reklamacje') }}">Zwroty i reklamacje</a></li>
                                <li><a href="{{ url('mojachata/regulamin') }}">Regulamin</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="column">
                            <h4>Płatność i dostawa</h4>
                            <ul>
                                <li><a href="{{ url('mojachata/formy-platnosci') }}">Formy płatności</a></li>
                                <li><a href="{{ url('mojachata/czas-i-koszty-dostawy') }}">Czas i koszty dostawy</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="column">
                            <h4>Informacje</h4>
                            <ul>
                                <li><a href="{{ url('mojachata/polityka-prywatnosci') }}">Polityka prywatności</a></li>
                                <!--<li><a href="{{ url('mojachata/jak-kupowac') }}">Jak kupować?</a></li>-->
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <div class="column">
                            <h4>Informacje</h4>
                            <ul class="social">
                                <li><a href="{{ url('mojachata/kontakt') }}">Kontakt i dane firmy</a></li>
                                <li><a href="{{ url('mojachata/o-nas') }}">O nas</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="navbar-inverse text-center copyright">
                    MojaChata - Mirosław Padel
                </div>
            </footer>
        <!--            <canvas style="position:absolute;top:0;" id="myCanvas">
                        
                    </canvas>-->
        </section>

        <script>
$(document).ready(function(){
@if (isset($super_category))
        var super_category_id = "{{$super_category}}";
if (super_category_id == "2") {
    $('.pozycje-w-menu-glownym > li > a').removeClass('active');
    $('a#lazienki').addClass('active');
} else
if (super_category_id == "3") {
    $('.pozycje-w-menu-glownym > li > a').removeClass('active');
    $('a#elektronika').addClass('active');
} else {
    $('.pozycje-w-menu-glownym > li > a').removeClass('active');
    $('.pozycje-w-menu-glownym > li > a[data-id="0"]').addClass('active');
}

@endif

        window.onscroll = function () {
            myFunction()
        };
menu_fixed = false;
function myFunction() {
    if (document.body.scrollTop > 100 && menu_fixed == false) {
        menu_fixed = true;
        $('#menu-glowne').addClass('menu-fixed');
    }
    if (document.body.scrollTop < 100 && menu_fixed == true) {
        menu_fixed = false;
        $('#menu-glowne').removeClass('menu-fixed');
    }
}

// ============================= START SEARCH ================================ //
$('#search-button').on('click', function (event) {
    event.preventDefault();
    var search = $('#search-input').val().trim();
    if (search != '') {
        document.location = "{{ url('search') }}" + "/" + search;
    }
});


// ============================= START MENU ================================== //

function openSlideMenu(obj) {
    var slide_speed = 100;
    var id = obj.data('id');
    if (!obj.hasClass('clicked')) {
        obj.addClass('clicked');

        obj.next('i.fa').removeClass('fa-caret-left');
        obj.next('i.fa').addClass('fa-caret-down');

        //            var this_el = obj;
        //            this_el.css({'margin':'0px'});

        $('ul.moczy-menu-part[data-parent="' + id + '"]').css({'display': 'block'});
        $('ul.moczy-menu-part[data-parent="' + id + '"]').animate({'height': '100%'}, slide_speed);
        $('li.el-moczy-menu[data-parent="' + id + '"]').css({'display': 'block'});
        $('li.el-moczy-menu[data-parent="' + id + '"]').animate({'height': '35px'}, slide_speed, function () {
            //                this_el.css({'margin':'-1px'});
        });
    } else {
        obj.next('i.fa').removeClass('fa-caret-down');
        obj.next('i.fa').addClass('fa-caret-left');

        obj.removeClass('clicked');
        $('li.el-moczy-menu[data-parent="' + id + '"]').animate({'height': 0}, slide_speed, function () {
            obj.css({'display': 'none'});
        });
        $('ul.moczy-menu-part[data-parent="' + id + '"]').animate({'height': 0}, slide_speed, function () {
            obj.css({'display': 'none'});
        });
    }
}
$.each($('li.el-moczy-menu'), function () {
    var poziom = $(this).data('poziom');
    var margin = (poziom * 12) + 'px';
    $(this).css({'margin-left': margin});
    if (poziom > 0) {
        $(this).css({'display': 'none', 'height': 0});
    }
});

//                $('li.el-moczy-menu').on('click', function () {
//                    openSlideMenu($(this));
//                });
function openRecursiveMenu(id) {
    var pid = $('li.el-moczy-menu[data-id="' + id + '"]').data('parent')
    console.log('...>' + pid)
    openSlideMenu($('li.el-moczy-menu[data-id="' + id + '"]'))
    if (pid = $('li.el-moczy-menu[data-id="' + id + '"]').data('parent')) {
        openRecursiveMenu(pid)
    }
    }
    @if (isset($category_id))
            var current_category_id = parseInt("{{$category_id}}");
    openRecursiveMenu(current_category_id);
    $('li.el-moczy-menu[data-id="' + current_category_id + '"]').addClass('activeCategory');
            @endif()
            // ========================= END MENU ================================== //
}
);
        </script>
<!--        <script src="http://cookiealert.sruu.pl/CookieAlert-latest.min.js"></script>
<script>CookieAlert.init();</script>-->
        <!--Tracker<script src="//127.0.0.1:8080/clientscr/88c3a9a1d1dcac070433ef1f30bb5ef14759bedc/trck"></script>--> 
{!! config('shop.uibooster') !!}
    </body>
    
</html>