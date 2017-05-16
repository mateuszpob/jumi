@extends('shop.mimity.left_side_bestsellers')

@section('items')
<div class="col-lg-9 col-md-9 col-sm-9">
        <div class="row">
            <div class="col-lg-12 col-sm-12"><h1 class="title-on-main-flow">FORMY PŁATNOŚCI</h1>
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
                <img style="    margin-left: -8px;width:120px" src="/img/payu_logo.jpg">
                <p>
                    PayU to szybkie, bezpieczne i proste metody zapłaty za towary oraz usługi oferowane w sklepie, 
                    tak aby nasz Klient odczuwał pełen komfort i wygodę. 
                    </br></br>
                    Schemat działania PayU:
                </p>
                
                <ul style="list-style-type: decimal;">
                    <li>Klient przechodzi do wyboru płatności za towary w sklepie</li>
                    <li>W odpowiedniej sekcji strony znajduje informacje na temat szeregu wygodnych sposobów zapłaty</li>
                    <li>Klient zostaje przekierowany do strony logowania swojego banku lub Centrum Autoryzacji Kart</li>
                    <li>Ostatnią czynnością jest obciążenie przez niego rachunku bankowego lub też karty płatniczej/kredytowej</li>
                    <li>Nad prawidłowym przebiegiem płatności czuwają specjaliści, a także profesjonalny Dział Obsługi Klienta serwisu PayU</li>
                </ul>
                </br></br>
                
                <span style="font-size: 16px;color: #4a4a4a;">Obsługiwane płatności:</span>

                
                <img alt="wspierane płatności" style="width:100%" src="/img/bank_logos.png">
              
                </br>
                <hr>
                <h3 style="font-size: 34px;color: #6a6a6a;">Płatność przelewem</h3>
                </br>
                <p>
                Przelew na konto bankowe
                <br>
                Mbank, nr konta: <strong>18 1950 0001 2006 0727 6705 0002</strong>
                
                </p>
                </br></br></br></br>
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