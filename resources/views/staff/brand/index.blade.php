

@extends('staff/layouts.app')

@section('title', 'Manage Brand')

@section('content')

<section class="content-header">
      <h1>
        Manage Brand
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Brand</li>
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

                <a class="btn btn-sm btn-success" href="{{ route('staff.brand.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Brand</a>

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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/brand/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th>No.</th>
                  <th>Name</th>
                  <th>Image</th>
                  <th>Date</th>
                  <!-- <th>Thumbnail</th> -->
                  <th>Status</th>
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($categories as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="brand">
                        <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td>
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td><?php echo $product->name ?></td>
                        <td>
                          @if($product->image_name!='')
                            <a rel="example1" class="example1"  href="<?php echo asset("storage/app/public/brand/$product->image_name") ?>" ><img src="<?php echo asset("storage/app/public/brand/thumbnail/$product->image_name") ?>" /></a>
                            @endif
                        </td>
                        <td>{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td>
                        <!-- <td class="alignCenter">

                        <a class="btn btn-primary btn-xs" style="cursor:pointer;" onclick="show_crop('<?php echo $product->id ?>');">
                            <span class="glyphicon glyphicon-picture"></span>
                                 </a>

                         </td>
 -->
                        <td class="alignCenter">
                            <a class="btn <?php echo ($product->status== 'Y') ? 'btn-success': 'btn-danger' ?>  btn-xs statusItem" id="statusItem{{$product->id}}" data-id="{{$product->id}}" data-from ="brand" title="<?php echo ($product->status == 'Y') ? 'Active': 'Inactive' ?>">
                                <span class="glyphicon <?php echo ($product->status== 'Y') ? 'glyphicon-ok': 'glyphicon-ban-circle' ?>"></span></a>
                        </td>

                        <td class="alignCenter">
                            <a class="btn btn-primary btn-xs" href="{{ route('staff.brand.edit',$product->id) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                            

                            <?php
                             $cat_exit = DB::select('select count(*) as count from products where `brand_id`="'.$product->id.'" ');   
                             if($cat_exit[0]->count==0)
                              {
                            ?>
                            <a class="btn btn-danger btn-xs deleteItem" href="{{ route('admin.brand.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                            <?php
                              }
                            ?>
                            
                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($categories) > 0) { ?>
              <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('brand');" id="btn_deleteAll" >
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
      
        var oTable = $('#cmsTable').DataTable(
          
        {
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


        }
      
      );


        // Add event listener for opening and closing details
       // $('#cmsTable tbody').on('click', 'td.details-control', function () {
        $('.openTable').on('click',  function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );

            var id = $(tr).attr('data-id');
            var from = $(tr).attr('data-from');

            if ( row.child.isShown() ) {
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                var resp = getRowDetails(id, from, row,tr);
            }
        });

    });

    function show_crop(id) {
        var url = APP_URL+'/staff/popup_imageCrop';

        var thumb_width     = '350';
        var thumb_height    = '230';

       $.ajax({
        type: "POST",
        cache: false,
        //url: "popup_imageCrop.php?from=other&folder=brand&id="+portfolio_id+"&thumb_width="+thumb_width+"&thumb_height="+thumb_height+'&ra//d1='+type,
        url: url,
        data:{
            from: 'other',
            folder: 'brand',
            id: id,
            thumb_width: thumb_width,
            thumb_height: thumb_height
        },
        success: function (data)
        {
            $.fancybox.hideLoading();
            $.fancybox({
                content:data,
                afterLoad:function() {
                },
                beforeClose:function() {
                }
            });
        }
    });
    }
</script>
@endsection
