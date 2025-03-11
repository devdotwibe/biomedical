

@extends('staff/layouts.app')

@section('title', 'Update Lead Option')

@section('content')


<section class="content-header">
      <h1>
        Update Lead Option
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url('staff/lead_option')}}">Manage Lead Option</a></li>
        <li class="active">Update Lead Option</li>
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
                     @if($lead->user_id!='')
                    <select name="customer_name" id="customer_name" class="form-control">
                      <option value="">-- Select Customer Name --</option>
                      @foreach($customer as $item) 
                        <option value='{{$item->id}}' @if(old('customer_name',$lead->user_id)==$item->id){{'selected'}} @endif>{{$item->business_name}}</option>
                      @endforeach
                    </select>
                  @else
                    <input type="text" name="new_customer" class="form-control" value="{{old('new_customer',$lead->customer_name)}}">
                  @endif
                </div>

                <div class="form-group  col-md-6">
                  <label>Contact Name*</label>
                  @if($lead->contact_person_id!='')
                    <select name="contact_name" id="contact_name" class="form-control">
                      <option value="">-- Select Contact Name --</option>
                      @foreach($contact as $item) 
                        <option value='{{$item->id}}' @if(old('contact_name',$lead->contact_person_id)==$item->id){{'selected'}} @endif>{{$item->name}}</option>
                      @endforeach
                    </select>
                    <a id="contact_link" href="" target='_blank'>Add contact</a> 
                  @else
                    <input type="text" name="new_contact" class="form-control" value="{{old('new_contact',$lead->contact_person_name)}}">
                  @endif 
                </div>

                <div class="form-group  col-md-6">
                  <label>Staff Name*</label>
                  <select name="staff_name" id="staff_name" class="form-control">
                    <option value="">-- Select Staff Name --</option>
                    @foreach($staff as $item) 
                       <option value='{{$item->id}}' @if(old('staff_name',$lead->staff_id)==$item->id){{'selected'}} @endif>{{$item->name}}</option>
                    @endforeach
                  </select>
                </div>

              <div class="form-group col-md-12">
                  <label for="description">Description</label>
                  <textarea class="form-control" id="description" name="description" rows="10" cols="80" placeholder="Description" >{{ $lead->description }}</textarea>
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
        $('#contact_name').load("{{url('admin/loadcontacts')}}/"+id);
        $("#contact_link"). attr("href", APP_URL+'/admin/customer/'+id);
    });
      

  });

  </script>

@endsection
