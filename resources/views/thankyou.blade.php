@extends('layouts.app')

@section('title', "Thank You")
@section('meta_keywords', "Thank You")
@section('meta_description', "Thank You")

@section('content')


<section class="landbnr-wraper">
    <div class="container">
        <div class="row landbnr-row">
            <div class="col-md-6 landbnr-col1">
                <h1><span class="hd-bdr">Thank You</span></h1>
                <p>Thank you for contacting us. One of our staff will contact you soon!.</p>
                <a class="get-btn" href="{{url('/hero')}}">Back to HeRO <span><img src="images/right-arrow-whie.svg" alt="" ></span></a>
            </div>
            <div class="col-md-6 landbnr-col2">
                <img src="images/Landing Image.png" alt="" >
            </div>
        </div>
        <div class="row pulse-row">
              <div class="col-md-6 pulse-col1 mr-auto">
                <div class="puls-animation">
                  <img src="images/pulse-animation.gif" alt="" >
                </div>
              </div>
            </div>
    </div>
</section>
@endsection