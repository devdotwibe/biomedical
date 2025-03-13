

@extends('staff/layouts.app')

@section('title', 'Manage Taluk')

@section('content')

<section class="content-header">
      <h1>
        Manage Taluk
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?php echo URL::to('admin'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Taluk</li>
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
                     <!-- <div class="btn-group">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                                                      <li>
                              <a href="#" target="_blank">
                              <i class="fa fa-share-square-o"></i> Login as client                              </a>
                           </li>
                                                                                 <li>
                              <a href="#" class="text-danger delete-text _delete"><i class="fa fa-remove"></i> Delete                               </a>
                           </li>
                                                   </ul>
                     </div> -->
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

                <a class="btn btn-sm btn-success" href="{{ route('taluk.create') }}"> <span class="glyphicon glyphicon-plus"></span>Add Taluk</a>

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
              <form name="dataForm" id="dataForm" method="post" action="{{ url('/admin/taluk/deleteAll') }}" />
              @csrf

              <table id="cmsTable" class="table table-bordered table-striped data-">
                <thead>
                <tr>
                  <th><input type="checkbox" name="select_all" value="1" id="selectAll" class="select-checkbox"></th>
                  <th>No.</th>
                 
                  <th>Country</th>
                  <th>State</th>
                  <th>District</th>
                  <th>Taluk</th>
                  <th>Date</th>
                  <!-- <th>Thumbnail</th> -->
               
                  <th class="alignCenter">Action</th>
                </tr>
                </thead>
                <tbody>
                    <?php $i = 1; ?>
                    @foreach ($company as $product)
                    <tr id="tr_{{$product->id}}" data-id="{{$product->id}}" data-from ="taluk">
                        <td><input type="checkbox" class="dataCheck" name="ids[]" value="{{$product->id}}" id="check{{$product->id}}">
                        </td>
                        <td>
                            <span class="slNo">{{$i}} </span>
                        </td>
                      
                        <td><?php
                        $country = App\Models\Country::find($product->country_id);
                        echo $country->name ?></td>
                        <td><?php
                        $state = App\Models\State::find($product->state_id);
                        echo $state->name ?></td>
                        <td><?php
                        $district = App\Models\District::find($product->district_id);
                        echo $district->name ?></td>
                          <td><?php echo $product->name ?></td>
                       
                        <td>{{ date('d-m-Y h:i A', strtotime($product->created_at)) }}</td>
                     

                        <td class="alignCenter">
                            <a class="btn btn-primary btn-xs" href="{{ route('taluk.edit',$product->id) }}" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                            <?php
                             $taluk_exit = DB::select('select count(*) as count from users where `taluk_id`="'.$product->id.'" ');   
                             if($taluk_exit[0]->count==0)
                              {
                            ?>

                            <a class="btn btn-danger btn-xs deleteItem" href="{{ route('taluk.destroy',$product->id) }}" id="deleteItem{{$product->id}}" data-tr="tr_{{$product->id}}" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
                              <?php } ?>
                        </td>
                      </tr>


                       <?php $i++ ?>
                     @endforeach

                <?php if(count($company) > 0) { ?>
              <div class="deleteAll">
                 <a class="btn btn-danger btn-xs" onClick="deleteAll('customer_category');" id="btn_deleteAll" >
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
        });



    });

</script>
@endsection
