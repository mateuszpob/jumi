@extends('shop.mimity.index')

@section('content')

<div class="row">
    <div class="col-md-6 col-md-offset-3">
        
            
        <h1 class="text-center" style="    font-size: 30px;">Dziękujemy za złożenie zamówienia</h1>
        </br>
        <h2 class="text-center" style="    font-size: 20px;">Numer Twojego zamówienia: {{$order_id}} </h2>
        </br>
        <h4 class="text-center">Całkowita wartość zakupów, wraz z kosztami wysyłki: {{$total_price}} zł</h4>
        </br>
        <p>
Za chwilę otrzymasz e-mail z prośbą o jego potwierdzenie.




Paczka wkrótce zostanie wysłana.

O zmianie statusu będziemy Cię również informować pocztą elektroniczną.
</br></br>
W razie jakichkolwiek pytań lub wątpliwości prosimy o kontakt 
telefoniczny: 501 246 654 lub e-mailowy: <a href="mailto:sklep@mojachata.eu">sklep@mojachata.eu</a>
</br></br>
Pozdrawiamy</br>
Zespół Obsługi Sklepu MojaChata - Mirosław Padel
        </br></br></br></br></br></br></br></br></br></p>
    </div>
</div>



@endsection