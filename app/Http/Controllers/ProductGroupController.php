<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

class ProductGroupController extends Controller
{
    use \App\Traits\GridTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Request::ajax()){
            return $this->gridAction('\App\ProductGroup');
        }else{
            return view('admin.product_group.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.product_group.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        if(isset($data['id']))
            $product_group = \App\ProductGroup::find((int)$data['id']);
        else
            $product_group = new \App\ProductGroup();
        if(isset($data['name']))
            $product_group->name = $data['name'];
        if(isset($data['description']))
            $product_group->description = $data['description'];
        if(isset($data['promotion_percentage']))
            $product_group->promotion_percentage = $data['promotion_percentage'];
        if(isset($data['promotion_start_date']))
            $product_group->promotion_start_date = $data['promotion_start_date'];
        if(isset($data['promotion_end_date']))
            $product_group->promotion_end_date = $data['promotion_end_date'];

        if(isset($data['promotion_once']) && $data['promotion_once'])
            $product_group->promotion_once = true;
        else
            $product_group->promotion_once = false;
        if(isset($data['active']) && $data['active'])
            $product_group->active = true;
        else
            $product_group->active = false;

        // podgraj obrazek
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
                Input::file('item_image')->move(storage_path('shop') . '/product_groups/', $file_name);
                $product_group->image_path = $file_name;
            }
        }

        $product_group->save();

        // usuwam wszystkie itemy z tej grupy przed dodaniem nowych
        \App\ProductGroupItem::where('group_id', $product_group->id)->delete();
        // teraz szukamy itemow do podpiecia do grupy item_ides
        if(isset($data['item_ides'])){
            $items = \App\Item::whereIn('id', explode(',', $data['item_ides']))->active()->get();
            foreach($items as $item){
                // ustaw discounted_price dodawanemu do grupy itemowi
                $item->price_discounted = round($item->price * (100-$product_group->promotion_percentage)/100);
                $item->save();
                // dodaj item do grupy
                $gropup_item = new \App\ProductGroupItem();
                $gropup_item->item_id = $item->id;
                $gropup_item->group_id = $product_group->id;
                $gropup_item->save();
            }
        }
        return \Redirect::to('/admin/product-groups');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $seo_text=null, $page=0, $take=10, $params=null)
    {
        $product_group = \App\ProductGroup::find($id);
        if(empty($product_group))
            abort(404);
        
        $items = $product_group->items()->active();
        $all_items_count = $items->get()->count();
        
        if(empty($items))
            abort(404);
        
        $page_max = round($all_items_count/$take,0);
        if($page > $page_max){
            $page = $page_max;
        }
        $url = url('kategorie/'.config('shop.promotion_page_prefix'));
        $itemsy = view('shop.mimity.item_flow_main')
                ->withItems($items->get())
                ->withCategory_name($product_group->name)
                ->withPagination(\App\Functions::getSimplePaginationHtml($url, $page, $page_max, $take))->render();

        $view = view('shop.mimity.left_side_categories_and_bestsellers')
                ->withItemsy($itemsy)
                ->withCategory_id(null)
                ->withSchema_url('xxx')
                ->withUrl(url('/'.config('shop.promotion_page_prefix').'/'.$page.'/'.$take))
                ->withPage_title( ' | Armatura łazienkowa')
                ->withSchow_categories(false);

        if($params)
            $view->withSearch_params($search_params);

        return $view;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // itemy z tej grupy
        $items = \App\Item::query()->selectRaw('items.*, product_group_items.active as group_item_active')
                        ->rightJoin('product_group_items', 'product_group_items.item_id', '=', 'items.id')
                        ->where('product_group_items.group_id', $id)->get();
        // dd($items->pluck('id')->all());
        return view('admin.product_group.edit')
                ->withObj(\App\ProductGroup::find($id))
                ->withItems($items);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
