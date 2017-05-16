<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AirtsController extends Controller {
    
        use \App\Traits\GridTrait;

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		if (\Request::ajax()){
                    return $this->gridAction('\App\Airts');
                }else{
                    return view('admin.airts.index');
                }
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
            return view('admin.airts.edit');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(\App\Http\Requests\AirtsRequest $request){
            if($request->has('id')){
                $item = \App\Airts::find($request->id);
            }else{
                $item = new \App\Airts();
            }
            $item->source = $request->source;
            $needle = $request->getSchemeAndHttpHost();
            $pos = stripos($request->target, $needle);
            if($pos!==false){
                //dd($pos);
                $item->target = mb_substr($request->target, mb_strlen($needle));
            }else{
                $item->target = $request->target;
            }
            $item->save();
            return \Redirect::action('AirtsController@index');
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
	 * @var $item \App\Airts::class
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
            try{
                $item = \App\Airts::findOrFail($id);
                if($item->target)
                \Session::flashInput([
                    'source' => $item->source,
                    'target' => preg_replace ('/^\//', \Request::getSchemeAndHttpHost().'/', $item->target),
                    'id' =>$item->id
                        ]);
                return view('admin.airts.edit');
            }  catch (ModelNotFoundException $e){
                \App::abort(404);
            }
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{

	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id){
            return \App\Airts::destroy($id);
	}
}