@extends('layouts.appmasterspace')
<?php
$title       = 'IB';
$description = 'IB';
$keywords    = 'IB';
$message="";
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<header class="header">
        <nav class="navbar navbar-expand-lg navbar-light bg-white custom-nav">
            <!-- <i class="fa fa-bars mobile-menu"></i> -->
            <a class="navbar-brand" href="https://biomedicalengineeringcompany.com/"><img src="https://biomedicalengineeringcompany.com/images/logo.png"></a>
            <div class="collapse navbar-collapse" id="user-img">
                @include('marketspace/navbar')
                  </div>
        </nav>
    </header>

    <div class="container-fluid height100">
        <div class="row dashboard-row leftnone">

            <main class="col-md-10" id="main">
                <section class="content-wrap bg-none">
                    <div class="content-col1">
                    <div class="content-col1-head">
                          <h2>Add IB</h2>
                          <div class="add-skill"><a href="{{ route('marketspace-ib') }}" id="add-reference"> Back to listing</a> 
                            
                          </div>
                        </div>

                       <div class="card">
                        <div class="card-top-section">
                      
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="tab-card">
                    <div class="tab-card-header">
        
        
                        <h5>	Add IB</h5>
                    </div>
                    <div class="tab-card-form">
                    <form autocomplete="off" role="form" name="frm_ib" id="frm_ib" method="post" action="{{route('ibstore')}}" enctype="multipart/form-data" >
                   @csrf
                   @if($marketspace->user_id==NULL)
                   Please select hospital from <a href="{{ route('marketspace/editprofile') }}">Edit profile page</a> before add IB
                   @endif
                   @if($marketspace->user_id>0)
                        <div class="form">
                            <div class="row" style="display:none;">
                                <div class="form-group col-md-6">
                                    <label for="pannumber">Internal Ref No*</label>
                                    @php
                        $inRefno = 'IB-'.rand(1000, 100000);
                        $extRefno = 'IB-'.rand(1000, 100000);
                    @endphp
                                    <input type="text" class="form-control" name="internal_ref_no" id="internal_ref_no" placeholder="Internal Ref No" readonly value="{{$inRefno}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pannumber">External Ref No*</label>
                                    <input type="text" name="external_ref_no" id="external_ref_no" class="form-control" placeholder="External Ref No" value="{{$extRefno}}">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-6" style="display:none;">
                                    <label for="pannumber">Select Customer*</label>
                                    <select required name="user_id" id="user_id" class="form-control" >
                                        <option value="" selected>Select Customer</option>
                                       
                                        @foreach($users as $user)
                                        @php  $sel =''; @endphp
                                   @if($marketspace->user_id == $user->id) 
                                   @php   $sel = 'selected';@endphp
                                    @endif
                                    <option value="{{ $user->id }}" {{$sel}}>{{ $user->business_name }}</option>
                                 @endforeach  
                                        
                                        </select>
                                </div>
                                <div class="form-group col-md-12">
                                    <label for="pannumber">Select Department*</label>
                                    <select required name="department_id" id="department_id" class="form-control">
                                        <option value="">Select Department</option>
                                        @foreach($categories as $department)
                                   @if(app('request')->input('department_id') == $department->id) 
                                        $sel = 'selected';
                                    @endif
                                    <option value="{{ $department->id }}">{{ $department->name }}</option>
                                 @endforeach    
                                        
                                        </select>
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="pannumber">Select Equipement*</label>
                                    <select required name="equipment_id" id="equipment_id" class="form-control">
                                    <option value="">Select Equipement</option>
                                    @foreach($products as $equipment)
                                   @if(app('request')->input('equipment_id') == $equipment->id) 
                                        $sel = 'selected';
                                    @endif
                                    <option value="{{ $equipment->id }}">{{$equipment->name}}</option>
                                 @endforeach    
                                        </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pannumber">Equipement Serial No</label>
                                    <input type="text" name="equipment_serial_no" id="equipment_serial_no" class="form-control" placeholder="Equipement Serial No">
                                </div>
                            </div>
                           
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="pannumber">Equipment Model No</label>
                                    <input type="text" name="equipment_model_no" id="equipment_model_no" class="form-control" placeholder="Equipment Model No">
                                    <input type="hidden" name="staff_id" id="staff_id" class="form-control" placeholder="staff" value="94">
                                    
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pannumber">Select Equipment Status*</label>
                                    <select required name="equipment_status_id" id="equipment_status_id" class="form-control">
                                        <option value="">Select Status</option>
                                        @foreach($equipment_status as $equipment_stat)
                                   @if(app('request')->input('equipment_status_id') == $equipment_stat->id) 
                                        $sel = 'selected';
                                    @endif
                                    <option value="{{ $equipment_stat->id }}">{{ $equipment_stat->name }}</option>
                                 @endforeach    
                                        </select>
                                </div>
                            </div>
                           
                            <div class="row">                                                                                              
                                <div class="form-group col-md-6">
                                    <label for="pannumber">Installation Date*</label>
                                    <input type="text" name="installation_date" id="installation_date" class="form-control" placeholder="Installation Date">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="pannumber">Warrenty End Date*</label>
                                    <input type="text" name="warrenty_end_date" id="warrenty_end_date" class="form-control" placeholder="Warrenty End Date">
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" name="description" id="description" placeholder="Description"></textarea>
                                </div>
                            </div>
        
                          
                            <div class="card-savebtn">
                                <button type="button" class="btn btn-default" id="ibcreate">Submit</button>
                                <button type="button" class="btn btn-default closeib">Cancel</button>
                                </div>
                               
        
                        </div>
        </form>
                </div>
                @endif
                </div>
                            </div>
        
                        
        
                            
        
                           
                          </div>
                        </div>
                    </div>
                        {{-- ----------------------------- --}}
                    </div>
                    
                </section>
                <div class="right-side-bar">
                    @include('marketspace/right-sidebar')
                </div>
        </div>
    </div>

@endsection

@section('scripts')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script>
     $(".closeib").click(function () {
location.href="{{ route('marketspace-ib') }}";
     });
    $("#ibcreate").click(function () {
 var form = $("#frm_ib");
form.validate({
 rules: {
         
     internal_ref_no: {
        required:true,
     },
     external_ref_no: {
        required:true,
     },
     user_id: {
        required:true,
     },
     department_id: {
        required:true,
     },
     equipment_id: {
        required:true,
     },
     equipment_serial_no: {
        required:true,
     },
     equipment_model_no: {
        required:true,
     },
     equipment_status_id: {
        required:true,
     },
     installation_date: {
        required:true,
     },
     warrenty_end_date: {
        required:true,
     },
     description: {
        required:true,
     },
     
     
     
 },
 messages: {
    internal_ref_no: {
         required:"Field is required!",
     },
     external_ref_no: {
         required:"Field is required!",
     },
     user_id: {
         required:"Field is required!",
     },
     department_id: {
         required:"Field is required!",
     },
     equipment_id: {
         required:"Field is required!",
     },
     equipment_serial_no: {
         required:"Field is required!",
     },
     equipment_model_no: {
         required:"Field is required!",
     },
     equipment_status_id: {
         required:"Field is required!",
     },
     installation_date: {
         required:"Field is required!",
     },
     warrenty_end_date: {
         required:"Field is required!",
     },
     description: {
         required:"Field is required!",
     },

 }
}); 
if(form.valid() === true) {
 
    $("#frm_ib").submit();

}

}); 

</script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#installation_date').datepicker({
                    //dateFormat:'yy-mm-dd',
                    dateFormat:'yy-mm-dd',
                    changeYear: true,
                    yearRange: "1990:2040",
                   // minDate: 0  
                });
        $('#warrenty_end_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat:'yy-mm-dd',
            changeYear: true,
            yearRange: "1990:2040",
            //minDate: 0  
        });
    </script>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
function matchStart(params, data) {
    params.term = params.term || '';
    if (data.text.toUpperCase().indexOf(params.term.toUpperCase()) == 0) {
        return data;
    }
    return false;
}
$(function() {
  //$('#user_id').selectpicker();
  $('#equipment_id').select2({
    matcher: function(params, data) {
        return matchStart(params, data);
    },
});
  
});
$(function() {
  //$('#user_id').selectpicker();
  $('#department_id').select2({
    matcher: function(params, data) {
        return matchStart(params, data);
    },
});
  
});

</script>
@endsection

<style>
.main-footer{display:none;}
</style>