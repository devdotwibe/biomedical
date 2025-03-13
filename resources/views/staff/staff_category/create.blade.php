

@extends('staff/layouts.app')

@section('title', 'Add Staff Category')

@section('content')

<section class="content-header">
      <h1>
        Add Staff Category
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.brand.index')}}">Manage Staff Category</a></li>
        <li class="active">Add Staff Category</li>
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
            </p>

            <form role="form" name="frm_category" id="frm_category" method="post" action="{{route('admin.staff_category.store')}}" enctype="multipart/form-data" >
               @csrf
                <div class="box-body">


                <div class="form-group">
                  <label for="name">Category Name*</label>
                  <input type="text" id="name" name="name" value="{{ old('name')}}" class="form-control" placeholder="Category Name">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="button" class="btn btn-primary"  onclick="validate_from()">Submit</button>
                <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('admin.staff_category.index')}}'">Cancel</button>
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

        if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
        }

     
         
        if(name!='' )
        {
         $("#frm_category").submit(); 
        }


      }

    </script>
@endsection
