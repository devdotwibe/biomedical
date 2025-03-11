
<?php
//use App\Settings; 
?>

    <!-- Logo -->
    <a href="<?php echo URL::to('dealer'); ?>" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><img src="{{ asset('images/company_logo.png') }}" width="50%" alt="" /></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><img src="{{ asset('images/company_logo.png') }}" width="50%" alt="" /></span>
    </a>

    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <!-- <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a> -->
      
      <div class="text-bold text-aqua compName">Welcome to <?php echo setting('DEALER_TITLE',"Dealer Control Panel"); //echo Settings::get('ADMIN_TITLE') ?></div>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          
          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">

              <span class="hidden-xs">Hi {{Auth::guard('dealer')->user()->dealer_name}}</span>
            </a>
            <ul class="dropdown-menu">

            <li>
                <a href="{{route('dealer.products.store')}}" class=""> Dashboard</a>
            </li>

            <li>
                <a href="<?php echo URL::to('dealer'); ?>/profile" class=""> Profile</a>
            </li>

            <!-- <li>
                <a href="<?php echo URL::to('dealer'); ?>/settings" class=""> Settings</a>
            </li> -->

            <li>
                <a href="<?php echo URL::to('dealer'); ?>/change-password" class=""> Change Password</a>
            </li>
            
            <li>
                <a href="<?php echo URL::to('dealer'); ?>/logout" class=""> Logout</a>
            </li>
            </ul>
          </li>
          
          <li>
                <a href="<?php echo URL::to('dealer'); ?>/logout" class=""> Logout</a>
            </li>
        </ul>
      </div>

    </nav>