@extends('dealer/layouts.app')



@section('title', 'Dashboard')



@section('content')

     

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

        Dashboard

      </h1>

      <ol class="breadcrumb">

        <li><a href="<?php echo URL::to('dealer'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Dashboard</li>

      </ol>

    </section>



    <!-- Main content -->

    <section class="content">

      <!-- Info boxes -->

      <div class="row">

        

 



        <div class="col-md-3 col-sm-6 col-xs-12">

          <div class="info-box">

              <a href="{{ route('dealer.products.index') }}">

            <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>



            <div class="info-box-content">

              <span class="info-box-text">Products</span>

              <span class="info-box-number"><?php //echo App\Product::all()->count();?></span>

            </div>

          </a>

            <!-- /.info-box-content -->

          </div>

          <!-- /.info-box -->

        </div>













      </div>

      <!-- /.row -->



 

      <!-- /.row -->

    </section>

    <!-- /.content -->

@endsection



