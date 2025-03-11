<?php

use Illuminate\Support\Facades\Route;
$currentPath = Route::getFacadeRoot()->current()->uri();
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

      @php
            $staff_id = session('STAFF_ID');
            $permission = \App\Models\User_permission::where('staff_id', $staff_id)->first();
            $cor_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','customer')->first();

            $ib_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','ib')->first();

            $bio_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','bio')->first();

            $bec_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','bec')->first();

            $techsure_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','techsure')->first();
            
            $msa_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','msa')->first();

            $contract_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','contract')->first();

            $pm_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','pm')->first();

            $sales_bio_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','sales_bio')->first();

            $sales_bec_permission = \App\Models\CoordinatorPermission::where('staff_id', $staff_id)->where('type','sales_bec')->first();
      @endphp

      @if($staff_id =="32")

        <li class="<?php echo (strstr($currentPath,'staff/msp')) ? 'active' : '' ?>">
              <a href="{{route('msp.create')}}">
                <span> MSP</span>
              </a>
        </li>
        
      @endif

            <li class="treeview <?php echo strstr($currentPath, 'staff/transation') || strstr($currentPath, 'staff/transation') ? 'active menu-open' : ''; ?>">
                <a href="#">
                    <span>Transaction</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="<?php echo strstr($currentPath, 'staff/transation') ? 'active' : ''; ?>">
                        <a href="{{ route('transation.index') }}">
                            <span>All Transaction</span>
                        </a>
                    </li>
                    <li class="<?php echo strstr($currentPath, 'staff/Pendingtransaction') ? 'active' : ''; ?>">
                        <a href="{{ route('Pendingtransaction') }}">
                            <span>Pending Transaction</span>
                        </a>
                    </li>
                    <li class="<?php echo strstr($currentPath, 'staff/sales_order') ? 'active' : ''; ?>">
                        <a href="{{ route('sales_order') }}?type=Sale Order">
                            <span>Sales Order</span>
                        </a>
                    </li>
                    <li class="<?php echo strstr($currentPath, 'staff/sales_order') ? 'active' : ''; ?>">
                        <a href="{{ route('sales_order') }}?type=Test Return">
                            <span>Test Return</span>
                        </a>
                    </li>


                    <!-- <li class="<?php //echo (strstr($currentPath,'staff/transactionindex')) ? 'active' : ''
                    ?>">
              <a href="{{ route('transactionindex') }}">
             <span>Pending Transaction</span>
            </a>
            </li> -->

                    <?php 
         $staff_id = session('STAFF_ID');
         $mange_invoice =  DB::select("SELECT * FROM `transaction_manage_staffs` WHERE manage_section='Invoice' AND staff_id='".$staff_id."'" );
    	if(count($mange_invoice)>0){
         ?>
                    <li class="<?php echo strstr($currentPath, 'staff/invoice') ? 'active' : ''; ?>">
                        <a href="{{ route('invoice.index') }}">
                            <span>Manage Invoice</span>
                        </a>
                    </li>

                    <?php 
      }
         $staff_id = session('STAFF_ID');
         $mange_dispatch =  DB::select("SELECT * FROM `transaction_manage_staffs` WHERE manage_section='Dispatch Invoice' AND staff_id='".$staff_id."'" );
    	if(count($mange_dispatch)>0){
         ?>
                    <li class="<?php echo strstr($currentPath, 'staff/dispatch') ? 'active' : ''; ?>">
                        <a href="{{ route('dispatch.index') }}">
                            <span>Manage Dispatch</span>
                        </a>
                    </li>
                    <?php
            }
            ?>





                </ul>
            </li>


            <?php 
         $staff_id = session('STAFF_ID');
         $user_permission =  DB::select("SELECT * FROM `user_permission` WHERE FIND_IN_SET('Opportunity',section) AND staff_id='".$staff_id."'" );
    	
         if( optional($permission)->product_view == 'view' )
         {
      ?>
            <li class="treeview <?php echo strstr($currentPath, 'staff/category') || strstr($currentPath, 'staff/competition_product') || strstr($currentPath, 'staff/importExportView') || strstr($currentPath, 'staff/subcategory') || strstr($currentPath, 'staff/brand') || strstr($currentPath, 'staff/product') ? 'active menu-open' : ''; ?>">
                <a href="#">
                    <span>Products</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="<?php echo strstr($currentPath, '/products') ? 'active' : ''; ?>">
                        <a href="{{ route('products.index') }}"> <span>Manage Products</span></a>
                    </li>

                    @if( optional($permission)->product_view == 'admin')

                        <li class="<?php echo strstr($currentPath, 'staff/brand') ? 'active' : ''; ?>">
                            <a href="{{ route('brand.index') }}">
                                <span>Manage Brand</span>
                            </a>
                        </li>

                        <li class="<?php echo strstr($currentPath, 'staff/category_type') ? 'active' : ''; ?>">
                            <a href="{{ route('category_type.index') }}">
                                <span>Manage Category</span>
                            </a>
                        </li>

                        <li class="<?php echo strstr($currentPath, 'staff/product_type') ? 'active' : ''; ?>">
                            <a href="{{ route('product_type.index') }}">
                                <span>Product Type</span>
                            </a>
                        </li>
                        <li class="<?php echo strstr($currentPath, 'staff/category') ? 'active' : ''; ?>">
                            <a href="{{ route('category.index') }}">
                                <span>Manage Care Area</span>
                            </a>
                        </li>

                        <li class="<?php echo strstr($currentPath, 'staff/modality') ? 'active' : ''; ?>">
                            <a href="{{ route('modality.index') }}">
                                <span>Manage Modality</span>
                            </a>
                        </li>

                        <li class="<?php echo strstr($currentPath, 'staff/competition_product') ? 'active' : ''; ?>">
                            <a href="{{ route('competition_product.index') }}">
                                <span>Competition Product</span>
                            </a>
                        </li>

                    @endif

                </ul>
            </li> <?php } ?>



            <li><a href="<?php echo URL::to('staff'); ?>/"><span>Dashboard</span></a></li>



            <li><a href="<?php echo URL::to('staff'); ?>/change-password"><span>Change Password</span></a></li>

            @if(optional($permission)->ib_access_view =='view' || optional($ib_permission)->ib_view =='view')

            <li class="<?php echo strstr($currentPath, 'ib') ? 'active' : ''; ?>"><a href="/ib"> <span>Ib</span></a></li>

            @endif


            <li class="treeview">
                <a href="#">
                    <span>Service</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="">
                        <a href="{{ route('service-create', 1) }}">
                            <span>Corrective Repair</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('service-create', 2) }}">
                            <span>Preventive Repair</span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('service-create', 3) }}">
                            <span>Installation</span>
                        </a>
                    </li>
                </ul>
            </li>

            @if ( optional($contract_permission)->common_view == 'view' || optional($pm_permission)->common_view == 'view' ||  optional($sales_bio_permission)->common_view == 'view' ||  optional($sales_bec_permission)->common_view == 'view')

                <li class="treeview <?php echo (strstr($currentPath,'staff/pm_order')  || strstr($currentPath,'staff/pm_create')) ? 'active menu-open' : '' ?>">
                    <a href="#">
                    <span>Orders</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-right pull-right"></i>
                    </span>
                    </a>
                    <ul class="treeview-menu">
                       
                    @if ( optional($contract_permission)->common_view == 'view')

                        <li class="<?php echo (strstr($currentPath,'staff/pm_order')) ? 'active' : '' ?>">
                            <a href="{{route('pm_order.index')}}">
                        <span>MSA Contract</span>
                        </a>
                        </li> 

                    @endif

                    @if ( optional($pm_permission)->common_view == 'view')

                        <li class="<?php echo (strstr($currentPath,'staff/pm_create')) ? 'active' : '' ?>">
                            <a href="{{route('pm_create',2)}}">
                        <span>PM</span>
                        </a>
                        </li>

                    @endif

                    @if ( optional($sales_bio_permission)->common_view == 'view')

                        <li class="<?php echo (strstr($currentPath,'staff/pm_order')) ? 'active' : '' ?>">
                            <a href="{{route('sales.index' ,['company_type'=>"bio"])}}">
                        <span>Customer Order (BIO)</span>
                        </a>
                        </li>

                    @endif

                    @if ( optional($sales_bec_permission)->common_view == 'view')

                        <li class="<?php echo (strstr($currentPath,'staff/pm_order')) ? 'active' : '' ?>">
                            <a href="{{route('sales.index',['company_type'=>"bec"])}}">
                        <span>Customer Order (BEC)</span>
                        </a>
                        </li>

                    @endif
        
                
        
                
                    </ul>
                </li>                                               
    
            @endif


            <li class="<?php echo strstr($currentPath, 'staff/quote') ? 'active' : ''; ?>"><a href="<?php echo URL::to('staff'); ?>/quote"> <span>Quote</span></a></li>

            <li class="<?php echo strstr($currentPath, 'staff/WorkReport') ? 'active' : ''; ?>"><a href="<?php echo URL::to('staff'); ?>/WorkReport"> <span>Work Report</span></a>
            </li>

            <li class="<?php echo strstr($currentPath, 'staff/quicktask') ? 'active' : ''; ?>"><a href="<?php echo URL::to('staff'); ?>/quicktask"> <span>Quick Task</span></a></li>
            @if (
                $staff_id == 31 ||
                    $staff_id == 13 ||
                    $staff_id == 34 ||
                    $staff_id == 35 ||
                    $staff_id == 37 ||
                    $staff_id == 33 ||
                    $staff_id == 36)
                <li class="<?php echo strstr($currentPath, 'staff/Staffstatus') ? 'active' : ''; ?>"><a href="<?php echo URL::to('staff'); ?>/Staffstatus"><i
                            class="fa fa-user-secret text-aqua"></i> <span>Staff Status</span></a></li>
            @endif
            <?php
        $staff_id = session('STAFF_ID');   
         $user_list= DB::select('select * from assign_supervisor where  `supervisor_id`="'.$staff_id.'" ');
         if(count($user_list)>0)
         {
        ?>
            <li class="<?php echo strstr($currentPath, 'staff/changestatus') ? 'active' : ''; ?>"><a href="<?php echo URL::to('staff'); ?>/changestatus"> <span>Change Status</span></a>
            </li>
            <?php
         }
        ?>

        @if(optional($permission)->customer_view == 'view' || optional($cor_permission)->customer_view == 'view')

            <li class="<?php echo strstr($currentPath, 'customer') ? 'active' : ''; ?>"><a href="/customer"><span>Customer</span></a></li>

        @endif

        @if(optional($permission)->report_view == 'view')

        <li class="<?php echo strstr($currentPath, 'staff/staff-sale/report') ? 'active' : ''; ?>"><a href="<?php echo URL::to('staff'); ?>/staff-sale/report"><span>Report</span></a></li>

        @endif




            <!-- <li  class="<?php echo strstr($currentPath, 'staff/staff_lead_option') ? 'active' : ''; ?>"><a href="<?php echo URL::to('staff'); ?>/staff_lead_option"><i class="fa fa-user-secret text-aqua"></i> <span>Lead Option</span></a></li> -->

            <!-- <li  class=" treeview <?php echo strstr($currentPath, 'staff/staff_lead_option') || strstr($currentPath, 'staff/my_lead_option') ? 'active menu-open' : ''; ?>">
          <a href="#">
            <i class="fa fa-user-secret text-aqua"></i> <span>Lead Option</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo strstr($currentPath, 'staff/staff_lead_option') ? 'active' : ''; ?>">
              <a href="{{ url('staff/staff_lead_option') }}"><i class="   fa fa-table"></i> <span>Lead Option</span></a>
            </li>

           <li class="<?php echo strstr($currentPath, 'staff/my_lead_option') ? 'active' : ''; ?>">
              <a href="{{ url('staff/my_lead_option') }}"><i class="   fa fa-table"></i> <span>Created Lead Options</span></a>
            </li>
          </ul>
          
        </li> -->

           
            <li class="treeview <?php echo strstr($currentPath, 'staff/list_oppertunity') || strstr($currentPath, 'staff/create_oppertunity') || strstr($currentPath, 'staff/staff_lead_option') || strstr($currentPath, 'staff/approve_quote') ? 'active menu-open' : ''; ?>">
                <a href="#">
                    <span>Opportunity</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    @if (optional($permission)->opperbio_create == 'create' || optional($permission)->opperbec_create == 'create' || optional($permission)->oppertechsure_create == 'create' || optional($permission)->oppermsa_create == 'create')

                        <li class="<?php echo strstr($currentPath, 'staff/create_oppertunity') ? 'active' : ''; ?>">
                            <a href="{{ url('staff/create_oppertunity') }}"> <span>Add Opportunity</span></a>
                        </li>

                    @endif

                    @if (optional($permission)->opperbio_view == 'view' || optional($bio_permission)->opper_view == 'view')
                        <li class="<?php echo strstr($currentPath, 'staff/list_oppertunity') && request('type', '') == 'bio' ? 'active' : ''; ?>">
                            <a href="{{ url('staff/list_oppertunity') }}?type=bio"> <span>List Opportunity
                                    (BIO)</span></a>
                        </li>
                    @endif
                    
                    @if (optional($permission)->opperbec_view == 'view' || optional($bec_permission)->opper_view == 'view')
                        <li class="<?php echo strstr($currentPath, 'staff/list_oppertunity') && request('type', '') == 'bec' ? 'active' : ''; ?>">
                            <a href="{{ url('staff/list_oppertunity') }}?type=bec"> <span>List Opportunity
                                    (BEC)</span></a>
                        </li>
                    @endif

                    @if (optional($permission)->oppertechsure_view == 'view' || optional($techsure_permission)->opper_view == 'view')
                        <li class="<?php echo strstr($currentPath, 'staff/list_oppertunity') && request('type', '') == 'techsure' ? 'active' : ''; ?>">
                            <a href="{{ url('staff/list_oppertunity') }}?type=techsure"> <span>List Opportunity
                                    (TECHSURE)</span></a>
                        </li>
                    @endif

                    @if (optional($permission)->oppermsa_view == 'view' || optional($msa_permission)->opper_view == 'view')
                        <li class="<?php echo strstr($currentPath, 'staff/list_oppertunity') && request('type', '') == 'msa' ? 'active' : ''; ?>">
                            <a href="{{ url('staff/list_oppertunity') }}?type=msa"> <span>List Opportunity (MSA
                                    Proposal)</span></a>
                        </li>
                    @endif
                        <li class="<?php echo strstr($currentPath, 'staff/staff_lead_option') ? 'active' : ''; ?>">
                            <a href="{{ url('staff/staff_lead_option') }}"><span>Lead Option</span></a>
                        </li>

                    <?php 
               $staff_id = session('STAFF_ID');
                if($staff_id==32 || $staff_id==55 || $staff_id==35 || $staff_id==121 || $staff_id==56 || $staff_id== 127 ||  $staff_id== 129 ||  $staff_id== 130 )
                {
                  ?>
                    <li class="<?php echo strstr($currentPath, 'staff/approve_quote') ? 'active' : ''; ?>">
                        <a href="{{ url('staff/approve_quote') }}"><i class="fa fa-table"></i> <span>Approve
                                Quote</span></a>
                    </li>
                    <?php
                }
              ?>

                </ul>
            </li>
            <?php
   $staff_id = session('STAFF_ID');
   $user_permission =  DB::select("SELECT * FROM `user_permission` WHERE FIND_IN_SET('Service Task',section) AND staff_id='".$staff_id."'" );

  
if(count($user_permission)>0 || $staff_id ==56)
{
?>

            <li class="treeview <?php echo strstr($currentPath, 'staff/service_responce') || strstr($currentPath, 'staff/service_visit') || strstr($currentPath, 'staff/service_part') ? 'active menu-open' : ''; ?>">
                <a href="#">
                    <span>Service</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    <li class="<?php echo strstr($currentPath, 'staff/service_task') || strstr($currentPath, 'staff/AllTaskservice') ? 'active' : ''; ?>">
                        <a href="{{ route('service_task.index') }}">
                            <span>Service Task</span>
                        </a>
                    </li>


                    <li class="<?php echo strstr($currentPath, 'staff/AllTaskservice') ? 'active' : ''; ?>">
                        <a href="{{ route('AllTaskservice') }}">
                            <span> All task</span>
                        </a>
                    </li>
                    <li class="<?php echo strstr($currentPath, 'staff/service_responce') ? 'active' : ''; ?>">
                        <a href="">
                            <span> Priventive maintance</span>
                        </a>
                    </li>
                    <li class="<?php echo strstr($currentPath, 'staff/service_responce') ? 'active' : ''; ?>">
                        <a href="">
                            <span> Corrective repaires</span>
                        </a>
                    </li>
                    <li class="<?php echo strstr($currentPath, 'staff/service_responce') ? 'active' : ''; ?>">
                        <a href="">
                            <span> Instllation</span>
                        </a>
                    </li>
                    <li class="<?php echo strstr($currentPath, 'staff/service_responce') ? 'active' : ''; ?>">
                        <a href="">
                            <span> FMI</span>
                        </a>
                    </li>
                    <li class="<?php echo strstr($currentPath, 'staff/asset') ? 'active' : ''; ?>">
                        <a href="{{ route('asset') }}">
                            <span> Assets</span>
                        </a>
                    </li>



                </ul>
            </li>
            <?php
}
?>


            <?php
   $staff_id = session('STAFF_ID');
   $user_permission =  DB::select("SELECT * FROM `user_permission` WHERE FIND_IN_SET('Order',section) AND staff_id='".$staff_id."'" );

  
if(count($user_permission)>0)
{
?>

            <li class=" treeview <?php echo strstr($currentPath, 'staff/order') || strstr($currentPath, 'staff/order') ? 'active menu-open' : ''; ?>">
                <a href="#">
                    <span>Order</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="<?php echo strstr($currentPath, 'staff/list_order') ? 'active' : ''; ?>">
                        <a href="{{ url('staff/list_order') }}"></i> <span>Order</span></a>
                    </li>

                </ul>

            </li>
            <?php
}
?>

            <li><a href="<?php echo URL::to('staff'); ?>/logout"> <span>Logout</span></a></li>






        </ul>
    </section>
</aside>
