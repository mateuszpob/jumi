<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Input;

class MailController extends Controller
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
            return $this->gridAction('\App\Mail');
        }else{
            return view('admin.mails.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.mails.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $mail_data = \Request::all();
        $mail = \App\Mail::findOrNew(\Input::get('id'));
        
        if(isset($mail_data['name'])){
            $mail->name = $mail_data['name'];
        }
        if(isset($mail_data['content'])){
            $mail->content = $mail_data['content'];
        }
        if(isset($mail_data['active'])){
            $mail->active = $mail_data['active'];
        }
        
        $mail->save();

        return \Redirect::route('admin.mails.index');
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
        $mail = \App\Mail::find($id);
        if(\View::exists('emails.'.$mail->name)){
            $view_html = file_get_contents('../resources/views/emails/'.$mail->name.'.blade.php');
            
            return view('admin.mails.edit')
                    ->withObj($mail)
                    ->withView_html(str_replace('"', "'", $view_html));
        }
        return view('admin.mails.edit')->withObj($mail);
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
}
