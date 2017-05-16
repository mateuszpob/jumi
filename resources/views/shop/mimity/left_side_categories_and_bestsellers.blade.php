@extends('shop.mimity.index')


@section('content')
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-12">

    @if(!(isset($schow_categories) && !$schow_categories))
        <!-- Categories -->
        <div class="col-lg-12 col-md-12 col-sm-6">
            <div class="no-padding">
                <span class="title">KATEGORIE</span>
            </div>
            {!! \App\Functions::getCategoryMenu($schema_url) !!} 
        </div>
        <!-- End Categories -->
    @endif

        {{--
        <!-- Start Search Options -->
        @if(!isset($show_search_options) || $show_search_options)
            <div class="col-lg-12 col-md-12 col-sm-6">
                <div class="no-padding">
                    <span class="title">ZAKRES CENOWY</span>
                </div>
                <div id="price-slider"></div>
                <div class="sep-10"></div>
            </div>
            <div class="col-lg-12 col-md-12 col-sm-6 producer-option">
                <div class="no-padding">
                    <span class="title">PRODUCENT</span>
                </div>
   
                <select name="search_producer" class="producer-selectpicker" multiple>
                    @foreach(\App\Producer::active()->get() as $producer)
                    <option value="{{ $producer->id }}" {{ !isset($search_params) || (isset($search_params) && in_array((String)$producer->id, $search_params->prods)) ? 'selected' : ''}}>{{ $producer->name }}</option>
                    @endforeach
                </select>
                <button id="searchSubmit" class="btn btn-primary left_side_pokaz_btn">Pokaż</button>
                <div class="sep-20"></div>
            </div>
        @endif
        <!-- End Search Options -->
        --}}

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
    @if(isset($itemsy))
        {!! $itemsy !!}
    @endif
</div>


@endsection