@extends('shop.mimity.left_side_bestsellers')

@section('items')
<div class="col-lg-9 col-md-9 col-sm-9">
        <div class="row">
            <div class="col-lg-12 col-sm-12"><h1 class="title-on-main-flow">KONTAKT</h1>
                <span class="title">
                    @if(isset($pagination))
                        <div class="pagination-simple">
                            {!! $pagination !!}
                        </div>
                    @endif
                    <div class="list-type-changer">
                        <span id="list-type-tiles" title="Widok kafelek" class="btn glyphicon glyphicon-th"></span>
                        <span id="list-type-list" title="Widok listy" class="btn glyphicon glyphicon-align-justify"></span>
                    </div>
                    
                </span>

            </div>
        </div>
    <div class="row">
        @yield('content')
    </div>
</div>
@stop