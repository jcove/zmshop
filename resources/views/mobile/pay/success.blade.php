@extends('mobile.layout.default')
@section('content')
    <div class="container message">
        <p><i class="iconfont icon-right1"></i>{{$message}}</p>
    </div>

@endsection
@section('script')
    <script>
        if(window && window.androidObj && window.androidObj.success){
            androidObj.success();
        }
        if(window && window.webkit && window.webkit.messageHandlers){
            window.webkit.messageHandlers.payOrderDidSuccess.postMessage(null);
        }

    </script>
@endsection