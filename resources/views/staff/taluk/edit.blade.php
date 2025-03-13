

@extends('admin/layouts.app')

@section('title', 'Edit Taluk')

@section('content')


<section class="content-header">
      <h1>
        Edit Taluk
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.taluk.index')}}">Manage Taluk</a></li>
        <li class="active">Edit Taluk</li>
      </ol>
    </section>

<section class="content">
      <div class="row">

        <!-- left column -->
        <div class="col-md-3 leftside-menu">
            <div class="panel_s mbot5">
               <div class="panel-body padding-10">
                  <h4 class="bold">
                 Options
                     <!-- <div class="btn-group">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                                                      <li>
                              <a href="#" target="_blank">
                              <i class="fa fa-share-square-o"></i> Login as client                              </a>
                           </li>
                                                                                 <li>
                              <a href="#" class="text-danger delete-text _delete"><i class="fa fa-remove"></i> Delete                               </a>
                           </li>
                                                   </ul>
                     </div> -->
                     </h4>
               </div>
            </div>
          
        @include('admin/layouts/options')
         </div>

         
        <!-- left column -->
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

            <form role="form" name="frm_company" id="frm_company" method="post" action="{{ route('admin.taluk.update', $taluk->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
                <div class="box-body">


                <div class="form-group ">
                  <label for="name">Country*</label>
                  <select id="country_id" name="country_id" class="form-control">
                  <option value="">Select Country</option>
                  @foreach($country as $values)
                  <?php
                  $sel = ($taluk->country_id == $values->id) ? 'selected': '';
                  echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="country_id_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group ">
                  <label for="name">State*</label>
                  <select id="state_id" name="state_id" class="form-control"  onchnage="change_state()">
                  <option value="">Select State</option>
                  @foreach($state as $values)
                  <?php
                  $sel = ($taluk->state_id == $values->id) ? 'selected': '';
                  echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="state_id_message" style="display: none">Field is required</span>
                </div>


                 <div class="form-group ">
                  <label for="name">District*</label>
                  <select id="district_id" name="district_id" class="form-control">
                  <option value="">Select District</option>
                  @foreach($district as $values)
                  <?php
                  $sel = ($taluk->district_id == $values->id) ? 'selected': '';
                  echo '<option value="'.$values->id.'" '.$sel.'>'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="district_id_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group">
                  <label for="name">Taluk*</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{$taluk->name}}" placeholder="Taluk">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

             


              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                 <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('admin.taluk.index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')
 <script type="text/javascript">
         function validate_from()
      {
        var name=$("#name").val();
        var country_id=$("#country_id").val();
        var state_id=$("#state_id").val();
        var district_id=$("#district_id").val();
        
        if(country_id=="")
        {
          $("#country_id_message").show();
        }
        else{
          $("#country_id_message").hide();
        }

         if(state_id=="")
        {
          $("#state_id_message").show();
        }
        else{
          $("#state_id_message").hide();
        }

         if(district_id=="")
        {
          $("#district_id_message").show();
        }
        else{
          $("#district_id_message").hide();
        }

        
        if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
        }

     
         
        if(name!='' && country_id!='' && state_id!='' && district_id!='')
        {
         $("#frm_company").submit(); 
        }


      }

      function change_country(){
  var country_id=$("#country_id").val();
  var url = APP_URL+'/admin/change_country';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            country_id: country_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select State</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#state_id").html(states_val);
             
              
           
          }
        });

  }

   function change_state(){
  var state_id=$("#state_id").val();
  var url = APP_URL+'/admin/change_state';
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
             
              
           
          }
        });

  }

    </script>

@endsection
