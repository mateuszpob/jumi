@extends('shop.mimity.left_side_bestsellers')

@section('items')
<div class="col-lg-9 col-md-9 col-sm-9">
        <div class="row">
            <div class="col-lg-12 col-sm-12"><h1 class="title-on-main-flow">KONTAKT</h1>
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
                        MojaChata - Mirosław Padel</br>
                        ul. Jana Olbrachta 23b m 59
                </p>
                <p>
                        00-107 Warszawa</br>
                        NIP: 7161954139 
                </p>
                <p>
                    Kontakt telefoniczny (od poniedziałku do piątku, w godzinach 8:00-16:00): <strong>501 246 654</strong></br>
                    Kontakt mailowy: <strong><a href="mailto:sklep@mojachata.eu">sklep@mojachata.eu</a></strong>
                </p>

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