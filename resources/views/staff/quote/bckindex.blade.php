
@extends('staff/layouts.app')

@section('title', 'Manage Quote')

@section('content')

<section class="content-header">
      <h1>
        Manage Quote
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Quote</li>
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

                <a class="btn btn-sm btn-success" href="{{ route('staff.quote.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Quote</a>

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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/staffquote/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <!-- <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th> -->
                  <th>No.</th>
                  <th>Quote Id</th>
                  <th>Customer</th>
                  <th>Company</th>
                 
                  <th>Date</th>
                  <!-- <th>Thumbnail</th> -->
                
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($quote as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="staffquote">
                        <!-- <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td> -->
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td><?php echo $product->quote_no ?></td>

                         <td><?php
                        $user = App\User::find($product->user_id);
                        echo empty($user)||empty($user->name)?"-": $user->name ?></td>
                         <td><?php
                        $company = App\Company::find($product->company_id);
                        echo empty($company)||empty($company->name)?"-": $company->name ?></td>
                       
                        <td>{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td>
                       
                        <td class="alignCenter">
                        <a class="btn btn-sm btn-success" target="_blank" href="{{ route('staff.quotepdf',$product->id) }}"> <span class="glyphicon "></span>Preview</a>
                        @if($product->status=="request")
                        <a class="btn btn-sm btn-warning" href="{{ route('staff.sendquote',$product->id) }}"> <span class="glyphicon "></span>Send</a>
                        @endif
                        @if($product->status=="recive")
                        <a class="btn btn-sm btn-warning" disabled> <span class="glyphicon "></span>Already Send</a>
                        @endif
                        </td>


                        <!-- <td class="alignCenter">
                            <a class="btn btn-primary btn-xs" href="{{ route('staff.quote.edit',$product->id) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                            <a class="btn btn-danger btn-xs deleteItem" href="{{ route('staff.quote.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                        </td> -->
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($quote) > 0) { ?>
              <!-- <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('quote');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a>
              </div> -->
               <?php } ?>

              </table>

              
            </form>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>


    <?php /*<div class="container">
    @foreach ($users as $user)
        {{ $user->name }}
    @endforeach
    </div>
    {{ $users->links() }}
    */ ?>

@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function() {
        var oTable = $('#cmsTable').DataTable({
        });



    });

</script>
@endsection
