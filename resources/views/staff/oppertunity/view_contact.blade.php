
@extends('admin/layouts.app')

@section('title', 'Customer')

@section('content')


<section class="content-header">
      <h1>
        Edit Customer
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="">Manage Customer</a></li>
        <li class="active">Customer</li>
      </ol>
</section>

<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-3 leftside-menu">
            <div class="panel_s mbot5">
               <div class="panel-body padding-10">
                  <h4 class="bold">
                  {{ucfirst($user->business_name)}}
                     </h4>
               </div>
               
               <div class="panel-body padding-10">
                 <h4 class="bold">
                    Oppertunity List
                  </h4>
                  <div>
                    <table class="table table-bordered table-striped data-">
                      <tr>
                        <td>No</td>
                        <td>Name</td>
                      </tr>
                      @if(sizeof($oppertunity)>0)
                      @php $i = 1; @endphp
                      @foreach($oppertunity as $op)
                      <tr>
                        <td>{{$i++}}</td>
                        <td>{{$op->oppertunity_name}}</td>
                      </tr>
                      @endforeach
                      @endif
                    </table>
                  </div>
               </div>
            </div>
            <ul class="nav navbar-pills navbar-pills-flat nav-tabs nav-stacked customer-tabs" role="tablist">
                  <li class="customer_tab_contacts ">
                    <a data-group="contacts" href="#">
                      <i class="fa fa-users menu-icon" aria-hidden="true"></i>
                      Contact person      </a>
                  </li> 
            </ul>
         </div>
         
         <div class="col-md-9">
          <!-- general form elements -->
          <div class="box box-primary">

            <!-- /.box-header -->
            <!-- form start -->

            @if(session()->has('message'))
                <div class="alert alert-success">
                    {{ session()->get('message') }}
                </div>
            @endif


            @if(session()->has('error_message'))
                <div class="alert alert-danger alert-dismissible">
                    {{ session()->get('error_message') }}
                </div>
            @endif

            <p class="error-content alert-danger">
            {{ $errors->first('name') }}
            </p>

            
            @if (session('success'))
                <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif

            <div class="box-body">

               <div class="col-lg-12 margin-tb">


            <div class="pull-left">

                  <a class="btn btn-sm btn-success" onclick="add_contact()" > <span class="glyphicon glyphicon-plus"></span>Add Contact Person</a>

              </div> 

            </div><br>


              <form name="dataForm" id="dataForm" method="post" action="{{ url('/admin/contact_person/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                  <th>No.</th>
                  <th>Name</th>
                  <th>Designation</th>
                  <th>Department</th>
                  <th>Email</th>
                  <th>Phone</th>
                 
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($contact_person as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="subcategory">
                        <!-- <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td> -->
                        <td data-th="No.">
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Name"><?php echo $product->title ?> <?php echo $product->name ?> <?php echo $product->last_name ?></td>
                       
                         <td data-th="Designation"><?php
                        $cat = App\Hosdesignation::find($product->designation);
                        echo $cat->name ?> ({{$product->contact_type}})
                        </td>

                         <td data-th="Department"><?php
                        $cat = App\Hosdeparment::find($product->department);
                        echo $cat->name ?>
                        </td>

                           <td data-th="Email"><?php echo $product->email ?></td> 
                        <td data-th="Phone"><?php echo $product->phone ?></td>
                        

                          <!-- <td>
                         <?php if($product->image_name1 != '') { 
                     $nameimg= explode(".",$product->image_name1);
                     if($nameimg[1]!='pdf')
                     {
                      ?> 
                      <a download="<?php echo asset("storage/app/public/contact/$product->image_name1") ?>"   href="<?php echo asset("storage/app/public/contact/$product->image_name1") ?>" >
                      Download1</a>
                  <?php
                     }
                     else{
                       ?>
                       <a download="<?php echo asset("storage/app/public/contact/$product->image_name1") ?>" href="<?php echo asset("storage/app/public/contact/$product->image_name1") ?>">
                      
                       Download1
                       </a>
                       <?php
                     }
                } ?>
                         </td>
                         <td>
                         <?php if($product->image_name2 != '') { 
                      
                      $nameimg= explode(".",$product->image_name2);
                      
                      if($nameimg[1]!='pdf')
                      {
                       ?> 
                    <a download="<?php echo asset("storage/app/public/contact/$product->image_name2") ?>"   href="<?php echo asset("storage/app/public/contact/$product->image_name2") ?>" >    
                    Download2</a>
                   <?php
                      }
                      else{
                        ?>
                        <a download="<?php echo asset("storage/app/public/contact/$product->image_name2") ?>" href="<?php echo asset("storage/app/public/contact/$product->image_name2") ?>">
                     
                        Download2
                        </a>
                        <?php
                      }
                 } ?>
                         </td> -->
                         <!-- <td>{{$product->added_by}} </td>
                         <td>{{$product->updated_by}} </td>

                   
                        <td>{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td> -->
                     
                      

                        <td class="alignCenter" data-th="Action">
                            <a class="btn btn-primary btn-xs deletebtn" attr-whatsapp="{{$product->whatsapp}}" attr-mobile="{{$product->mobile}}" attr-contacttype="{{$product->contact_type}}" attr-lastname="{{$product->last_name}}" attr-title="{{$product->title}}"  attr-remark="{{$product->remark}}" attr-image1="{{$product->image_name1}}"  attr-image2="{{$product->image_name2}}"  attr-id="{{$product->id}}" attr-name="{{$product->name}}"  attr-email="{{$product->email}}"   attr-phone="{{$product->phone}}"  attr-department="{{$product->department}}" attr-designation="{{$product->designation}}"   title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a class="btn btn-danger btn-xs deleteItem" href="{{ route('admin.contact_person.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($contact_person) > 0) { ?>
              <!-- <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('contact_person');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a>
              </div> -->
               <?php } ?>

              </table>
            </form>
            </div>


          
          </div>

        </div>

      </div>
</section>


<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
  <form name="contactform" id="contactform" method="post" action"" />
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Contact Person</h4>
      </div>
      
      <div class="modal-body">

      
      <div class="form-group  col-md-6">
          <label for="name">Title*</label>
          <select  id="title" name="title" class="form-control" >
          <option value="">Select Title</option>
          <option value="Mr.">Mr.</option>
          <option value="Ms.">Ms.</option>
          <option value="Dr.">Dr.</option>
          <option value="Fr.">Fr.</option>
          <option value="Sr.">Sr.</option>

          </select>
          <span class="error_message" id="title_message" style="display: none">Field is required</span>
        </div>

        <div class="form-group  col-md-6">
          <label for="name">First Name*</label>
          <input type="text" id="name" name="name" class="form-control" value="{{ old('name')}}" placeholder="Name">
          <span class="error_message" id="name_message" style="display: none">Field is required</span>
        </div>

        <div class="form-group  col-md-6">
          <label for="name">Last Name</label>
          <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name')}}" placeholder="Last Name">
          <span class="error_message" id="last_name_message" style="display: none">Field is required</span>
        </div>

         <div class="form-group  col-md-6">
          <label for="name">Designation*</label>
         
          <select id="designation" name="designation" class="form-control" >
              <option value="">Select Designation</option>      
              @foreach ($hosdesignation as $desigval)
              <option value="{{$desigval->id}}">{{$desigval->name}}</option> 
              @endforeach
          </select>

          <span class="error_message" id="designation_message" style="display: none">Field is required</span>
        </div>

        <div class="form-group  col-md-6">
          <label for="name">Department*</label>
          <select id="department" name="department" class="form-control" >
              <option value="">Select Department</option>      
              @foreach ($hosdeparment as $hosdepart)
              <option value="{{$hosdepart->id}}">{{$hosdepart->name}}</option> 
              @endforeach
          </select>
          <span class="error_message" id="department_message" style="display: none">Field is required</span>
        </div>

        

        <div class="form-group  col-md-6">
        <div class="form-group col-md-3">
                    <!-- <label >Status*</label> -->
                  <div class="radio">
                    <label>
                      <input type="radio" name="contact_type" id="status" value="HOD" checked="true">
                      HOD
                    </label>
                  </div>
                 
                </div>
        
                <div class="form-group col-md-3">
                    <!-- <label >Status*</label> -->
                  <div class="radio">
                    <label>
                      <input type="radio" name="contact_type" id="status1" value="Staff">
                      Staff
                    </label>
                  </div>
                  

                </div>
                </div>         
        <div class="form-group  col-md-12">
          <label for="name">Email*</label>
          <input type="text" id="email" name="email" class="form-control" value="{{ old('email')}}" placeholder="Email">
          <span class="error_message" id="email_message" style="display: none">Field is required</span>
        </div>

        
        <div class="form-group col-md-6">
                  <label for="name">Password*<a onclick="get_password()"> Generate Password</a></label>
                  <?php
                  $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
                  $passwordval = substr($random, 0, 10);
                
                  ?>
                    <input type="hidden" id="demopassword" name="demopassword" value="{{$passwordval}}">
                  
                  <input type="text" id="password" name="password" value="{{ old('password')}}" class="form-control" placeholder="Password">
                  <span class="error_message" id="password_message" style="display: none">Field is required</span>
                  
                </div>


         <div class="form-group  col-md-6">
          <label for="name">Phone*</label>
          <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone')}}" placeholder="Phone">
          <span class="error_message" id="phone_message" style="display: none">Field is required</span>
        </div>

        
        <div class="form-group  col-md-6">
          <label for="name">Mobile</label>
          <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile')}}" placeholder="Mobile">
          <span class="error_message" id="mobile_message" style="display: none">Field is required</span>
        </div>

        
        <div class="form-group  col-md-6">
          <label for="name">Whatsapp</label>
          <input type="text" id="whatsapp" name="whatsapp" class="form-control" value="{{ old('whatsapp')}}" placeholder="Whatsapp">
          <span class="error_message" id="whatsapp_message" style="display: none">Field is required</span>
        </div>

         

          <div class="form-group  col-md-12">
          <label for="name">Remark</label>
          <textarea  id="remark" name="remark" class="form-control" value="{{ old('remark')}}" placeholder="Remark"></textarea>
          <span class="error_message" id="remark_message" style="display: none">Field is required</span>
        </div>


            <div class="form-group col-md-6">
                  <label for="image_name">Image1</label>
                  <input type="file" id="image_name1" name="image_name1" accept=".jpg,.jpeg,.png,.pdf"/>
               

                  <p class="help-block">(Allowed Type: jpg,jpeg,png,pdf )</p>
                    <span class="error_message" id="image_name1_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group col-md-6">
                  <label for="image_name">Image2</label>
                  <input type="file" id="image_name2" name="image_name2" accept=".jpg,.jpeg,.png,.pdf"/>
                 

                  <p class="help-block">(Allowed Type: jpg,jpeg,png,pdf )</p>
                    <span class="error_message" id="image_name2_message" style="display: none">Field is required</span>
                </div>

        
        <input type="hidden" id="user_id" name="user_id" class="form-control" value="{{$user->id}}">
        

      </div>
      <div class="modal-footer">
      <div class="load-add" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
      <span id="successshow" style="display:none;color:green;">Contact Person Added</span>
        <button type="button" class="mdm-btn submit-btn  "  onclick="validate_contact()">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>



<!-- Modal -->
<div id="myModaledit" class="modal fade" role="dialog">
  <div class="modal-dialog">
  <form name="contactformedit" id="contactformedit" method="post" action"" />
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Contact Person</h4>
      </div>
      
      <div class="modal-body">

      <div class="form-group  col-md-6">
          <label for="name">Title*</label>
          <select  id="title_edit" name="title" class="form-control" >
          <option value="">Select Title</option>
          <option value="Mr.">Mr.</option>
          <option value="Ms.">Ms.</option>
          <option value="Dr.">Dr.</option>
          <option value="Fr.">Fr.</option>
          <option value="Sr.">Sr.</option>

          </select>
          <span class="error_message" id="title_edit_message" style="display: none">Field is required</span>
        </div>
                  
        <div class="form-group  col-md-6">
          <label for="name">First Name*</label>
          <input type="text" id="name_edit" name="name" class="form-control" value="{{ old('name')}}" placeholder="First Name">
          <span class="error_message" id="name_edit_message" style="display: none">Field is required</span>
        </div>

        <div class="form-group  col-md-6">
          <label for="name">Last Name</label>
          <input type="text" id="last_name_edit" name="last_name" class="form-control" value="" placeholder="Last Name">
          <span class="error_message" id="last_name_edit_message" style="display: none">Field is required</span>
        </div>

        <div class="form-group  col-md-6">
          <label for="name">Designation*</label>
         
          <select id="designation_edit" name="designation" class="form-control" >
              <option value="">Select Designation</option>      
              @foreach ($hosdesignation as $desigval)
              <option value="{{$desigval->id}}">{{$desigval->name}}</option> 
              @endforeach
          </select>        
          <span class="error_message" id="designation_edit_message" style="display: none">Field is required</span>
        </div>

        <div class="form-group  col-md-6">
          <label for="name">Department*</label>
         
          <select id="department_edit" name="department" class="form-control" >
              <option value="">Select Department</option>      
              @foreach ($hosdeparment as $hosdepart)
              <option value="{{$hosdepart->id}}">{{$hosdepart->name}}</option> 
              @endforeach
          </select>
          <span class="error_message" id="department_edit_message" style="display: none">Field is required</span>
        </div>

         

        <div class="form-group  col-md-6">
        <div class="form-group col-md-3">
                    <!-- <label >Status*</label> -->
                  <div class="radio">
                    <label>
                      <input type="radio" name="contact_type_edit" id="status_edit" value="HOD" checked="true">
                      HOD
                    </label>
                  </div>
                 
                </div>
        
                <div class="form-group col-md-3">
                    <!-- <label >Status*</label> -->
                  <div class="radio">
                    <label>
                      <input type="radio" name="contact_type_edit" id="status1_edit" value="Staff">
                      Staff
                    </label>
                  </div>
                  

                </div>
                </div>         

        <div class="form-group  col-md-12">
          <label for="name">Email*</label>
          <input type="text" id="email_edit" name="email" class="form-control" value="{{ old('email')}}" placeholder="Email">
          <span class="error_message" id="email_edit_message" style="display: none">Field is required</span>
        </div>

         <div class="form-group  col-md-12">
          <label for="name">Phone*</label>
          <input type="text" id="phone_edit" name="phone" class="form-control" value="{{ old('phone')}}" placeholder="Phone">
          <span class="error_message" id="phone_edit_message" style="display: none">Field is required</span>
        </div>


          <div class="form-group  col-md-6">
          <label for="name">Mobile</label>
          <input type="text" id="mobile_edit" name="mobile" class="form-control" value="{{ old('mobile')}}" placeholder="Mobile">
          <span class="error_message" id="mobile_edit_message" style="display: none">Field is required</span>
        </div>

        
        <div class="form-group  col-md-6">
          <label for="name">Whatsapp</label>
          <input type="text" id="whatsapp_edit" name="whatsapp" class="form-control" value="{{ old('whatsapp')}}" placeholder="Whatsapp">
          <span class="error_message" id="whatsapp_edit_message" style="display: none">Field is required</span>
        </div>

         

        <div class="form-group  col-md-12">
          <label for="name">Remark</label>
          <textarea  id="remark_edit" name="remark" class="form-control" value="" placeholder="Remark"></textarea>
          <span class="error_message" id="remark_edit_message" style="display: none">Field is required</span>
        </div>



         <div class="form-group hideRow col-md-12">
                  <label for="image_name">Image1</label>
                  <input type="file" id="image_name1_edit" name="image_name1_edit"  accept=".jpg,.jpeg,.png,.pdf"/>
                  <input type="hidden" id="current_image1" name="current_image1" value=""/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png,pdf )</p>

                   
                  <span class="error_message" id="image_name1_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group hideRow col-md-12">
                  <label for="image_name">Image2</label>
                  <input type="file" id="image_name2_edit" name="image_name2_edit"  accept=".jpg,.jpeg,.png,.pdf"/>
                  <input type="hidden" id="current_image2" name="current_image2" value=""/>

                  <p class="help-block">(Allowed Image Type: jpg,jpeg,png,pdf )</p>

                  <span class="error_message" id="image_name1_message" style="display: none">Field is required</span>
                </div>


        
        
        <input type="hidden" id="user_id_edit" name="user_id" class="form-control" value="{{$user->id}}">
        <input type="hidden" id="contact_id" name="contact_id" class="form-control" value="0">
                  
      </div>
      <div class="modal-footer">
      <div class="load-edit" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
      <span id="successshowedit" style="display:none;color:green;">Contact Person Edited</span>
        <button type="button" class="btn btn-primary"  onclick="validate_contact_edit()">Save</button>
      </div>
    </div>
    </form>
  </div>
</div>



@endsection

@section('scripts')
<script type="text/javascript">

function edit_contact(id)
{

  $("#myModaledit").modal('show');
  
}

function add_contact()
{$("#successshow").hide();
  $("#myModal").modal('show');
  
}
    jQuery(document).ready(function() {

       jQuery(".deletebtn").click(function() {
         var name=$(this).attr('attr-name');
         var email=$(this).attr('attr-email');
         var phone=$(this).attr('attr-phone');
         var department=$(this).attr('attr-department');
         var designation=$(this).attr('attr-designation');
         var id=$(this).attr('attr-id');
         var image1=$(this).attr('attr-image1');
         var image2=$(this).attr('attr-image2');
         var remark=$(this).attr('attr-remark');

          var title=$(this).attr('attr-title');
          var whatsapp=$(this).attr('attr-whatsapp');
          var mobile=$(this).attr('attr-mobile');
          var contact_type=$(this).attr('attr-contacttype');
          var last_name=$(this).attr('attr-lastname');
 
          $("#title_edit").val(title);
          $("#whatsapp_edit").val(whatsapp);
          $("#mobile_edit").val(mobile);
          $("#email_edit").val(email);
          $("#last_name_edit").val(last_name);
        if(contact_type=="HOD")
        {
          jQuery("#status_edit").attr('checked', true);
        }
        else{
          jQuery("#status1_edit").attr('checked', true);
        }
         $("#email_edit").val(email);
         $("#name_edit").val(name);
         $("#phone_edit").val(phone);
         $("#department_edit").val(department);
         $("#designation_edit").val(designation);
         $("#contact_id").val(id);
         $("#current_image1").val(image1);
         $("#current_image2").val(image2);
         $("#remark_edit").val(remark);
         
         
         $("#myModaledit").modal('show');
  
       });
      
        var oTable = $('#cmsTable').DataTable({
          
          initComplete: function(settings) {

            var info = this.api().page.info();
            var api = this.api();

            if (info.pages > 1) {

                $(".dataTables_paginate").show();
            } else {
                $(".dataTables_paginate").hide();

            }

            var searchInput = $('<input type="number" min="1" step="1" class="page-search-input" placeholder="Search pages...">');
            $(".col-sm-7").append(searchInput);

            if (info.pages > 1) {

                searchInput.on('input', function() {

                    var searchValue = $(this).val().toLowerCase();

                    var pageNum = searchValue - 1;

                    api.page(pageNum).draw('page');
                });
            }


            if (info.recordsTotal == 0) {

                $(".dataTables_info").hide();
            } else {
                $(".dataTables_info").show();
            }
            },

            createdRow: function(row, data, dataIndex) {

            // $(row).find('td').each(function(i, e) {

            //     $(e).attr('data-th', theader[i]);
                
            // });
            },
            drawCallback: function() {

            },
        });
    });
function validate_contact()
{
  
  var name=$("#name").val();
  var title=$("#title").val();
  var last_name=$("#last_name").val();
  var mobile=$("#mobile").val();
  var whatsapp=$("#whatsapp").val();
  var email=$("#email").val();
  var phone=$("#phone").val();
  var department=$("#department").val();
  var designation=$("#designation").val();
  var user_id=$("#user_id").val();
  var password=$("#password").val();
  var contact_type=$("input[name='contact_type']:checked").val();
 // alert(contact_type)
 
  if(title=="")
      {
        $("#title_message").show();
      }
      else{
        $("#title_message").hide();
      }
     /* if(last_name=="")
      {  
        $("#last_name_message").show();
      }
      else{
        $("#last_name_message").hide();
      }*/
    
   if(name=="")
      {
        $("#name_message").show();
      }
      else{
        $("#name_message").hide();
      }
      if(email=="")
      {
        $("#email_message").show();
      }
      else{
        $("#email_message").hide();
      }
      
   if(password=="")
      {
        $("#password_message").show();
      }
      else{
        $("#password_message").hide();
      }
      if(phone=="")
      {
        $("#phone_message").show();
      }
      else{
        $("#phone_message").hide();
      }
      if(department=="")
      {
        $("#department_message").show();
      }
      else{
        $("#department_message").hide();
      }  
      if(designation=="")
      {
        $("#designation_message").show();
      }
      else{
        $("#designation_message").hide();
      }     

  if(title!='' && password!='' && email!='' && name!=''  && phone!='' && phone!='' && department!='' && designation!='')
        {


          
          var formData = new FormData();
            
            var image_name1 = $('#image_name1').val();
              if(image_name1 != '') {    
              formData.append('image_name1',$("#image_name1")[0].files[0]);
              }
           var image_name2 = $('#image_name2').val();
            if(image_name2 != '') {    
              formData.append('image_name2',$("#image_name2")[0].files[0]);
            }
          formData.append('title',title);  
          formData.append('mobile',mobile);
          formData.append('whatsapp',whatsapp); 
          formData.append('last_name',last_name); 
          formData.append('contact_type',contact_type); 
          
          formData.append('name',name);
         
          formData.append('email',email);
          formData.append('phone',phone);
         
          
          formData.append('department',department);
          formData.append('designation',designation);
          formData.append('user_id',user_id);
          formData.append('password',password);

          formData.append('remark',$("#remark").val());
         
          
          $('.load-add').show();
          var url = APP_URL+'/admin/add_contact_person';
          $.ajax({
          type: "POST",
          cache: false,
          url: url,
          processData: false,
          contentType: false,
         // data:$("#contactform").serialize(),
         data:formData,
          success: function (data)
          {   $('.load-add').hide();
          $("#successshow").show();
           window.location="{{ url('admin/view_contact/'.$user->id) }}"
          }
        });  

         //$("#frm_user").submit(); 
        }
}

function validate_contact_edit()
{
 
  var name=$("#name_edit").val();
  var email=$("#email_edit").val();
  var phone=$("#phone_edit").val();
  var title=$("#title_edit").val();
  var last_name=$("#last_name_edit").val();
  var mobile=$("#mobile_edit").val();
  var whatsapp=$("#whatsapp_edit").val();
  var department=$("#department_edit").val();
  var designation=$("#designation_edit").val();
  var user_id=$("#user_id").val(); 
  var contact_type=$("input[name='contact_type_edit']:checked").val();
 
 

  var current_image1=$("#current_image1").val();
  var current_image2=$("#current_image2").val();
  
  
  if(title=="")
      {
        $("#title_message").show();
      }
      else{
        $("#title_message").hide();
      }
     /* if(last_name=="")
      {
        $("last_name_message").show();
      }
      else{
        $("#last_name_message").hide();
      }*/
   if(name=="")
      {
        $("#name_edit_message").show();
      }
      else{
        $("#name_edit_message").hide();
      }
      if(email=="")
      {
        $("#email_edit_message").show();
      }
      else{
        $("#email_edit_message").hide();
      }
      

      if(phone=="")
      {
        $("#phone_edit_message").show();
      }
      else{
        $("#phone_edit_message").hide();
      }
      if(department=="")
      {
        $("#department_edit_message").show();
      }
      else{
        $("#department_edit_message").hide();
      }  
      if(designation=="")
      {
        $("#designation_edit_message").show();
      }
      else{
        $("#designation_edit_message").hide();
      }     
     
  if(title!='' &&  email!='' && name!=''  && phone!='' && department!='' && designation!='')
        {
        
           var formData = new FormData();
            
            var image_name1 = $('#image_name1_edit').val();
              if(image_name1 != '') {    
              formData.append('image_name1',$("#image_name1_edit")[0].files[0]);
              }
           var image_name2 = $('#image_name2_edit').val();
            if(image_name2 != '') {    
              formData.append('image_name2',$("#image_name2_edit")[0].files[0]);
            }

          formData.append('title',title);  
          formData.append('mobile',mobile);
          formData.append('whatsapp',whatsapp); 
          formData.append('last_name',last_name); 
          formData.append('contact_type',contact_type); 

          formData.append('name',name);
          formData.append('email',email);
          formData.append('phone',phone);
          formData.append('department',department);
          formData.append('designation',designation);
          formData.append('user_id',user_id);
          formData.append('remark',$("#remark_edit").val());

          formData.append('current_image1',current_image1);
          formData.append('current_image2',current_image2);
          formData.append('contact_id',$("#contact_id").val());
          $('.load-edit').show();
          var url = APP_URL+'/admin/contactformedit';
          $.ajax({
          type: "POST",
          cache: false,
          processData: false,
          contentType: false,
          url: url,
          data:formData,
        //  data:$("#contactformedit").serialize(),
          success: function (data)
          {   $('.load-edit').hide();
          $("#successshowedit").show();
          window.location="{{ url('admin/view_contact/'.$user->id) }}"
          }
        });  

         //$("#frm_user").submit(); 
        }
}

function get_password()
      {
        var demopassword=$("#demopassword").val();
        $("#password").val(demopassword);
      }
</script>
@endsection
 