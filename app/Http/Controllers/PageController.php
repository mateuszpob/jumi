<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    
        use \App\Traits\GridTrait;
        
        
        
        
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($url=null){
        if(empty($url))
            return view('shop.pages.artykuly');
        
        $page = \App\Page::url($url)->first();
        if(empty($page))
            abort(404);
        
        if(empty($page->page_title))
            $pt = $page->name . ' | MojaChata';
        else
            $pt = $page->page_title;
        
        if(!empty($page->description))
            $pd = $page->description;
        
        $v = view('pages.'.$page->template)->withPage($page)->withPage_title($pt);
        
        if(!empty($page->description))
            $v->withPage_description($pd);
            
        return $v;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        if (\Request::ajax()){
            return $this->gridAction('\App\Page');
        }else{
            return view('admin.page.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(\Request $r)
    {
        return view('admin.page.create_template');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $o = \App\Page::find($id);
        return view('admin.page.create_template')->withObject($o);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $data = \Request::all();
            
        if(isset($data['id']) && $data['id'] > 0){
            $o = \App\Page::find($data['id']);
        }else{
            $o = new \App\Page();
        }
        
        if(isset($data['name']) && $data['name']){
            $o->name = $data['name'];
        }else{
            $o->name = null;
        }
        if(isset($data['template']) && $data['template']){
            $o->template = $data['template'];
        }else{
            $o->template = null;
        }
        if(isset($data['url']) && $data['url']){
            $o->url = $data['url'];
        }else{
            $o->url = null;
        } 
        if(isset($data['html']) && $data['html']){
            $o->html = $data['html'];
        }else{
            $o->html = null;
        }
        
        if(isset($data['page_title']) && $data['page_title']){
            $o->page_title = $data['page_title'];
        }else{
            $o->page_title = null;
        }
        
        if(isset($data['page_title']) && $data['page_title']){
            $o->page_title = $data['page_title'];
        }else{
            $o->page_title = null;
        }
        if(isset($data['description']) && $data['description']){
            $o->description = $data['description'];
        }else{
            $o->description = null;
        }
        
        $o->save();
        //dd($o);
        return \Redirect::to('/admin/pages/');
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
    
    public function templates(){
        
    }
    
    public function createTemplate(\Request $r)
    {
      
        
        
    }
    
    public function storeTemplateFile(){
       
                
        \File::put('../resources/views/pages/' . \Request::get('filename') . '.blade.php', \Request::get('html'));
    }

    
    public function getPage($page){
        switch($page){
            case 'zwroty-i-reklamacje':
                return view('shop.pages.reklamacje');
                break;
            case 'regulamin':
                return view('shop.pages.regulamin');
                break;
            case 'formy-platnosci':
                return view('shop.pages.platnosci');
                break;
            case 'czas-i-koszty-dostawy':
                return view('shop.pages.dostawy');
                break;
            case 'polityka-prywatnosci':
                return view('shop.pages.prywatnosc');
                break;
            case 'jak-kupowac':
                return view('shop.pages.jakkupowac');
                break;
            case 'kontakt':
                return view('shop.pages.kontakt');
                break;
            case 'promocje':
                return view('shop.pages.promotions');
                break;
            case 'o-nas':
                return view('shop.pages.o_nas');
                break;
            default:
                abort(404);
        }
    }

}


/**
 
 <div class="jebac">
  <h1>A to je standardowa hajedynka bez stylu</h1>
  <p style="color:#f00;"> Jebany akapit czerwony ma byc </p>
</div> 
 
 */