

@extends('staff/layouts.app')

@section('title', 'Manage Customer')

@section('content')

<section class="content-header">
      <h1>
        Manage Customer
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Customer</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">

          <div class="box">

                <div class="row">

        <div class="col-lg-12 margin-tb">


          <!-- <div class="pull-left">

                <a class="btn btn-sm btn-success" href="{{ route('staff.customer.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Customer</a>

            </div>  -->

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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/staff/customer/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  
                  <th>No.</th>
                  <th>Hospital Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Status</th>
                
               
                
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($viewstaff as $values)
                    <?php 
                   //
                     $userall =  DB::select('select * from users  where staff_id="'.$values.'" ');
                   
                    /* print_r($product);
                     echo $product[0]->id;
                   exit;*/
                     
                    ?>
                      @foreach ($userall as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="subcategory" >
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                        <td><a href="{{ route('staff.customer.edit',$product->id) }}"><?php echo $product->business_name ?></a></td>
                         <td><?php echo $product->email ?></td>
                      
                         <td><?php echo $product->phone ?></td>
                        @if($product->status=="Y")
                         <td>Active</td>
                        @endif
                        @if($product->status=="N")
                         <td>InActive</td>
                        @endif
                       
                      </tr>

                     
                      <?php
                     // echo 'select * from contact_person  where user_id="'.$product[0]->id.'" ';
                      $contact_person= DB::select('select * from contact_person  where user_id="'.$product->id.'" ');
                     
                     $j=1;
                     ?>
                     @if(count($contact_person)>0)
                     <thead>
                      <tr style="background-color:#ccc;">
                        <th>No.</th>
                        <th>Contact Person Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                      </tr>
                      </thead>
                     @endif

                      @foreach ($contact_person as $person)
                    <tr id="tr_{{$person->id}}" data-id="{{$person->id}}" data-from ="contact_person" style="background-color:#ccc;">
                        <td>
                            <span class="slNo">{{$j}} </span>
                        </td>
                        <td><a href="{{ route('staff.customer.show',$product->id) }}?id={{$person->id}}"><?php echo $person->name ?></a></td>
                         <td><?php echo $person->email ?></td>
                      
                         <td><?php echo $person->phone ?></td>
                         @if($person->status=="Y")
                         <td>Active</td>
                        @endif
                        @if($person->status=="N")
                         <td>InActive</td>
                        @endif
                     
                       
                      </tr>
                      <?php $j++;?>
                      @endforeach
                     



                       <?php $i++ ?>
                       @endforeach
                     @endforeach

               

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
