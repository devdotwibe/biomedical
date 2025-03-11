<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin - @yield('title')</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/Ionicons/css/ionicons.min.css') }}">
    <!-- jvectormap -->
    <!-- <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/jvectormap/jquery-jvectormap.css') }}">
   -->

    <!--Datatables -->
    <link rel="stylesheet"
        href="{{ asset('AdminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/skins/_all-skins.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/custom-style.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/AdminLTE.min.css') }}">

    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/user-style.css') }}">
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
            @include('staff/layouts/header')
        </header>

        @include('staff/layouts/sidebar')

        <div class="content-wrapper">
            @yield('content')
        </div>

        <footer class="main-footer">
            @include('staff/layouts/footer')
        </footer>


        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
            <!-- Create the tabs -->
            <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab">
                    <h3 class="control-sidebar-heading">Recent Activity</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-birthday-cake bg-red"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                                    <p>Will be 23 on April 24th</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-user bg-yellow"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                                    <p>New phone +1(800)555-1234</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                                    <p>nora@example.com</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <i class="menu-icon fa fa-file-code-o bg-green"></i>

                                <div class="menu-info">
                                    <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                                    <p>Execution time 5 seconds</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                    <h3 class="control-sidebar-heading">Tasks Progress</h3>
                    <ul class="control-sidebar-menu">
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Custom Template Design
                                    <span class="label label-danger pull-right">70%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Update Resume
                                    <span class="label label-success pull-right">95%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-success" style="width: 95%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Laravel Integration
                                    <span class="label label-warning pull-right">50%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0)">
                                <h4 class="control-sidebar-subheading">
                                    Back End Framework
                                    <span class="label label-primary pull-right">68%</span>
                                </h4>

                                <div class="progress progress-xxs">
                                    <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <!-- /.control-sidebar-menu -->

                </div>
                <!-- /.tab-pane -->

                <!-- Settings tab content -->
                <div class="tab-pane" id="control-sidebar-settings-tab">
                    <form method="post">
                        <h3 class="control-sidebar-heading">General Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Report panel usage
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Some information about this general settings option
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Allow mail redirect
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Other sets of options are available
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Expose author name in posts
                                <input type="checkbox" class="pull-right" checked>
                            </label>

                            <p>
                                Allow the user to show his name in blog posts
                            </p>
                        </div>
                        <!-- /.form-group -->

                        <h3 class="control-sidebar-heading">Chat Settings</h3>

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Show me as online
                                <input type="checkbox" class="pull-right" checked>
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Turn off notifications
                                <input type="checkbox" class="pull-right">
                            </label>
                        </div>
                        <!-- /.form-group -->

                        <div class="form-group">
                            <label class="control-sidebar-subheading">
                                Delete chat history
                                <a href="javascript:void(0)" class="text-red pull-right"><i
                                        class="fa fa-trash-o"></i></a>
                            </label>
                        </div>
                        <!-- /.form-group -->
                    </form>
                </div>
                <!-- /.tab-pane -->
            </div>
        </aside>
        <!-- /.control-sidebar -->
        <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
        <div class="control-sidebar-bg"></div>

    </div>

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
                    <button type="button" class="btn btn-primary" id="btnDeleteItem" data-id=""
                        data-href="">Delete</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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
                    <button type="button" class="btn btn-primary" id="btnDeleteAll" data-id=""
                        data-href="">Delete</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
        <div class="slides"></div>
        <h3 class="title"></h3>
        <!--<a class="prev">‹</a>-->
        <a class="prev">&lsaquo;</a>
        <!-- <a class="next">›</a>-->
        <a class="next">&rsaquo;</a>
        <!--<a class="close">×</a>-->
        <a class="close">&times</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
    </div>


    <!-- jQuery 3 -->
    <script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery-validate.js') }}"></script>
    <script src="{{ asset('AdminLTE/bower_components/jquery/dist/jquery-validate-additional-methods.js') }}"></script>
    <!-- Bootstrap 3.3.7 -->
    <script src="{{ asset('AdminLTE/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <?php /*<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script> */ ?>
    <script src="https://cdn.datatables.net/rowreorder/1.2.5/js/dataTables.rowReorder.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.5/css/rowReorder.dataTables.min.css"
        type="text/css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"
        type="text/css" />





    <!-- FastClick -->
    <script src="{{ asset('AdminLTE/bower_components/fastclick/lib/fastclick.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('AdminLTE/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>
    <!-- jvectormap  -->
    <!-- <script src="{{ asset('AdminLTE/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script> -->
    <!-- SlimScroll -->
    <script src="{{ asset('AdminLTE/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

    <!-- CK Editor -->
    <script src="{{ asset('AdminLTE/bower_components/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ asset('AdminLTE/bower_components/bootstrap-notify/bootstrap-notify.min.js') }}"></script>

    <script type="text/javascript"
        src="{{ asset('AdminLTE/bower_components/fancybox/lib/jquery.mousewheel-3.0.6.pack.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/fancybox/source/jquery.fancybox.css?v=2.1.5') }}"
        type="text/css" media="screen" />
    <script type="text/javascript"
        src="{{ asset('AdminLTE/bower_components/fancybox/source/jquery.fancybox.pack.js?v=2.1.5') }}"></script>
    <script type="text/javascript"
        src="{{ asset('AdminLTE/bower_components/fancybox/source/helpers/jquery.fancybox-media.js?v=1.0.6') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('AdminLTE/bower_components/image-upload/css/common.css') }}"
        media="screen" />
    <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/blueimp_gallery/css/blueimp-gallery.min.css') }}">

    <script src="{{ asset('AdminLTE/bower_components/blueimp_gallery/js/blueimp-gallery.min.js') }}"></script>

    <link href="{{ asset('AdminLTE/bower_components/imageCrop/dist/cropper.css') }}" rel="stylesheet">

    <script src="{{ asset('AdminLTE/bower_components/imageCrop/dist/cropper.js') }}"></script>

    <!-- ChartJS -->
    <!--<script src="{{ asset('AdminLTE/bower_components/chart.js/Chart.js') }}"></script>-->
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <!--<script src="{{ asset('AdminLTE/dist/js/pages/dashboard2.js') }}"></script>-->
    <!-- AdminLTE for demo purposes -->
    <!--<script src="{{ asset('AdminLTE/dist/js/demo.js') }}"></script>    -->
    <script src="{{ asset('js/admin.js') }}"></script>
    <script src="{{ asset('js/jquery-sortable.js') }}"></script>
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">

    <script type="text/javascript">
        var APP_URL = {!! json_encode(url('/')) !!}
    </script>



    <script type="text/javascript">


        const popoverButton = document.getElementById('popover-button');
        const popover = document.getElementById('popover');

        popoverButton.addEventListener('click', () => {
            
            if (event.target.closest('.preventclick')) {
                return;
            }

            event.stopPropagation(); 
            if (popover.style.display === 'block') {
                popover.style.display = 'none';
            } else {
                popover.style.display = 'block';

            }
        });

        // document.addEventListener('click', (event) => {
            
        //     if (!popover.contains(event.target) && event.target !== popoverButton) {

        //         if (!event.target.closest('.show_pmdtails')) {
        //             popover.style.display = 'none';
        //             console.log('Popover hidden');
        //         }
        //     }
        // });
        

        $('.example1').click(function(event) {
            event = event || window.event;

            var target = event.target || event.srcElement,
                link = target.src ? target.parentNode : target,
                options = {
                    index: link,
                    event: event,
                    carousel: true
                }, //carousel: true
                links = $("a[class*='example1']"); //this.getElementsByTagName('a');

            blueimp.Gallery(links, options);
        });
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        function stafflocationupdate() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $.post("{{ route('staff.update_current_location') }}", {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    }, function(res) {
                        setTimeout(stafflocationupdate, 1000 * 60);
                        console.log(res)
                    })
                });
            }
        }
        $(function() {
            stafflocationupdate();
        })
    </script>

    <script type="text/javascript">
        //  $(document).ready(function() {
        //    console.log('...');
        //    console.log($(window).width());

        //   if ($(window).width() <= 1024) {
        //   }
        //   else{
        //     $(".sidebar-toggle").trigger("click");
        //   }
        // });
    </script>

    @yield ('scripts')

</body>

</html>
