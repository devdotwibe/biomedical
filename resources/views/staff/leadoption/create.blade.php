

@extends('staff/layouts.app')

@section('title', 'Add Lead Option')

@section('content')


<section class="content-header">
      <h1>
        Add Lead Option
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/lead_option')}}">Manage Lead Option</a></li>
        <li class="active">Add Lead Option</li>
      </ol>
</section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-10">
          <!-- general form elements -->
          <div class="box box-primary">
<!--            <div class="box-header with-border">
              <h3 class="box-title">Change Password</h3>
            </div>-->
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

           @if (count($errors) > 0)
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          @endif
            <form role="form" name="frm_products" id="frm_products" method="post"  enctype="multipart/form-data" >
               @csrf
              <div class="box-body">
                
                <div class="form-group  col-md-6">
                  <label>Customer Name*</label>
                  <select name="customer_name" id="customer_name" class="form-control">
                    <option value="">-- Select Customer Name --</option>
                    <?php
                    foreach($customer as $item) {
                      
                      echo '<option value="'.$item->id.'">'.$item->business_name.'</option>';
    
                    } ?>
                  </select>
                </div>

                <div class="form-group  col-md-6">
                  <label>Contact Name*</label>
                  <select name="contact_name" id="contact_name" class="form-control">
                    <option value="">-- Select Contact Name --</option>
                  </select>
                  <a id="contact_link" href="" target='_blank'>Add contact</a>  
                </div>

                <div class="form-group  col-md-12">
                  <a href="#" class="showClick show">Add New Customer</a>
                </div>

                <div id ="cust" style="display: none;">
                  <div class="form-group  col-md-6">
                    <label>Customer Name</label>
                    <input type="text" name="new_customer" class="form-control" value="">
                  </div>
                  <div class="form-group  col-md-6">
                    <label>Contact Name</label>
                    <input type="text" name="new_contact" class="form-control" value="">
                  </div>
                </div>

                <div class="form-group  col-md-6">
                  <label>Staff Name*</label>
                  <select name="staff_name" id="staff_name" class="form-control">
                    <option value="">-- Select Staff Name --</option>
                    <?php
                    foreach($staff as $item) {
                      
                      echo '<option value="'.$item->id.'">'.$item->name.'</option>';
    
                    } ?>
                  </select>
                </div>

              <div class="form-group col-md-12">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="10" cols="80" placeholder="Description">{{ old('description') }}</textarea>
              </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer col-md-12">
                <input type="submit" class="btn btn-primary">
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{url('admin/lead_option')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>




@endsection

@section('scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

 <script>
  $(document).ready(function(){
    $('#customer_name').change(function(){
      var id= $('#customer_name option:selected').val();
        $('#contact_name').load("{{url('staff/loadcontacts')}}/"+id);
        $("#contact_link"). attr("href", APP_URL+'/admin/customer/'+id);
    });
      

  });

  </script>

  <script type="text/javascript">
    $(function (){
      $('.showClick').click(function (){
        $('#cust').show();
        return false;
      });
    });
  </script>

@endsection
