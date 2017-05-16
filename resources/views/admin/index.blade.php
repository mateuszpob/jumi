<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>AdminLTE 2 | Dashboard</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- Bootstrap 3.3.4 -->
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <!-- AdminLTE Skins. Choose a skin from the css/skins 
             folder instead of downloading all of them to reduce the load. -->
        <link href="/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
        <!-- jQuery Colorbox style -->
        <link rel="stylesheet" href="/css/colorbox.css" />
        <!-- Main page styles -->
        <link href="/css/main-admin.css" type="text/css" rel="stylesheet" />
        <!-- jQuery 2.1.4 -->
        <script src="/plugins/jQuery/jQuery-2.1.4.min.js" type="text/javascript"></script>
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
        <!-- jQuery Colorbox -->
        <script src="/js/jquery.colorbox-min.js" type="text/javascript"></script> 
        
        <!-- Socket.io -->
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.4.5/socket.io.min.js"></script>
       
        
    </head>
    <body class="skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="index2.html" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>D</b>LT</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Dupa</b>LTE</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top" role="navigation">
                    {{ \Auth::user()->email }}
                </nav>
            </header>

            <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
              <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                  <!-- Sidebar user panel -->
                  <div class="user-panel">

                  </div>
                  <ul class="sidebar-menu">
                    <li class="header">MAIN NAVIGATION</li>
                    <li class="active treeview">
                        <a href="">
                            <i class="fa fa-dashboard"></i> <span>Tracker</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                    </li>
                    <li class="active treeview">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>User</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('admin/users') }}"><i class="fa fa-circle-o"></i> Users</a></li>
                            <li><a href="{{ url('admin/roles') }}"><i class="fa fa-circle-o"></i> Roles</a></li>
                            
                            
                        </ul>
                    </li>
                    <li class="active treeview">
                        <a href="#">
                            <i class="fa fa-dashboard"></i> <span>Sklep</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('admin/items') }}"><i class="fa fa-circle-o"></i> Przedmoity</a></li>
                            <li><a href="{{ url('admin/categories') }}"><i class="fa fa-circle-o"></i> Kategorie</a></li>
                            <li><a href="{{ url('admin/schemas') }}"><i class="fa fa-circle-o"></i> Schematy</a></li>
                            <li><a href="{{ url('admin/shipment') }}"><i class="fa fa-circle-o"></i> Wysyłki</a></li>
                            <li><a href="{{ url('admin/product-groups') }}"><i class="fa fa-circle-o"></i> Grupy produktów</a></li>
                            <li class="active treeview">
                                <a href="#">
                                    <i class="fa fa-dashboard"></i> <span>Zamówienia</span> <i class="fa fa-angle-left pull-right"></i>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="{{ url('admin/orders') }}"><i class="fa fa-circle-o"></i> Wszystkie</a></li>


                                </ul>
                            </li>
                            
                        </ul>
                    </li>
                    
                    <li class="active treeview">
                        <a href="{{ url('admin/pages') }}">
                            <i class="fa fa-dashboard"></i> <span>Pages</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="{{ url('admin/pages') }}"><i class="fa fa-circle-o"></i> Pages</a></li>
                            <li><a href="{{ url('admin/templates') }}"><i class="fa fa-circle-o"></i> Templates</a></li>
                            
                            
                        </ul>
                    </li>
                    <li class="active treeview">
                        <a href="{{ url('admin/mails') }}">
                            <i class="fa fa-dashboard"></i> <span>Mails</span> <i class="fa fa-angle-left pull-right"></i>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="#">
                            <i class="fa fa-files-o"></i>
                            <span>Layout Options</span>
                            <span class="label label-primary pull-right">4</span>
                        </a>
                        <ul class="treeview-menu">
                          <li><a href="{{url('admin/elfinder/app')}}"><i class="fa fa-circle-o"></i>Disk - app</a></li>
                          <li><a href="{{url('admin/elfinder/shop')}}"><i class="fa fa-circle-o"></i>Disk - shop</a></li>
                          <li><a href="pages/layout/boxed.html"><i class="fa fa-circle-o"></i> Boxed</a></li>
                          <li><a href="pages/layout/fixed.html"><i class="fa fa-circle-o"></i> Fixed</a></li>
                          <li><a href="pages/layout/collapsed-sidebar.html"><i class="fa fa-circle-o"></i> Collapsed Sidebar</a></li>
                        </ul>
                    </li>
                    <!--
                    <li class="treeview">
                        <a href="#">
                          <i class="fa fa-share"></i> <span>Multilevel</span>
                          <i class="fa fa-angle-left pull-right"></i>
                        </a>
                        <ul class="treeview-menu">
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                            <li>
                                <a href="#"><i class="fa fa-circle-o"></i> Level One <i class="fa fa-angle-left pull-right"></i></a>
                                <ul class="treeview-menu">
                                    <li><a href="#"><i class="fa fa-circle-o"></i> Level Two</a></li>
                                    <li>
                                        <a href="#"><i class="fa fa-circle-o"></i> Level Two <i class="fa fa-angle-left pull-right"></i></a>
                                        <ul class="treeview-menu">
                                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                            <li><a href="#"><i class="fa fa-circle-o"></i> Level Three</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li><a href="#"><i class="fa fa-circle-o"></i> Level One</a></li>
                        </ul>
                    </li>
                    -->
                </section>
            </aside>
            
            
            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <div id="timer-mo"></div>
                        <small>Control panel</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">
                    @yield('content')
                    
                </section>
            </div>
        </div>
        
    <script>
        var curr_time = 0;
        var curr_mins = 0;
        myTimer();
        
        function myTimer() {
            var d = new Date();
            var curr_sec = curr_time % 60;
            if(curr_time > 0 && curr_sec == 0){
                curr_mins++;
            }
            document.getElementById("timer-mo").innerHTML = curr_mins + ':' + curr_sec;
            curr_time++;
                setTimeout(myTimer, 1000);
        }
        
    </script>
    </body>
</html>
  