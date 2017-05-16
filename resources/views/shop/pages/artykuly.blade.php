@extends('shop.mimity.left_side_bestsellers')

@section('items')
<div class="col-lg-9 col-md-9 col-sm-9">
        <div class="row">
            <div class="col-lg-12 col-sm-12"><h1 class="title-on-main-flow">ARTYKUŁY</h1>
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
                <ul>
                    @foreach(\App\Page::active()->get() as $p)
                    <li><a href="{{ url('artykuly/'.$p->url) }}">{{ $p->name }}</a></li>
                    @endforeach
                </ul>
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