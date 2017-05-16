@extends('shop.mimity.left_side_bestsellers')

@section('items')
<div class="col-lg-9 col-md-9 col-sm-9">
        <div class="row">
            <div class="col-lg-12 col-sm-12"><h1 class="title-on-main-flow">ZREALIZUJ SWOJĄ WIZJĘ ŁAZIENKI</h1>
                <span class="title">
                    @if(isset($pagination))
                        <div class="pagination-simple">
                            {!! $pagination !!}
                        </div>
                    @endif
                    
                </span>

            </div>
        </div>
    <div class="row">
            <div class="col-lg-12 col-sm-12 pages-text-box">
               <p>
                        Jeśli cenisz jakość, komfort i estetykę, to z pewnością wybierzesz nasze produkty. Oferujemy

                        szeroki wybór wyposażenia łazienki, które sprawi, że każda chwila spędzona w tym miejscu

                        będzie dla Ciebie przyjemnością. Proponowana przez nas armatura sanitarna to połączenie

                        jakości i wygody z trendami wzorniczymi i nowoczesnymi rozwiązaniami technologicznymi.

                        W naszym sklepie znajdziesz funkcjonalne wyposażenie zarówno

                        luksusowe, jak również skromniejsze na miarę każdego budżetu. Z nami z pewnością

                        zrealizujesz swoją wizję łazienki! Zapraszamy!
                </p>
                <br>
                <div>
                    <div class="col-md-2" style="margin-top:30px;">
                        <a title="Baterie sanitarne" href="{{ url('kategorie/artykuly-sanitarne/baterie') }}">
                            <img src="/img/page_images/baterie.jpg" style="width:100%" alt="baterie sanitarne" />
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a title="Lustra na wymiar" href="{{ url('kategorie/artykuly-sanitarne/lustra') }}">
                            <img src="/img/page_images/lustra.jpg" style="width:100%" alt="lustra" />
                        </a>
                    </div>
                    <div class="col-md-2" style="margin-top:50px;padding: 0;">
                        <a title="Wanny" href="{{ url('kategorie/artykuly-sanitarne/wanny') }}">
                            <img src="/img/page_images/wanny.jpg" style="width:100%" alt="wanny" />
                        </a>
                    </div>
                    <div class="col-md-2">
                        <a title="KAbiny prysznicowe" href="{{ url('kategorie/artykuly-sanitarne/kabiny-prysznicowe') }}">
                            <img src="/img/page_images/kabiny.jpg" style="width:100%" alt="kabiny prysznicowe" />
                        </a>
                    </div>
                    <div class="col-md-2" style="margin-top:50px;padding: 0;">
                        <a title="Zawory kątowe" href="{{ url('kategorie/artykuly-sanitarne/zawory-katowe') }}">
                            <img src="/img/page_images/zawory.jpg" style="width:100%" alt="zawory-katowe" />
                        </a>
                    </div>
                    <div class="col-md-2" style="margin-top:30px;">
                        <a title="Kotły CO" href="{{ url('kategorie/artykuly-sanitarne/kotly') }}">
                            <img src="/img/page_images/kotly.jpg" style="width:100%" alt="kotły" />
                        </a>
                    </div>
                </div>
                
            </div>
    </div>
</div>

<script>
    // podswietl pozycje w menu na glownej belce
   $(document).ready(function(){
       var newURL = window.location.protocol + "//" + window.location.host  + window.location.pathname;
       console.log(newURL)
       $('a.main-menu-option[href="'+newURL+'"]').addClass('active');
   });
</script>
@stop