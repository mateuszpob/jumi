@extends('admin.index')


@section('content')

<!-- START DEFAULT DATATABLE -->
<div class="panel panel-default">
    <div class="panel-heading">                                
        <h3 class="panel-title">Zamówienia</h3>
        <ul class="panel-controls">
            <li><a href="/admin/orders/create"><span class="fa fa-times rotate-to-plus"></span></a></li>
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
                        <th>Nr.</th>
                        <th>Email</th>
                        <th>Data złożenia</th>
                        <th>Data płatności</th>
                        <th>Data wysłania</th>
                        <th>Wartość przedmiotów</th>
                        <th>Wartość wysyłki</th>
                        <th>Wartość całkowita</th>
                        <th>Szczegóły</th>
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
                url: "/admin/orders/index",
                type: 'GET'
            },
            "deferRender": true,
            searching: true,
            ordering: true,
            processing: true,
            draw: 1,
            columns: [
                { "data": "id" },
                { "data": "email" },
                { "data": "created_at" },
                { "data": "payment_received_date" },
                { "data": "sent_date" },
                { "data": "cart_amount" },
                { "data": "shipment_amount" },
                { "data": "total_amount" },

                { 
                    "orderable": false, 
                    "data": "id",
                    render: function ( data, type, row ) {
                        return '<button class="btn btn-default btn-rounded btn-condensed btn-sm edit" onclick="window.location=\'{!! Request::url() !!}/show/'+data+'\'"><span class="fa fa-pencil"></span></button>\
                <button class="btn btn-danger btn-rounded btn-condensed btn-sm delete" itemid="'+data+'" ><span class="fa fa-times"></span></button>'
                    },
                },

            ]
        });
        
        $('*').on('click', function(){
            table.ajax.reload();
        })
        
        
    });

</script>






@stop