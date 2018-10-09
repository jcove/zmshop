@extends('pc.layout.default')
@section('content')
    <div class="cart-section">
        <cart :carts="{{json_encode($list->items())}}"></cart>
    </div>
@endsection
