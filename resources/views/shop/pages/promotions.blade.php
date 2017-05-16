@extends('shop.mimity.left_side_bestsellers')

@section('items')
<div class="col-lg-9 col-md-9 col-sm-9">
        <div class="row">
            <div class="col-lg-12 col-sm-12"><h1 class="title-on-main-flow">PROMOCJE</h1>
                <span class="title">
                    @if(isset($pagination))
                        <div class="pagination-simple">
                            {!! $pagination !!}
                        </div>
                    @endif
                    
                </span>

            </div>
        </div>
    <div class="row">
        <div class="col-lg-12 col-sm-12 pages-text-box">
            <div class="row">
            @foreach( \App\ProductGroup::active()->get() as $pr)
        <div class="col-md-4 col-sm-6 col-xs-6 ">
            <a class="no-hover" href="{{ $pr->getUrl() }}">
                <div class="groups-on-welcome thumbnail shadow-box">
                    <div class="promotion-group-description">
                        <h3>{!! str_replace('#date', date('Y-m-d', strtotime($pr->promotion_end_date)) ,$pr->name) !!}</h3>
                    </div>
                    <div class="main-image">
                        <img class="" src="{{ url('image/product_groups/'.$pr->image_path) }}" />
                        <div class="promotion-percentage">
                            <font style="color:#fff;">-{{$pr->promotion_percentage}}%</font>
                        </div>
                    </div>
                    <div class="line-separator"></div>
                    <div class="row product-group-items">
                        @foreach($pr->items->take(3) as $item)
                         <div class="col-md-4 col-sm-4 col-xs-4">
                             <img class="" src="{{ url('image/'.$item->image_path) }}" />
                         </div>
                        @endforeach
                    </div>
                </div>
            </a>
        </div>
    @endforeach
            </div>
        </div>
    </div>
</div>
<script>
    // podswietl pozycje w menu na glownej belce
   $(document).ready(function(){
       var newURL = window.location.protocol + "//" + window.location.host  + window.location.pathname;
       console.log(newURL)
       $('a.main-menu-option[href="'+newURL+'"]').addClass('active');
   });
</script>
@stop