

@extends('staff/layouts.app')

@section('title', 'Manage Customer')

@section('content')

@php
  
  $staff_id =session('STAFF_ID');

@endphp
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
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th>No.</th>
                  <th>Hospital Name</th>
                  <th>Head of the Institution</th>
                  <th>Customer Category</th>
                  
                  <th>Email</th>
                  <th>Phone</th>
               
                  <th>Address</th>  
                  <th>Taluk</th>
                  <th>Pincode</th>
             <!--     <th>Added By Staff</th>  -->
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($user as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="subcategory">
                        <td  data-th="Action"><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td>
                        <td  data-th="No.">
                            <span class="slNo">{{$i}} </span>
                        </td>
                        @if($staff_id ==32)

                        <td data-th="Hospital Name"><a href="{{ route('staff.customer.edit',$product->id) }}"><?php echo $product->business_name ?></a></td>

                        @elseif($product->staff_id == $staff_id)

                        <td data-th="Hospital Name"><a href="{{ route('staff.customer.edit',$product->id) }}"><?php echo $product->business_name ?></a></td>
                        
                        @else
                        
                        <td data-th="Hospital Name"><?php echo $product->business_name ?></td>

                        @endif

                        

<td data-th="Head of the Institution"><?php echo $product->name ?></td>
<td data-th="Customer Category"><?php
if( $product->customer_category_id>0){
    $cus_cat = App\Customercategory::find($product->customer_category_id);
echo $cus_cat->name;
}
?></td>
<td data-th="Email"><?php echo $product->email ?></td>


<td data-th="Phone"><?php echo $product->phone ?></td>
<td data-th="Address"><?php echo $product->address1 ?></td>


<td data-th="Taluk"><?php
if( $product->taluk_id>0){
    $taluk = App\Taluk::find($product->taluk_id);
echo $taluk->name;
}
?></td>
<td data-th="Pincode"><?php echo $product->zip ?></td>
<!-- <td data-th="Added By Staff"><?php echo $product->staff_email ?></td> -->
                        
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($user) > 0) { ?>
              <!-- <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('user');" id="btn_deleteAll" >
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


@endsection

@section('scripts')
<script type="text/javascript">
    jQuery(document).ready(function() {
        var oTable = $('#cmsTable').DataTable({
        });
    });

 
</script>
@endsection
