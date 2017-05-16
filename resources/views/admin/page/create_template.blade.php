@extends('admin.index')


@section('content')


<!-- START DEFAULT DATATABLE -->
<div class="panel panel-default">
    <div class="panel-heading">                                
        <h3 class="panel-title">Stwórz nową stronę</h3>
                                   
    </div>
    <div class="panel-body">
        {!! Form::open(array('action' => 'PageController@store', 'class' => 'form')) !!}

        <div class="form-group">
                <label for="name">Page name:</label>
                <input class="form-control" name="name" id="name" value="{{ empty($object->name) ? null : $object->name }}" />
        </div>
        
        <div class="form-group">
                <label for="template">Template:</label>
                <input class="form-control" name="template" id="template" value="{{ empty($object->template) ? null : $object->template }}" />
        </div>
        
        <div class="form-group">
                <label for="url">Url:</label>
                <input class="form-control" name="url" id="url" value="{{ empty($object->url) ? null : $object->url }}" />
        </div>
        
        <div class="form-group">
                <label for="page_title">Page title:</label>
                <input class="form-control" name="page_title" id="page_title" value="{{ empty($object->page_title) ? null : $object->page_title }}" />
        </div>
        
        <div class="form-group">
                <label for="description">Description:</label>
                <textarea style="height: 100px;" class="form-control" rows="15" name="description" id="description">{!! empty($object->description) ? null : $object->description !!}</textarea>
        </div>
        
        <div class="form-group">
                <label for="html">HTML content:</label>
                <textarea class="form-control" rows="15" name="html" id="html">{!! empty($object->html) ? null : $object->html !!}</textarea>
        </div>
        
        @if(!empty($object->name))
            <input name="id" type="hidden" value="{{$object->id}}"/>
        @endif
        
        <div class="form-group">
            {!! Form::submit('Ok', 
              array('class'=>'btn btn-primary')) !!}
        </div>
        
        {!! Form::close() !!}
    </div>
</div>
<!-- END DEFAULT DATATABLE -->
<link rel="stylesheet" href="/plugins/highlight/styles/default.css">
<script src="/plugins/highlight/highlight.pack.js"></script>
<script>hljs.initHighlightingOnLoad();</script>

<script>
$(document).ready(function(){

    
    $(".table.datatable2").on("click", '.delete', function(){
        var id = $(this).attr('itemid');
        console.log(id)
        $.ajax({
            type :'DELETE',
            url  :'{{ route("admin.roles.index") }}' + '/' + id,
            datatype:'json',
            data: {_token: "{{Session::token()}}" },
            success:function(res){
                table.ajax.reload();
                //$('tr[itemid="'+ id +'"]').hide();
            }
            
        })
    });
    
   
//  $('pre code').each(function(i, block) {
//    hljs.highlightBlock(block);
//
//    });
    
});



</script>

<pre><code class="xml hljs">
<div class="jebac">
  <h1>A to je standardowa hajedynka bez stylu</h1>
  <p style="color:#f00;"> Jebany akapit czerwony ma byc </p>
  <h2>xXx</h2>
</div>
</code></pre>

@stop