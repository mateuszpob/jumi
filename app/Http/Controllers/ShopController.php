<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShopController extends Controller
{
    
    private $categories = [];
    
    

    /*
     * Wyświetla wszystkie itemy z danej kategorii, we wskazany sposob;
     */
    public function itemsOfCategory($schema_url, $category_name_link=null, $page=0, $take=10, $params=null){

        /*
         * @TODO dorobic zeby nie mozna bylo za duzo na raz itemow i page dobre itp.
         */

        if($category_name_link == null){
            return $this->index($schema_url);
        }

        $schema = \App\SchemaCategories::active()->where('url', $schema_url)->first();
        
        // nie ma schematu?
        if(empty($schema)){
            abort(404);
        }

        $c = \App\Category::where('schema_id', $schema->id)->where('url_name', $category_name_link)->first();
        $category_name = $c->name;
        $this->categories[] = $c;
        $this->getChildren($c);

        $category_ides = [];
        foreach($this->categories as $cat)
            $category_ides[] = $cat->id;

        if(!empty($category_ides)){
            if($params){
                $search_params = json_decode(urldecode($params));
                $items = $c->items()->active();
                    if(isset($search_params->prods))
                        $items->whereIn('producer_id', $search_params->prods);
                    if(isset($search_params->pmin))
                        $items->where('price', '>=', $search_params->pmin);
                    if(isset($search_params->pmax))
                        $items->where('price', '<=', $search_params->pmax);
                    $all_items_count = $items->count();
                    $items->skip($page*$take)->take($take);
            }else{
                // pobieraj itemy takze z kategorii podrzednych
                $items = \App\Item::active()
                ->leftJoin('category_item', 'category_item.item_id', '=', 'items.id')
                ->whereIn('category_item.category_id', $category_ides);
              
                $all_items_count = $items->count();
                $items = $items->skip($page*$take)->take($take);
            
//            $items = $items->take($take);
            $page_max = round($all_items_count/$take,0);
            if($page > $page_max)
                $page = $page_max;

            }
            $url = url('kategorie/'.$schema_url.'/'.$category_name_link);
            $itemsy = view('shop.mimity.item_flow_main')
                    ->withItems($items->get())
                    ->withCategory_name($category_name)
                    ->withPagination(\App\Functions::getSimplePaginationHtml($url, $page, $page_max, $take))->render();

            $view = view('shop.mimity.left_side_categories_and_bestsellers')
                    ->withItemsy($itemsy)
                    ->withCategory_id($c->id)
                    ->withSchema_url($schema_url)
                    ->withUrl(url($schema_url .'/'.$category_name_link.'/'.$page.'/'.$take))
                    ->withPage_title($c->name . ' | Artykuły sanitarne');
            
            if($params)
                $view->withSearch_params($search_params);
            
            return $view;
        }
        // Nie ma tekiej kategori.
        return abort(404);
        
    }
    /**
     * Wyswietl ...
     * @return Response
     */
    public function index($schema_url){

        \App\User::registerActivity();// zapisz usera jaki wszedl, skąd, kto to, do pliku

        $schema = \App\SchemaCategories::active()->where('url', $schema_url)->first();
        // nie ma schematu?
        if(empty($schema)){
            abort(404);
        }
        
        $take = \App\Item::active()->where('schema_id', (int)$schema->id)->count();
        if($take < 1) // jesli schema nei ma itemow
            abort(404);
        if($take > 10) 
            $take = 10;
        $itemsy = view('shop.mimity.item_flow_main')
                ->withItems(\App\Item::active()->where('schema_id', (int)$schema->id)->get()->random($take))->render();
        return view('shop.mimity.left_side_categories_and_bestsellers')
                    ->withItemsy($itemsy)
                    ->withSchema_url($schema->url)
                    ->withUrl(url($schema->id))
                    ->withPage_title('Polecane produkty | Artykuły sanitarne')
                    ->withPage_description('Stylowe kolekcje artykułów sanitarnych. Przedmity objęte promocją to zbiór naszych najlepiej sprzedających się produktów różnych producentów.');
    }
    public function searchWithOption(){
        $item_data = \Request::all();
        dd(\Input::get('search_producer'));
    }
    public function producenci($producent=null){
        if(!$producent)
            return view('shop.mimity.producers')
                        ->withFeatured_item(\App\Item::active()->get()->random(1));
        
    }
    /*
     * Wyświetla wszystkie itemy adnego producenty;
     */
    public function itemsOfProducer($category_name_link, $page=0, $take=10){
        /*
         * @TODO dorobic zeby nie mozna bylo za duzo na raz itemow i page dobre itp.
         */
        $category_name = str_replace('-', ' ', $category_name_link);
        $c = \App\Category::whereRaw("lower(name) = '" . $category_name . "'")->first();
   

        if($c){
            $all_items_count = $c->items()->active()->count();
            $items = $c->items()->active()->skip($page*$take)->take($take)->get();
            
            $page_max = round($all_items_count/$take,0);
            if($page > $page_max)
                $page = $page_max;

            $url = url('kategorie/'.$category_name_link);
            
            return view('shop.mimity.item_flow_main')
                    ->withItems($items)
                    ->withCategory_name($category_name)
                    ->withPagination(\App\Functions::getSimplePaginationHtml($url, $page, $page_max, $take));
            
        }
        // Nie ma takiego producenta.
        return abort(404);
        
    }
    
    public function searchWithOptions(){
        return $this->itemsOfCategory();
    }
    

    private function getChildren($category){
        $tmp = $category->getChildren()->all();
        foreach($tmp as $t){
            $this->categories[] = ($t);

            $this->getChildren($t);
        }
        
    }


    /*
     * Szukajka
     */
    public function searchItem($search, $page=0, $take=10){
        // $search = \Request::get('search');
        $items = \App\Item::whereRaw("(lower(name) like '%".strtolower($search)."%' OR lower(code) like '%".strtolower($search)."%' OR lower(ean) like '%".strtolower($search)."%' OR lower(description) like '%".strtolower($search)."%') AND active is TRUE AND schema_id = ".config('shop.default_schema_id'));
        // dd($items->get());
        $item_count = $items->count();

        $page_max = round($item_count/$take,0);
        if($page > $page_max){
            $page = $page_max;
        }
        $items->skip($page*$take)->take($take);

        $url = url('search/'. $search);

        $itemsy = view('shop.mimity.item_flow_main')->withItems($items->get())
                    ->withCategory_name('Szukana fraza: '.$search)
                    ->withPagination(\App\Functions::getSimplePaginationHtml($url, $page, $page_max, $take))->render();

        $view = view('shop.mimity.left_side_categories_and_bestsellers')
                    ->withItemsy($itemsy)
                    ->withCategory_id(0)
                    ->withSchema_id(1)
                    ->withSchema_url('12s')
                    ->withSchow_categories(false)
                    ->withUrl($url.'/'.$page.'/'.$take);

        return $view;

    }
    

    
}
