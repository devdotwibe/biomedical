
<?php
//use App\Settings; 
?>
    <!-- Logo -->
    <a href="<?php echo URL::to('staff'); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="{{ asset('images/company_logo.png') }}" width="50%" alt="" /></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="{{ asset('images/company_logo.png') }}" width="50%" alt="" /></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      
      <div class="text-bold text-aqua compName">Welcome to <?php echo setting('ADMIN_TITLE'); //echo Settings::get('ADMIN_TITLE') ?></div>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
@php
$staff_id = session('STAFF_ID') ?? session('ADMIN_ID');
$staff_deti =  DB::select("select * from staff where `id`='".$staff_id."' ") ;

@endphp
              @if(!empty(auth()->guard('staff')->user()->name))

              <span class="hidden-xs">Hi {{auth()->guard('staff')->user()->name}}</span>

              @else

              <span class="hidden-xs">Hi {{auth()->guard('admin')->user()->name}}</span>

              @endif
            </a>
            <ul class="dropdown-menu">

            <li>
                <a href="<?php echo URL::to('staff'); ?>/" class=""><i class="fa fa-dashboard"></i> Dashboard</a>
            </li>

           

            <li>
                <a href="<?php echo URL::to('staff'); ?>/change-password" class=""><i class="fa fa-user-secret"></i> Change Password</a>
            </li>
            
            <li>
                <a href="<?php echo URL::to('staff'); ?>/logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
            </li>
            </ul>
          </li>
          
          <li>
                <a href="<?php echo URL::to('staff'); ?>/logout" class=""><i class="fa fa-sign-out"></i> Logout</a>
            </li>
        </ul>
      </div>

    </nav>