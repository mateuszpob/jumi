@extends('admin.index')


@section('content')

<style>
    iframe{
        position:absolute;
        top:0;
        left:0;
        
    }
</style>

<script>
    var tracker_data = JSON.parse("{{$data}}".replace(/&quot;/g,'"'));
    var canvas, ctx, flag = false, prevX, prevY, currX, currY;
    function init() {
        canvas = document.getElementById('myCanvas');

        ctx = canvas.getContext("2d");
    }
    function draw() {
        ctx.beginPath();
        ctx.moveTo(prevX, prevY);
        ctx.lineTo(currX, currY);
        ctx.strokeStyle = 'black';
        ctx.lineWidth = 2;
        ctx.stroke();
        ctx.closePath();
    }
    $(window).load(function(){
        var pcw = $('#page-container').width();
        var user_screen_width = {{$screen_width}};
        var scale = pcw/user_screen_width;
        var left = ((pcw - user_screen_width)*scale/2)-30;
        $('iframe').css({'transform':'scale('+scale+')'});
        $('iframe').css({'left':left});
        $('canvas').css({'transform':'scale('+scale+')'});
        $('canvas').css({'left':left});
        init();
        tracker_data.forEach(function(d){
            console.log(d);
            
            if(d.type=="move" && flag){
                currX = parseInt(d.x);
                currY = parseInt(d.y);
                draw();
                prevX = currX;
                prevY = currY;
            }
            if(!flag){
                prevX = parseInt(d.x);
                prevY = parseInt(d.y);
                flag = true;
            }
        })
    });
</script>
<div id="page-container" style="position: relative;">
    <iframe style="width:{{$screen_width}}px;height:400px;" src="{{url()}}"></iframe>
        <canvas style="position:absolute;top:0;" width="{{$screen_width}}" height="400" id="myCanvas">
                
        </canvas>
</div>
@endsection