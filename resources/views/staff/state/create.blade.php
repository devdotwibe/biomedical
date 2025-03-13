

@extends('admin/layouts.app')

@section('title', 'Add State')

@section('content')

<section class="content-header">
      <h1>
        Add State
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.state.index')}}">Manage State</a></li>
        <li class="active">Add State</li>
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

            <form role="form" name="frm_company" id="frm_company" method="post" action="{{route('admin.state.store')}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body">

      <div class="form-group">
                  <label for="name">Country*</label>
                  <select id="country_id" name="country_id" class="form-control">
                  <option value="">Select Country</option>
                  @foreach($country as $values)
                  <?php
                  
                  echo '<option value="'.$values->id.'" >'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="country_id_message" style="display: none">Field is required</span>
                </div>


                <div class="form-group">
                  <label for="name">State*</label>
                  <input type="text" id="name" name="name" value="{{ old('name')}}" class="form-control" placeholder="State">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>


              


              
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('admin.state.index')}}'">Cancel</button>
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
        if(country_id=="")
        {
          $("#country_id_message").show();
        }
        else{
          $("#country_id_message").hide();
        }

        
        if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
        }

     
         
        if(name!='' && country_id!='' )
        {
         $("#frm_company").submit(); 
        }


      }

    </script>
@endsection
