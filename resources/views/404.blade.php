@extends('layouts.app')

<?php
$title       = 'Page Not Found'; 
$description = 'Page Not Found'; 
$keywords    = 'Page Not Found'; 
?>


@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')

<section class="inner-cnt-wrapper prdt-detailpage">
        <div class="container">
            <div class="row">
              
              <div class="col-md-12 text-left main-headname">
                  <h1>Page Not Found</h1>
                  
              </div>
               </div>
               <div class="row">
              <div class="col-md-12 text-left sub-headname detail-cntent">

                 <img src="{{ asset('images/warning.jpg') }}" />
                  <p>Sorry, the page you are looking for could not be found..</p>
                  
              </div>
                
            </div>
            
        </div>
</section> 

@endsection
