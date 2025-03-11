<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Dealer - @yield('title')</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- jvectormap -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/jvectormap/jquery-jvectormap.css') }}">
  
  <!--Datatables -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/skins/_all-skins.min.css') }}">
  <!-- Theme style -->
    <!-- <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/scrollBar.css') }}"> -->
  <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/custom-style.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  
  <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body class="hold-transition skin-blue sidebar-collapse">
<div class="wrapper">
    
    <header class="main-header">
        @include('dealer/layouts/header')
    </header>
  

   <div class="content-wrapper">
        @yield('content')
    </div>
        
    <footer class="main-footer">
        @include('dealer/layouts/footer')
    </footer> 
        
<div class="modal fade" id="modalDelete">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Confirm Delete</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete this row?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btnDeleteItem" onclick="deleteItemConfirm($(this))" data-href="">Delete</button>
        </div>
      </div>
    </div>
</div>
    
<div class="modal fade" id="modalDeleteAll">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Confirm Delete</h4>
        </div>
        <div class="modal-body">
          <p>Are you sure you want to delete selected rows?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="btnDeleteAll" data-id="" data-href="">Delete</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- jQuery 3 -->
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery-validate.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery-validate-additional-methods.js') }}"></script>


<!-- DataTables -->
<script src="{{ asset('AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<?php /*<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> */ ?>
<script src="https://cdn.datatables.net/rowreorder/1.2.5/js/dataTables.rowReorder.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

<link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.5/css/rowReorder.dataTables.min.css" type="text/css"/>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" type="text/css"/>





<!-- FastClick -->
<script src="{{ asset('AdminLTE/bower_components/fastclick/lib/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
<script src="{{ asset('AdminLTE/dist/js/mcustomscrollbar.js') }}"></script>

<!-- Sparkline -->
<script src="{{ asset('AdminLTE/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
<!-- jvectormap  -->
<script src="{{ asset('AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>
<!-- SlimScroll -->
<script src="{{ asset('AdminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

<!-- CK Editor -->
<script src="{{ asset('AdminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

<script type="text/javascript" src="{{ asset('AdminLTE/bower_components/fancybox/lib/jquery.mousewheel-3.0.6.pack.js') }}"></script>
<link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/fancybox/source/jquery.fancybox.css?v=2.1.5') }}" type="text/css" media="screen" />
<script type="text/javascript" src="{{ asset('AdminLTE/bower_components/fancybox/source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>
<script type="text/javascript" src="{{ asset('AdminLTE/bower_components/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6') }}"></script>  
<link rel="stylesheet" type="text/css" href="{{ asset('AdminLTE/bower_components/image-upload/css/common.css') }}" media="screen" />
<link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/blueimp_gallery/css/blueimp-gallery.min.css') }}">

<script src="{{ asset('AdminLTE/bower_components/blueimp_gallery/js/blueimp-gallery.min.js') }}"></script>

<link  href="{{ asset('AdminLTE/bower_components/imageCrop/dist/cropper.css') }}" rel="stylesheet">

<script src="{{ asset('AdminLTE/bower_components/imageCrop/dist/cropper.js') }}"></script>

<!-- ChartJS -->
<!--<script src="{{ asset('AdminLTE/bower_components/chart.js/Chart.js') }}"></script>-->
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<!--<script src="{{ asset('AdminLTE/dist/js/pages/dashboard2.js') }}"></script>-->
<!-- AdminLTE for demo purposes -->
<!--<script src="{{ asset('AdminLTE/dist/js/demo.js') }}"></script>    -->
 <script src="{{ asset('js/dealer.js') }}"></script>   
<script src="{{ asset('js/jquery-sortable.js') }}"></script>


<link  href="{{ asset('css/admin.css') }}" rel="stylesheet">

<script type="text/javascript">
var APP_URL = {!! json_encode(url('/')) !!}
</script>

  

<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>




@yield ('scripts')
  
</body>
</html>