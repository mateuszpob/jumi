<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TrackerController extends Controller
{
    use \App\Traits\GridTrait;
    
    public function index(){
        if (\Request::ajax()){
            return $this->gridAction('\App\Tracker');
        }else{
            return view('admin.tracker.index');
        }
    }
    
    public function update(Request $request, $id)
    {
        //
    }
    
    public function view($id){
        $tracker = \App\Tracker::find($id);
//        dd(json_encode(unserialize($tracker->data),JSON_HEX_QUOT));
        return view('admin.tracker.view')
                ->withData(json_encode(unserialize($tracker->data),JSON_HEX_QUOT))
                ->withScreen_width((int)unserialize($tracker->data)[0]['width']);
    }
    
    public function storeTrack(Request $request){
        $track = new \App\Tracker();
        $track->data = serialize($request->get('points'));
    
        $track->save();
    }

}
