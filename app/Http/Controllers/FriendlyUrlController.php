<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Router;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class FriendlyUrlController extends Controller
{
    use \App\Traits\GridTrait;
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (\Request::ajax()){
                return $this->gridAction('\App\FriendlyUrl');
            }else{
                return view('admin.friendly_urls.index');
            }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Router $router)
    {
        $r = $this->getActions($router);

        return view('admin.friendly_urls.add')->withRoutes($r);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $r = \Request::all();
        $o_url = \App\FriendlyUrl::findOrNew(\Illuminate\Support\Facades\Input::get('id'));
        if(isset($r['action']))
            $o_url->action = $r['action'];
        if(isset($r['url']))
            $o_url->url = $r['url'];
        
        $o_url->save();
        return \Redirect::route('admin.urls.index');
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
    public function edit($id, Router $router)
    {
        $o_ulr = \App\FriendlyUrl::find($id);
        
        return view('admin.friendly_urls.edit', [
                'id' => $id,
                'action' => $o_ulr->action,
                'url' => $o_ulr->url,
                'routes' => $this->getActions($router)
            ]);
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
    public function destroy($id)
    {
        //
    }
    
    private function getActions(Router $router){
        $list = $router->getRoutes();
        
        $data=array();
        foreach($list as $route){
            
            $m=$route->middleware();
            
           
            $s = str_pad($route->uri(), 40,' ');
            
            if(strpos($s, 'auth') === false && strpos($s, 'password') === false){
                $item = " | ". $s;
                $item.= " | ".str_replace('App\Http\Controllers\\', '', str_pad($route->getActionName(),61));
                $data[$route->getActionName()]=  str_replace(' ', '&nbsp;', $item);
            }
        }
            
        return $data;
    }
    
}
