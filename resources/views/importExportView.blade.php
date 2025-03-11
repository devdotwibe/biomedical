

@extends('admin/layouts.app')

@section('title', 'Manage Products')

@section('content')


<section class="content-header">
      <h1>
        Manage Products
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Products</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">

            <div class="pull-left">

                <a class="btn btn-sm btn-success" href="{{ route('admin.products.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Product</a>

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



<div class="container">

    <div class="card bg-light mt-3">

        <div class="card-header">

            

        </div>

        <div class="card-body">

            <form action="{{ route('admin.importproduct') }}" method="POST" enctype="multipart/form-data">

                @csrf

                <input type="file" name="file" class="form-control">

                <br>

                <button class="btn btn-success">Import User Data</button>

                <a class="btn btn-warning" href="{{ route('admin.exportproduct') }}">Export User Data</a>

            </form>

        </div>

    </div>

</div>
              
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>



@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function() {

    });

  
  </script>
@endsection
