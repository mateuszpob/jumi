<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
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

    // public function getNoImage(){
    //     $server = \League\Glide\ServerFactory::create([
    //                    'source' => \Storage::disk('shop')->getDriver(),
    //                    'cache'  =>  \Storage::disk('shop')->getDriver(),
    //                    'source_path_prefix'    =>  '',
    //                    'cache_path_prefix'     =>  'images_cached',
                       
    //                ]);
    //     $server->outputImage('2015-09-26/no-thumb.png', $_GET);
    // }


    public function getImage($directory, $directory2, $file=null){//dd($directory, $directory2, $file);
        if($file){
            $filename = $file;
            $source = $directory.'/'.$directory2;
        }
        else{
            $filename = $directory2;
            $source = $directory;
        } 
         $server = \League\Glide\ServerFactory::create([
                       'source' => \Storage::disk('shop')->getDriver(),
                       'cache'  =>  \Storage::disk('shop')->getDriver(),
                       'source_path_prefix'    =>  $source,
                       'cache_path_prefix'     =>  'images_cached',
                       
                   ]);
        
        $server->outputImage( $filename, $_GET);
    }


    public function get_imagexxxxxxxx($path){
        $sizes = (object)null;
        $sizes->width  = [];
        $sizes->height = [];
        
        $this->get($path,'images',$sizes);
    }


    protected function getxxxxxxxx($path,$storage,$sizes,$prefix=''){
        ini_set('memory_limit', '326M');
        if($this->check_variables($sizes)){
            $server=$this->prepare_server($storage,$prefix);
            $_GET['fit']='crop';
            $_GET['crop']='top';
            try{
                $server->getImageResponse($path);
                $server->outputImage($path, $_GET);
            }
            catch(\Exception $e){
//                $status = $this->download_image($path,$storage);
//                if($status == 404){
//                    return abort(404);
//                }
                
                if($storage == 'avatars'){
                    $path = '2013-04-18/377751d80a06cd41ac81a4bcfab66918.jpg';
                }
                else{
                    \Log::error($e->getMessage() . '  ' . $e->getTraceAsString());
                    return abort(404);
                }
                
                $server->getImageResponse($path);
                
                $server->outputImage($path, $_GET);
            }
        } 
        else{
            return abort(404);
        }
    }









}
