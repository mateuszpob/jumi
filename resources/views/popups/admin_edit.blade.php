        <!-- Main page styles -->
        <link href="/css/main-admin.css" type="text/css" rel="stylesheet" />
        
        <!-- jQuery UI 1.11.2 -->
        <script src="/plugins/jQueryUI/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
        <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
        <script>
          $.widget.bridge('uibutton', $.ui.button);
        </script>
        <!-- Bootstrap 3.3.2 JS -->
        <script src="/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- Main admin scripts -->
        <script src="/js/main-admin.js" type="text/javascript"></script> 
        <!-- datepicker -->
        <script src="/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="/js/app.js" type="text/javascript"></script> 
        <!-- jQuery DataTables -->
        <script src="/plugins/datatables/jquery.dataTables.min.js" type="text/javascript"></script> 
<!--         jQuery Colorbox 
        <script src="/js/jquery.colorbox-min.js" type="text/javascript"></script> -->
        
        <!-- Socket.io -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.5/socket.io.min.js"></script>
       
        


<div class="popup-panel" style="@if(isset($popup_width)) width:{{$popup_width}}px;@endif position:relative;">
<!--    <input type="text" name="item_name">
    <input type="hidden" name="item_id">
    <textarea name="item_description"></textarea>
    <button class="popup-submit">Zapisz</button>-->
    <div class="popup-content">
        @yield('content')
    </div>
    
    <button class="popup-close" >X</button>
</div>
<script>
    // edycja itemow dla admina na zwyklych podstronach - zamkniecie popupa
    
    $('.submit-form').on('click', function () {
        $('.popup-close').trigger('click');
    });
    
    $('.popup-close').on('click', function () {
        
        $( '#item-flow-container' ).load( window.location.href+" #load-wrapper" );
        
        
        $('#popup-bck').fadeOut(50);
        $('#popup-bck').html('');
    });






</script>