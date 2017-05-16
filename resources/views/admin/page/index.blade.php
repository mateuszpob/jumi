@extends('admin.index')


@section('content')
<!-- START DEFAULT DATATABLE -->
<div class="panel panel-default">
    <div class="panel-heading">                                
        <h3 class="panel-title"><a href="/admin/pages/create">Create Page</a></h3>
        <ul class="panel-controls">
            <li><a href="{{route('admin.roles.create')}}"><span class="fa fa-times rotate-to-plus"></span></a></li>
        </ul>                                
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table datatable2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Url</th>
                        <th>Type</th>
                        <th>Create Date</th>
                        <th>Edit</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
<!-- END DEFAULT DATATABLE -->

<script>
$(document).ready(function(){
    var table = $('.datatable2').DataTable({
        serverSide: true,
        ajax: {
            url: "{{route('admin.pages.index')}}",
            type: 'GET'
        },
        "deferRender": true,
        searching: true,
        ordering: true,
        processing: true,
        draw: 1,
        columns: [
            { "data": "id" },
            { "data": "name" },
            { "data": "url" },
            { "data": "type_id" },
            { "data": "created_at" },
            { 
                "orderable": false, 
                "data": "id",
                "render": function ( data, type, row ) {
                    return '<button class="btn btn-default btn-rounded btn-condensed btn-sm edit" onclick="window.location=\'{!! Request::url() !!}/'+data+'/edit\'"><span class="fa fa-pencil"></span></button>\
            <button class="btn btn-danger btn-rounded btn-condensed btn-sm delete" itemid="'+data+'" ><span class="fa fa-times"></span></button>'
                },
            }
        ]
    });
    
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
});


</script>



@stop