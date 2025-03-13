

@extends('admin/layouts.app')

@section('title', 'Edit Checklist')

@section('content')


<section class="content-header">
      <h1>
        Edit Checklist
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{route('admin.checklist.index')}}">Manage Checklist</a></li>
        <li class="active">Edit Checklist</li>
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

            <form role="form" name="frm_company" id="frm_company" method="post" action="{{ route('admin.checklist.update', $checklist->id) }}" enctype="multipart/form-data" >
               @csrf
               {{method_field('PUT')}}
                <div class="box-body">

               

                <div class="form-group">
                  <label for="name">Related Category*</label>
                  <select  onchange="change_relative_category()" id="related_category_id" name="related_category_id" class="form-control">
                  <option value="">Select Related Category</option>
                  @foreach($relatedto_category as $values)
                  <?php
                    $sel = ($checklist->related_subcategory_id == $values->id) ? 'selected': '';
                  echo '<option value="'.$values->id.'" '.$sel.' >'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="related_category_id_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group">
                  <label for="name">Related SubCategory*</label>
                  <select id="related_subcategory_id" name="related_subcategory_id" class="form-control">
                  <option value="">Select Related SubCategory</option>
                  @foreach($relatedto_subcategory as $values)
                  <?php
                      $sel = ($checklist->related_subcategory_id == $values->id) ? 'selected': '';
                  echo '<option value="'.$values->id.'"  '.$sel.' >'.$values->name.'</option>';
                  ?>
                  <!-- <option value="{{$values->id}}">{{$values->name}}</option> -->
                  @endforeach
                  </select>
                  <span class="error_message" id="related_subcategory_id_message" style="display: none">Field is required</span>
                </div>

                 <div class="form-group">
                  <label for="name">Name*</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{$checklist->name}}" placeholder="Name">
                  <span class="error_message" id="name_message" style="display: none">Field is required</span>
                </div>



             

                <div class="form-group">
                    <label >Status*</label>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="Y" <?php echo ($checklist->status == 'Y') ? 'checked': '' ?>>
                      Active
                    </label>
                  </div>
                  <div class="radio">
                    <label>
                      <input type="radio" name="status" id="status1" value="N" <?php echo ($checklist->status == 'N') ? 'checked': '' ?>>
                      Inactive
                    </label>
                  </div>

                </div>

              </div>
              <!-- /.box-body -->

              <div class="box-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                 <button type="button" class="btn btn-danger" onClick="window.location.href='{{route('admin.checklist.index')}}'">Cancel</button>
              </div>
            </form>
          </div>

        </div>
      </div>
</section>

@endsection

@section('scripts')
 <script type="text/javascript">

  function change_relative_category(){
  var related_category_id=$("#related_category_id").val();
  var url = APP_URL+'/admin/change_relative_category';
   $.ajax({
          type: "POST",
          cache: false,
          url: url,
          data:{
            related_category_id: related_category_id,
          },
          success: function (data)
          {    
            var proObj = JSON.parse(data);
            states_val='';
            states_val +='<option value="">Select Relative Subcategory</option>';
            for (var i = 0; i < proObj.length; i++) {
             
              states_val +='<option value="'+proObj[i]["id"]+'">'+proObj[i]["name"]+'</option>';
           
              }
              $("#related_subcategory_id").html(states_val);
             
              
           
          }
        });

  }

  
      function validate_from()
      {
        var name=$("#name").val();
        var image_name=$("#image_name").val();
        
        if(name=="")
        {
          $("#name_message").show();
        }
        else{
          $("#name_message").hide();
        }

     

         
        if(name!='' )
        {
         $("#frm_company").submit(); 
        }


      }

    </script>

@endsection
