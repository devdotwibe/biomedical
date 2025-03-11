

@extends('staff/layouts.app')

@section('title', 'Manage Staff Sales')

@section('content')


<section class="content-header">
    <h1>
        Staff Sales
    </h1>
    <ol class="breadcrumb">
      <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{route('staff.subcategory.index')}}">Manage Staff Sales</a></li>
      <li class="active">Staff Sales</li>
    </ol>
  </section>

<section class="content">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="box box-primary">

          <!-- /.box-header -->
          <!-- form start -->

          @if(session()->has('message'))
              <div class="alert alert-success">
                  {{ session()->get('message') }}
              </div>
          @endif


          @if(session()->has('error_message'))
              <div class="alert alert-danger alert-dismissible">
                  {{ session()->get('error_message') }}
              </div>
          @endif


          <form role="form" name="frm_subcategory" id="frm_subcategory" action="#"  >
             
              <div class="box-body">
                <div class="row">
                    <div class="form-group  col-md-4 col-sm-6 col-lg-4">
                        <label>Engineer Name</label>
                        <input type="text" class="form-control" value="{{isset($data->staff)?$data->staff->name:$data->engineer_name}}"  readonly>
                    </div>
                    <div class="form-group  col-md-4 col-sm-6 col-lg-4">
                        <label>Date</label>
                        <input type="text" class="form-control" value="{{\Carbon\Carbon::parse($data->created_at)->format("d/m/Y g:i A")}}"  readonly>
                    </div>
                    <div class="form-group  col-md-4 col-sm-6 col-lg-4">
                        <label>Customer Name</label>
                        <input type="text" class="form-control" value="{{isset($data->customer)?$data->customer->business_name:$data->customer_name}}"  readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-4 col-sm-6 col-lg-4">
                        <label>Address</label>
                        <textarea class="form-control" readonly>
                            {{isset($data->customer)?$data->customer->address1:$data->address}}
                        </textarea>
                    </div>
                    <div class="form-group col-md-8 col-sm-6 col-lg-8">
                        <label>Contact Person</label>
                        <ul class="list-group">
                            <?php
                                $contactnames=explode(",",$data->contact_person_name);
                                $contactphone=explode(",",$data->contact_person_phone);
                                $contactdesig=explode(",",$data->contact_person_designation);
                            ?>
                            @for ($i = 0; $i < count($contactnames); $i++)                                
                            <li class="list-group-item">{{$contactnames[$i]}} @isset($contactdesig[$i]) -{{$contactdesig[$i]}} @endisset @isset($contactphone[$i]) -{{$contactphone[$i]}} @endisset</li>
                            @endfor
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group  col-md-4 col-sm-6 col-lg-4">
                        <label>Care Area</label>
                        <input type="text" class="form-control" value="{{isset($data->careArea)?$data->careArea->name:$data->care_area}}"  readonly>
                    </div>
                    <div class="form-group  col-md-4 col-sm-6 col-lg-4">
                        <label >Status</label>
                        <input type="text" class="form-control" value="{{$data->status}}"  readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group  col-md-6">
                        <label >Introduced</label>
                        <ul class="list-group">
                            @foreach ($data->productType as $item)
                                @if ($item->sale_type=="introduced")
                                    <li class="list-group-item">{{$item->name}}</li>
                                @endif                            
                            @endforeach                                
                        </ul>
                    </div>
                    <div class="form-group  col-md-6">
                        <label >Intrested</label>
                        <ul class="list-group">
                            @foreach ($data->productType as $item)
                                @if ($item->sale_type=="intrested")
                                    <li class="list-group-item">{{$item->name}}</li>
                                @endif                            
                            @endforeach                                
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-12">
                        <label>Comment</label>
                        <textarea class="form-control" readonly>
                            {{$data->comment}}
                        </textarea>
                    </div>
                </div>
                
            </div>
          </form>
        </div>

      </div>
    </div>
</section>
@endsection

@section('scripts')
<script type="text/javascript">

</script>
@endsection
