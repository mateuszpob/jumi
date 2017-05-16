@extends('shop.mimity.left_side_bestsellers')

@section('items')

<div class="col-lg-9 col-md-9 col-sm-9">
        <div class="row">
            <div class="col-lg-12 col-sm-12"><h1 class="title-on-main-flow">{{ $page->name }}</h1>
                <span class="title">
                    @if(isset($pagination))
                        <div class="pagination-simple">
                            {!! $pagination !!}
                        </div>
                    @endif
                    
                </span>

            </div>
        </div>
        <div class="row blog">
            <div class="col-lg-12 col-sm-12">
                {!! $page->html !!}
            </div>
        </div>
    </div>
</div>

@stop