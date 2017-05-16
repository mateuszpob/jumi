<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(){
        return view('admin.index');
    }

    /*
    * Opisy z ceesfałki
    */
    public function getDescriptionFromCsv(){
        set_time_limit(0);
        header('Content-Type: text/html; charset=UTF-8');
        echo 'elo mordo</br>';
        $desc = [];
        if (($handle = fopen(storage_path('shop/docs/novoterm.csv'), "r")) !== FALSE) {
            for($ii=0 ; $ii < 20 ; $ii++)
                fgetcsv($handle, 1000, ",");
            
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $desc[$data[1]] = $data[2];
            }
            fclose($handle);
        }
        $items = \App\Item::where('producer_id', 1)->get();
        foreach($items as $item){
            if(isset($desc[$item->ean])){
                $item->description = $desc[$item->ean];
            }else{
                $item->description = '';
            }
            $item->save();
        }
    }

    public function getDuschyPriceFromCsv(){
        $vat = 1;


        set_time_limit(0);
        header('Content-Type: text/html; charset=UTF-8');
        echo 'elo mordo</br>';
        $price = [];
        if (($handle = fopen(storage_path('shop/docs/duschy.csv'), "r")) !== FALSE) {
            for($ii=0 ; $ii < 20 ; $ii++)
                fgetcsv($handle, 1000, ",");
            $i=0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // echo '<pre>'.print_r(explode(PHP_EOL, $data[4]), 1).' '.print_r(explode(PHP_EOL, $data[5]), 1).'</pre>';
                $ean_count = count(explode(PHP_EOL, $data[4]));
                $j=0;

                foreach(explode(PHP_EOL, $data[1]) as $r1){
                    $code = explode(' ', $r1)[0];
                    preg_match('/[0-9]{13}/', $r1, $ean);
                    $ean = isset($ean[0]) ? $ean[0] : 'huj';
                    $price = explode(PHP_EOL, $data[6])[0];
echo $code . ' ';
                    $item = \App\Item::where('code', $code)->first();
                    if($item){
                        $item->ean = $ean;
                        $item->price_producer = (double)$price;
                        $item->save();
                        echo $item->id . ' ';
                    }
                echo '</br>';
                }
            }
        }
    }
    /*
     * Ustawia price_producer da itemow od 'dubielvitrum' 5905241905242
     */
    public function getDubielPriceFromCSV(){
        $vat = 1;


        set_time_limit(0);
        header('Content-Type: text/html; charset=UTF-8');
        echo 'elo mordo</br>';
        $price = [];
        if (($handle = fopen(storage_path('shop/docs/dv.csv'), "r")) !== FALSE) {
            for($ii=0 ; $ii < 20 ; $ii++)
                fgetcsv($handle, 1000, ",");
            $i=0;
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                // echo '<pre>'.print_r(explode(PHP_EOL, $data[4]), 1).' '.print_r(explode(PHP_EOL, $data[5]), 1).'</pre>';
                $ean_count = count(explode(PHP_EOL, $data[4]));
                $j=0;
                foreach(explode(PHP_EOL, $data[4]) as $r1){
                    //echo $r1 . '</br>';
                    $p = explode(PHP_EOL, $data[7]);
                    if(count($p)==1){
                        preg_match('/[0-9]{13}/', $r1, $pk);
                        if(isset($pk[0]) && $prc = (float)str_replace(',', '.', $p[0]))
                            $price[$pk[0]] = $prc*$vat;

                    }elseif($ean_count == count($p)){
                        // $price[$i] = '11111111111111111111111111111111111111';
                        preg_match('/[0-9]{13}/', $r1, $pk);
                        if(isset($pk[$j])){
                            preg_match('/[0-9]{1-5},/', $p[0], $pv);
                            if(isset($pv[0]) && $prc = (float)str_replace(',', '.', $pv[0]))
                                $price[$pk[$j]+''] = $prc*$vat;
                        }
                    }
                $j++;
                }
                //echo print_r(explode(PHP_EOL, $data[5]), 1).'</pre> </br>';


                $i++;
            }
            fclose($handle);
        }
        // dd($price, count($price));
        $i=0;
        foreach($price as $k=>$v){
            // $item = \App\Item::where('ean', $k)->first(); dd($item);
            // if($item){
            //     $item->price_producer = $k;
            //     $i++;
            // }


            $item = \App\ProductVariant::where('ean', $k)->first(); //dd($item);
            if($item){
                $item->price_producer = $v;
                $item->save();
                $i++;
            }
        }
        dd($i);
    }

    /*
     * Tworzy adresy url na podstawie nazwy dla kategorii
     */
    public function createUrlForCategories(){
        $categories = \App\Category::all();
        $i=1;
        foreach($categories as $c){
            $name = \App\Functions::createUrlFromString($c->name);
            echo $i .' => '.$c->name.' ==== '.$name.'</br>';
            $c->url_name = $name;
            $c->save();
        $i++;
        }
    }
    /*
     * wywala zle znaki z urla przedmiotu i nazwy
     */
    public function changeBad(){
        $category = \App\Category::all();
        // dd($category);
        foreach($category as $c){
            $c->url_name = str_replace(' ', '-', \App\Functions::changeBadCharacters(strtolower($c->name)));
            $c->name = \App\Functions::changeBadCharactersPl($c->name);
            $c->save();
        }
        $items = \App\Item::all();
        foreach($items as $item){
            $item->name = \App\Functions::changeBadCharactersPl($item->name);
            $item->save();
        }
    }
    /*
     * Popup do edycji itemu
     */
    public function getItemEditPopup() { 
//        $item_id = \Request::get('item_id');
//        $item = \App\Item::find($item_id);
//        if($item){
//            $v = view('popups.admin_edit')
//                    ->withItem($item)
//                    ->withPopup_width(1024)
//                    ->render();
//            return ['Success'=>true, 'view'=>$v];
//        }
//        return ['Success'=>false];
        
        
        
        $id = \Request::get('item_id');
        
        $item = \App\Item::find($id); //dd($item);
        $v = view('admin.item.edit')->withTpl('popups.admin_edit')
            ->withObj($item)
            ->withVariants(\App\ProductVariant::where('item_id', $id)->active()->get())
//                ->withRedirect_after_edit(false)
            ->withPopup_width(1024);
        
        if($item->ext_desc)
            $v->withExt_description(json_decode($item->ext_desc));
        
        return ['Success'=>true, 'html'=>$v->render()];
       
    }
    
}
