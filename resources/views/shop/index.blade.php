@extends('test')

@section('content')
    <div class="row">
    @foreach($items as $i)
        
        <div class="panel panel-default product-panel col-md-3">
            <div class="panel-heading">
                <a class="product-details-link" href="{{ url('item/' . $i->id) }}">{{ $i->name }}</a>
                <button class="add-to-cart" href="#" value="Buy!">
            </div>
            <div class="panel-body">
                {{ $i->description }}
                <img class="shop-item-image" src="{{ url('image/' . $i->image_path . '?w=300') }}" />
            </div>
        </div>
    @endforeach
    </div>
@endsection
