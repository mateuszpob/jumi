@extends('shop.mimity.index')

@section('content')
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-6 col-xs-12">
        <div class="slider-on-welcome-container shadow-box">
            <div id="slider-on-welcome" class="owl-carousel owl-theme">
                <div class="item"><img style="width:100%;height:400px" src="/img/slider/lazienka-styl-skandynawski.jpg" alt="lazienka styl skandynawski"></div>
                <div class="item"><img style="width:100%;height:400px" src="/img/slider/biala-lazienka-zobacz-gotowe-12.jpg" alt="bialalazienka"></div>
                <div class="item"><img style="width:100%;height:400px" src="/img/slider/lazienka-slie.jpg" alt="bialalazienka"></div>
                <div class="item"><img style="width:100%;height:400px" src="/img/slider/luxum_lazienka.jpg" alt="bialalazienka"></div>
            </div>
            <div class="slider-buttons">
                <div class="slider-prev"></div>
                <div class="slider-next"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
        <div class="thumbnail welcome-featured-item shadow-box">
            <a href="{{$featured_item->getUrl()}}">
                    <div class="img-container">
                        <img class="polecane-img shop-item-image polecane-img" src="{{ url('image/'.$featured_item->image_path) }}" alt="" >
                    </div>
                </a>
            <div class="caption prod-caption">
                <h4><a href="{{$featured_item->getUrl()}}">{{ ucwords($featured_item->name) }} </a></h4>
                <div class="caption-container @if($featured_item->getPriceDiscounted() > 0) caption-with-old-price @endif"><p>{!! $featured_item->description !!}</p></div>
                
                <div class="btn-group btn-group-tile">
                
                            @if($featured_item->getPriceDiscounted() > 0)
<!--                                <div class="discount-percentage"><div>{!! '-'.round($featured_item->getdiscountPercentage(),0).'%' !!}</div></div></br>-->
                                <span class="old-price-on-wide-list">{{ number_format($featured_item->getPrice(), 2, '.', ' ') }} PLN</span>
                                <span class="discounted-price price-on-wide-list">{{ number_format($featured_item->getPriceDiscounted(), 2, '.', ' ') }} PLN</span>
                            @else
                                <span class="price-on-wide-list">{{ number_format($featured_item->getPrice(), 2, '.', ' ') }} PLN</span>
                            @endif
                            @if($v_count = $featured_item->variants()->active()->count())
                                <a href="{{$featured_item->getUrl()}}"><div class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Pokaż wszystkie opcje ({{$v_count}})</div></a>
                            @else
                                <div data-item_id="{{ $featured_item->id }}" data-variant_id="no_variants" class="add-to-cart btn btn-primary"><i class="fa fa-shopping-cart"></i>Dodaj do koszyka</div>     
                            @endif                    

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <h1 class="title-on-main-flow">ARMATURA ŁAZIENKOWA - STYLOWE WYPOSAŻENIE WNĘTRZ</h1>
    </div>
</div>
<div class="row"></br></div>
<div class="row main-catagories">

    @foreach( \App\ProductGroup::active()->get() as $pr)
        <div class="col-md-3 col-sm-3 col-xs-6 ">
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
<script>
$(document).ready(function() {
    var owl_slider = $("#slider-on-welcome");
    owl_slider.owlCarousel({

        navigation : true, // Show next and prev buttons
        slideSpeed : 300,
        paginationSpeed : 400,
        singleItem:true,
        autoPlay : 3000,

//        autoHeight : true,
        // "singleItem:true" is a shortcut for:
        // items : 1, 
        // itemsDesktop : false,
        // itemsDesktopSmall : false,
        // itemsTablet: false,
        // itemsMobile : false

    });
    $(".slider-next").click(function(){
        owl_slider.trigger('owl.next');
    })
    $(".slider-prev").click(function(){
        owl_slider.trigger('owl.prev');
    })
});
</script>
@endsection
