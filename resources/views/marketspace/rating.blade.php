@extends('layouts.appmasterspace')
<?php
$title       = 'Rating';
$description = 'Rating';
$keywords    = 'Rating'
?>
@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
@section('content')
<link href="{{ asset('css/hirestyle.css') }}" rel="stylesheet">
<section class="login-page signup-page" id="step1" style="margin-top:200px;"> 
<div class="container">
  <div class="feedback-page">
    <div class="feedback-box">
      
      @if(session()->has('success'))
                            <div class="alert alert-success">
                                {{ session()->get('error_message') }}
                            </div>
                        @endif
  
    
@if(count($rating_exit)>0)
<div class="alert alert-success">
       You have successfully added review
    </div>
@endif
@if(count($rating_exit)==0)

<div class="top-label">Please leave your feedback and rating for this projects</div>

      <form method="POST" action="{{route('save_rating')}}" name="registeruser1" id="registeruser1" class="registeruser1" autocomplete="off" enctype="multipart/form-data">
      @csrf


  


      <div class="form-group review-group">
          
          <div class="div review-star">
	  <input type="hidden" id="php1_hidden" value="1">
	  <img src="{{ asset('images/star-blank.svg') }}" onmouseover="change(this.id);" id="php1" class="php">
	  <input type="hidden" id="php2_hidden" value="2">
	  <img src="{{ asset('images/star-blank.svg') }}" onmouseover="change(this.id);" id="php2" class="php">
	  <input type="hidden" id="php3_hidden" value="3">
	  <img src="{{ asset('images/star-blank.svg') }}" onmouseover="change(this.id);" id="php3" class="php">
	  <input type="hidden" id="php4_hidden" value="4">
	  <img src="{{ asset('images/star-blank.svg') }}" onmouseover="change(this.id);" id="php4" class="php">
	  <input type="hidden" id="php5_hidden" value="5">
	  <img src="{{ asset('images/star-blank.svg') }}" onmouseover="change(this.id);" id="php5" class="php">
  </div>
  <label>Clarify in specification</label>

  
  <input type="hidden" name="phprating" id="phprating" value="">
  <input type="hidden" name="post_id" id="post_id" value="{{$id}}">
  <input type="hidden" name="hire_id" id="hire_id" value="{{$hire_id}}">
  <input type="hidden" name="user_id" id="user_id" value="{{$user_id}}">

        </div>

        <div class="form-group">
        <textarea  name="content" id="content" class="form-control" placeholder="How was your experince on this project?" value="{{ old('content') }}"></textarea>
          {{ $errors->first('content') }}
        </div>
        <input type="button" name="" class="btn continue-btn" id="continue_form1" value="Save" >
@endif

      </form>


    </div>
  </div>
</div>
</section>
    @endsection
    @section('scripts')
  <script>
    $( document ).ready(function() {
      $('#continue_form1').click(function() {
            // var validator = $(".registeruser").validate({
                var form = $(".registeruser1");
       form.validate({
                 rules: {
                  phprating: {
                    required:true,
                    },
                  content: {
                        required:true,
                     },
                 },
                 messages: {
                  phprating: {
                  required:"Field is required!",
                  },
                  content: {
                         required:"Field is required!",
                     },
                 }
             }); 
              if(form.valid() === true) {
                $(".registeruser1").submit();
             } else {
                 validator.focusInvalid();
                 return false;
             }
         });
        });
        function change(id)
   {
      var cname=document.getElementById(id).className;
      var ab=document.getElementById(id+"_hidden").value;
      document.getElementById(cname+"rating").innerHTML=ab;
$("#phprating").val(ab)
      for(var i=ab;i>=1;i--)
      {
         document.getElementById(cname+i).src="{{ asset('images/star-fill.svg') }}";
      }
      var id=parseInt(ab)+1;
      for(var j=id;j<=5;j++)
      {
         document.getElementById(cname+j).src="{{ asset('images/star-blank.svg') }}";
      }
   }
    </script>
<style>
img
{
	margin-top:10px;
	width:50px;
	height:50px;
	float:left;
}
</style>
    @endsection