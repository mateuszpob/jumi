

<div class="item-on-flow thumbnail" data-item_id="{{ $item->id }}">
                @if(\Auth::check() && \Auth::user()->hasEditPermissions())
                    <span class="edit-for-admin" data-item_id="{{ $item->id }}">Edit</span>
                @endif
                    <a href="{{$item->getUrl()}}">
                        <div class="img-container">
                            <img class="shop-item-image" src="{{ url('image/'.$item->image_path) }}" alt="{{ucwords($item->getAltText()) }} w kategorii {{ isset($category_name) ? ucwords($category_name) : 'POLECANE PRODUKTY' }}" >
                        </div>
                    </a>
                    <div class="caption prod-caption">
                        <h4><a class="item-name-on-main-flow" href="{{$item->getUrl()}}" data-item_id="{{ $item->id }}">{{ ucwords($item->name) }}</a></h4>
                        <div class="shop-short-desc" data-item_id="{{ $item->id }}">{{ $item->description }}</div>
                        <p></p>
                        <div class="btn-group btn-group-tile">
                            <!-- <span data-item_id="{{ $item->id }}" class="add-to-cart btn btn-primary"><i class="fa fa-shopping-cart"></i> 
                                <span class="button-price">{{ $item->getPrice() }}zł</span>
                                Dodaj do koszyka
                            </span> -->

                            @if($item->getPriceDiscounted() > 0)
                                
                                <!--<div class="discount-percentage"><div>{!! '-'.round($item->getdiscountPercentage(),0).'%' !!}</div></div></br>-->
                                <span class="old-price-on-wide-list">{{ number_format($item->getPrice(), 2, '.', ' ') }} PLN</span>
                                <span class="discounted-price price-on-wide-list">{{ number_format($item->getPriceDiscounted(), 2, '.', ' ') }} PLN</span>
                            @else
                                <span class="price-on-wide-list">{{ number_format($item->getPrice(), 2, '.', ' ') }} PLN</span>
                            @endif
                            @if($v_count = $item->variants()->active()->count())
                                <a href="{{$item->getUrl()}}"><div class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Pokaż wszystkie opcje ({{$v_count}})</div></a>
                            @else
                                <div data-item_id="{{ $item->id }}" data-variant_id="no_variants" class="add-to-cart btn btn-primary"><i class="fa fa-shopping-cart"></i>Dodaj do koszyka</div>     
                            @endif
                        </div>
                        <p></p>
                    </div>
                    <div class="btn-group-list">
                        <div class="btn-group price-container-wide-list">
                            @if($item->getPriceDiscounted() > 0)
                                <!--<div class="discount-percentage"><div>{!! '-'.round($item->getdiscountPercentage(),0).'%' !!}</div></div></br>-->
                                <span class="old-price-on-wide-list">{{ number_format($item->getPrice(), 2, '.', ' ') }} PLN</span>
                                <span class="discounted-price price-on-wide-list">{{ number_format($item->getPriceDiscounted(), 2, '.', ' ') }} PLN</span>
                            @else
                                <span class="price-on-wide-list">{{ number_format($item->getPrice(), 2, '.', ' ') }} PLN</span>
                            @endif
                            @if($v_count = $item->variants()->active()->count())
                                <a href="{{$item->getUrl()}}"><div class="btn btn-primary"><i class="fa fa-shopping-cart"></i>Pokaż wszystkie opcje ({{$v_count}})</div></a>
                            @else
                                <div data-item_id="{{ $item->id }}" data-variant_id="no_variants" class="add-to-cart btn btn-primary"><i class="fa fa-shopping-cart"></i>Dodaj do koszyka</div>     
                            @endif
                            
                                
                        </div>
                    </div>
                </div>

