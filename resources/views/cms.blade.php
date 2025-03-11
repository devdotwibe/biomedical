@extends('layouts.app')

<?php
$title       = $cms->name;
$description = ($cms->meta_description != '') ? $cms->meta_description: setting('META_DESCRIPTION');
$keywords    = ($cms->meta_keywords != '') ? $cms->meta_keywords: setting('METAKEYWORDS');
?>


@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

@section('content')

<section class="inner-cnt-wrapper prdt-detailpage">
        <div class="container">
            <div class="row">
              
              <div class="col-md-12 text-left main-headname">
                  <h1>{{ $cms->title }}</h1>
                  
              </div>
               </div>
               <div class="row">
              <div class="col-md-12 text-left sub-headname detail-cntent">
                  <?php echo $cms->description ?>
              </div>
                
            </div>
            
        </div>
</section>   

@endsection
