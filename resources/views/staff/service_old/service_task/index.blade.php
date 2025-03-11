

@extends('staff/layouts.app')

@section('title', 'Service Tasks')

@section('content')


<section class="content-header">
      <h1>
        Service Tasks
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Service Tasks</li>
      </ol>
</section>

    <!-- Main content -->
<div class="se-pre-con1"></div>
<section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

            </div>

        </div>

    </div>

            @if (session('success'))
               <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <!-- /.box-header -->
            <div class="box-body">
              <form name="dataForm" id="dataForm" method="post" action="" />
              @csrf

                <table id="cmsTable" class="table table-bordered table-striped data-" onmousedown="return false" onselectstart="return false">
                  <thead>
                  <tr class="headrole">
                      <th>Sl.No</th>
                      <th>Service Reference</th>
                      <th>Service Type</th>
                      <th>Customer Name</th>
                      <th>Contact Details</th>
                      <th>Equipment Name</th>
                      <th>Equipment Status</th>
                      <th>Machine Status</th>
                      <th>Call Details</th>
                      <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  @if($services->isEmpty())
                            <tr>
                              <td>No Result Found</td>  
                            </tr>
                        @else
                            @foreach ($services as $key=>$value)
                            <tr>
                                <td>{{ ($services->currentpage()-1) * $services->perpage() + $key + 1 }}</td>
                                <td>{{ $value->internal_ref_no }}</td>
                                <td>{{ $value->serviceServiceType->name }}</td>   
                                <td>{{ $value->serviceUser->business_name }}</td>
                                <td>{{ $value->serviceContactPerson->name }}, {{ $value->serviceContactPerson->mobile }}</td>
                                <td>{{ $value->serviceProduct->name }}</td>
                                <td>{{ $value->serviceEquipmentStatus->name }}</td>
                                <td>{{ $value->serviceMachineStatus->name }}</td>
                                <td>{{ $value->call_details }}</td>
                                <td>
                                    <a class="btn btn-primary btn-xs report">Create Report</a>
                                </td>
                            </tr>
                          @endforeach
                        @endif    
                  
                  </tbody>
                </table>
                {{ $services->appends(request()->query()) }}
              </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

<!-- Modal -->
    <div class="modal fade" id="createReport" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header text-center">
                      <h4 class="modal-title w-100 font-weight-bold">Create Report</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <form autocomplete="off" role="form" name="frm_createReport" id="frm_createReport" method="POST" action="" enctype="multipart/form-data" >
                    @csrf
                      <div class="modal-body">
                          <div class="form-group col-md-12 col-sm-12 col-lg-12">
                            <label for="name">Comment</label>
                            <textarea id="task_comment" name="task_comment" class="form-control" placeholder=""></textarea>
                            <span class="error_message" id="task_comment_message" style="display: none">Field is required</span>
                          </div>
                          <div class="form-group col-md-12 col-sm-12 col-lg-12">                       
                            <label for="name">If need further requirement</label>
                            <input id="nextStage" type="checkbox">
                            <span class="error_message" id="task_comment_message" style="display: none">Field is required</span>
                          </div>
                          <div id="not_closed" class="form-group col-md-12 col-sm-6 col-lg-12">                       
                            <label for="name">Field Visit</label>
                            <input id="field_visit" type="checkbox">
                            <label for="name">Equipment Req</label>
                            <input id="equipment_req" type="checkbox">
                            <label for="name">Higher Engineer Required</label>
                            <input id="high_eng_req" type="checkbox">
                          </div> 
                      </div>
                      <div class="modal-footer justify-content-center">
                          <button class="btn btn-primary" id="edit_createReport_submit" type="submit">Submit</button>
                          <button class="btn btn-danger" data-dismiss="modal">Cancel</button>
                      </div>
                  </form>
              </div>
          </div>
      </div>

</section>

@endsection

@section('scripts')
<script>
$(document).ready(function() {
  $('#not_closed').hide();
});
    $('.report').click(function(){
      $('#createReport').modal('show'); 
    });

    $("#nextStage").click(function(){

        if ($(this).is(":checked")) {
          $('#not_closed').show();
        }
        else {
          $('#not_closed').hide();
        }
    });

</script>  
@endsection
