@extends('shop.mimity.left_side_bestsellers')

@section('items')

<?php
$variants = $item->variants()->orderBy('price')->get();

$variants_count = $item->variants->count();
?>

<div class="col-lg-9 col-md-9 col-sm-12">
    <div class="col-lg-12 col-sm-12">
        <span class="title item-details-title"><h1 class="title-on-main-flow">{{ $item->name }}</h1></span>
        @if(\Auth::check() && \Auth::user()->hasEditPermissions())
                    <span class="edit-for-admin" data-item_id="{{ $item->id }}">Edit</span>
                @endif
        <div class="menu-track">
            <?php
            $schema = $item->schema()->first();
            if ($schema) {
                ?>
                <a href="{{ $schema->getUrl() }}">{{ $schema->name }}</a> 
            <?php } ?>
            @foreach($item->getCategoryList() as $c)
            <span>>></span> 
            <a href="{{ $c->getUrl() }}">{{ $c->name }}</a>
            @endforeach

        </div>
    </div>
    <div class="col-lg-12 col-sm-12 hero-feature">

        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <!-- Main Image -->
                <div class="product-main-image-container">
                    <!--<img src="images/loader.gif" alt="" class="product-loader" style="display: none;">-->
                    <span class="thumbnail product-main-image" style="position: relative; overflow: hidden;">
                        <img src="{{ url('image/'.$item->image_path) }}" alt="">
                        <!--<img src="images/detail3.jpg" class="zoomImg" style="position: absolute; top: -1.61421px; left: -5.93909px; opacity: 0; border: none; max-width: none; max-height: none; width: 400px; height: 400px;"></span>-->
                        </div>
                        <!-- End Main Image -->
                        </div>

                        <div class="visible-xs">
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="well product-short-detail">
                                <div class="ean-item-details">Kod ean: {{$item->ean}}</div>


                                <div class="btn-group price-container-wide-list row price-block-on-details" >
                                    <div class="row" >
                                        <div class="col-md-6" style="border-right: 1px solid #ddd;">
                                            @if($item->getPriceDiscounted() > 0)
<!--                                            <div class="discount-percentage">
                                                <div>{!! '-'.round($item->getdiscountPercentage(),0).'%' !!}</div>
                                            </div>-->
                                            </br>
                                            <span class="old-price-on-wide-list">{{ number_format($item->getPrice(), 2, '.', ' ') }} PLN</span>
                                            <span class="discounted-price price-on-wide-list" data-item_id="{{ $item->id }}">{{ number_format($item->getPriceDiscounted(), 2, '.', ' ') }} PLN</span>
                                            @else
                                            </br></br>
                                            <span class="price-on-wide-list" data-item_id="{{ $item->id }}">
                                                @if($variants_count > 0)
                                                {{ number_format($variants->first()->getPrice(), 2, '.', ' ') }} PLN
                                                @else
                                                {{ number_format($item->getPrice(), 2, '.', ' ') }} PLN
                                                @endif
                                            </span>
                                            @endif

                                        </div>
                                        <div class="col-md-6">
                                            @if($variants_count > 0)
                                            <span class="price-on-wide-list">Dostępnych opcji: {{$variants_count}}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row" >
                                        <div class="col-md-6" style="border-right: 1px solid #ddd;">
                                            <div 
                                                @if($variants_count > 0)
                                                data-variant_id="not_select_variants"
                                                @else 
                                                data-variant_id="no_variants"
                                                @endif
                                                data-item_id="{{ $item->id }}" class="add-to-cart btn btn-primary">Dodaj do koszyka</div>

                                        </div>
                                        <div class="col-md-6">
                                            @if($variants_count > 0)
                                            <div class="show-variants btn btn-success">Wybierz</div>
                                            @endif
                                        </div>
                                    </div>


                                </div>
                                <div class="clearfix"></div><br>

                            </div>
                        </div>
                </div>

                <div class="clearfix"></div><br clear="all">

                <div class="col-xs-12 product-detail-tab">
                    <!-- Nav tabs -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            
                            
                            @if($item->ext_desc)
                            <!--Rozszerzone opisy - linki na liście-->
                            <?php $ext_desc_i = 1; ?>
                                @foreach(json_decode($item->ext_desc) as $k => $v)
                                    <li class="@if($ext_desc_i==1)active @endif"><a href="#tab_{{ $ext_desc_i }}" class="variants-tab-btn" data-toggle="tab" aria-expanded="false">{{ $k }}</a></li>
                                    <?php $ext_desc_i++; ?>
                                @endforeach
                            @else
                                <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Opis produktu</a></li>
                            @endif
                            
                            @if($variants_count > 0)
                            <li class=""><a href="#tab_2" class="variants-tab-btn" data-toggle="tab" aria-expanded="false">Dostępne warianty</a></li>
                            @endif

                            <li class="pull-right"><a href="#" class="text-muted"><i class="fa fa-gear"></i></a></li>
                        </ul>
                        <div class="tab-content details-desc-panel">
                            @if($item->ext_desc)
                            <!-- Rozszerzone opisy -->
                            <?php $ext_desc_i = 1; ?>
                                @foreach(json_decode($item->ext_desc) as $k => $v)
                                    <div class="tab-pane @if($ext_desc_i==1) active @endif" id="tab_{{ $ext_desc_i }}">
                                        {!! $v !!}
                                    </div>
                                    <?php $ext_desc_i++; ?>
                                @endforeach
                            @else
                            <div class="tab-pane active" id="tab_1">
                                {!! $item->description !!}
                            </div>
                            @endif
                            @if($variants_count > 0)
                            <div class="tab-pane" id="tab_2">



                                <table class="table">
                                    <tbody>
                                        <tr class="variant-table-header" style="background: #ddd;">
                                            @foreach(json_decode($variants[0]->data) as $k=>$d)
                                            <th>{{ $k }}</th>
                                            @endforeach
                                            <th>Cena</th>
                                            <th>Kod EAN</th>
                                            <th style="text-align: right;">Do koszyka</th>
                                        </tr>
                                        @foreach($variants as $v)
                                        <tr>
                                            @foreach(json_decode($v->data) as $k=>$d)
                                            <td>{{ $d }}</td>
                                            @endforeach
                                            <td class="price-on-variant-list" data-variant_id="{{ $v->id }}">
                                                {{ number_format($v->getPrice(), 2, '.', ' ') }} PLN
                                            </td>
                                            <td>
                                                {{ $v->ean }}
                                            </td>
                                            <td>
                                                <input data-item_id="{{$item->id}}" name="variant_id" class="variant-id-on-details" value="{{ $v->id }}" type="radio" style="float:right;margin-right: 30px;">
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>    
                                </table>
                            </div>
                            @endif
                            
                            
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        @if($item->hasRelatedItems())
        <div class="col-lg-12 col-sm-12">
            <span class="title">PODOBNE PRODUKTY</span>
        </div>
        @foreach($item->getRelatedItems(4) as $related_item)
        <div class="col-lg-3 col-sm-3 hero-feature text-center">
            <div class="thumbnail related-items">
                <a href="{{$related_item->getUrl()}}">
                    <div class="img-container">
                        <img class="shop-item-image" src="{{ url('image/'.$related_item->image_path) }}" alt="" >
                    </div>
                </a>
                <div class="caption prod-caption">
                    <h4><a href="{{$related_item->getUrl()}}">{{ ucwords($related_item->name) }} </a></h4>
                    @if(\Auth::check() && \Auth::user()->hasEditPermissions())
                        <span class="edit-for-admin" data-item_id="{{ $related_item->id }}">Edit</span>
                    @endif
                    <div class="caption-container"><p>{{ $related_item->description }}</p></div>


                    @if($related_item->getPriceDiscounted() > 0)
                    <!--<div class="discount-percentage"><div>{!! '-'.round($related_item->getdiscountPercentage(),0).'%' !!}</div></div></br>-->
                    <span class="old-price-on-wide-list">{{ number_format($related_item->getPrice(), 2, '.', ' ') }} PLN</span>
                    <span class="discounted-price price-on-wide-list">{{ number_format($related_item->getPriceDiscounted(), 2, '.', ' ') }} PLN</span>
                    @else
                    <span class="price-on-wide-list">{{ number_format($related_item->getPrice(), 2, '.', ' ') }} PLN</span>
                    @endif
                    @if($v_count = $related_item->variants()->active()->count())
                    <a href="{{$related_item->getUrl()}}"><div class="btn btn-primary view-more-var-btn">Pokaż wszystkie opcje ({{$v_count}})</div></a>
                    @else
                    <div data-item_id="{{ $related_item->id }}" data-variant_id="no_variants" class="add-to-cart btn btn-primary">Dodaj do koszyka</div>     
                    @endif

                </div>
            </div>
        </div>
        @endforeach
        @endif
    </div>













    @endsection