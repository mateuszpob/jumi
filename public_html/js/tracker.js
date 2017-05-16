var move = 1, rast = 2, send_moment = 10;
var point_stack = [];
var socket;
var time_start;
var session_id;

var cookie_name = 'tracker_sid';
var session_exp_days = 1;

function onmousemoveM(e){
    var dt = new Date();
    
    move++;
    if(move % rast == 0){
        var time_from_start = Date.now() - time_start;
        console.log(time_from_start)
        point_stack.push({
            type: 'move',
            pathname: window.location.pathname,
            time:time_from_start,
            x:e.pageX, 
            y:e.pageY
        });
    }
    if(move % send_moment == 0){ 
        sendData();
        move = 1;
    }
}

function sendData(){
    console.log(point_stack)
    if(point_stack.length){
        var points_data = {
            session_id: session_id,
            app_key: 'hwdpjp100%',
            session_started_at: time_start,
            width: window.innerWidth, 
            height: window.innerHeight,
            tracking_data: point_stack,
            origin: window.location.origin
        }
        socket.emit('points_data', points_data);
        point_stack = [];
        console.log('Start DAte: '+time_start)
    }
}




function setCookie(cname, cvalue, exdays) {
    var d = new Date();
//    d.setTime(d.getTime() + (exdays*24*60*60*1000)); // dni
    d.setTime(d.getTime() + (exdays*60*1000)); // minuty
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
} 
function getSessionId(){
    var sid = getCookie('tracker_sid');
    if(sid !== "")
        return sid;
    var new_sid = Array(40+1).join((Math.random().toString(36)+'00000000000000000').slice(2, 18)).slice(0, 40);
    setCookie("tracker_sid", new_sid, session_exp_days);
    return new_sid;
}




$(document).ready(function(){
    var body = document.getElementsByTagName("BODY")[0];
    time_start = Date.now();
    socket = io.connect('http://127.0.0.1:1337');
    session_id = getSessionId();
   
    body.addEventListener("mousemove", function(e){
        onmousemoveM(e);
    });
});


window.addEventListener("beforeunload", function (event) {
    // to i tak nie zadziała
    sendData();
});

window.addEventListener("mouseout", function (event) { console.log('SPIERDOLILA')
    sendData();
});