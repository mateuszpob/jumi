<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

class CategoryController extends Controller
{
    use \App\Traits\GridTrait;
    
    protected function gridActionCategory($model){
            $table_data = \Request::all();
            
            $count = \App\Category::query()->count();            
            // $search = $model::query()->select;
            // if(!empty($table_data['search']['value']))
            //     $search->searchGrid($table_data['search']['value']);


            $search = \App\Category::query()->selectRaw('count(case when items.active is true then 1 else null end) as item_count, categories.id_upper, categories.name, categories.id, categories.description')
            ->leftJoin('category_item', 'category_item.category_id', '=', 'categories.id')
                    ->leftJoin('items', 'category_item.item_id', '=', 'items.id');
            
            if(! \Auth::user()->isAdmin())
                $search->whereIn('categories.schema_id', \Auth::user()->getSchemas()->get()->pluck('id')->all());
            
            foreach($table_data['order'] as $order){
                $search->orderBy('categories.'.$table_data['columns'][$order['column']]['data'], $order['dir']);
            }
            $search->groupBy('categories.name')->groupBy('categories.id')->groupBy('categories.description');

            $data = array(
                "draw"            => $table_data['draw'],
                "recordsTotal"    => $count,
                "recordsFiltered" => $search->count(),
                "data"            => null
            );

            $data['data']=$search->take($table_data['length'])->offset($table_data['start'])->get();

            return $data;
    }
    public function index()
    {
            
        if (\Request::ajax()){
            return $this->gridActionCategory('\App\Category');
        }else{
            return view('admin.category.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.category.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        
        $category_data = \Request::all();
//        dd($category_data);
        $id = isset($category_data['id']) ? $category_data['id'] : null; 
        $category = \App\Category::findOrNew($id);
        
        // spr czy edytujesz swoj item, jak nie to spierdalaj
        // admin nie wstawia shcema id
        if(!\Auth::user()->isAdmin()){
            $current_user_schema_id = \Auth::user()->getSchemas()->first()->id;
            if($category->schema_id != null && $current_user_schema_id != $category->schema_id)
                return \Redirect::route('admin.categories.index');
            $category->schema_id = $current_user_schema_id;
        }
        
        
        
        if(isset($category_data['name'])){
            $category->name = $category_data['name'];
        }
        if(isset($category_data['description'])){
            $category->description = $category_data['description'];
        }
        if(isset($category_data['id_upper']) && $category_data['id_upper'] > 0){
            if($category_data['id_upper'] == $id)
                return 'Error. This is the same.';
            
            if(\App\Category::find($category_data['id_upper'] ))
                $category->id_upper = $category_data['id_upper'];
        }else{
            $category->id_upper = null;
        }
        if(isset($category_data['active']) && $category_data['active'] ){
            $category->active = true;
        }else{
            $category->active = false;
        }
        if(isset($category_data['on_main_page']) && $category_data['on_main_page']){
            $category->on_main_page = true;
        }else{
            $category->on_main_page = false;
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
        $category->save();
        
        return \Redirect::route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.category.edit')->withObj(\App\Category::find($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function remove($id)
    {
        $c = \App\Category::find($id);
        $c->delete();
        return \Redirect::back();
    }
}
