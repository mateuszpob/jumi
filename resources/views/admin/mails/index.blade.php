@extends('admin.index')


@section('content')

<!-- START DEFAULT DATATABLE -->
<div class="panel panel-default">
    <div class="panel-heading">                                
        <h3 class="panel-title">Mails</h3>
        <ul class="panel-controls">
            <li><a href="{{route('admin.mails.create')}}"><span class="fa fa-times rotate-to-plus"></span></a></li>
        </ul>   
        <!--
        <ul class="panel-controls">
            <li><a href="#" class="panel-collapse"><span class="fa fa-angle-down"></span></a></li>
            <li><a href="#" class="panel-refresh"><span class="fa fa-refresh"></span></a></li>
            <li><a href="#" class="panel-remove"><span class="fa fa-times"></span></a></li>
        </ul>       
        -->
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table datatable2">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Template</th>
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
            url: "{{route('admin.mails.index')}}",
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
            { "data": "template" },
            { 
                "orderable": false, 
                "data": "id",
                render: function ( data, type, row ) {
                    return '<button class="btn btn-default btn-rounded btn-condensed btn-sm edit" onclick="window.location=\'{!! Request::url() !!}/'+data+'/edit\'"><span class="fa fa-pencil"></span></button>\
            <button class="btn btn-danger btn-rounded btn-condensed btn-sm delete" itemid="'+data+'" ><span class="fa fa-times"></span></button>'
                },
            }
        ]
    });
    });

</script>






@stop