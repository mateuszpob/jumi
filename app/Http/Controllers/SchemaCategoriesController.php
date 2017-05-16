<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SchemaCategoriesController extends Controller
{
    use \App\Traits\GridTrait;
    
    protected function gridActionSchema($model){
            $table_data = \Request::all();
            
            $count = $model::query()->count();            
            $search = $model::query();
            if(!empty($table_data['search']['value']))
                $search->searchGrid($table_data['search']['value']);
            $user = \Auth::user();
            if(!empty($user) && !$user->isAdmin()){
                $search->where('owner_id', $user->id);
            }
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
    
    public function index()
    {
        if (\Request::ajax()){
            return $this->gridActionSchema('\App\SchemaCategories');
        }else{
            return view('admin.schema.index');
        }
    }
    
    public function create()
    {
        return view('admin.schema.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $item_data = \Request::all();
        $item = \App\SchemaCategories::findOrNew(\Input::get('id'));
//        dd($item_data);
        if(isset($item_data['name'])){
            $item->name = $item_data['name'];
        }
        if(isset($item_data['description'])){
            $item->description = $item_data['description'];
        }
        if(isset($item_data['url'])){
            $item->url = $item_data['url'];
        }
        if  (isset($item_data['active']) && $item_data['active'] === "1"){
            $item->active = true;
        }else{
            $item->active = false;
        }
        $item->owner_id = \Auth::id();
        
        $item->save();

        return \Redirect::to('admin/schemas');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin.schema.add');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        
        return view('admin.schema.edit')->withObj(\App\SchemaCategories::find($id));
    }

}
