

@extends('staff/layouts.app')

@section('title', 'Manage Subcategory')

@section('content')

<section class="content-header">
      <h1>
        Manage Subcategory
      </h1>
      <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Subcategory</li>
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

                <a class="add-button" href="{{ route('subcategory.create') }}"> Add Subcategory</a>

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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/subcategory/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th width="80">No.</th>
                  <th>Name</th>
                  <th>Category</th>
                  <th>Date</th>
                  <!-- <th>Thumbnail</th> -->
                  <th>Status</th>
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($subcategories as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="subcategory">
                        <td data-th="" ><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td>
                        <td data-th="No." >
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td data-th="Name" ><?php echo $product->name ?></td>
                         <td data-th="Category" ><?php
                        $cat = App\Models\Category::find($product->categories_id);
                        if($cat){
                            echo $cat->name;
                        }
                        ?></td>
                      
                        <td data-th="Date" >{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td>
                        <!-- <td class="alignCenter">

                        <a class="btn btn-primary btn-xs" style="cursor:pointer;" onclick="show_crop('<?php echo $product->id ?>');">
                            <span class="glyphicon glyphicon-picture"></span>
                                 </a>

                         </td>
 -->
                        <td data-th="Status" >
                            <a class="tick-icon btn <?php echo ($product->status== 'Y') ? 'btn-success': 'btn-danger' ?>  btn-xs statusItem" id="statusItem{{$product->id}}" data-id="{{$product->id}}" data-from ="subcategories" title="<?php echo ($product->status == 'Y') ? 'Active': 'Inactive' ?>">
                                <span class="glyphicon <?php echo ($product->status== 'Y') ? 'glyphicon-ok': 'glyphicon-ban-circle' ?>"></span></a>
                        </td>

                        <td data-th="Action"  class="alignCenter">
                            <a class="edit-btn" href="{{ route('subcategory.edit',$product->id) }}" title="Edit"><img src="{{ asset('images/edit.svg') }}"></a>
                            <a class="delete-btn deleteItem" href="{{ route('subcategory.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><img src="{{ asset('images/delete.svg') }}"></a>
                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($subcategories) > 0) { ?>
              <div class="deleteAll">
                 <a class="mdm-btn cancel-btn" onClick="deleteAll('subcategory');" id="btn_deleteAll" >
                                <span class="glyphicon glyphicon-trash"></span> Delete All Selected</a>
              </div>
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


@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function() {
        var oTable = $('#cmsTable').DataTable({
        });
    });

 
</script>
@endsection
