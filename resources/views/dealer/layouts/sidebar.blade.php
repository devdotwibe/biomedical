<?php

use Illuminate\Support\Facades\Route;
$currentPath= Route::getFacadeRoot()->current()->uri();
//print_r($currentPath);
//print_r(Route::getFacadeRoot()->current());
?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image" style="display: none;">
          
        </div>
        <div class="pull-left info">
<!--          <p>Welcome to Test
          </p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>-->
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form" style="display:none;">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat">
                  <i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        
        <li class="<?php echo (strstr($currentPath,'admin/msp')) ? 'active' : '' ?>">
              <a href="{{route('admin.msp.create')}}">
                <span> MSP</span>
              </a>
        </li>

         
        <li class="<?php echo (strstr($currentPath,'admin/marketspace')) ? 'active' : '' ?>">
              <a href="{{route('admin.marketspace.create')}}">
                <span> Marketspace</span>
              </a>
        </li>



        <li class="<?php echo (strstr($currentPath,'admin/ib') ) ? 'active' : '' ?>">
          <a href="{{route('admin.ib-index')}}">
            <span>IB</span>
          </a>
        </li>

        <li class="treeview">
          <a href="#">
            <span>Service</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              
          <li class="">
            <a href="{{route('admin.service-create',1)}}">
              <span>Corrective Repair</span>
            </a>
            </li>

            <li class="">
              <a href="{{route('admin.service-create',2)}}">
              <span>Preventive Repair</span>
            </a>
            </li>
            <li class="">
              <a href="{{route('admin.service-create',3)}}">
             <span>Installation</span>
            </a>
            </li>

          </ul>
        </li>
        

       


        <li class="treeview <?php echo (strstr($currentPath,'admin/category') || strstr($currentPath,'admin/units') || strstr($currentPath,'admin/category_type') || strstr($currentPath,'admin/competition_product') || strstr($currentPath,'admin/importExportView') || strstr($currentPath,'admin/subcategory')  || strstr($currentPath,'admin/brand') || strstr($currentPath,'admin/product')) ? 'active menu-open' : '' ?>">
          <a href="#">
            <span>Products</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="<?php echo (strstr($currentPath,'admin/products')) ? 'active' : '' ?>">
                <a href="{{route('admin.products.index')}}"><span>Manage Products</span></a>
              </li>
              <li class="<?php echo (strstr($currentPath,'admin/brand')) ? 'active' : '' ?>">
              <a href="{{route('admin.brand.index')}}">
              <span>Manage Brand</span>
            </a>
            </li>
            
            <li class="<?php echo (strstr($currentPath,'admin/category_type')) ? 'active' : '' ?>">
              <a href="{{route('admin.category_type.index')}}">
             <span>Manage Category</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/product_type')) ? 'active' : '' ?>">
              <a href="{{route('admin.product_type.index')}}">
             <span>Product Type</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/category')) ? 'active' : '' ?>">
              <a href="{{route('admin.category.index')}}">
              <span>Manage Care Area</span>
            </a>
            </li>
            
            <li class="<?php echo (strstr($currentPath,'admin/modality')) ? 'active' : '' ?>">
              <a href="{{route('admin.modality.index')}}">
               <span>Manage Modality</span>
              </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/subcategory')) ? 'active' : '' ?>">
              <a href="{{route('admin.subcategory.index')}}">
               <span>Manage Sub Category</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/competition_product')) ? 'active' : '' ?>">
              <a href="{{route('admin.competition_product.index')}}">
              <span>Competition Product</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/importExportView')) ? 'active' : '' ?>">
              <a href="{{route('admin.importExportView')}}">
             <span>Product Export and Import</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/units')) ? 'active' : '' ?>">
              <a href="{{route('admin.units.index')}}">
              <span>Units</span>
            </a>
            </li>

          </ul>
        </li>

        <li class="treeview <?php echo (strstr($currentPath,'admin/transation')  || strstr($currentPath,'admin/transation')) ? 'active menu-open' : '' ?>">
          <a href="#">
            <span>Transaction</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              
          <li class="<?php echo (strstr($currentPath,'admin/transation')) ? 'active' : '' ?>">
              <a href="{{route('admin.transation.index')}}">
             <span>All Transaction</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/sales_order')) ? 'active' : '' ?>">
              <a href="{{route('admin.sales_order')}}">
             <span>Sales Order Create</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/transaction_manage_staff')) ? 'active' : '' ?>">
              <a href="{{route('admin.transaction_manage_staff.create')}}">
             <span>Manage Transaction Staff</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/invoice')) ? 'active' : '' ?>">
              <a href="{{route('admin.invoice.index')}}">
             <span>Manage Invoice</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/credit')) ? 'active' : '' ?>">
              <a href="{{route('admin.credit.index')}}">
             <span>Manage Credit Note</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/invoice_complete_flow')) ? 'active' : '' ?>">
              <a href="{{route('admin.invoice_complete_flow.index')}}">
             <span>Manage Invoice Complete</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/courier')) ? 'active' : '' ?>">
              <a href="{{route('admin.courier.index')}}">
             <span>Courier</span>
            </a>
            </li>
            
            

          </ul>
        </li>


        <li class="treeview <?php echo (strstr($currentPath,'admin/warehouse') || strstr($currentPath,'admin/warehouse_shelf') || strstr($currentPath,'admin/warehouse_rack') ) ? 'active menu-open' : '' ?>">
          <a href="#">
            <span>Warehouse</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="<?php echo (strstr($currentPath,'admin/warehouse')) ? 'active' : '' ?>">
                <a href="{{route('admin.warehouse.index')}}"> <span>Manage Warehouse</span></a>
              </li>
            
            
            <li class="<?php echo (strstr($currentPath,'admin/warehouse_shelf')) ? 'active' : '' ?>">
              <a href="{{route('admin.warehouse_shelf.index')}}">
             <span>Warehouse Shelf</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/warehouse_rack')) ? 'active' : '' ?>">
              <a href="{{route('admin.warehouse_rack.index')}}">
               <span>Warehouse Racks</span>
            </a>
            </li>

          </ul>
        </li>



        <li class="treeview <?php echo (strstr($currentPath,'admin/service_responce') || strstr($currentPath,'admin/service_visit') || strstr($currentPath,'admin/service_part') ) ? 'active menu-open' : '' ?>">
          <a href="#">
             <span>Service</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
         





 <li class="<?php echo (strstr($currentPath,'admin/service_task') || strstr($currentPath,'admin/AllTaskservice')) ? 'active' : '' ?>">
              <a href="{{route('admin.service_task.index')}}">
               <span>Service Task</span>
            </a>
            </li>


            <li class="<?php echo (strstr($currentPath,'admin/AllTaskservice')) ? 'active' : '' ?>">
              <a href="{{route('admin.AllTaskservice')}}">
              <span> All task</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/service_responce')) ? 'active' : '' ?>">
              <a href="">
              <span> Priventive maintance</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/service_responce')) ? 'active' : '' ?>">
              <a href="">
               <span> Corrective repaires</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/service_responce')) ? 'active' : '' ?>">
              <a href="">
              <span> Instllation</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/service_responce')) ? 'active' : '' ?>">
              <a href="">
              <span> FMI</span>
            </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/asset')) ? 'active' : '' ?>">
              <a href="{{route('admin.asset')}}">
               <span> Assets</span>
              </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/import-asset')) ? 'active' : '' ?>">
              <a href="{{route('admin.import-asset')}}">
                <span> Import Assets</span>
              </a>
            </li>
            <li class="<?php echo (strstr($currentPath,'admin/contract')) ? 'active' : '' ?>">
              <a href="{{route('admin.contract')}}">
                <span> Contract</span>
              </a>
            </li>

            <!-- <li class="<?php echo (strstr($currentPath,'admin/service_responce')) ? 'active' : '' ?>">
              <a href="{{route('admin.service_responce.index')}}">
              <i class="fa fa-table"></i> <span>First Responce</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/service_visit')) ? 'active' : '' ?>">
              <a href="{{route('admin.service_visit.index')}}">
              <i class="fa fa-table"></i> <span>Visit</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/service_part')) ? 'active' : '' ?>">
              <a href="{{route('admin.service_part.index')}}">
              <i class="fa fa-table"></i> <span>Part Intent</span>
            </a>
            </li> -->

          </ul>
        </li>

        <li class="<?php echo (strstr($currentPath,'admin/purchase')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/purchase"><span>Purchase Order</span></a></li>
        <li class="<?php echo (strstr($currentPath,'admin/goodsrecivenote')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/goodsrecivenote"><span>Goods Recive Note</span></a></li>
        <li class="<?php echo (strstr($currentPath,'admin/importstock')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/importstock"><span>Import Stock</span></a></li>
        <li class="<?php echo (strstr($currentPath,'admin/stock_register')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/stock_register"><span>Stock Register</span></a></li>
        <li class="<?php echo (strstr($currentPath,'admin/inoutregister')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/inoutregister"><span>Inventory In and Out Log</span></a></li>
        <li class="<?php echo (strstr($currentPath,'admin/invoice')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/invoice"> <span>Invoice</span></a></li>
        <li class="<?php echo (strstr($currentPath,'admin/vendor')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/vendor"><span>Vendor</span></a></li>

        
        <li class="header">LINKS</li>
        <li><a href="<?php echo URL::to('admin'); ?>/"><span>Dashboard</span></a></li>
        <li><a href="<?php echo URL::to('admin'); ?>/settings"><span>Settings</span></a></li>
        <li><a href="<?php echo URL::to('admin'); ?>/change-password"><span>Change Password</span></a></li>
        <li><a href="<?php echo URL::to('admin'); ?>/testimonial"><span>Testimonial</span></a></li>

         <li class="<?php echo (strstr($currentPath,'admin/staff')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/staff"><span>Staff</span></a></li>
         <li  class="<?php echo (strstr($currentPath,'admin/quote')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/quote"> <span>Quote</span></a></li>

<li  class="<?php echo (strstr($currentPath,'admin/Staffstatus')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/Staffstatus"><span>Staff Status</span></a></li>
<li  class="<?php echo (strstr($currentPath,'admin/workupdate')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/workupdate"><span>Work Update</span></a></li>

         <li  class="<?php echo (strstr($currentPath,'admin/assginsupervisor')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/assginsupervisor"><span>Assign Supervisor</span></a></li>

 <li  class="<?php echo (strstr($currentPath,'admin/dailyclosing')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/dailyclosing"><span>Daily Closing</span></a></li>

<li  class="<?php echo (strstr($currentPath,'admin/dailyclosingstaff')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/dailyclosingstaff"><span>Daily Closing Staff</span></a></li>


   <li class="treeview <?php echo (strstr($currentPath,'admin/task') || strstr($currentPath,'admin/verifyTask')  || strstr($currentPath,'admin/AllTask') || strstr($currentPath,'admin/infinityTask') || strstr($currentPath,'admin/importExportTask') || strstr($currentPath,'admin/inprogressTask') || strstr($currentPath,'admin/completeTask') || strstr($currentPath,'admin/pendingTask') || strstr($currentPath,'admin/approvedTask') ) ? 'active menu-open' : '' ?>">
          <a href="#">
            <span>Task</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>

          <?php 
          $count_notstart =  DB::select("select count(*) as count_task from  task where status='Not Started' ");
          $count_complete =  DB::select("select count(*) as count_task from  task where status='Complete' ");
          $count_pending =  DB::select("select count(*) as count_task from  task where status='Pending' ");
          $count_progress =  DB::select("select count(*) as count_task from task where status='In Progress' ");
          ?>
          <ul class="treeview-menu">
          <li class="<?php echo (strstr($currentPath,'admin/AllTask')) ? 'active' : '' ?>">
                <a href="{{route('admin.AllTask')}}"><span>All Task </span></a>
              </li>
              <li class="<?php echo (strstr($currentPath,'admin/infinityTask')) ? 'active' : '' ?>">
                <a href="{{route('admin.infinityTask')}}"><span>Infinity </span></a>
              </li>

              <li class="<?php echo (strstr($currentPath,'admin/verifyTask')) ? 'active' : '' ?>">
                <a href="{{route('admin.verifyTask')}}"> <span>Verify </span></a>
              </li>
          
              
          
              <li class="<?php echo (strstr($currentPath,'admin/completeTask')) ? 'active' : '' ?>">
                <a href="{{route('admin.completeTask')}}"><span>Completed ( {{$count_complete[0]->count_task}} )</span></a>
              </li>
              <!-- <li class="<?php echo (strstr($currentPath,'admin/approvedTask')) ? 'active' : '' ?>">
                <a href="{{route('admin.approvedTask')}}"><i class="fa fa-list"></i> <span>Approved</span></a>
              </li> -->
            
             

              <li class="<?php echo (strstr($currentPath,'admin/importExportTask')) ? 'active' : '' ?>">
                <a href="{{route('admin.importExportTask')}}"><span>Export Task </span></a>
              </li>
              </ul>
          </li>

        <li  class="<?php echo (strstr($currentPath,'admin/changestatus')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/changestatus"><span>Count Details</span></a></li>
        <li><a href="<?php echo URL::to('admin'); ?>/logout"><span>Logout</span></a></li>

         <li  class="<?php echo (strstr($currentPath,'admin/user_permission')) ? 'active' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/user_permission"><span>User Permission</span></a></li>


     
        <li class="treeview <?php echo (strstr($currentPath,'admin/customer') ) ? 'active menu-open' : '' ?>">
          <a href="#">
            <span>Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="<?php echo (strstr($currentPath,'admin/customer') ) ? 'active' : '' ?>">
                <a href="{{route('admin.customer.index')}}"><span>Customers</span></a>
              </li>

               <li class="<?php echo (strstr($currentPath,'admin/importExportViewCustomer')) ? 'active' : '' ?>">
              <a href="{{route('admin.importExportViewCustomer')}}">
              <span>Customer Export and Import</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/seller')) ? 'active' : '' ?>">
              <a href="">
             <span>Seller</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/staff')) ? 'active' : '' ?>">
              <a href="">
              <span>Staff Member</span>
            </a>
            </li>

            <li class="<?php echo (strstr($currentPath,'admin/admin')) ? 'active' : '' ?>">
              <a href="">
              <span>Admin</span>
            </a>
            </li>

        

            

          </ul>
        </li>


          
        <li  class=" <?php echo (strstr($currentPath,'admin/hosdesignation') || strstr($currentPath,'admin/hosdeparment') || strstr($currentPath,'admin/company') || strstr($currentPath,'admin/designation') ) ? 'active menu-open' : '' ?>"><a href="<?php echo URL::to('admin'); ?>/company"><span>Options</span></a>
        </li>

      


        <li class="treeview <?php echo (strstr($currentPath,'admin/list_oppertunity') || strstr($currentPath,'admin/lead_option') ) ? 'active menu-open' : '' ?>">
          <a href="#">
            <span>Opportunity</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="<?php echo (strstr($currentPath,'admin/list_oppertunity') ) ? 'active' : '' ?>">
                <a href="{{url('admin/list_oppertunity')}}"> <span>List Opportunity</span></a>
              </li>
              <li class="<?php echo (strstr($currentPath,'admin/lead_option') ) ? 'active' : '' ?>">
                <a href="{{url('admin/lead_option')}}"> <span>Lead Option</span></a>
              </li>

              <li class="<?php echo (strstr($currentPath,'admin/approve_quote') ) ? 'active' : '' ?>">
                <a href="{{url('admin/approve_quote')}}"><i class="fa fa-table"></i> <span>Approve Quote</span></a>
              </li>
              <li class="<?php echo (strstr($currentPath,'admin/oppertunity_report') ) ? 'active' : '' ?>">
                <a href="{{url('admin/oppertunity_report')}}"><i class="fa fa-table"></i> <span>Report</span></a>
              </li>
              <!-- <li class="<?php echo (strstr($currentPath,'admin/importExportViewCustomer')) ? 'active' : '' ?>">
              <a href="{{url('admin/list_oppertunity')}}">
              <i class="fa fa-table"></i> <span>Update Oppertunity</span>
              </a>
              </li>-->
              <!-- <li class="<?php echo (strstr($currentPath,'admin/prospectus') ) ? 'active' : '' ?>">
                <a href="{{url('admin/prospectus')}}"><i class="fa fa-table"></i> <span>Prospectus</span></a>
              </li> -->
          </ul>
        </li>

        <!-- <li  class=" <?php echo (strstr($currentPath,'admin/lead_option') ) ? 'active menu-open' : '' ?>">
          <a href="{{url('admin/lead_option')}}"><i class="   fa fa-user-secret text-aqua"></i> <span>Lead Option</span></a>
        </li> -->

        <!-- <li  class=" treeview <?php echo (strstr($currentPath,'admin/lead_option') || strstr($currentPath,'admin/my_lead_option') ) ? 'active menu-open' : '' ?>">
          <a href="#">
            <i class="fa fa-user-secret text-aqua"></i> <span>Lead Option</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo (strstr($currentPath,'admin/lead_option') ) ? 'active' : '' ?>">
              <a href="{{url('admin/lead_option')}}"><i class="   fa fa-table"></i> <span>Lead Option</span></a>
            </li>
          </ul>
          
        </li> -->

        <li  class=" treeview <?php echo (strstr($currentPath,'admin/order') || strstr($currentPath,'admin/order') ) ? 'active menu-open' : '' ?>">
          <a href="#">
            <span>Order</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo (strstr($currentPath,'admin/list_order') ) ? 'active' : '' ?>">
              <a href="{{url('admin/list_order')}}"><span>Order</span></a>
            </li>

          </ul>
          
        </li>

        <li  class=" treeview <?php echo (strstr($currentPath,'admin/home_banner') || strstr($currentPath,'admin/home_banner') ) ? 'active menu-open' : '' ?>">
          <a href="#">
            <span>Home Page</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-right pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo (strstr($currentPath,'admin/home_banner') ) ? 'active' : '' ?>">
              <a href="{{url('admin/home_banner')}}"><span>Home Banner</span></a>
            </li>

          </ul>
          
        </li>





      </ul>
    </section>
  </aside>

  <?php
  
  use App\Http\Controllers\admin\TaskController;
  echo TaskController::cron(); 
  
  echo TaskController::cron_customdays(); 
  echo TaskController::update_dailytask(); 


  ?>


  
 
 