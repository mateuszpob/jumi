<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ShipmentController extends Controller
{
    use \App\Traits\GridTrait;
    
    protected function gridAction($model){
            $table_data = \Request::all();
            
            
            $sql = ", (SELECT count(id) FROM items WHERE active IS TRUE) AS active_items";
            
            $count = $model::query()->count();            
            $search = $model::query()->selectRaw('shipments.id, shipments.name, shipments.description, shipments.price, shipments.price_percentage, count(i1.id) as items_count')
                    ->leftJoin('items as i1', 'i1.shipment_id', '=', 'shipments.id')
//                    ->leftJoin('items as i2', 'i2.shipment_id', '=', 'shipments.id')
                    ->where('i1.active', true)
//                    ->where('i2.active', true)
                    ->groupBy('shipments.id')
                    ->groupBy('shipments.name')
                    ->groupBy('shipments.description')
                    ->groupBy('shipments.price')
                    ->groupBy('shipments.price_percentage');
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
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (\Request::ajax()){
            return $this->gridAction('\App\Shipment');
        }else{
            return view('admin.shipment.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.shipment.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $shipment_data = \Request::all();
      
        $id = isset($shipment_data['id']) ? $shipment_data['id'] : null; 
        $shipment = \App\Shipment::findOrNew($id);
        
        if(isset($shipment_data['name'])){
            $shipment->name = $shipment_data['name'];
        }
        if(isset($shipment_data['description'])){
            $shipment->description = $shipment_data['description'];
        }
        if(isset($shipment_data['price']) && (float)$shipment_data['price'] > 0){
            $shipment->price = $shipment_data['price'];
        }else{
            $shipment->price = null;
        }
        if(isset($shipment_data['price_percentage']) && (int)$shipment_data['price_percentage'] > 0){
            $shipment->price_percentage = $shipment_data['price_percentage'];
        }else{
            $shipment->price_percentage = null;
        }
        if(isset($shipment_data['max_quantity']) && (int)$shipment_data['max_quantity'] > 0){
            $shipment->max_quantity = $shipment_data['max_quantity'];
        }else{
            $shipment->max_quantity = null;
        }
        if(isset($shipment_data['active']) && $shipment_data['active'] ){
            $shipment->active = true;
        }else{
            $shipment->active = false;
        }
        if(isset($shipment_data['quantity_multiplication']) && $shipment_data['quantity_multiplication'] ){
            $shipment->quantity_multiplication = true;
        }else{
            $shipment->quantity_multiplication = false;
        }
//        dd($shipment);
        $shipment->save();
        
        return \Redirect::route('admin.shipment.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return view('admin.shipment.show')->withObj(\App\Shipment::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        return view('admin.shipment.edit')->withObj(\App\Shipment::find($id));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $s = \App\Shipment::find($id);
        $s->delete();
        return \Redirect::back();
    }
}
