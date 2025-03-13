

@extends('staff/layouts.app')

@section('title', 'Manage Checklist')

@section('content')

<section class="content-header">
      <h1>
        Manage Checklist
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Checklist</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

       <!-- left column -->
       <div class="col-md-3 leftside-menu">
            <div class="panel_s mbot5">
               <div class="panel-body padding-10">
                  <h4 class="bold">
                     Options
                    
                     </h4>
               </div>
            </div>
          
        @include('staff/layouts/options')
         </div>

        <div class="col-xs-9">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">


            <div class="pull-left">

                <a class="btn btn-sm btn-success" href="{{ route('checklist.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Checklist</a>

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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/admin/checklist/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Related Category</th>
                  <th>Related SubCategory</th>
                  
                 
                  <th>Date</th>
                  <!-- <th>Thumbnail</th> -->
                  <th>Status</th>
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($checklist as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="checklist">
                        <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td>
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td><?php echo $product->name ?></td>

                         <td><?php
                        $related_category = App\Models\Relatedto_category::find($product->related_category_id);
                        echo $related_category->name ?></td>
                      

                        <td><?php
                        $related_subcategory = App\Models\Relatedto_subcategory::find($product->related_subcategory_id);
                        echo $related_subcategory->name ?></td>
                      
                        <td>{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td>
                 

                        <td class="alignCenter">
                            <a class="btn <?php echo ($product->status== 'Y') ? 'btn-success': 'btn-danger' ?>  btn-xs statusItem" id="statusItem{{$product->id}}" data-id="{{$product->id}}" data-from ="relatedto_category" title="<?php echo ($product->status == 'Y') ? 'Active': 'Inactive' ?>">
                                <span class="glyphicon <?php echo ($product->status== 'Y') ? 'glyphicon-ok': 'glyphicon-ban-circle' ?>"></span></a>
                        </td>

                        <td class="alignCenter">
                            <a class="btn btn-primary btn-xs" href="{{ route('checklist.edit',$product->id) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                           
                           <a class="btn btn-danger btn-xs deleteItem" href="{{ route('checklist.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                          
                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($checklist) > 0) { ?>
              <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('checklist');" id="btn_deleteAll" >
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

          initComplete: function(settings) {

          var info = this.api().page.info();
          var api = this.api();

          if (info.pages > 1) {

              $(".dataTables_paginate").show();
          } else {
              $(".dataTables_paginate").hide();

          }

          var searchInput = $('<input type="number" min="1" step="1" class="page-search-input" placeholder="Search pages...">');
          $(".col-sm-7").append(searchInput);

          if (info.pages > 1) {

              searchInput.on('input', function() {

                  var searchValue = $(this).val().toLowerCase();

                  var pageNum = searchValue - 1;

                  api.page(pageNum).draw('page');
              });
          }


          if (info.recordsTotal == 0) {

              $(".dataTables_info").hide();
          } else {
              $(".dataTables_info").show();
          }
          },

          createdRow: function(row, data, dataIndex) {

          // $(row).find('td').each(function(i, e) {

          //     $(e).attr('data-th', theader[i]);
              
          // });
          },
          drawCallback: function() {

          },

        });



    });

</script>
@endsection
