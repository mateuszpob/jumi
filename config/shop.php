<?php 

return [
    'products_in_row' => 4, // not use
    'buy_not_logged' => true, //not logged user may buy items,
    'product_on_page_list' => 20, // ile produktow na stronie, na szerokiej liście
    
    
    
    
    
    
    
    
    // www.xxxx.pl/shop_link_prefix/nazwa-kategorii
    'shop_link_prefix' => 'artykuly-sanitarne',
    'item_page_prefix' => 'produkt',
    'page_prefix' => 'mojachata',
    'promotion_page_prefix' => 'promocje',
    'default_schema_id' => 1,
    
    
    
    
    
    // MAILE
    
    'mail_from' => 'dupa@dupa.pl',
    'mail_from_name' => 'Ziutek',
    
    
    'order_confirmed_mail' => 'mateuszpob@gmail.com',
    'vat' => 23,
    
    // PayU
    'payu_merchant_pos_id' => env('PAYU_MERCHANT_ID', '223499'),
    'payu_key_2' => env('PAYU_KEY_2', '211b0a886415b92c10228e4d67c1b737'),
    'payu_url' => env('PAYU_URL', 'https://secure.payu.com/api/v2_1/orders'),
    
    
    'page_title' => 'Artykuły sanitarne | Lustra na wymiar | Stylowe wyposażenie wnętrz - MojaChata',
    'page_description' => 'Artykuły sanitarne najwyższej jakości w przystępnej cenie. Lustra na wymiar w różnych wariantach oraz meble łazienkowe. Gwarantowana możliwość montażu w Warszawie. Montaż na terenie całej Polski po uprzednim uzgodnieniu. Wszystkie nasze produkty są fabrycznie nowe, wysyłane bezpośrednio od producenta.',

    
       'uibooster' => env('UIBOOSTER', '')
    
    ];