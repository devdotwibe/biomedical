@extends('staff/layouts.app')

@section('title', 'Add Customer')

@section('content')

<section class="content-header">
      <h1>
        Add Customer
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('staff.customer.index')}}">Manage Customer</a></li>
        <li class="active">Add Customer</li>
      </ol>
    </section>


<section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-10">
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
            {{ $errors->first('email') }}
            </p>

            <form role="form" name="frm_user" id="frm_user" method="post" action="{{route('staff.customer.store')}}" enctype="multipart/form-data" >
               @csrf
       
           
               <div class="box-body">

               
<div class="form-group">
    <div class="row">
		<div class="col-xs-12">

			<!-- tabs -->
			<div class="tabbable tabs-left">
				<ul class="nav nav-tabs">
					<li class="active"><a href="#personal_details" data-toggle="tab">Business Name</a></li>
					<!-- <li><a href="#about" data-toggle="tab">Feature</a></li> -->
					<li><a href="#account_details" data-toggle="tab">Bank Details</a></li>
          <li><a href="#business_details" data-toggle="tab">Shipping Address</a></li>
          
				</ul>
				<div class="tab-content">
				

					<div class="tab-pane active" id="personal_details">
						<div class="">
            <!-- <h4>Personal Details</h4> -->
             <div class="form-group  col-md-6">
                  <label for="name">Business Name*</label>
                  <input type="text" id="business_name" name="business_name" class="form-control" value="{{ old('business_name')}}" placeholder="Business Name">
                  <span class="error_message" id="business_name_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group  col-md-6">
                  <label for="name">Email*</label>
                  <input  type="email" id="email" name="email" class="form-control" value="{{ old('email')}}" placeholder="Email">
                  <span class="error_message" id="email_message" style="display: none">Field is required</span>
                </div>


                  <div class="form-group col-md-6">
                  <label for="name">Password*</label>
                  <?php
                  $random = str_shuffle('abcdefghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&');
                  $passwordval = substr($random, 0, 10);
                
                  ?>
                    <input type="hidden" id="demopassword" name="demopassword" value="{{$passwordval}}">
                  
                  <input type="text" id="password" name="password" value="{{ old('password')}}" class="form-control" placeholder="Password">
                  <span class="error_message" id="password_message" style="display: none">Field is required</span>
                  <a onclick="get_password()">Generate Password</a>
                </div>


                 <div class="form-group  col-md-6">
                  <label for="name">Phone*</label>
                  <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone')}}" placeholder="Phone">
                  <span class="error_message" id="phone_message" style="display: none">Field is required</span>
                </div>

              

                

                 <div class="form-group  col-md-12">
                  <label for="name">Address*</label>
                  <textarea  id="address1" name="address1" class="form-control"  placeholder="Address">{{ old('address1')}}</textarea>
                
                  <span class="error_message" id="address1_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group  col-md-6">
                  <label for="name">GST</label>
                  <input type="text" id="gst" name="gst" class="form-control" value="{{ old('gst')}}" placeholder="Business Name">
                  <span class="error_message" id="gst_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group  col-md-6">
                  <label for="name">Country</label>
                  <select id="country_id" name="country_id" class="form-control">
                  <option value="">Select Country</option>
                  @foreach($country as $values)
                  <option value="{{$values->id}}">{{$values->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="country_id_message" style="display: none">Field is required</span>
                </div>

                  <div class="form-group  col-md-6">
                  <label for="name">State*</label>
                  <input type="text" id="state" name="state" class="form-control" value="{{ old('state')}}" placeholder="State">
                  <span class="error_message" id="state_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">City*</label>
                  <input type="text" id="city" name="city" class="form-control" value="{{ old('city')}}" placeholder="City">
                  <span class="error_message" id="city_message" style="display: none">Field is required</span>
                </div>
              
                <div class="form-group  col-md-6">
                  <label for="name">Zip*</label>
                  <input type="text" id="zip" name="zip" class="form-control" value="{{ old('zip')}}" placeholder="Zip">
                  <span class="error_message" id="zip_message" style="display: none">Field is required</span>
                </div>
              

               
                           

						</div>
					</div>



					<div class="tab-pane" id="account_details">
						<div class="">
            <h2>Bank Details</h2>

                 <div class="form-group  col-md-6">
                  <label for="name">Account Name</label>
                  <input type="text" id="account_name" name="account_name" class="form-control" value="{{ old('account_name')}}" placeholder="Account Name">
                  <span class="error_message" id="account_name_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">Bank</label>
                  <input type="text" id="bank_name" name="bank_name" class="form-control" value="{{ old('bank_name')}}" placeholder="Bank">
                  <span class="error_message" id="bank_name_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-12">
                  <label for="name">Bank Address</label>
                  <textarea id="bank_address" name="bank_address" class="form-control" placeholder="Bank Address">{{ old('bank_address')}}</textarea>
                  <span class="error_message" id="bank_address_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group  col-md-6">
                  <label for="name">IFSC Code</label>
                  <input type="text" id="ifsc_code" name="ifsc_code" class="form-control" value="{{ old('ifsc_code')}}" placeholder="IFSC Code">
                  <span class="error_message" id="ifsc_code_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Account No</label>
                  <input type="text" id="account_no" name="account_no" class="form-control" value="{{ old('account_no')}}" placeholder="Account No">
                  <span class="error_message" id="account_nomessage" style="display: none">Field is required</span>
                </div>
               

						</div>
          </div>

          <div class="tab-pane" id="business_details">
          <input type="hidden" id="count_addr" name="count_addr" class="form-control" value="1">
						<div class="panel panel-default" id="row_1">
             
            <div class="panel-body">

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
                  <select id="shipping_country_id" name="shipping_country_id[]" class="form-control">
                  <option value="">Select Country</option>
                  @foreach($country as $values)
                  <option value="{{$values->id}}">{{$values->name}}</option>
                  @endforeach
                  </select>
                  <span class="error_message" id="shipping_country_id_message" style="display: none">Field is required</span>
                </div>

                <div class="form-group  col-md-6">
                  <label for="name">City</label>
                  <input type="text" id="shipping_city" name="shipping_city[]" class="form-control" value="" placeholder="City">
                  <span class="error_message" id="shipping_city_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">State</label>
                  <input type="text" id="state" name="shipping_state[]" class="form-control" value="" placeholder="State">
                  <span class="error_message" id="shipping_state_message" style="display: none">Field is required</span>
                </div>
                <div class="form-group  col-md-6">
                  <label for="name">Zip</label>
                  <input type="text" id="shipping_zip" name="shipping_zip[]" class="form-control" value="" placeholder="Zip">
                  <span class="error_message" id="shipping_zip_message" style="display: none">Field is required</span>
                </div>
                
                </div>
						</div>
            <div id="responcesec"></div>
            <button type="button" class="btn btn-primary" onclick="addmore()">Add More</button>



          </div>

         

				</div>
			</div>
			<!-- /tabs -->
		</div>
	</div>
</div>

               

              


            
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary" onclick="validate_from()">Submit</button>
                 <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('staff.customer.index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>

@endsection


@section('scripts')
 <script type="text/javascript">
  function addmore()
      {
        
        var count_addr=$("#count_addr").val();
        var add_count=parseInt(count_addr)+1;
        $("#count_addr").val(add_count);
        var htmls='<div class="panel panel-default" id="row_'+add_count+'">'+
            
           ' <div class="panel-body">'+

              '<div class="form-group  col-md-6">'+
              '<label for="name">Street Address1</label>'+
              '<input type="text"   id="shipping_address1'+add_count+'" name="shipping_address1[]" class="form-control"  placeholder="Address1" value="">'+
                
              '<span class="error_message" id="address1_message" style="display: none">Field is required</span>'+
              '</div>'+
              ' <div class="form-group  col-md-6">'+
              '  <label for="name">Street Address2</label>'+
              '  <input type="text"  id="shipping_address2'+add_count+'" name="shipping_address2[]" class="form-control"  placeholder="Address2" value="">'+
              '  <span class="error_message" id="address2_message" style="display: none">Field is required</span>'+
              ' </div>'+

              ' <div class="form-group  col-md-6">'+
              ' <label for="name">Country</label>'+
              '  <select id="shipping_country_id" name="shipping_country_id[]" class="form-control">'+
              '   <option value="">Select Country</option>'+
              '  @foreach($country as $values)'+
                  '   <option value="{{$values->id}}">{{$values->name}}</option>'+
                  '  @endforeach'+
                  '</select>'+
                  '<span class="error_message" id="bank_city_message" style="display: none">Field is required</span>'+
                '</div>'+

               '<div class="form-group  col-md-6">'+
               ' <label for="name">City</label>'+
               ' <input type="text" id="shipping_city'+add_count+'" name="shipping_city[]" class="form-control" value="" placeholder="City">'+
               '  <span class="error_message" id="city_message" style="display: none">Field is required</span>'+
               ' </div>'+
               ' <div class="form-group  col-md-6">'+
               ' <label for="name">State</label>'+
               ' <input type="text" id="shipping_state'+add_count+'" name="shipping_state[]" class="form-control" value="" placeholder="State">'+
               ' <span class="error_message" id="state_message" style="display: none">Field is required</span>'+
               ' </div>'+
               '  <div class="form-group  col-md-6">'+
               '  <label for="name">Zip</label>'+
               '  <input type="text" id="shipping_zip'+add_count+'" name="shipping_zip[]" class="form-control" value="" placeholder="Zip">'+
               ' <span class="error_message" id="zip_message" style="display: none">Field is required</span>'+
               ' </div>'+
              
                 '  <div class="form-group  col-md-6">'+
               ' <br>'+
               ' <button type="button" class="btn btn-danger" onClick="remove('+add_count+')">Remove</button>'+
               ' </div>'+
                
               ' </div>'+
               '</div>';
        //responcesec
        $("#responcesec").append(htmls);
      }
      function remove(row_no)
      {
        var count_addr=$("#count_addr").val();
        var add_count=parseInt(count_addr)-1;
        $("#count_addr").val(add_count);
        $("#row_"+row_no).remove();
      }
  function get_password()
      {
        var demopassword=$("#demopassword").val();
        $("#password").val(demopassword);
      }
     function validate_from()
      {
       
        var phone=$("#phone").val();
        var email=$("#email").val();
        var password=$("#password").val();
        var business_name=$("#business_name").val();
        var address1=$("#address1").val();
        var state=$("#state").val();
        var city=$("#city").val();
        var zip=$("#zip").val();

        var account_name=$("#account_name").val();
        var bank_name=$("#bank_name").val();
        var bank_address=$("#bank_address").val();
        var ifsc_code=$("#ifsc_code").val();
        var account_no=$("#account_no").val();
        
       
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

         if(business_name=="")
        {
          $("#business_name_message").show();
        }
        else{
          $("#business_name_message").hide();
        }

        if(address1=="")
        {
          $("#address1_message").show();
        }
        else{
          $("#address1_message").hide();
        }
        
        if(city=="")
        {
          $("#city_message").show();
        }
        else{
          $("#city_message").hide();
        }

      if(state=="")
        {
          $("#state_message").show();
        }
        else{
          $("#state_message").hide();
        }

      if(zip=="")
        {
          $("#zip_message").show();
        }
        else{
          $("#zip_message").hide();
        }


      
        if(account_name=="")
        {
          $("#account_name_message").show();
        }
        else{
          $("#account_name_message").hide();
        }
        if(bank_name=="")
        {
          $("#bank_name_message").show();
        }
        else{
          $("#bank_name_message").hide();
        }

         if(bank_address=="")
        {
          $("#bank_address_message").show();
        }
        else{
          $("#bank_address_message").hide();
        }

            if(ifsc_code=="")
        {
          $("#ifsc_code_message").show();
        }
        else{
          $("#ifsc_code_message").hide();
        }

        if(account_no=="")
        {
          $("#account_no_message").show();
        }
        else{
          $("#account_no_message").hide();
        }
      
      
    
   
         
        if(account_no!='' && ifsc_code!='' && bank_address!='' && bank_name!='' && account_name!='' && email!='' && password!=''  && phone!='' && business_name!='' && address1!='' && city!='' && state!='' && zip!='')
        {
         $("#frm_user").submit(); 
        }


      }

    </script>

@endsection

