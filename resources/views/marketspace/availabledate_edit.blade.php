@extends('layouts.appmasterspace')
<?php
$title       = 'Available Date ';
$description = 'Available Date';
$keywords    = 'Available Date';
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
                       
                
                        
                @if (session('success'))
               <div class="alert alert-success alert-block fade in alert-dismissible show">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
					
                    
                    <h2>Available Date</h2>
                    <div class="add-skill">
                      <a  class="close_exp" href="{{ route('marketspace/availabledate') }}" >Back to listing</a>
                    </div>

                    </div>
                <form role="form" name="frm_avail_date" id="frm_avail_date" method="post" action="{{route('available_date_update')}}" enctype="multipart/form-data" >
               @csrf
					<div class="form">
						<div class="row">
							<div class="form-group col-md-6">
									<label class="control-label">Select Date</label>
									<input required type="text" name="start_date" id="start_date" class="form-control" value="{{$data->start_date}}">
                                    <input required type="hidden" name="id" id="id" class="form-control" value="{{$data->id}}">
							</div>
						</div>
                    </div>
					<div class="card-savebtn">
						<button type="button" class="btn btn-default" id="create-date">Submit</button>
					</div>

    </form>
					<div class="date-table outer-table">
					
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
<script>
   
    $("#create-date").click(function () {
 var form = $("#frm_avail_date");
form.validate({
 rules: {
         
    start_date: {
        required:true,
     },
     start_time: {
        required:true,
     },
     end_time: {
        required:true,
     },
     avail_hour: {
        required:true,
     },
     
 },
 messages: {
    start_date: {
         required:"Field is required!",
     },
     start_time: {
         required:"Field is required!",
     },
     end_time: {
         required:"Field is required!",
     },
     avail_hour: {
         required:"Field is required!",
     },
     

 }
}); 
if(form.valid() === true) {
 
    $("#frm_avail_date").submit();

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

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#start_date').datepicker({
                    //dateFormat:'yy-mm-dd',
                    dateFormat:'yy-mm-dd',
                    changeYear: true,
                    yearRange: "1990:2040",
                   // minDate: 0  
                });
      
    </script>
@endsection
<style>
    .main-footer{display:none;}
    </style>
