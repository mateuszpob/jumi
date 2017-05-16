

    <!-- Featured -->
    <div class="col-lg-9 col-md-9 col-sm-9">
        <div class="row">
            <div class="col-lg-12 col-sm-12"><h1 class="title-on-main-flow">{{ isset($category_name) ? strtoupper($category_name) : 'POLECANE PRODUKTY' }}</h1>
                <span class="title">
                    @if(isset($pagination))
                        <div class="pagination-simple">
                            {!! $pagination !!}
                        </div>
                    @endif
<!--                    <div class="list-type-changer">
                        <span id="list-type-tiles" title="Widok kafelek" class="btn glyphicon glyphicon-th"></span>
                        <span id="list-type-list" title="Widok listy" class="btn glyphicon glyphicon-align-justify"></span>
                    </div>-->
                    
                </span>

            </div>
        </div>

 
        <div id="item-flow-container" >
            <div id="load-wrapper">
                @foreach($items as $item)
                    @include('shop.mimity.item_tile', ['item' => $item])
                @endforeach
            </div>
        </div>
        <div class="pagination-simple bottom">
            @if(isset($pagination))
                        {!! $pagination !!}
                        @endif
                    </div>
    </div>
    <!-- End Featured -->

    <script>
        function assignLayout(){
            var options = {
                autoResize: true,
      //          offset: 40,
                //verticalOffset: 20,
                
                align: 'center',
            };

           var wookmark = new Wookmark('#load-wrapper',options);
        }
        
        $(document).ready(function(){
            $('.item-on-flow').removeClass('thumbnail');
            $('.item-on-flow').addClass('wideList');
                
            //assignLayout();
            
            /*
            * Zmiana wyglady itemow na liscie w sklepie, zmienia typ listy
            */
           $('#list-type-list').on('click', function(){
                $('.item-on-flow').removeClass('thumbnail');
                $('.item-on-flow').addClass('wideList');
                assignLayout();
           });
           $('#list-type-tiles').on('click', function(){
                $('.item-on-flow').addClass('thumbnail');
                $('.item-on-flow').removeClass('wideList');
                assignLayout();
           });
        });
        
        
    </script>
