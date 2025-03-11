@extends('staff/layouts.app')
@section('title', 'Edit Transaction')
@section('content')
<section class="content-header">
      <h1>
      Edit Transaction
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.transation.index')}}">Manage Transaction</a></li>
        <li class="active">Edit Transaction</li>
      </ol>
    </section>
<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <!-- /.box-header -->
            <!-- form start -->
            @if(session()->has('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif
            @if(session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session()->get('error_message') }}
                </div>
            @endif
            <p class="error-content alert-danger">
            {{ $errors->first('name') }}
            {{ $errors->first('image_name') }}
            </p>

            <form role="form" name="frm_brand" id="frm_brand" method="post" action="{{ route('staff.transation.update', $transation->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}

                <div class="box-body">


                  <div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
        @if($type=="tech")
        <li class="active"><a href="#technical_approval" data-toggle="tab">Technical Approval</a></li>
        @endif

				@if($type=="msp")
        <li class="active"><a href="#otherpro" data-toggle="tab">MSP,Payout,otherprovisions if any</a></li>
        @endif
			
          
          
          <!-- <li><a href="#stock_availiability" data-toggle="tab">Stock Availiability</a></li>
          <li><a href="#financial_approval" data-toggle="tab">Financial Approval</a></li>
          <li><a href="#cust_conform" data-toggle="tab">Customer Confirmation on COD/Site readness</a></li> -->

				</ul>
				<div class="tab-content">


				
          <div class="tab-pane @if($type=="tech") active @endif" id="technical_approval">
            <!-- Technical Approval start -->
           
            <div class="box-body row">

                <div class="panel panel-default">
                <div class="panel-body ">
                  <h2>Configuration</h2>
                <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>

                    <th>Name</th>
                    <th>Qty</th>
                    <th>FOC</th>
                    <th>Unit Price</th>
                    <th>HSN</th>
                    <th>CGST</th>
                    <th>SGST</th>
                    <th>IGST</th>
                    <th>Cess</th>
                    
                        <th>MSP</th>
                        <th>Surplus / Deficit</th>
                    <th>Net Amount</th>

                  </tr>
                </thead>
                <tbody id="tabledata">
                <?php
                if(count($transation_product)>0)
                {
                  foreach($transation_product as $values)
                  {
                    if( $values->product_id>0){
                      $product = App\Product::find($values->product_id);
                  }
                   ?>
                    <tr>

                      <td><?php   if($product){
                        echo $product->name;
                      }?></td>
                      <td>{{$values->quantity}}</td>
                      <td>{{$values->foc}}</td>
                      <td>{{$values->sale_amount}}</td>
                      <td>{{$values->hsn}}</td>
                      <td>{{$values->cgst}}</td>
                      <td>{{$values->sgst}}</td>
                      <td>{{$values->igst}}</td>
                      <td>{{$values->cess}}</td>
                     
                      <td>{{$values->msp}}</td>
                      <td>{{$values->amt}}</td>

                    </tr>
                    <?php
                  }
                }
                else{
                  ?>
                   <tr  data-from ="staffquote">
                       <td colspan="4" class="noresult">No result</td>
                    </tr>
                  <?php
                }
                ?>

               </table>

             

                </div>
                </div>

                </div>

                
            <div class="box-body row bdr-split">
                <div class="tech-left col-md-6 col-sm-6 col-lg-6">
                    <div class="form-group config-sect box-boder">
                      <div class="panel panel-default">
                      <div class="panel-body ">
                     <h2>Warranty </h2>
                     <table class="table tech-table2" >
                            <thead>
                              <tr>
                                <th>Standard Warranty</th>
                                <th>Additional Warranty</th>
                              </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{$transation->stan_warrenty}}</td>
                                    <td>{{$transation->add_warrenty}}</td>
                                </tr>
                            </tbody>
                         </table>
                      </div>
                      </div>
                </div>
                <div class="form-group po-sect box-boder">
                <div class="panel panel-default">
                <div class="panel-body ">
                    <h2>PO Details</h2>
                     <table class="table tech-table3" >
                      <thead>
                        <tr>
                           <?php
                if($transation->user_id>0)
                {
                  $user = App\User::find($transation->user_id);
                  $gstno=$user->gst;
                  if($user)
                  {
                    $customer_name=$user->business_name;
                  }
                  if($user->state_id>0)
                  {
                    $state = App\State::find($user->state_id);
                    $state_name=$state->name;
                  }
                  if($user->district_id>0)
                  {
                    $district = App\District::find($user->district_id);
                    $district_name=$district->name;
                  }
                }
                if($transation->contact_id>0)
                {
                  $contact_person = App\Contact_person::find($transation->contact_id);
                  $contact_person_name=$contact_person->name;
                  $contact_person_email=$contact_person->email;
                  $contact_person_phone=$contact_person->phone;
                }
                else{
                  $contact_person_name='';
                  $contact_person_email='';
                  $contact_person_phone='';
                }
                ?>
                          <th>PO Details</th>
                          <th>State</th>
                          <th>District</th>
                          <th>Customer Name</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>{{$transation->collect_date}}</td>
                              <td>@if($state_name)
                              {{$state_name}}
                              @endif</td>
                              <td>@if($district_name)
                  {{$district_name}}
                  @endif</td>
                              <td>@if($customer_name)
                  {{$customer_name}}
                  @endif</td>
                          </tr>
                      </tbody>
                   </table>


               <table class="table tech-table4" >
                    <thead>
                        <tr>
                          <th>Shipping Address</th>
                          <th>Contact Person</th>
                        </tr>
                      </thead>
                      <tbody>
                          <tr>
                              <td>{{$transation->user_shipping}}</td>
                              <td>@if($contact_person_name)
                  {{$contact_person_name}}
                  @endif</td>
                          </tr>
                        </tbody>
                   </table>



                   <table class="table tech-table5" >
                  <thead>
                    <tr>
                      <th>Mail</th>
                      <th>GST Number </th>
                      <th>Designation*</th>
                      <th>Phone*</th>
                    </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td>@if($contact_person_email)
                  {{$contact_person_email}}
                  @endif</td>
                          <td>@if($gstno)
                  {{$gstno}}
                  @endif</td>
                          <td>{{$transation->add_warrenty}}</td>
                          <td>@if($contact_person_phone)
                  {{$contact_person_phone}}
                  @endif</td>
                      </tr>
                  </tbody>
               </table>

          


                </div>
                </div>
                </div>
                 <div class="form-group buton-sect" >

                  <?php 
                    if($transation->current_status=="Technical Approval")
                    {
                      echo '<button type="button" class="mdm-btn-line submit-btn  approval_customer_approval"  onclick="approval_customer()">Approve</button>';
                      echo '  <button type="button" class="mdm-btn-line submit-btn  approval_customer_approved" style="display:none;"  >Approved</button>';
                   ?>
                   
                   <a href="{{ route('staff.transation.edit',$transation->id) }}?type=tech" class="mdm-btn-line cancel-btn"  >Edit</a>
                   <?php
                     
                    }
                    else{
                      echo '  <button type="button" class="mdm-btn-line submit-btn "  >Approved</button>';
                    }
                    ?>

                  </div>
                </div>
                 <div class="tech-right col-md-6 col-sm-6 col-lg-6">
                     <div class="technical-dtl box-boder">
           
                       <div class="panel panel-default">
                       <div class="panel-body ">
                         <div class="row">
                       <?php
                       if(count($transation_pocopy)>0)
                       {
                         foreach($transation_pocopy as $values)
                         {
                           $imgpath=asset("storage/app/public/transation/$values->image_name");
                           ?>
                           <!-- <a download="<?php echo $imgpath;?>" href="<?php echo $imgpath;?>">Download</a> -->
                           <div class="col-md-12">
                           <iframe src="<?php echo $imgpath;?>" height="300" width="600"></iframe>
                           </div>
                         </div>
                         <div class="row">
                           <?php
                         }
                       }
                       if($transation->attach_gst!='')
                       {
                         $imgpath=asset("storage/app/public/transation/$transation->attach_gst");
                         ?>
                         <div class="col-md-12">
                         <iframe src="<?php echo $imgpath;?>" height="300" width="600"></iframe>
                         </div>
                         <?php
                       }
                       ?>
                     </div>
                       </div>
                       </div>

                 </div>
                 </div>


                 
                </div>

            <!-- Technical approval end -->
          </div>
          <div class="tab-pane @if($type=="msp") active @endif " id="otherpro">

          <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>
                    <th>No.</th>
                  
                    <th>Name</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>MSP</th>
                    <th>Net Amount</th>
                    <th>Surplus / Deficit</th>
                    <th>Insentive</th>
                  </tr>
                </thead>
                <tbody id="tabledata">
                <?php
                if(count($transation_product)>0)
                {$k=0;$p=1;$total_msp=0;$total_net=0;$total_ins=0;
                  foreach($transation_product as $values)
                  {
                    if($values->product_id>0){
                      $product = App\Product::find($values->product_id);
                    }
                   
                    ?>
                    <tr  data-from ="staffquote" class="tr_{{$values->product_id}}">
                    <td>{{$p}}</td>
                  

                    <td>{{$product->name}}</td>
                <td>{{$values->quantity}}</td>
               <td>{{$values->sale_amount}}

               </td>

               <td><input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" type="number" style="width:80px;" value="{{$values->msp}}" id="mspamount_{{$values->product_id}}"  class="msp" name="msp[]" data-id="{{$values->product_id}}" style="width:60px;" onchange="change_msp({{$values->product_id}})"  onkeyup="change_msp({{$values->product_id}})">
              </td>
               <td>{{$values->amt}}
               <input type="hidden" value="{{$values->amt}}" id="saleamount_{{$values->product_id}}"  class="salemaount" name="salemaount[]" data-id="{{$values->product_id}}" style="width:60px;" >
               </td>
               <td>
               <?php
               $net_amount=$values->amt;
               
               $total_net +=$net_amount;
               $msp=$values->msp;
               $total_msp +=$msp;
               $diffe=$net_amount-$msp;
               $total_ins +=$values->insentive;
               if($diffe>0)
               {
                $column_color="green";
               }
               else if($diffe<0){
                $column_color="red";
               }
               else if($diffe==0){
                $column_color="orange";
                }
               ?>
               <span style="background-color:<?php echo $column_color;?>" class="surplus_{{$values->product_id}}">{{$diffe}}</span> </td>
               <td><input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')" type="number" value="{{$values->insentive}}" id="insentive_{{$values->product_id}}"  class="insentive" name="insentive[]" data-id="{{$values->product_id}}" style="width:60px;" onchange="change_incentive({{$values->product_id}})"  onkeyup="change_incentive({{$values->product_id}})">
               <div class="inse_loader_{{$values->product_id}}" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
              </td>

         

                    </tr>
                    

                    <?php
                    $k++; $p++;
                  }
                  ?>
                  <tr> 
                  <td></td>
                 
                  <td></td>
                  <td></td>
                  <td>Total</td>
                  <td id="msp_val">{{$total_msp}}</td>
                  <td>{{$total_net}}</td>
                  <td></td>
                  <td id="total_insentive">{{$total_ins}}</td>
                  
                  </tr>
                  <?php
                  if($transation->owner>0)
                    {
                      $staff_owner = App\Staff::find($transation->owner);
                      if($staff_owner)
                      {
                        $owner=$staff_owner->name;
                      }else{$owner='';}
                    }else{$owner='';}
                    if($transation->second_owner>0)
                    {
                      $staff_second_owner = App\Staff::find($transation->second_owner);
                      if($staff_second_owner)
                      {
                        $second_owner=$staff_second_owner->name;
                      }else{$second_owner='';}
                    }else{$second_owner='';}
                    ?>
                  <tr> 
                  <td></td>
                 
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>Owner</td>
                  <td >{{$owner}}</td>
                  <td><input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')"
                   type="text"  onchange="change_ownerval()"  onkeyup="change_ownerval()" value="{{$transation->per_owner}}" id="owner_value"  class="owner_value" name="owner_value"  style="width:60px;" ><span>%</span>
                   <?php 
                   if($transation->per_owner>0)
                   {
                    $owner_persen_persa = ($transation->per_owner / 100) * $total_ins;
                   }
                   else{
                     $owner_persen_persa='';
                   }
                   ?>
                   <span id="owner_persen">{{$owner_persen_persa}}</span>
                   </td>
                  
                  </tr>

                  <tr> 
                  <td></td>
                 
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td>Secondary Owner</td>
                  <td>{{$second_owner}}</td>
                  <td><input oninput="this.value = this.value.replace(/[^0-9\.]/g, '').split(/\./).slice(0, 2).join('.')"
                   type="number" onchange="change_secondval()"   onkeyup="change_secondval()"  value="{{$transation->per_second_owner}}" id="secondowner_value"  class="secondowner_value" name="secondowner_value"  style="width:60px;" ><span>%</span>
                   <?php 
                   if($transation->per_second_owner>0)
                   {
                    $sownser_persen_persa = ($transation->per_second_owner / 100) * $total_ins;
                   }
                   else{
                     $sownser_persen_persa='';
                   }
                   ?>

                    <span id="secondowner_persen">{{$sownser_persen_persa}}</span>
                   </td>
                  
                  </tr>

                  <tr> 
                  <td></td>
                 
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  
                  <td>
                  @if($transation->current_status!="Technical Approval" && $transation->current_status!="MSP Approval")
                  
                  <button type="button" class="mdm-btn-line submit-btn "  >Approved</button>
                @endif
                @if($transation->current_status=="MSP Approval")
                
                  <button type="button" class="mdm-btn-line submit-btn  approval_msp_not_approve"  onclick="approval_msp_owner()">Approve</button>
                  <button type="button" class="mdm-btn-line submit-btn  approval_msp_owner" style="display:none;" >Approved</button>
                  @endif
                
                  
                   </td>
                  
                  </tr>




                  <?php
                }
                else{
                  echo '<tr><td>No Result</td></tr>';
                }
                    ?>
               </table>

          </div>
          <div class="tab-pane " id="stock_availiability">

          <table id="cmsTable" class="table table-bordered table-striped data-" >
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Name</th>
                   

                    <th>Qty</th>
                    <th>Available Qty</th>
                 
                  </tr>
                </thead>
                <tbody id="tabledata">
                <?php
                if(count($transation_product)>0)
                {$k=0;
                  foreach($transation_product as $values)
                  {
                    $date=date("Y-m-d");
                   // $stock_exit =  DB::select("select * from stock_register where `product_id`='".$values->product_id."' order by id desc limit 1  ");
                   $stock_exit =  DB::select(" select *,sum(stock_in_hand) as tot_stock from  stock_register where `product_id`='".$values->product_id."'  group by product_id ");
                  
                    if($stock_exit)
                    {
                      $stock_count=$stock_exit[0]->stock_in_hand;
                    }
                    else{
                      $stock_count=0;
                    }
                    if($values->product_id>0){
                      $product = App\Product::find($values->product_id);
                    }
                    if($product->image_name==null || $product->image_name=='')
                    {
                      $imgs=asset('images/no-image.jpg');
                    }
                    else{
                      $imgs=asset('storage/app/public/products/thumbnail/'.$product->image_name);
                    }
                ?>
                    <tr  data-from ="staffquote" class="tr_{{$values->product_id}}">
               <td><img width="50px" height="50px" src="{{$imgs}}"/></td><td>{{$product->name}}</td>
              <td><input type="text" @if($transation->stock_terms=="Y") readonly="true" @endif    value="{{$values->quantity}}" id="qn_stock_{{$values->product_id}}" name="stock_quantity[]" class="quantity" onkeyup="update_qty(this.value,{{$values->product_id}})" data-id="{{$values->product_id}}" style="width:40px;">
              <div class="update_qty_{{$values->product_id}}" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
              </td>
              <td><input type="text"  disabled="true"  value="{{$stock_count}}" id="qn__avail_stock{{$values->product_id}}" name="stok_in_hand_qty[]" class="quantity"  data-id="{{$values->product_id}}" style="width:40px;"></td>
              
              </tr>

                    </tr>

                    <?php
                    $k++;
                  }
                }
                else{
                  echo '<tr><td>No Result</td></tr>';
                }
                    ?>
               </table>

               
               <?php
                  if($transation->stock_terms=="N")
                  {
                    echo '<button type="button" class="mdm-btn-line submit-btn stock_terms_approval"  onclick="stock_terms_approval()">Approve</button>';
                    echo '  <button type="button" class="mdm-btn-line submit-btn stock_terms_approved" style="display:none;"  >Approved</button>';
                   // echo '<button type="button" class="btn btn-warning"  onclick="edit_stock_terms()">Edit</button>';
                  }
                  else{
                    echo '  <button type="button" class="mdm-btn-line submit-btn "  >Approved</button>';
                  }
                  ?>


               <div class="panel panel-default">
                 <div class="panel-body ">

                  <div class="form-group col-md-12 ">
                  <h3>Delivery terms </h3>
                  <p>{{$transation->del_terms}}</p>
                  <h3>Expected date of supply </h3>
                  <p>{{$transation->expect_date}}</p>

                  <?php
                  if($transation->approval_delivery_terms=="N")
                  {
                    echo '<button type="button" class="mdm-btn-line submit-btn delivery_terms_approval"  onclick="delivery_terms_approval()">Approve</button>';
                    echo '  <button type="button" class="mdm-btn-line submit-btn delivery_terms_approved" style="display:none;"  >Approved</button>';
                  
                  }
                  else{
                    echo '  <button type="button" class="mdm-btn-line submit-btn"  >Approved</button>';
                  }
                  ?>


                  </div>
                </div>
              </div>

              


          </div>
          <div class="tab-pane " id="financial_approval">

          <div class="panel panel-default">
                 <div class="panel-body ">

                  <div class="form-group col-md-12 ">
                  <h3>Payment terms </h3>
                  <p>{{$transation->payment_terms}}</p>
                
                  <?php  
                  if($transation->current_status=="Financial Approval")
                  {
                    echo '<button type="button" class="mdm-btn-line submit-btn  payment_terms_approval"  onclick="payment_terms_approval()">Approve</button>';
                    echo '  <button type="button" class="mdm-btn-line submit-btn  payment_terms_approved" style="display:none;"  >Approved</button>';
                  
                  }
                  if($transation->current_status!="Financial Approval" && $transation->current_status!="Technical Approval"  && $transation->current_status!="MSP Approval" ){
                    echo '  <button type="button" class="mdm-btn-line submit-btn  "  >Approved</button>';
                  }
                  ?>

                  </div>
                </div>
              </div>

          </div>
         
          <div class="tab-pane " id="cust_conform">66
          </div>
       </div>
       </div>



              </div>
              <!-- /.box-body -->

            </form>
          </div>
        </div>
      </div>
</section>




<div class="modal fade" id="modal_ship" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel" style="color:#000;">Add Shipping Address</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="add_address" id="add_address" method="post">

        <div class="form-group  col-md-6">
                  <label for="name">Street Address1</label>
                  <input type="text"   id="shipping_address1" name="shipping_address1[]" class="form-control"  placeholder="Address1" value="">

                  <span class="error_message" id="shipping_address1_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Street Address2</label>
                  <input type="text"  id="shipping_address2" name="shipping_address2[]" class="form-control"  placeholder="Address2" value="">
                  <span class="error_message" id="shipping_address2_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Country</label>
                  <select id="shipping_country_id" name="shipping_country_id" class="form-control">
                  <option value="">Select Country</option>
                  @foreach($country as $values_con)
                  <?php
               // $sel = ($values->country_id == $values_con->id) ? 'selected': '';
                  echo '<option value="'.$values_con->id.'" >'.$values_con->name.'</option>';
                  ?>

                  @endforeach
                  </select>
                  <span class="error_message" id="shipping_country_id_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">State</label>
                  <input type="text" id="shipping_state" name="shipping_state" class="form-control" value="" placeholder="State">
                  <span class="error_message" id="shipping_state_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">City</label>
                  <input type="text" id="shipping_city" name="shipping_city" class="form-control" value="" placeholder="City">
                  <span class="error_message" id="shipping_city_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Zip</label>
                  <input type="text" id="shipping_zip" name="shipping_zip" class="form-control" value="" placeholder="Zip">
                  <span class="error_message" id="shipping_zip_message" style="display: none">Field is required</span>
                </div>



        </form>
      </div>
      <div class="modal-footer">
      <span class="success_msg" style="display:none;color:green">Data saved successfully</span>
      <button type="button" class="btn btn-primary"  onclick="save_shipping()">Save</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>




<div class="modal fade" id="modal_ship_address_view" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title" id="exampleModalLabel" style="color:#000;">Select Shipping Address</h3>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form>

      <div class="display_address"></div>



        </form>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-primary"  onclick="add_shipaddress()" >Add</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal_success_tran" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Success</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Successfully Approved Transaction</p>
      </div>
      <div class="modal-footer">
       
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


@endsection
@section('scripts')


  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />


 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css" rel="stylesheet" />

    <script type="text/javascript">
jQuery(document).ready(function() {
  if ($(window).width() <= 1024) {
  }
  else{
    $(".sidebar-toggle").trigger("click");
  }
});
     function edit_podetails()
    {
      $(".save_podetails").show();
      $("#collect_date").removeAttr("readonly");
      $("#user_shipping").removeAttr("readonly");
      $("#contact_id").removeAttr("readonly");
        
    }
    function save_podetails()
    {
      var collect_date=$("#collect_date").val();
      
      if(collect_date!='')
      {
        $("#collect_date_message").hide();
      }
      else{
        $("#collect_date_message").show();
      }
      var user_address=$("#user_address").val();
      var user_shipping=$("#user_shipping").val();
      var designation=$("#designation").val();
      var contact_phone=$("#contact_phone").val();
      var contact_mail=$("#contact_mail").val();
      var contact_id=$("#contact_id").val();
      
      var gst=$("#gst").val();
      
      
      if(collect_date!='')
      {
        var url = APP_URL+'/staff/save_po_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,collect_date:collect_date,user_address:user_address,user_shipping:user_shipping,contact_id:contact_id,designation:designation,contact_phone:contact_phone,contact_mail:contact_mail,gst:gst
                  },
                  success: function (data)
                  {
                    $(".save_podetails").hide();
                    $("#po_success").show();
                    setTimeout(function(){ $("#po_success").hide(); }, 3000);
                    $("#collect_date").attr("readonly", "readonly");
                    $("#user_shipping").attr("readonly", "readonly");
                    $("#contact_id").attr("readonly", "readonly");
                  }
          });
      }
    }
    function edit_certification(){
      $(".save_certification").show();
      $("#owner").removeAttr("readonly");
      $("#second_owner").removeAttr("readonly");
      $("#po").removeAttr("readonly");
      $("#po_date").removeAttr("readonly");
    }
    function save_certification(){
    
      var owner=$("#owner").val();
      var second_owner=$("#second_owner").val();
      var po=$("#po").val();
      var po_date=$("#po_date").val();
      if(owner!='')
      {
        $("#owner_message").hide();
      }
      else{
        $("#owner_message").show();
      }
      if(second_owner!='')
      {
        $("#second_owner_message").hide();
      }
      else{
        $("#second_owner_message").show();
      }
      if(po!='')
      {
        $("#po_message").hide();
      }
      else{
        $("#po_message").show();
      }
      if(po_date!='')
      {
        $("#po_date_message").hide();
      }
      else{
        $("#po_date_message").show();
      }
      if(owner!='' && second_owner!='' && po!='' && po_date!='')
      {
        
        var url = APP_URL+'/staff/save_certifi_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,owner:owner,second_owner:second_owner,po:po,po_date:po_date
                  },
                  success: function (data)
                  {
                    $(".save_certification").hide();
                    $("#frm_brand").submit();
                    $("#cert_success").show();
                    setTimeout(function(){ $("#cert_success").hide(); }, 3000);
                    $("#owner").attr("readonly", "readonly");
                    $("#second_owner").attr("readonly", "readonly");
                    $("#po").attr("readonly", "readonly");
                    $("#po_date").attr("readonly", "readonly");
                  }
          });
      }
    }
    
    function edit_payment_term()
    {
      $("#payment_terms").removeAttr("readonly");
    }
    function save_payment_term()
    {
      
      var payment_terms=$("#payment_terms").val();
      if(payment_terms!='')
      {
        $("#payment_terms_message").hide();
      }
      else{
        $("#payment_terms_message").show();
      }
      if(payment_terms!='')
      {
        var url = APP_URL+'/staff/save_payment_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,payment_terms:payment_terms
                  },
                  success: function (data)
                  {
                    $("#pay_success").show();
                    setTimeout(function(){ $("#pay_success").hide(); }, 3000);
                    $("#payment_terms").attr("readonly", "readonly");
                  }
          });
      }
    }
    function edit_delivery()
    {
      $(".save_delivery").show();
      $("#del_terms").removeAttr("readonly");
      $("#expect_date").removeAttr("readonly");
    }
    function save_delivery()
    {  
      var del_terms=$("#del_terms").val();
      var expect_date=$("#expect_date").val();
      if(del_terms!='')
      {
        $("#del_terms_message").hide();
      }
      else{
        $("#del_terms_message").show();
      }
      if(expect_date!='')
      {
        $("#expect_date_message").hide();
      }
      else{
        $("#expect_date_message").show();
      }
      if(del_terms!='' && expect_date!='')
      {
            
        var url = APP_URL+'/staff/save_delivery_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,del_terms:del_terms,expect_date:expect_date
                  },
                  success: function (data)
                  {
                    $(".save_delivery").hide();
                    $("#del_success").show();
                    setTimeout(function(){ $("#del_success").hide(); }, 3000);
                    $("#del_terms").attr("readonly", "readonly");
                    $("#expect_date").attr("readonly", "readonly");
                  }
          });
      }
    }
    
    function edit_other_terms()
    {
      $(".save_other_terms").show();
      $("#other_terms").removeAttr("readonly");
    }
    function save_other_terms()
    {
      var other_terms=$("#other_terms").val();
      if(other_terms!='')
      {
        $("#other_terms_message").hide();
      }
      else{
        $("#other_terms_message").show();
      }
      if(other_terms!='')
      {
        var url = APP_URL+'/staff/save_other_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,other_terms:other_terms
                  },
                  success: function (data)
                  {
                    $(".save_other_terms").hide();
                    $("#otherterm_success").show();
                    setTimeout(function(){ $("#otherterm_success").hide(); }, 3000);
                    $("#other_terms").attr("readonly", "readonly");
                  }
          });
      }
    }
    
    function edit_warrenty()
    {
$(".save_warrenty").show();
      $("#stan_warrenty").removeAttr("readonly");
      $("#add_warrenty").removeAttr("readonly");
      
      
    }
    function save_warrenty()
    {
      var stan_warrenty=$("#stan_warrenty").val();
      var add_warrenty=$("#add_warrenty").val();
      if(stan_warrenty!='')
      {
        $("#stan_warrenty_message").hide();
      }
      else{
        $("#stan_warrenty_message").show();
      }
      if(add_warrenty!='')
      {
        $("#add_warrenty_message").hide();
      }
      else{
        $("#add_warrenty_message").show();
      }
      if(stan_warrenty!='' && add_warrenty!='')
      {
        var url = APP_URL+'/staff/save_config_transation';
        var transation_id="<?php echo $transation->id?>";
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:transation_id,add_warrenty:add_warrenty,stan_warrenty:stan_warrenty
                  },
                  success: function (data)
                  {
                    $(".save_warrenty").hide();
                    $("#conf_success").show();
                    setTimeout(function(){ $("#conf_success").hide(); }, 3000);
                    $("#stan_warrenty").attr("readonly", "readonly");
                    $("#add_warrenty").attr("readonly", "readonly");
                  }
          });
    
      }
    }
   /* function approval_msp_owner(){
      
      $(".approval_msp_not_approve").hide();
      $(".approval_msp_owner").show();
      var url = APP_URL+'/staff/approval_transation_mspowner';
      var owner_value=$("#owner_value").val();
        var secondowner_value=$("#secondowner_value").val();
       if(owner_value!='' && secondowner_value!='')
       {
        var id='<?php echo $transation->id;?>';
          $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data:{
                    id:id,owner_value:owner_value,secondowner_value:secondowner_value
                  },
                  success: function (data)
                  {
                  
                  }
          });
       }
       else{
         alert("Please check owner persentage");
       }
        
    }*/
    function approval_msp_owner(){
      
      
      
      var url = APP_URL+'/staff/approval_transaction_staff';
        var type_approval='MSP Approval';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            status: type_approval,trans_id:id
          },
          success: function (data)
          {
            $("#modal_success_tran").modal('show');
            $(".approval_msp_not_approve").hide();
      $(".approval_msp_owner").show();
            setTimeout(function(){ location.reload(); }, 2000);
          }
   });
        
    }
    function change_ownerval(){
      var owner_value=$("#owner_value").val();
      if(owner_value>100)
      {
        alert('Please enter less than 100')
        $("#owner_value").val('');
        $("#owner_persen").html(' ');
      }
      else{
        var secondowner_value=$("#secondowner_value").val();
        if(secondowner_value>0)
        {
          var send_ow_val=parseInt(secondowner_value)+parseInt(owner_value);
          if(send_ow_val>100)
          {
            alert('Please check value')
            $("#owner_value").val('');
            $("#owner_persen").html(' ');
            
          }else{
            var total_insentive=$("#total_insentive").html();
            var per=total_insentive * owner_value / 100;
            $("#owner_persen").html(per);
          }
         
           
        }
        else{
          var total_insentive=$("#total_insentive").html();
            var per=total_insentive * owner_value / 100;
            $("#owner_persen").html(per);
        }
      
        
      }
     
      
      
    /*  var owner_value=$("#owner_value").val();
       var send_ow_val=100-parseFloat(owner_value);
      $("#secondowner_value").val(send_ow_val);
      var myTotal=0;
      $('input[name^="insentive[]').each(function() {
      // alert( $(this).val())
        myTotal = parseFloat($(this).val())+parseFloat(myTotal);
    });
    var discount_owner= (myTotal - ( myTotal * owner_value / 100 )).toFixed(2);
    var discount_secondowner= (myTotal - ( myTotal * send_ow_val / 100 )).toFixed(2);
    
    $("#owner_persen").html(discount_secondowner);
    $("#secondowner_persen").html(discount_owner);*/
    }
    function change_secondval(){
      
      var secondowner_value=$("#secondowner_value").val();
      if(secondowner_value>100)
      {
        alert('Please enter less than 100')
        $("#secondowner_value").val('');
        $("#secondowner_persen").html(' ');
      }
      else{
        var owner_value=$("#owner_value").val();
        if(owner_value>0)
        {
          var send_ow_val=parseInt(secondowner_value)+parseInt(owner_value);
          if(send_ow_val>100)
          {
            alert('Please check value');
            $("#secondowner_persen").html(' ');
           
          }else{
            var total_insentive=$("#total_insentive").html();
      var per=total_insentive * secondowner_value / 100;
      $("#secondowner_persen").html(per);
          }
          
          
        }else{
          var total_insentive=$("#total_insentive").html();
      var per=total_insentive * secondowner_value / 100;
      $("#secondowner_persen").html(per);
        }
        
      }
      /*var owner_value=$("#owner_value").val();
       var send_ow_val=100-parseFloat(owner_value);
      $("#secondowner_value").val(send_ow_val);
      var myTotal=0;
      $('input[name^="insentive[]').each(function() {
      // alert( $(this).val())
        myTotal = parseFloat($(this).val())+parseFloat(myTotal);
    });
    var discount_owner= (myTotal - ( myTotal * owner_value / 100 )).toFixed(2);
    var discount_secondowner= (myTotal - ( myTotal * send_ow_val / 100 )).toFixed(2);
    
    $("#owner_persen").html(discount_secondowner);
    $("#secondowner_persen").html(discount_owner);*/
    }
    
    function change_incentive(product_id)
    {
        $(".inse_loader_"+product_id).show();
        var transation_id='<?php echo $transation->id;?>';
        var insentive=$("#insentive_"+product_id).val();
        var url = APP_URL+'/staff/save_transation_insentive';
      
var tot=0;
$('input[name="insentive[]"]').each(function(){
  if($(this).val()>0)
  {
    tot =parseInt($(this).val())+parseInt(tot);
  }
 
});
$("#total_insentive").html(tot);
if($("#owner_value").val()>0)
{
  //tot
  var total_insentive=$("#total_insentive").html();
var per=total_insentive * $("#owner_value").val() / 100;
$("#owner_persen").html(per);
}
if($("#secondowner_value").val()>0)
{
  //tot
  var total_insentive=$("#total_insentive").html();
var per=total_insentive * $("#secondowner_value").val() / 100;
$("#secondowner_persen").html(per);
}
        $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            insentive: insentive,product_id:product_id,transation_id:transation_id
          },
          success: function (data)
          {
            $(".inse_loader_"+product_id).hide();
          }
   });
    }
    function change_msp(product_id)
    {
        var net_amount=$("#saleamount_"+product_id).val();
        var msp=$("#mspamount_"+product_id).val();
        var diffe=parseFloat(net_amount)-parseFloat(msp);
               if(diffe>0)
               {
                var column_color="green";
               }
               else if(diffe<0){
                var column_color="red";
               }
               else if(diffe==0){
                var column_color="orange";
                }
                $(".surplus_"+product_id).html(diffe.toFixed(2));
                $(".surplus_"+product_id).css("background-color",column_color );
    }
    function edit_purchase()
    {
      $(".purchase_order_click").trigger("click");
    }
     var arr_total = [];
        var arr_product = [];
  var prd_array  = [];
  var old_product = [];
  var opt_product = '';
  var main_product= '';
    function change_conf(typpes)
    {
      var tran_type=$("#tran_type").val();
      if(tran_type!='')
      {
        if(typpes=="Opportunity")
                {
                  $(".op_id").show();
                  $(".product_id").hide();
                }
                else{ $(".op_id_date").hide();
                  $(".op_id").hide();
                  $(".product_id").show();
                    $(".add_oppur_prod").show();
                }
                $("#tran_type_message").hide();
      }
      else{
        $("#type_conf").val('');
        $("#tran_type_message").show();
      }
    }
      function change_tran_type(tran_type)
      {
        $(".conf").show();
        if(tran_type=="Intra State Registered Sales")
          {
            $(".cgst").show();
            $(".sgst").show();
             $(".cess").hide();
             $(".igst").hide();
          }
         if(tran_type=="Intra State Un-Registered Sales")
          {
            $(".cgst").show();
            $(".sgst").show();
             $(".cess").show();
             $(".igst").hide();
          }
        if(tran_type=="InterState Registered Sales")
          {
             $(".igst").show();
             $(".cess").hide();
             $(".cgst").hide();
            $(".sgst").hide();
          }
         if(tran_type=="InterState Un-Registered Sales")
          {
             $(".igst").show();
             $(".cess").show();
             $(".cgst").hide();
            $(".sgst").hide();
          }
        if(tran_type=="InterState Un-Registered Sales")
          {
              $(".cgst").show();
            $(".sgst").show();
            $(".igst").hide();
             $(".cess").hide();
          }
         if(tran_type=="Government Sales Registered")
          {
              $(".cgst").show();
            $(".sgst").show();
              $(".cess").show();
            $(".igst").hide();
          }
      }
    </script>
    <script>
function stock_terms_approval(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='stock_terms';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".stock_terms_approval").hide();
            $(".stock_terms_approved").show();
          }
   });
}
function delivery_terms_approval(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_delivery_terms';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".delivery_terms_approval").hide();
            $(".delivery_terms_approved").show();
          }
   });
}
function payment_terms_approval(){
        var url = APP_URL+'/staff/approval_transaction_staff';
        var type_approval='Financial Approval';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            status: type_approval,trans_id:id
          },
          success: function (data)
          {
            $("#modal_success_tran").modal('show');
            $(".payment_terms_approval").hide();
            $(".payment_terms_approved").show();
            setTimeout(function(){ location.reload(); }, 2000);
          }
   });
      }
      function approval_company(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_company';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".approval_company_approval").hide();
            $(".approval_company_approved").show();
          }
   });
      }
         function approval_product(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_product';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".approval_product_approval").hide();
            $(".approval_product_approved").show();
          }
   });
      }
      function approval_config(){
        var url = APP_URL+'/staff/approval_transation';
        var type_approval='approval_config';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            type_approval: type_approval,id:id
          },
          success: function (data)
          {
            $(".approval_config_approval").hide();
            $(".approval_config_approved").show();
          }
   });
      }
        function approval_customer(){
        var url = APP_URL+'/staff/approval_transaction_staff';
        var type_approval='Technical Approval';
        var id='<?php echo $transation->id;?>';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            status: type_approval,trans_id:id
          },
          success: function (data)
          {
            $("#modal_success_tran").modal('show');
            $(".approval_customer_approval").hide();
            $(".approval_customer_approved").show();
            setTimeout(function(){ location.reload(); }, 2000);
          }
   });
      }
    $('#user_id').select2();
    $('#op_id').select2();
    $('#product_id').select2();
    $('#state_id').select2();
    $('#district_id').select2();
 function change_state(){
  var state_id=$("#state_id").val();
  $("#district_id").html('<option value="">Select District</option>');
  $("#user_id").html('<option value="">Select Client</option>');
  //$('#district_id').selectpicker('refresh');
  //$('#user_id').selectpicker('refresh');
  var url = APP_URL+'/staff/change_state';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,
          },
          success: function (data)
          {
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select District</option>';
            for (var i = 0; i < proObj.length; i++) {
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
              }
              $("#district_id").html(states_val);
            //  $('#district_id').selectpicker('refresh');
          }
        });
  }
  function change_district(){
  var state_id=$("#state_id").val();
  var district_id=$("#district_id").val();
  $("#user_id").val('<option value="">Select Client</option>');
  //$('#user_id').selectpicker('refresh');
  var url = APP_URL+'/staff/get_client_use_state_district';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,district_id:district_id
          },
          success: function (data)
          {
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Client</option>';
            for (var i = 0; i < proObj.length; i++) {
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
              }
              $("#user_id").html(states_val);
            //  $('#user_id').selectpicker('refresh');
          }
        });
  }
        function change_user_id(user_id){
$(".loader_user_id").show();
$("#user_id_hidden").val(user_id);
$(".shiplink").show();
  var url = APP_URL+'/staff/get_user_all_details';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            user_id: user_id
          },
          success: function (data)
          {
            $(".loader_user_id").hide();
             var res = data.split("*");
      var contact = JSON.parse(res[1]);
            var proObj = JSON.parse(res[0]);
console.log(proObj);
           //proObj[i]["id"]
           $("#custnemdis").html(proObj[0]["business_name"]);
            $("#user_address").val(proObj[0]["address1"]+' '+proObj[0]["address2"]);
             $("#user_shipping").val(proObj[0]["shiping_address"]+' '+proObj[0]["shiping_address2"]);
              $("#gst").val(proObj[0]["gst"]);
            var optionval='';
            optionval +='<option value="">Select Contact</option>';
              for (var i = 0; i < contact.length; i++) {
                optionval +='<option value="'+contact[i]["id"]+'">'+contact[i]["name"]+' '+contact[i]["last_name"]+'</option>';
              }
            $("#contact_id").html(optionval);
          }
        });
  }
      function change_contact_id(id){
$(".loader_contact_id").show();
  var url = APP_URL+'/staff/get_contactperson_all_details';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            id: id
          },
          success: function (data)
          {
            $(".loader_contact_id").hide();
             var res = data.split("*");
              var contact = JSON.parse(res[0]);
            var desig=res[1];
            $("#designation").html('<option value="'+contact[0]["designation"]+'">'+desig+'</option>');
             $("#contact_phone").val(contact[0]["mobile"]);
             $("#contact_mail").val(contact[0]["email"]);
          }
        });
  }
      function change_oppertunity(opper_id){
$(".loader_opp").show();
  var url = APP_URL+'/staff/get_oppurtunitydetails';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            opper_id: opper_id
          },
          success: function (data)
          {
            $(".loader_opp").hide();
            $(".op_id_date").show();
            var proObj = JSON.parse(data);
          $(".add_oppur_prod").show();
              $("#opp_date").html(proObj[0]["es_order_date"]);
           // $("#user_id").val(user_id);
             //$('#user_id').select2('refresh');
          //  $("#user_id").value(proObj[0]["user_id"]);
            change_user_id(proObj[0]["user_id"]);
            //$("#user_id").select2("val", user_id);
            //  $('#user_id').selectpicker('refresh');
          }
        });
  }
  function add_oppur_prod()
      {
        $(".loader").show();
       var type_conf=$("#type_conf").val();
      if(type_conf=="Opportunity")
        {
          
          
          $("#oppur_status").val(1);
         $(".cust_name").hide();
          $(".cust_details").show();
      //  $("#type_conf").html('<option value="">Select Type</option><option value="Product">Product</option>');
         var url         = APP_URL+'/staff/get_opportunity_all_details_transation';
         $("#cmsTable").show();
          var prd_array  = [];
          var product_id=$("#op_id").val();
        
          prd_array.push(product_id);
          $.ajax({
           type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: product_id,
          },
          success: function (data)
          {     $(".loader").hide();
            $(".noresult").hide();
            $("#preview_btn").show();
            $("#save_btn").show();
               var res = data.split("*11*");
            var proObj = JSON.parse(res[0]);
            var products_warenty= JSON.parse(res[1]);
           
             htmls='';
           var quantity=0;
            var sale_amount=0;
           
           
            
            for (var i = 0; i < proObj.length; i++) {
              if(proObj[i]["image_name"]==null || proObj[i]["image_name"]=='')
              {
                var imgs="{{asset('images/')}}/no-image.jpg";
              }
              else{
                var imgs="{{asset('storage/app/public/products/thumbnail/')}}/"+proObj[i]["image_name"];
               }
               quantity    = 1;
                    opt_product = 1;
                    sale_amount = proObj[i]["unit_price"];
                    if(sale_amount==""){
                      sale_amount = 0;
                    }
               var company = proObj[i]["company_id"];
                    opt_product = 0;
                    main_product = proObj[i]["id"];
                  amt = quantity * sale_amount;
            var tax=proObj[i]["tax_percentage"];
             var tran_type=$("#tran_type").val();
             var taxab_amount=amt;
             
             if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  var cgst_per=tax/2;
                  cgst=taxab_amount*cgst/100;
                  var sgst=tax/2;
                  var sgst_per=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var cess_per=0;
                  var igst_per=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    var cgst_per=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    var sgst_per=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    var cess_per=cess;
                  var igst_per=0;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    var igst_per=igst;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var sgst_per=0;
                    var cess_per=0;
                  var igst_per=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    var cgst_per=0;
                    var sgst_per=0;
                    var cess_per=1;
                  var igst_per=tax;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
                  amt =taxab_amount+tax_cal;
                  var diffe=amt-proObj[i]["msp"];
                          htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
                         
                          htmlscontent += '<td><input type="text" value="'+quantity+'" name="quantity[]" id="qn_'+proObj[i]["id"]+'" class="quantity form-control" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
                          '<td><input type="text" value="0" id="foc_'+proObj[i]["id"]+'"   class="foc form-control" name="foc[]" data-id="'+proObj[i]["id"]+'" style="width:40px;" onchange="change_foc(this.value,'+proObj[i]["id"]+')">'+
                          '<td><input type="text" value="'+sale_amount+'" name="sale_amount[]" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt form-control" data-id="'+proObj[i]["id"]+'" style="width:60px;">'+
                          '<td><input type="text" readonly="true" value="'+proObj[i]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'" name="hsn[]"  class="hsn form-control" data-id="'+proObj[i]["id"]+'" style="width:70px;">'+
                          '<td><input type="text" readonly="true" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst form-control" name="cgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+cgst_per+'<span>%</span>'+
                          '<td><input type="text" readonly="true" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst form-control" name="sgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+sgst_per+'<span>%</span>'+
                          '<td><input type="text" readonly="true" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst form-control" name="igst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+igst_per+'<span>%</span>'+
                          '<td><input type="text" readonly="true" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess form-control" name="cess[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+cess_per+'<span>%</span>'+
                          '<input type="hidden" name="purchase_product_id[]" value="0">'+
                          ' <input type="hidden" name="transation_product_id[]" value="0">';
                          if(proObj[i]["msp"]>0)
                          {
                          htmlscontent +='<td><input type="text" readonly="true" value="'+proObj[i]["msp"]+'" data-val="'+proObj[i]["msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">';
                          }else{
                          htmlscontent +='<td><input type="text" readonly="true" value="'+proObj[i]["msp"]+'" data-val="'+proObj[i]["msp"]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">Please contact admin for msp';
                          }
                          htmlscontent +=
                          '<td><input type="text" readonly="true" value="'+diffe.toFixed(2)+'" id="surplus_amt_'+proObj[i]["id"]+'"  class="surplus_amt form-control" name="surplus_amt[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
                          '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt form-control" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="btn btn-danger btn-xs " onclick="deletepro('+proObj[i]["id"]+',1)" data-id="'+proObj[i]["id"]+'"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'" class="hqn_'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"></tr>';
              $("#tabledata").append(htmlscontent);
              //$("#pdfsec").append(pdfsec);
             // arr_total.push(amt);
           
          arr_total[proObj[i]["id"]] = amt;
             
           }
           console.log(arr_total);
           var myTotal = 0; 
$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
 $('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
 });
 var tot_sgst=0
 $('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
 });
 var tot_igst=0
 $('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
 });
 var tot_cess=0
 $('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
 });
 var tot_taxable=0
 $('input[name^="taxable_amount"]').each(function() {
  tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
 });
               $(".footertr").hide();
               htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
               
              
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
           $("#tabledata").append(htmlscontent);
          }
        }); 
      }
      else{
          var url         = APP_URL+'/staff/get_multiple_product_all_details_transation';
        $("#cmsTable").show();
          var prd_array  = [];
        
var product_id=$("#product_id").val();
    //  prd_array.push(product_id);
    var oppur_status=$("#oppur_status").val();
    if(oppur_status==1)
    {
    
    }
    else{
      $(".cust_name").show();
        $(".cust_details").hide();
    }
      
      
         $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: product_id,
          },
          success: function (data)
          {      $(".loader").hide();
            $(".noresult").hide();
            $("#preview_btn").show();
            $("#save_btn").show();
            var res = data.split("*11*");
            var proObj = JSON.parse(res[0]);
            var products_warenty=JSON.parse(res[2]);
             htmls='';
           var quantity=0;
            var sale_amount=0;
            for (var i = 0; i < proObj.length; i++) {
              if(proObj[i]["image_name"]==null || proObj[i]["image_name"]=='')
              {
                var imgs="{{asset('images/')}}/no-image.jpg";
              }
              else{
                var imgs="{{asset('storage/app/public/products/thumbnail/')}}/"+proObj[i]["image_name"];
              }
               quantity    = 1;
                    opt_product = 1;
                    sale_amount = proObj[i]["unit_price"];
                    if(sale_amount==""){
                      sale_amount = 0;
                    }
               var company = proObj[i]["company_id"];
                    opt_product = 0;
                    main_product = proObj[i]["id"];
                
                  amt = quantity * sale_amount;
                //pdfsec='<input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'">';
                   var tax=proObj[i]["tax_percentage"];
                var tran_type=$("#tran_type").val();
                          
                var taxab_amount=amt;
                  if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  var cgst_per=tax/2;
                  cgst=taxab_amount*cgst/100;
                  var sgst=tax/2;
                  var sgst_per=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var cess_per=0;
                  var igst_per=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    var cgst_per=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    var sgst_per=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    var cess_per=cess;
                  var igst_per=0;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    var igst_per=igst;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var sgst_per=0;
                    var cess_per=0;
                  var igst_per=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    var cgst_per=0;
                    var sgst_per=0;
                    var cess_per=1;
                  var igst_per=tax;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
                  amt =taxab_amount+tax_cal;
                  var diffe=amt-res[1];
                 
               htmlscontent='<tr class="tr_'+proObj[i]["id"]+'"><td><img width="50px" height="50px" src="'+imgs+'"/></td><td>'+proObj[i]["name"]+'</td>';
              
              htmlscontent += '<td><input type="text" value="'+quantity+'" id="qn_'+proObj[i]["id"]+'" name="quantity[]" class="quantity form-control" onchange="change_qty(this.value,'+proObj[i]["id"]+')" data-id="'+proObj[i]["id"]+'" style="width:40px;"></td>'+
              '<td><input type="text" value="0" id="foc_'+proObj[i]["id"]+'"  name="foc[]"  class="foc form-control" data-id="'+proObj[i]["id"]+'" style="width:40px;" onchange="change_foc(this.value,'+proObj[i]["id"]+')">'+ 
              '<td><input type="text" name="sale_amount[]" value="'+sale_amount+'" id="sa_'+proObj[i]["id"]+'" onchange="change_sale_amt(this.value,'+proObj[i]["id"]+')" class="sale_amt form-control" data-id="'+proObj[i]["id"]+'" style="width:60px;">'+
               '<td><input type="text" readonly="true" value="'+proObj[i]["hsn_code"]+'" id="hsn_'+proObj[i]["id"]+'"  class="hsn form-control" name="hsn[]" data-id="'+proObj[i]["id"]+'" style="width:70px;">'+
               '<td><input type="text" readonly="true" value="'+cgst+'" id="cgst_'+proObj[i]["id"]+'"  class="cgst form-control" name="cgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+cgst_per+'<span>%</span>'+
               '<td><input type="text"  readonly="true" value="'+sgst+'" id="sgst_'+proObj[i]["id"]+'"  class="sgst form-control" name="sgst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+sgst_per+'<span>%</span>'+
               '<td><input type="text" readonly="true" value="'+igst+'" id="igst_'+proObj[i]["id"]+'"  class="igst form-control" name="igst[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+igst_per+'<span>%</span>'+
               '<td><input type="text" readonly="true" value="'+cess+'" id="cess_'+proObj[i]["id"]+'"  class="cess form-control" name="cess[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+cess_per+'<span>%</span>'+
               '<input type="hidden" name="purchase_product_id[]" value="0">'+
               ' <input type="hidden" name="transation_product_id[]" value="0">';
               if(res[1]>0)
                {
                htmlscontent +='<td><input type="text" readonly="true" value="'+res[1]+'" data-val="'+res[1]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">';
                }else{
                htmlscontent +='<td><input type="text" readonly="true" value="'+res[1]+'" data-val="'+res[1]+'"  id="msp_'+proObj[i]["id"]+'"  class="msp form-control" name="msp[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">Please contact admin for msp';
                }
                htmlscontent +=
               '<td><input type="text" readonly="true" value="'+diffe.toFixed(2)+'" id="surplus_amt_'+proObj[i]["id"]+'"  class="surplus_amt form-control" name="surplus_amt[]" data-id="'+proObj[i]["id"]+'" style="width:40px;">'+
               '<div style="display:none;" class="error_message error_sale_'+proObj[i]["id"]+'"></div></td><td><input type="text" value="'+amt+'" id="am_'+proObj[i]["id"]+'" class="amt form-control" name="amt[]" data-id="'+proObj[i]["id"]+'" readonly></td><td> <a class="btn btn-danger btn-xs " onclick="deletepro('+proObj[i]["id"]+',0)" data-id="'+proObj[i]["id"]+'"  title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td><input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'"><input type="hidden" name="quantity[]" value="'+quantity+'" class="hqn_'+proObj[i]["id"]+'"><input type="hidden" name="amount[]" value="'+amt+'" class="hamt_'+proObj[i]["id"]+'"><input type="hidden" name="sale_amount[]" value="'+sale_amount+'" class="hsa_'+proObj[i]["id"]+'"><input type="hidden" name="company[]" value="'+company+'"><input type="hidden" name="optional[]" value="'+opt_product+'"><input type="hidden" name="main_pdt[]" value="'+main_product+'"></tr>';
              $("#tabledata").append(htmlscontent);
              //$("#pdfsec").append(pdfsec);
              //arr_total.push(amt);
              arr_total[proObj[i]["id"]] = amt;
           }
           console.log(arr_total);
           var myTotal = 0; 
            $('input[name^="amt"]').each(function() {
                myTotal = parseFloat($(this).val())+parseFloat(myTotal);
            });
            var tot_cgst=0
            $('input[name^="cgst"]').each(function() {
              tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
            });
            var tot_sgst=0
            $('input[name^="sgst"]').each(function() {
              tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
            });
            var tot_igst=0
            $('input[name^="igst"]').each(function() {
              tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
            });
            var tot_cess=0
            $('input[name^="cess"]').each(function() {
              tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
            });
            var tot_taxable=0
            $('input[name^="taxable_amount"]').each(function() {
              tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
            });
      $(".footertr").hide();
      htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
              
              
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);
          }
        }); 
        var tot_products=[];
        tot_products.push(product_id);
            $('input[name^="product_id"]').each(function() {
              tot_products.push($(this).val());
            });
      
            
        var url         = APP_URL+'/staff/get_sort_product_transaction';
        $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            product_id: tot_products,
          },
          success: function (data)
          {  
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='';
            states_val +='<option value="">Select Products</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#product_id").html(states_val);
              $('#product_id').selectpicker('refresh');
          }
        });
        }
        console.log('arr_product'+arr_total)
      
      }
      
function change_foc(qty,product_id)
{
  var total_qty=$("#qn_"+product_id).val();
/*if(qty>total_qty)
{*/
 
 if(qty>0)
  {
    var quantity   = parseInt(total_qty)-parseInt(qty);
  }
  else{
    var quantity   = total_qty;
  }
        var product_id = product_id;
        var tran_type=$("#tran_type").val();
       // alert(quantity+'--'+product_id);
        var url = APP_URL+'/staff/get_product_all_details';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
              var proObj = JSON.parse(data);
            
              for (var i = 0; i < proObj.length; i++) {
                var  sale_amount = proObj[i]["unit_price"];;
                var amt    = quantity * sale_amount;
                var taxab_amount=amt;
               // alert(amt)
                var tax=proObj[i]["tax_percentage"];
                if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  cgst=taxab_amount*cgst/100;
                  var sgst=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
   amt =taxab_amount+tax_cal;
               
             
                $("#am_"+product_id).val(amt);
             $(".hqn_"+product_id).val(qty);
             $("#cgst_"+product_id).val(cgst);
                $("#sgst_"+product_id).val(sgst);
                $("#igst_"+product_id).val(igst);
                $("#cess_"+product_id).val(cess);
                $("#taxable_amount_"+product_id).val(taxab_amount);
             
               var myTotal = 0; 
 
$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
 $('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
 });
 var tot_sgst=0
 $('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
 });
 var tot_igst=0
 $('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
 });
 var tot_cess=0
 $('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
 });
 var tot_taxable=0
 $('input[name^="taxable_amount"]').each(function() {
  tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
 });
      $(".footertr").hide();
    
            
              }
            }
        });
 
}
function change_qty(qty,product_id)
{
var foc=$("#foc_"+product_id).val();
if(foc>0)
  {
    var quantity   = parseInt(qty)-parseInt(foc);
  }
  else{
    var quantity   = qty;
  }
        var product_id = product_id;
        var tran_type=$("#tran_type").val();
       // alert(quantity+'--'+product_id);
        var url = APP_URL+'/staff/get_product_all_details';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
              var proObj = JSON.parse(data);
              for (var i = 0; i < proObj.length; i++) {
                var  sale_amount = proObj[i]["unit_price"];;
                var amt    = quantity * sale_amount;
               var taxab_amount=amt;
               // taxab_amount taxable_amount
                var tax=proObj[i]["tax_percentage"];
              
                if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  cgst=taxab_amount*cgst/100;
                  var sgst=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
   amt =taxab_amount+tax_cal;
               
               // $("#sa_"+product_id).val(amt);
                $("#am_"+product_id).val(amt);
                $("#cgst_"+product_id).val(cgst);
                $("#sgst_"+product_id).val(sgst);
                $("#igst_"+product_id).val(igst);
                $("#cess_"+product_id).val(cess);
                $("#taxable_amount_"+product_id).val(taxab_amount);
                
             //   $("#sa_"+product_id).val(amt);
                $(".hqn_"+product_id).val(qty);
              //  $(".hamt_"+product_id).val(amt);
               // $(".hsa_"+product_id).val(amt);
               var myTotal = 0; 
 
$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
 $('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
 });
 var tot_sgst=0
 $('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
 });
 var tot_igst=0
 $('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
 });
 var tot_cess=0
 $('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
 });
 var tot_taxable=0
 $('input[name^="taxable_amount"]').each(function() {
  tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
 });
      $(".footertr").hide();
      htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
              
                '<td>'+tot_taxable+'</td>'+
               
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);
            
              }
            }
        });
}
function change_foc(qty,product_id)
{
  var total_qty=$("#qn_"+product_id).val();
/*if(qty>total_qty)
{*/
 
 if(qty>0)
  {
    var quantity   = parseInt(total_qty)-parseInt(qty);
  }
  else{
    var quantity   = total_qty;
  }
        var product_id = product_id;
        var tran_type=$("#tran_type").val();
       // alert(quantity+'--'+product_id);
        var url = APP_URL+'/staff/get_product_all_details';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
              var proObj = JSON.parse(data);
            
              for (var i = 0; i < proObj.length; i++) {
                var  sale_amount = proObj[i]["unit_price"];;
                var amt    = quantity * sale_amount;
                var taxab_amount=amt;
               // alert(amt)
                var tax=proObj[i]["tax_percentage"];
                if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  cgst=taxab_amount*cgst/100;
                  var sgst=tax/2;
                  sgst=taxab_amount*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    cgst=taxab_amount*cgst/100;
                    var sgst=tax/2;
                    sgst=taxab_amount*sgst/100;
                    var igst=0;   
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    igst= taxab_amount*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var tax_cal= taxab_amount*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    cess=taxab_amount*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= taxab_amount*tax/100;
                  }
   amt =taxab_amount+tax_cal;
               
             
                $("#am_"+product_id).val(amt);
             $(".hqn_"+product_id).val(qty);
             $("#cgst_"+product_id).val(cgst);
                $("#sgst_"+product_id).val(sgst);
                $("#igst_"+product_id).val(igst);
                $("#cess_"+product_id).val(cess);
                $("#taxable_amount_"+product_id).val(taxab_amount);
             
               var myTotal = 0; 
 
$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
 $('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
 });
 var tot_sgst=0
 $('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
 });
 var tot_igst=0
 $('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
 });
 var tot_cess=0
 $('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
 });
 var tot_taxable=0
 $('input[name^="taxable_amount"]').each(function() {
  tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
 });
      $(".footertr").hide();
      htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                
                '<td>'+tot_taxable+'</td>'+
            
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);
            
              }
            }
        });
}
function change_sale_amt(sale_amount,product_id)
{
       var product_id = product_id;
        var sale_amount=sale_amount;
        var tran_type=$("#tran_type").val();
        var foc=$("#foc_"+product_id).val();
        if(foc>0)
          {
            var quantity   = parseInt(qty)-parseInt(foc);
          }
          else{
            var quantity   = $("#qn_"+product_id).val();;
          }
       // alert(quantity+'--'+product_id);
        var url = APP_URL+'/staff/get_product_all_details';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,
            },
            success: function (data)
            {     
              var proObj = JSON.parse(data);
              for (var i = 0; i < proObj.length; i++) {
                var amt    = quantity * proObj[i]["unit_price"];
                var unit_price=proObj[i]["unit_price"];
                var max_sale_amount=proObj[i]["max_sale_amount"];
                var min_sale_amount=proObj[i]["min_sale_amount"];
                
                //alert(unit_price);
                 max_sale_amount=parseInt(max_sale_amount);
                  min_sale_amount=parseInt(min_sale_amount);
                  unit_price=parseInt(unit_price);
                  sale_amount= $("#sa_"+product_id).val();
                //  alert(sale_amount)
                if(sale_amount!=0)
                {
                  $(".error_sale_"+product_id).html('');
                //  alert(max_sale_amount+'--'+min_sale_amount+'--'+sale_amount)
                if(max_sale_amount==0 && min_sale_amount==0 && sale_amount!=unit_price)
                {
                  $(".error_sale_"+product_id).html('Maximum and minimum Unit Price is given zero. So can not make change in actual sales amount.');
                  $(".error_sale_"+product_id).show();
                }
               else if(sale_amount!=unit_price && unit_price==0)
                {
                  $(".error_sale_"+product_id).html('Actual Unit Price is given zero. So can not give sales amount other than zero');
                  $(".error_sale_"+product_id).show();
                }
               // alert(max_sale_amount+'--'+min_sale_amount+'--'+sale_amount)
                if(sale_amount>=min_sale_amount && sale_amount<=max_sale_amount)
                  {//alert('111')
                    $(".error_sale_"+product_id).html('');
                  $(".error_sale_"+product_id).hide();
                  }
                  else{//alert('222')
                   /* */
                  $(".error_sale_"+product_id).html('Unit Price is between '+min_sale_amount+' and '+max_sale_amount+' ');
                  $(".error_sale_"+product_id).show();
                  }
                }//not zero if
           
                var tax=proObj[i]["tax_percentage"];
                var sale_amount= $("#sa_"+product_id).val();
               var quantity= $("#qn_"+product_id).val();
                var amt    = quantity * sale_amount;
                if(tran_type=="Intra State Registered Sales" || tran_type=="Government Sales Registered")
                  {
                  var cgst=tax/2;
                  cgst=amt*cgst/100;
                  var sgst=tax/2;
                  sgst=amt*sgst/100;
                  var igst=0;   
                  var cess=0;
                  var tax_cal= amt*tax/100;
                  }
                  if(tran_type=="Intra State Un-Registered Sales" || tran_type=="Government Sales Unregistered")
                   {
                    var cgst=tax/2;
                    cgst=amt*cgst/100;
                    var sgst=tax/2;
                    sgst=amt*sgst/100;
                    var igst=0;   
                    var cess=1;
                    cess=amt*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= amt*tax/100;
                  }
                 if(tran_type=="InterState Registered Sales")
                  {
                    var igst=tax;
                    igst= amt*igst/100;
                    var cgst=0;
                    var sgst=0;
                    var cess=0;
                    var tax_cal= amt*tax/100;
                  }
                  if(tran_type=="InterState Un-Registered Sales")
                   {
                    var igst=tax;
                    var cgst=0;
                    var sgst=0;
                    var cess=1;
                    cess=amt*cess/100;
                    tax=parseInt(tax)+parseInt(cess);
                    var tax_cal= amt*tax/100;
                  }
   amt =amt+tax_cal;
              
               // $("#sa_"+product_id).val(amt);
                $("#am_"+product_id).val(amt);
                $(".hsa_"+product_id).val(amt);
              }
              var myTotal = 0; 
 
$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
 $('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
 });
 var tot_sgst=0
 $('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
 });
 var tot_igst=0
 $('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
 });
 var tot_cess=0
 $('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
 });
      $(".footertr").hide();
      htmlscontent='<tr class="footertr">'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
                '<td></td>'+
              
                '<td>'+tot_cgst.toFixed(2)+'</td>'+
                '<td>'+tot_sgst.toFixed(2)+'</td>'+
                '<td>'+tot_igst.toFixed(2)+'</td>'+
                
                '<td>'+tot_cess.toFixed(2)+'</td>'+
                '<td></td>'+
                '<td></td>'+
               '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);
            }
        });
}
  function deletepro(product_id,types)
    {
      $(".tr_"+product_id).remove();
      var transation_id='<?php echo $transation->id?>'
      var url = APP_URL+'/staff/delete_product_transation';
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,transation_id:transation_id
            },
            success: function (data)
            {
              var myTotal = 0; 
$('input[name^="amt"]').each(function() {
    myTotal = parseFloat($(this).val())+parseFloat(myTotal);
});
var tot_cgst=0
$('input[name^="cgst"]').each(function() {
  tot_cgst = parseFloat($(this).val())+parseFloat(tot_cgst);
});
var tot_sgst=0
$('input[name^="sgst"]').each(function() {
  tot_sgst = parseFloat($(this).val())+parseFloat(tot_sgst);
});
var tot_igst=0
$('input[name^="igst"]').each(function() {
  tot_igst = parseFloat($(this).val())+parseFloat(tot_igst);
});
var tot_cess=0
$('input[name^="cess"]').each(function() {
  tot_cess = parseFloat($(this).val())+parseFloat(tot_cess);
});
var tot_taxable=0
$('input[name^="taxable_amount"]').each(function() {
  tot_taxable = parseFloat($(this).val())+parseFloat(tot_taxable);
});
$(".footertr").hide();
htmlscontent='<tr class="footertr">'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
    '<td></td>'+
  
    '<td>'+tot_cgst.toFixed(2)+'</td>'+
    '<td>'+tot_sgst.toFixed(2)+'</td>'+
    '<td>'+tot_igst.toFixed(2)+'</td>'+
    
    '<td>'+tot_cess.toFixed(2)+'</td>'+
    '<td></td>'+
    '<td></td>'+
   '<td>'+myTotal.toFixed(2)+'</td><td></td></tr>';
              $("#tabledata").append(htmlscontent);
            }
        });
        
    }
 function update_qty(qty,product_id)
{
  if(qty>0)
  {
    var url = APP_URL+'/staff/update_qty_transation';
 $(".update_qty_"+product_id).show();
 var transation_id='<?php echo $transation->id?>'
        $.ajax({
            type: "POST",
            cache: false,
            url: url,
            data:{
              product_id: product_id,qty:qty,transation_id:transation_id
            },
            success: function (data)
            {
              $(".update_qty_"+product_id).hide();
            }
        });
  }
        
}
 function change_state(){
  var state_id=$("#state_id").val();
  $("#district_id").html('<option value="">Select District</option>');
  $("#user_id").html('<option value="">Select Client</option>');
  $('#district_id').selectpicker('refresh');
  $('#user_id').selectpicker('refresh');
  var url = APP_URL+'/staff/change_state';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,
          },
          success: function (data)
          {
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select District</option>';
            for (var i = 0; i < proObj.length; i++) {
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
              }
              $("#district_id").html(states_val);
              $('#district_id').selectpicker('refresh');
          }
        });
  }
  function change_district(){
  var state_id=$("#state_id").val();
  var district_id=$("#district_id").val();
  $("#user_id").val('<option value="">Select Client</option>');
  $('#user_id').selectpicker('refresh');
  var url = APP_URL+'/staff/get_client_use_state_district';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            state_id: state_id,district_id:district_id
          },
          success: function (data)
          {
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Client</option>';
            for (var i = 0; i < proObj.length; i++) {
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["business_name"]+'</option>';
              }
              $("#user_id").html(states_val);
              $('#user_id').selectpicker('refresh');
          }
        });
  }
  function add_shipping(){
    var shipping_address1=$("#shipping_address1").val('');
  var shipping_address2=$("#shipping_address2").val('');
  var shipping_country_id=$("#shipping_country_id").val('');
  var shipping_city=$("#shipping_city").val('');
  var shipping_state=$("#shipping_state").val('');
  var shipping_zip=$("#shipping_zip").val('');
    $(".success_msg").hide();
    $("#modal_ship").modal("show");
  }
    function select_shipping(){
      var user_id=$("#user_id").val();
        var user_id=$("#user_id").val();
  if(user_id==0)
  {
    var  user_id=$("#user_id_hidden").val();
  }
     var url = APP_URL+'/staff/select_shipping_address_user';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            user_id:user_id
          },
          success: function (data)
          {
            $(".display_address").html(data);
            $("#modal_ship_address_view").modal("show");
          }
   });
    }
function save_shipping()
{
  var shipping_address1=$("#shipping_address1").val();
  var shipping_address2=$("#shipping_address2").val();
  var shipping_country_id=$("#shipping_country_id").val();
  var shipping_city=$("#shipping_city").val();
  var shipping_state=$("#shipping_state").val();
  var shipping_zip=$("#shipping_zip").val();
  if(shipping_address1=="")
  {
    $("#shipping_address1_message").show();
  }
  else{
    $("#shipping_address1_message").hide();
  }
  if(shipping_country_id=="")
  {
    $("#shipping_country_id_message").show();
  }
  else{
    $("#shipping_country_id_message").hide();
  }
  if(shipping_city=="")
  {
    $("#shipping_city_message").show();
  }
  else{
    $("#shipping_city_message").hide();
  }
  if(shipping_state=="")
  {
    $("#shipping_state_message").show();
  }
  else{
    $("#shipping_state_message").hide();
  }
  if(shipping_zip=="")
  {
    $("#shipping_zip_message").show();
  }
  else{
    $("#shipping_zip_message").hide();
  }
  var user_id=$("#user_id").val();
  if(user_id==0)
  {
    var  user_id=$("#user_id_hidden").val();
  }
if(user_id!='' && shipping_zip!='' && shipping_state!='' && shipping_city!='' && shipping_country_id!='' && shipping_address1!='')
{
  var url = APP_URL+'/staff/save_shipping_address_user';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            user_id:user_id,shipping_address1: shipping_address1,shipping_address2:shipping_address2,shipping_country_id:shipping_country_id,shipping_city:shipping_city,shipping_state:shipping_state,shipping_zip:shipping_zip
          },
          success: function (data)
          {
            $(".success_msg").show();
            var shipping_address1=$("#shipping_address1").val('');
  var shipping_address2=$("#shipping_address2").val('');
  var shipping_country_id=$("#shipping_country_id").val('');
  var shipping_city=$("#shipping_city").val('');
  var shipping_state=$("#shipping_state").val('');
  var shipping_zip=$("#shipping_zip").val('');
          }
   });
}
}
  function add_shipaddress()
    {
      var address_option = $("input[name='address_option']:checked").val();
      var address=$("#addrsec"+address_option).html();
      $("#user_shipping").val(address);
      $("#modal_ship").modal("hide");
    }
$(document).ready(function() {
    $("#state_id").selectpicker({
      enableFiltering: true,
    });
    $("#user_id").selectpicker({
      enableFiltering: true,
    });
    $("#district_id").selectpicker({
      enableFiltering: true,
    });
});
function add_contact()
{
  var user_id='<?php echo $transation->user_id;?>'
 
  $(".addlink").attr("href", "http://dentaldigital.in/beczone/staff/customer/"+user_id+"/edit");
}
function addmore_photo()
  {
    var count_addr_photo = $("#count_addr_photo").val();
    var add_count        = parseInt(count_addr_photo)+1;
    $("#count_addr_photo").val(add_count);
    var htmls='<div class="form-group col-md-8" id="p_row_'+add_count+'">'+
                  '<input type="file" id="photo'+add_count+'" name="photo[]" accept=".jpg,.jpeg,.png" onchange="loadPreview(this,preview_photo'+add_count+')" class="form-control">'+
                  '<p class="help-block">(Allowed Type: jpg,jpeg,png )</p>'+
                  '<div id="preview_photo'+add_count+'" class="form-group col-md-12 mb-2"></div>'+
                    '<span class="error_message" id="photo_message" style="display: none">Field is required</span>'+
                '</div>'+
                '  <div class="form-group  col-md-4" id="pr_row_'+add_count+'">'+
               ' <button type="button" class="btn btn-danger" onClick="remove_photo('+add_count+')">Remove</button>'+
               ' </div>';
    $("#addphoto").append(htmls);
  }
  function remove_photo(row_no)
  {
    var count_addr=$("#count_addr_photo").val();
    var add_count=parseInt(count_addr)-1;
    $("#count_addr_photo").val(add_count);
    $("#p_row_"+row_no).remove();
    $("#pr_row_"+row_no).remove();
  }
  </script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
$('#po_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            minDate: 0
        });
$('#collect_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            minDate: 0
        });
$('#expect_date').datepicker({
    //dateFormat:'yy-mm-dd',
    dateFormat:'yy-mm-dd',
    minDate: 0
});
</script>

@endsection