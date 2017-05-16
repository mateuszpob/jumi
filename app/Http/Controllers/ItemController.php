<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

class ItemController extends Controller
{
    use \App\Traits\GridTrait;
    /**
     * Display a listing of the items in ADMIN page.
     *
     * @return Response
     */
    public function index()
    {
        if (\Request::ajax()){
            return $this->gridActionItem('\App\Item');
        }else{
            return view('admin.item.index');
        }
    }
    
    protected function gridActionItem($model, $date = ''){
        $table_data = \Request::all();
            
            $count = $model::query()->count();            
            $search = $model::query()
                    ->selectRaw('items.id as id, items.name as name, items.description as description, categories.name as category, items.price as price, items.image_path as image_path')
                    ->leftJoin('category_item', 'category_item.item_id', '=', 'items.id')
                    ->leftJoin('categories', 'categories.id', '=', 'category_item.category_id');
            
            // pokazuj tylko itemy ze schematu ktory nalezy do tego usera, hyba ze to admin to pokazuj wszystko
            if(! \Auth::user()->isAdmin())
                $search->whereIn('items.schema_id', \Auth::user()->getSchemas()->get()->pluck('id')->all());

            if(!empty($table_data['search']['value']))
                $search->searchGrid($table_data['search']['value']);
            
            $data = array(
                "draw"            => $table_data['draw'],
                "recordsTotal"    => $count,
                "recordsFiltered" => $search->count(),
                "data"            => null
            );
            //order after getting count
            foreach($table_data['order'] as $order){
                $search->orderBy($table_data['columns'][$order['column']]['data'], $order['dir']);
            }
            $data['data']=$search->take($table_data['length'])->offset($table_data['start'])->get();
            
            return $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        
        return view('admin.item.edit')->withObj((object)null);
    }
    
    public function storeItemImage(Request $request){
//        dd(Input::file('file'), $request->all());
        if (Input::file('file') && Input::file('file')->isValid()) {

            $mime_type = Input::file('file')->getMimeType();

            switch($mime_type){
                case 'image/jpeg':
                    $file_name = md5(time()) . '.jpg';
                    break;
                case 'image/png':
                    $file_name = md5(time()) . '.png';
                    break;
                default:
                    $file_name = null;
            }

            if($file_name){
                Input::file('file')->move(storage_path('shop/ext_desc_images/') . '/' . date('Y-m-d'), $file_name);
                return ['Success'=>1, 'img_path' => '/image/ext_desc_images/'.date('Y-m-d') . '/' . $file_name];
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {

        $item_data = $request->all();
        
        $redirect_after_edit = true;
        if(!empty($item_data['redirect_after_edit']))
                $redirect_after_edit = (boolean)$item_data['redirect_after_edit'];
        
        $item = \App\Item::findOrNew(\Input::get('id'));
        
        // spr czy edytujesz swoj item, jak nie to spierdalaj
        // admin nie wstawia shcema id
        if(!\Auth::user()->isAdmin()){
            $current_user_schema_id = \Auth::user()->getSchemas()->first()->id;
            if($item->schema_id != null && $current_user_schema_id != $item->schema_id)
                return \Redirect::route('admin.items.index');
            $item->schema_id = $current_user_schema_id;
        }
        
//        $validator = \Validator::make($item_data, [
//                    'name' => 'required|max:32',
//                    'description' => 'required',
//                    'price' => 'required|max:32',
//        ]);
        
//        $request->flash();
        // Wróć do formulaża i pokaż błędy, jeśli są błedy
//        if ($validator->fails()) {
//            return redirect(redirect()->getUrlGenerator()->previous() )
//                            ->withErrors($validator)
//                            ->withInput();
//        }
        if(isset($item_data['shipment_id']) && $item_data['shipment_id']){
            $item->shipment_id = (int)$item_data['shipment_id'];
        }
        
        if(isset($item_data['name']) && $item_data['name']){
            $item->name = $item_data['name'];
        }
        if(isset($item_data['description']) && $item_data['description']){
            $item->description = $item_data['description'];
        }
        if(isset($item_data['price']) && $item_data['price']){
            $item->price = (float)str_replace(',', '.', ""+$item_data['price']);
        }else{
            $item->price = null;
        }
        if(isset($item_data['ean']) && $item_data['ean']){
            $item->ean = $item_data['ean'];
        }else{
            $item->ean = null;
        }
        if(isset($item_data['price_producer']) && $item_data['price_producer']){
            $item->price_producer = (float)str_replace(',', '.', ""+$item_data['price_producer']);
        }//dd($item_data['active']);
        if(isset($item_data['active']) && $item_data['active'] ){
            $item->active = true;
        }else{
            $item->active = false;
        }
        if(isset($item_data['count']) && $item_data['count']){
            $item->count = $item_data['count'];
        }
        if(isset($item_data['weight']) && $item_data['weight']){
            $item->weight = $item_data['weight'];
        }
        $item->save();
        
        if(count(\Input::get('category_id')) > 0){
            $item->categories()->detach();
            foreach(\Input::get('category_id') as $c)
                if(\App\Category::find($c))
                    $item->categories()->attach($c);
        }
        
        // dodajemy obrazek
        if (Input::file('item_image') && Input::file('item_image')->isValid()) {

            $mime_type = Input::file('item_image')->getMimeType();

            switch($mime_type){
                case 'image/jpeg':
                    $file_name = md5(time()) . '.jpg';
                    break;
                case 'image/png':
                    $file_name = md5(time()) . '.png';
                    break;
                default:
                    $file_name = null;
            }

            if($file_name){
                Input::file('item_image')->move(storage_path('shop') . '/' . date('Y-m-d'), $file_name);
                $item->image_path = date('Y-m-d') . '/' . $file_name;
            }
        }
        // usuwamy wszystkie warianty zeby zaraz zapisac nowe
        $item->variants()->delete();
        // dodajemy warianty produktu 

        if(isset($item_data['variants'])){
            $variants = json_decode($item_data['variants']);
            foreach($variants as $v){//dd($v);
                if(isset($v->data) && !empty((array)$v->data)){
                    $variant = new \App\ProductVariant();
                    $variant->item_id = $item->id;
                    if(isset($v->ean))
                        $variant->ean = $v->ean;
                    else
                        $variant->ean = $item->ean;

                    if(isset($v->price))
                        $variant->price = (float)str_replace(',', '.', ""+$v->price);
                    else
                        $variant->price = $item->price;

                    if(isset($v->price_producer))
                        $variant->price_producer = (float)str_replace(',', '.', ""+$v->price_producer);
                    else
                        $variant->price_producer = $item->price_producer;

                    if(isset($v->name))
                        $variant->name = $v->name;

                    $variant->data = json_encode($v->data);

                    $variant->save();
                }
            }
        }
        // zdżejsonowane rozszerzone opisy
        $item->ext_desc = $item_data['desc_values'];


        $saved = $item->save();
//        
//        if($redirect_after_edit)
//            return \Redirect::to('/admin/items');
//        
//        
            //return $item;
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin.item.add');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id){
        $item = \App\Item::find($id);
        $v = view('admin.item.edit')
            ->withObj($item)
            ->withVariants(\App\ProductVariant::where('item_id', $id)->active()->get());
        
        if($item->ext_desc)
            $v->withExt_description(json_decode($item->ext_desc));
        return $v;
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function remove($id)
    {
        $c = \App\Item::find($id);
        $c->delete();
        return \Redirect::back();
    }
    


    

    public function manufacturers(){
        if (\Request::ajax()){
            return $this->gridAction('\App\Producer');
        }else{
            return view('admin.item.manufacturers');
        }
    }
    public function createManufacture()
    {
        return view('admin.item.create_manufacture');
    }
    public function editManufacture($id){
        return view('admin.item.edit_manufacture')->withObj(\App\Producer::find($id));
    }
    public function storeManufacture(){
        $item_data = \Request::all();
        $item = \App\Producer::findOrNew(\Input::get('id'));
        
        if(isset($item_data['name'])){
            $item->name = $item_data['name'];
        }
        if(isset($item_data['active'])){
            $item->active = $item_data['active'];
        }
        
        if (Input::file('item_image') && Input::file('item_image')->isValid()) {

            $mime_type = Input::file('item_image')->getMimeType();

            switch($mime_type){
                case 'image/jpeg':
                    $file_name = md5(time()) . '.jpeg';
                    break;
                case 'image/png':
                    $file_name = md5(time()) . '.png';
                    break;
                default:
                    $file_name = null;
            }

            if($file_name){
                Input::file('item_image')->move(storage_path('shop') . '/' . date('Y-m-d'), $file_name);
                $item->image_path = date('Y-m-d') . '/' . $file_name;
            }
        }

        $item->save();

        return \Redirect::action('ItemController@manufacturers');
    }


    /*
     * Edycja itemu poprzez strone z itemami (od strony usera)
     */
    public function saveEditedItem(){
        // sprawdz czy ten koles może edytować itemy
        if(!\Auth::check() || !\Auth::user()->hasEditPermissions())
            return ['Success'=>false];

        $item_data = \Request::all();
        $item = \App\Item::find($item_data['item_id']);
        if($item){
            $item->name = $item_data['item_name'];
            $item->description = $item_data['item_description'];
            $item->save();
            return ['Success'=>true];
        }
        return ['Success'=>false];
    }

    /*
     * Zwraca item o podanym id (do ajaxa)
     */
    public function getItemData($item_id = null){
        if(!$item_id)
            $item_id = \Request::get('item_id');
        $item = \App\Item::find($item_id);
        if($item){
            return ['Success'=>true, 'item'=>$item];
        }
        return ['Success'=>false];
    }

    /////////////////////////////////////////
    /////// FOR SHOP PAGE //////////////////
    ///////////////////////////////////////
    /*
    Strona produktu 
    */
    public function details($id, $seo_text=null){
        $item = \App\Item::active()->find($id);
        if(!empty($item->schema_id))
            $sch = \App\SchemaCategories::active()->find($item->schema_id);
        
        if( !$item || (empty($sch->active) || !$sch->active) )
            abort(404);
        
        return view('shop.mimity.item_details')
                ->withItem($item)
                ->withShow_search_options(false)
                ->withPage_title($item->getSeoPageTitle())
                ->withPage_description($item->getSeoPageDescription());
    }



}
