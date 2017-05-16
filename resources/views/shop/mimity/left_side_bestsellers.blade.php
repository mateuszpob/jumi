@extends('shop.mimity.index')


@section('content')
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-12">

        <!-- Categories -->
<!--        <div class="col-lg-12 col-md-12 col-sm-6">
            <div class="no-padding">
                <span class="title">KATEGORIE</span>
            </div>




        </div>-->
        <!-- End Categories -->

        <!-- Best Seller -->
        <div class="col-lg-12 col-md-12 col-sm-6">
            <div class="no-padding">
                <span class="title">POLECANE</span>
            </div>
            @foreach(\App\Item::getRandomItems(2) as $bestseller)
                @include('shop.mimity.item_tile', ['item' => $bestseller])
            @endforeach
        </div>
        <!-- End Best Seller -->
       
    </div>

    <div class="clearfix visible-sm"></div>
    
    @yield('items')
    
</div>

@endsection