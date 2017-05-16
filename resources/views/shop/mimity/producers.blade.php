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
            <a href="/item/{{$featured_item->id}}">
                    <div class="img-container">
                        <img class="shop-item-image" src="{{ url('image/'.$featured_item->image_path) }}" alt="" >
                    </div>
                </a>
            <div class="caption prod-caption">
                <h4><a href="/item/{{$featured_item->id}}">{{ ucwords($featured_item->name) }} </a></h4>
                <div class="caption-container"<p>{!! $featured_item->description !!}</p></div>
                <p>
                </p><div class="btn-group">
                    <span style="width: 95px;" class="btn btn-default">{{ $featured_item->price }}zł</span>
                    <span data-item_id="{{ $featured_item->id }}" class="add-to-cart btn btn-primary"><i class="fa fa-shopping-cart"></i> Dodaj do koszyka</span>
                </div>
                <p></p>
            </div>
        </div>
    </div>
</div>

<div class="row main-catagories">
    @foreach(\App\Producer::active()->get() as $c)
    <div class="col-md-3 col-sm-3 col-xs-3  ">
        
            <div class="category-tile shadow-box">
                <a href="{{$c->getUrl()}}"><h4>{{$c->name}}</h4></a>
                <div class="cat-picture">
                    <a href="{{$c->getUrl()}}"><img class="shop-item-image" src="{{ url('image/'.$c->getImageSrc()) }}" alt="" ></a>
                </div>
                <div class="subcategories-menu">
                    <ul>
                        @foreach($c->getCategories() as $cat)
                        <li><a href="">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>   
                </div>
            </div>
        
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
