@extends('shop.mimity.left_side_bestsellers')

@section('items')
<div class="col-lg-9 col-md-9 col-sm-9">
        <div class="row">
            <div class="col-lg-12 col-sm-12"><h1 class="title-on-main-flow">Regulamin sklepu internetowego MojaChata.eu</h1>
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
                <div class="col-lg-12 col-sm-12">
                
<style>
    h2, h5{
        text-align: center;
        font-size: 26px;
    }
</style>



<h5>§ 1</h5>
<h2>Postanowienia wstępne</h2>

<ul style="list-style-type:decimal;">
    <li>Sklep internetowy MojaChata, dostępny pod adresem internetowym www.mojachata.eu prowadzony jest przez Mirosława Padel prowadzącego 
        działalność gospodarczą pod firmą Jumi - Mirosław Padel wpisaną do Centralnej Ewidencji i Informacji o Działalności Gospodarczej (CEIDG) 
        prowadzonej przez ministra właściwego ds. gospodarki, NIP 7161954139, REGON 060329555
    </li>
    <li>Niniejszy regulamin skierowany jest do Konsumentów i określa zasady i tryb zawierania z Konsumentem Umowy Sprzedaży na odległość za pośrednictwem Sklepu. </li>
</ul>


<h5>§ 2</h5>
<h2>Definicje</h2>

<ul style="list-style-type:decimal;">
    <li>Konsument - osoba fizyczna zawierająca ze Sprzedawcą umowę w ramach Sklepu, której przedmiot nie jest związany bezpośrednio z jej działalnością gospodarczą lub zawodową. </li>
    <li>Sprzedawca - osoba fizyczna prowadząca działalność gospodarczą pod firmą Jumi - Mirosław Padel wpisaną do Centralnej Ewidencji i Informacji o Działalności Gospodarczej (CEIDG) prowadzonej przez ministra właściwego ds. gospodarki, NIP 7161954139, REGON 060329555</li>
    <li>Klient - każdy podmiot dokonujący zakupów za pośrednictwem Sklepu.</li>
    <li>Przedsiębiorca - osoba fizyczna, osoba prawna i jednostka organizacyjna niebędąca osobą prawną, której odrębna ustawa przyznaje zdolność prawną, wykonująca we własnym imieniu działalność gospodarczą, która korzysta ze Sklepu.</li>
    <li>Sklep - sklep internetowy prowadzony przez Sprzedawcę pod adresem internetowym www.mojachata.eu.</li>
    <li>Umowa zawarta na odległość - umowa zawarta z Klientem w ramach zorganizowanego systemu zawierania umów na odległość (w ramach Sklepu), bez jednoczesnej fizycznej obecności stron, z wyłącznym wykorzystaniem jednego lub większej liczby środków porozumiewania się na odległość do chwili zawarcia umowy włącznie.</li>
    <li>Regulamin - niniejszy regulamin Sklepu.</li>
    <li>Zamówienie - oświadczenie woli Klienta składane za pomocą Formularza Zamówienia i zmierzające bezpośrednio do zawarcia Umowy Sprzedaży Produktu lub Produktów ze Sprzedawcą.</li>
    <li>Konto - konto klienta w Sklepie, są w nim gromadzone są dane podane przez Klienta oraz informacje o złożonych przez niego Zamówieniach w Sklepie.</li>
    <li>Formularz rejestracji - formularz dostępny w Sklepie, umożliwiający utworzenie Konta.</li>
    <li>Formularz zamówienia - interaktywny formularz dostępny w Sklepie umożliwiający złożenie Zamówienia, w szczególności poprzez dodanie Produktów do Koszyka oraz określenie warunków Umowy Sprzedaży, w tym sposobu dostawy i płatności.</li>
    <li>Koszyk – element oprogramowania Sklepu, w którym widoczne są wybrane przez Klienta Produkty do zakupu, a także istnieje możliwość ustalenia i modyfikacji danych Zamówienia, w szczególności ilości produktów.</li>
    <li>Produkt - dostępna w Sklepie rzecz ruchoma/usługa będąca przedmiotem Umowy Sprzedaży między Klientem a Sprzedawcą.</li>
    <li>Umowa Sprzedaży - umowa sprzedaży Produktu zawierana albo zawarta między Klientem a Sprzedawcą za pośrednictwem Sklepu internetowego. Przez Umowę Sprzedaży rozumie się też - stosowanie do cech Produktu - umowę o świadczenie usług i umowę o dzieło.</li>
    <li></li>

</ul>
<h5>§ 3</h5>
<h2>Kontakt ze Sklepem</h2>
<p>
    
    1. Adres Sprzedawcy: ul. Jana Olbrachta 23b m 59, 01-107 Warszawa </br>
    2. Adres e-mail Sprzedawcy: sklep@mojachata.eu </br>
    3. Numer telefonu Sprzedawcy: 501 246 654 </br>
    4. Numer rachunku bankowego Sprzedawcy 18 1950 0001 2006 0727 6705 0002 </br>
    5. Klient może porozumiewać się ze Sprzedawcą za pomocą adresów i numerów telefonów podanych w niniejszym paragrafie. </br>
    6. Klient może porozumieć się telefonicznie ze Sprzedawcą w godzinach 8:00-16:00
</p>



<h5>§ 4</h5>
<h2>Wymagania techniczne</h2>

<p>
    Do korzystania ze Sklepu, w tym przeglądania asortymentu Sklepu oraz składania zamówień na Produkty, niezbędne są: </br>
        a. urządzenie końcowe z dostępem do sieci Internet i przeglądarką internetową typu chrome 49, FireFox 47, Opera 40, IE 11 </br>
        b. aktywne konto poczty elektronicznej (e-mail), </br>
        c. włączona obsługa plików cookies
</p>


<h5>§ 5</h5>
<h2>Informacje ogólne</h2>
<ul style="list-style-type:decimal;">
    <li>
        Sprzedawca w najszerszym dopuszczalnym przez prawo zakresie nie ponosi odpowiedzialności za zakłócenia w tym przerwy w funkcjonowaniu Sklepu spowodowane siłą wyższą, niedozwolonym działaniem osób trzecich lub niekompatybilnością Sklepu internetowego z infrastrukturą techniczną Klienta. 
    </li>
    <li>
        Przeglądanie asortymentu Sklepu nie wymaga zakładania Konta. Składanie zamówień przez Klienta na Produkty znajdujące się w asortymencie Sklepu możliwe jest albo po założeniu Konta zgodnie z postanowieniami § 6 Regulaminu albo przez podanie niezbędnych danych osobowych i adresowych umożliwiających realizację Zamówienia bez zakładania Konta. 
    </li>
    <li>
       Ceny podane w Sklepie są podane w polskich złotych i są cenami brutto (uwzględniają podatek VAT).
    </li>
    <li>
        Na końcową (ostateczną) kwotę do zapłaty przez Klienta składa się cena za Produkt oraz koszt dostawy (w tym opłaty za transport, dostarczenie i usługi pocztowe), o której Klient jest informowany na stronach Sklepu w trakcie składania Zamówienia, w tym także w chwili wyrażenia woli związania się Umową Sprzedaży.
    </li>
    <li>
        W przypadku Umowy obejmującej prenumeratę lub świadczenie usług na czas nieoznaczony końcową (ostateczną) ceną jest łączna cena obejmująca wszystkie płatności za okres rozliczeniowy.
    </li>
    <li>
        Gdy charakter przedmiotu Umowy nie pozwala, rozsądnie oceniając, na wcześniejsze obliczenie wysokości końcowej (ostatecznej) ceny, informacja o sposobie, w jaki cena będzie obliczana, a także o opłatach za transport, dostarczenie, usługi pocztowe oraz o innych kosztach, będzie podana w Sklepie w opisie Produktu.
    </li>
</ul>

<h5>§ 6</h5>
<h2>Zakładanie Konta w Sklepie</h2>
<ul style="list-style-type:decimal;">
    <li>
        Aby założyć Konto w Sklepie, należy wypełnić Formularz rejestracji. Niezbędne jest podanie następujących danych: imię, nazwisko, adres, e-mail, nr telefonu.
    </li>
    <li>
        Założenie Konta w Sklepie jest darmowe.
    </li>
    <li>
        Logowanie się na Konto odbywa się poprzez podanie loginu i hasła ustanowionych w Formularzu rejestracji.
    </li>
    <li>
        Klient ma możliwość w każdej chwili, bez podania przyczyny i bez ponoszenia z tego tytułu jakichkolwiek opłat usunąć Konto poprzez wysłanie stosownego żądania do Sprzedawcy, w szczególności za pośrednictwem poczty elektronicznej lub pisemnie na adresy podane w § 3.
    </li>
</ul>

<h5>§ 7</h5>
<h2>Zasady składania Zamówienia</h2>
W celu złożenia Zamówienia należy: <br>
<ul style="list-style-type:decimal;">
    <li>
        1. zalogować się do Sklepu (opcjonalnie);
    </li>
    <li>
        2. wybrać Produkt będący przedmiotem Zamówienia, a następnie kliknąć przycisk „Do koszyka” (lub równoznaczny);
    </li>
    <li>
        3. zalogować się lub skorzystać z możliwości złożenia Zamówienia bez rejestracji;
    </li>
    <li>
        4. jeżeli wybrano możliwość złożenia Zamówienia bez rejestracji - wypełnić Formularz zamówienia poprzez wpisanie danych odbiorcy Zamówienia oraz adresu, na który ma nastąpić dostawa Produktu.
    </li>
    <li>
        5. kliknąć przycisk “Zamawiam i płacę”/kliknąć przycisk “Zamawiam i płacę” oraz potwierdzić zamówienie, klikając w link przesłany w wiadomości e-mail,
    </li>
    <li>
        6. wybrać jeden z dostępnych sposobów płatności i w zależności od sposobu płatności, opłacić zamówienie w określonym terminie, z zastrzeżeniem § 8 pkt 3
    </li>
</ul>

<h5>§ 8</h5>
<h2>Oferowane metody dostawy oraz płatności</h2>
<p>Wszystkie produkty wysyłane są przez producentów w oryginalnym opakowaniu, na co Sklep nie ma wpływu.</p>
<p>Akceptujemy płątności PayU, oraz przelew tradycyjny na numer konta 18 1950 0001 2006 0727 6705 0002</p>

<h5>§ 9</h5>
<h2>Wykonanie umowy sprzedaży</h2>
<p>
    
    1. Zawarcie Umowy Sprzedaży między Klientem a Sprzedawcą następuje po uprzednim złożeniu przez Klienta Zamówienia za pomocą Formularza zamówienia w Sklepie internetowym zgodnie z § 7 Regulaminu.</br>
2. Po złożeniu Zamówienia Sprzedawca niezwłocznie potwierdza jego otrzymanie oraz jednocześnie przyjmuje Zamówienie do realizacji. Potwierdzenie otrzymania Zamówienia i jego przyjęcie do realizacji następuje poprzez przesłanie przez Sprzedawcę Klientowi stosownej wiadomości e-mail na podany w trakcie składania Zamówienia adres poczty elektronicznej Klienta, która zawiera co najmniej oświadczenia Sprzedawcy o otrzymaniu Zamówienia i o jego przyjęciu do realizacji oraz potwierdzenie zawarcia Umowy Sprzedaży. Z chwilą otrzymania przez Klienta powyższej wiadomości e-mail zostaje zawarta Umowa Sprzedaży między Klientem a Sprzedawcą.</br>
3. W przypadku wyboru przez Klienta: </br>
a. płatności przelewem, płatności elektronicznych albo płatności kartą płatniczą, Klient obowiązany jest do dokonania płatności w terminie …. dni kalendarzowych od dnia zawarcia Umowy Sprzedaży - w przeciwnym razie zamówienie zostanie anulowane.</br>
b. płatności za pobraniem przy odbiorze przesyłki, Klient obowiązany jest do dokonania płatności przy odbiorze przesyłki.</br>
c. płatności gotówką przy odbiorze osobistym przesyłki, Klient obowiązany jest dokonać płatności przy odbiorze przesyłki w terminie …. dni od dnia otrzymania informacji o gotowości przesyłki do odbioru.</br>
4. Jeżeli Klient wybrał sposób dostawy inny niż odbiór osobisty, Produkt zostanie wysłany przez Sprzedawcę w terminie wskazanym w jego opisie (z zastrzeżeniem ustępu 5 niniejszego paragrafu), w sposób wybrany przez Klienta podczas składania Zamówienia. </br>
5. A W przypadku zamówienia Produktów o różnych terminach dostawy, terminem dostawy jest najdłuższy podany termin.</br>
B W przypadku zamówienia Produktów o różnych terminach dostawy, Klient ma możliwość żądania dostarczenia Produktów częściami lub też dostarczenia wszystkich Produktów po skompletowaniu całego zamówienia.</br>
6. Początek biegu terminu dostawy Produktu do Klienta liczy się w następujący sposób:</br>
a. W przypadku wyboru przez Klienta sposobu płatności przelewem, płatności elektroniczne lub kartą płatniczą - od dnia uznania rachunku bankowego Sprzedawcy.</br>
b. W przypadku wyboru przez Klienta sposobu płatności za pobraniem – od dnia zawarcia Umowy Sprzedaży,</br>
6. W przypadku wyboru przez Klienta odbioru osobistego Produktu, Produkt będzie gotowy do odbioru przez Klienta w terminie wskazanym w opisie Produktu. O gotowości Produktu do odbioru Klient zostanie dodatkowo poinformowany przez Sprzedawcę poprzez przesłanie stosownej wiadomości e-mail na podany w trakcie składania Zamówienia adres poczty elektronicznej Klienta.</br>
7. W przypadku zamówienia Produktów o różnych terminach gotowości do odbioru, terminem gotowości do odbioru jest najdłuższy podany termin. <br>
8. Początek biegu terminu gotowości Produktu do odbioru przez Klienta liczy się w następujący sposób:<br>
a. W przypadku wyboru przez Klienta sposobu płatności przelewem, płatności elektroniczne - od dnia uznania rachunku bankowego Sprzedawcy.<br>
b. W przypadku wyboru przez Klienta sposobu gotówką przy odbiorze osobistym – od dnia zawarcia Umowy Sprzedaży.<br>
9. Dostawa Produktu do Klienta jest odpłatna, chyba że Umowa Sprzedaży stanowi inaczej. Koszty dostawy Produktu (w tym opłaty za transport, dostarczenie i usługi pocztowe) są wskazywane Klientowi na stronach Sklepu internetowego w zakładce „Dostawa” oraz w trakcie składania Zamówienia, w tym także w chwili wyrażenia przez Klienta woli związania się Umową Sprzedaży. 
10. Odbiór osobisty Produktu przez Klienta jest niemożliwy. 


</p>

<h5>§ 10</h5>
<h2>Prawo odstąpienia od umowy</h2>
<p>
    
1. Konsument może w terminie 14 dni odstąpić od Umowy Sprzedaży bez podania jakiejkolwiek przyczyny.<br>
2. Bieg terminu określonego w ust. 1 rozpoczyna się od dostarczenia Produktu Konsumentowi lub wskazanej przez niego osobie innej niż przewoźnik.<br>
3. W przypadku Umowy, która obejmuje wiele Produktów, które są dostarczane osobno, partiami lub w częściach, termin wskazany w ust. 1 biegnie od dostawy ostatniej rzeczy, partii lub części.<br>
4. W przypadku Umowy, która polega na regularnym dostarczaniu Produktów przez czas oznaczony (prenumerata), termin wskazany w ust. 1 biegnie od objęcia w posiadanie pierwszej z rzeczy.<br>
5. Konsument może odstąpić od Umowy, składając Sprzedawcy oświadczenie o odstąpieniu od Umowy. Do zachowania terminu odstąpienia od Umowy wystarczy wysłanie przez Konsumenta oświadczenia przed upływem tego terminu.<br>
9. W przypadku gdy ze względu na charakter Produktu nie może on zostać odesłany w zwykłym trybie pocztą, informacja o tym, a także o kosztach zwrotu Produktu, będzie się znajdować w opisie Produktu w Sklepie.<br>
10. Prawo do odstąpienia od umowy zawartej na odległość nie przysługuje Konsumentowi w odniesieniu do Umowy:<br>
a. w której przedmiotem świadczenia jest rzecz nieprefabrykowana, wyprodukowana według specyfikacji Konsumenta lub służąca zaspokojeniu jego zindywidualizowanych potrzeb,<br>
b. w której przedmiotem świadczenia jest rzecz dostarczana w zapieczętowanym opakowaniu, której po otwarciu opakowania nie można zwrócić ze względu na ochronę zdrowia lub ze względów higienicznych, jeżeli opakowanie zostało otwarte po dostarczeniu,<br>
c. w które przedmiotem świadczenia jest rzecz ulegająca szybkiemu zepsuciu lub mająca krótki termin przydatności do użycia,<br>
d. o świadczenie usług, jeżeli Sprzedawca wykonał w pełni usługę za wyraźną zgodą Konsumenta, który został poinformowany przez rozpoczęciem świadczenia, że po spełnieniu świadczenia przez Sprzedawcę utraci prawo odstąpienia od Umowy,<br>
e. w którym cena lub wynagrodzenie zależy od wahań na rynku finansowym, nad którym Sprzedawca nie sprawuje kontroli, i które mogą wystąpić przed upływem terminu do odstąpienia od Umowy,<br>
f. w której przedmiotem świadczenia są rzeczy, które po dostarczeniu, ze względu na swój charakter, zostają nierozłącznie połączone z innymi rzeczami,<br>
g. w której przedmiotem świadczenia są napoje alkoholowe, których cena została uzgodniona przy zawarciu umowy sprzedaży, a których dostarczenie może nastąpić dopiero po upływie 30 dni i których wartość zależy od wahań na rynku, nad którymi Sprzedawca nie ma kontroli,<br>
h. w której przedmiotem świadczenia są nagrania dźwiękowe lub wizualne albo programy komputerowe dostarczane w zapieczętowanym opakowaniu, jeżeli opakowanie zostało otwarte po dostarczeniu,<br>
i. o dostarczanie dzienników, periodyków lub czasopism, z wyjątkiem umowy o prenumeratę,<br>
j. o dostarczenie treści cyfrowych, które nie są zapisane na nośniku materialnym, jeżeli spełnianie świadczenia rozpoczęło się za wyraźną zgodą Konsumenta przed upływem terminu do odstąpienia od umowy i po poinformowaniu go przez Sprzedawcę o utracie prawa odstąpienia od Umowy,<br>

</p>

<h5>§ 11</h5>
<h2>Reklamacja i gwarancja</h2>

<p>
    1. Umową Sprzedaży objęte są nowe Produkty.<br>
    2. W przypadku wystąpienia wady zakupionego u Sprzedawcy towaru Klient ma prawo do reklamacji w oparciu o przepisy dotyczące rękojmi w kodeksie cywilnym. Jeżeli Klientem jest Przedsiębiorca, strony wyłączają odpowiedzialność z tytułu rękojmi.<br>
    3. Reklamację należy zgłosić pisemnie na podane w niniejszym Regulaminie adresy Sprzedawcy. <br>
    
</p>

<h5>§ 12</h5>
<h2>Pozasądowe sposoby rozpatrywania reklamacji i dochodzenia roszczeń</h2>
<p>
    1. Szczegółowe informacje dotyczące możliwości skorzystania przez Konsumenta z  pozasądowych sposobów rozpatrywania reklamacji i dochodzenia roszczeń oraz zasady dostępu do tych procedur dostępne są w siedzibach oraz na stronach internetowych powiatowych (miejskich) rzeczników konsumentów, organizacji społecznych, do których zadań statutowych należy ochrona konsumentów, Wojewódzkich Inspektoratów Inspekcji Handlowej oraz pod następującymi adresami internetowymi Urzędu Ochrony Konkurencji i Konsumentów: http://www.uokik.gov.pl/spory_konsumenckie.php; http://www.uokik.gov.pl/sprawy_indywidualne.php oraz http://www.uokik.gov.pl/wazne_adresy.php.<br>
2. Konsument posiada następujące przykładowe możliwości skorzystania z pozasądowych sposobów rozpatrywania reklamacji i dochodzenia roszczeń:<br>
a. Konsument uprawniony jest do zwrócenia się do stałego polubownego sądu konsumenckiego, o którym mowa w art. 37 ustawy z dnia 15 grudnia 2000 r. o Inspekcji Handlowej (Dz.U. z 2014 r. poz. 148 z późn. zm.), z wnioskiem o rozstrzygnięcie sporu wynikłego z Umowy zawartej ze Sprzedawcą. <br>
b. Konsument uprawniony jest do zwrócenia się do wojewódzkiego inspektora Inspekcji Handlowej, zgodnie z art. 36 ustawy z dnia 15 grudnia 2000 r. o Inspekcji Handlowej (Dz.U. z 2014 r. poz. 148 z późn. zm.), z wnioskiem o wszczęcie postępowania mediacyjnego w sprawie polubownego zakończenia sporu między Konsumentem a Sprzedawcą. <br>
c. Konsument może uzyskać bezpłatną pomoc w sprawie rozstrzygnięcia sporu między nim a Sprzedawcą, korzystając także z bezpłatnej pomocy powiatowego (miejskiego) rzecznika konsumentów lub organizacji społecznej, do której zadań statutowych należy ochrona konsumentów (m.in. Federacja Konsumentów, Stowarzyszenie Konsumentów Polskich).<br>
</p>


<h5>§ 13</h5>
<h2>Dane osobowe w Sklepie internetowym</h2>

<p>
    1. Administratorem danych osobowych Klientów zbieranych za pośrednictwem Sklepu internetowego jest Sprzedawca.<br>
2. Dane osobowe Klientów zbierane przez administratora za pośrednictwem Sklepu internetowego zbierane są w celu realizacji Umowy Sprzedaży, a jeżeli Klient wyrazi na to zgodę - także w celu marketingowym.<br>
3. Odbiorcami danych osobowych Klientów Sklepu internetowego mogą być: <br>
a. W przypadku Klienta, który korzysta w Sklepie internetowym ze sposobu dostawy przesyłką pocztową lub przesyłką kurierską, Administrator udostępnia zebrane dane osobowe Klienta wybranemu przewoźnikowi lub pośrednikowi realizującemu przesyłki na zlecenie Administratora.<br>
b. W przypadku Klienta, który korzysta w Sklepie internetowym ze sposobu płatności elektronicznych lub kartą płatniczą Administrator udostępnia zebrane dane osobowe Klienta, wybranemu podmiotowi obsługującemu powyższe płatności w Sklepie internetowym. <br>
4. Klient ma prawo dostępu do treści swoich danych oraz ich poprawiania.<br>
5. Podanie danych osobowych jest dobrowolne, aczkolwiek niepodanie wskazanych w Regulaminie danych osobowych niezbędnych do zawarcia Umowy Sprzedaży skutkuje brakiem możliwości zawarcia tejże umowy. <br>
</p>


<h5>§ 11</h5>
<h2>Postanowienia końcowe</h2>

<p>
    1. Umowy zawierane poprzez Sklep internetowy zawierane są w języku polskim.<br>
2. Sprzedawca zastrzega sobie prawo do dokonywania zmian Regulaminu z ważnych przyczyn to jest: zmiany przepisów prawa, zmiany sposobów płatności i dostaw - w zakresie, w jakim te zmiany wpływają na realizację postanowień niniejszego Regulaminu. O każdej zmianie Sprzedawca poinformuje Klienta z co najmniej 7 dniowym wyprzedzeniem.<br>
3. W sprawach nieuregulowanych w niniejszym Regulaminie mają zastosowanie powszechnie obowiązujące przepisy prawa polskiego, w szczególności: Kodeksu cywilnego; ustawy o świadczeniu usług drogą elektroniczną; ustawy o prawach konsumenta, ustawy o ochronie danych osobowych. <br>
4. Klient ma prawo skorzystać z pozasądowych sposobów rozpatrywania reklamacji i dochodzenia roszczeń. W tym celu może złożyć skargę za pośrednictwem unijnej platformy internetowej ODR dostępnej pod adresem: http://ec.europa.eu/consumers/odr/.<br>
</p>

<br><br><br><br><br><br>


</div>
</div>
</div>




@stop