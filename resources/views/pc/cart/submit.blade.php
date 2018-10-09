@extends('pc.layout.default')
@section('content')
    <div class="cart-section">
        <submit :carts="{{json_encode($list->all())}}"></submit>
    </div>
@endsection
