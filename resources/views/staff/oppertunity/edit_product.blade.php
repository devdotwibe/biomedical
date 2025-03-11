@extends('staff/layouts.app')

@section('title', 'Edit Product')

@section('content')

    <section class="content-header">
        <h1>
            Edit Product
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('staff/list_oppertunity') }}">Manage Opportunity</a></li>
            <li class="active">Edit Product ({{ $op_name->oppertunity_name }})</li>
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

                    @if (session()->has('success'))
                        <div class="alert alert-success">
                            {{ session()->get('success') }}
                        </div>
                    @endif


                    @if (session()->has('error_message'))
                        <div class="alert alert-danger alert-dismissible">
                            {{ session()->get('error_message') }}
                        </div>
                    @endif

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <h4><b>{{ $op_name->oppertunity_name }}</b></h4>
                    <form role="form" name="frm_company" id="frm_company" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">
                            <div class="row">
                                <div class="form-group  col-md-3 col-sm-6 col-lg-3">
                                    <label>Product*</label>
                                    <select name="product_id" id="product_id" class="form-control" disabled="disabled">
                                        <option value="">-- Select Product --</option>

                                        @foreach ($products as $item)
                                            <option value='{{ $item->id }}'
                                                @if (old('product_id', $pdt->product_id) == $item->id) {{ 'selected' }} @endif>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    <span class="error_message" id="product_id_message" style="display: none">Field is
                                        required</span>
                                </div>
                            </div>
                            <div class="row edit_orty-row">
                                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                    <label>Quantity*</label>

                                    <input type="hidden" id="product_id_hidden" name="product_id" class="form-control"
                                        value="{{ old('product_id', $pdt->product_id) }}">
                                    <input type="text" id="quantity" name="quantity" class="form-control"
                                        value="{{ old('quantity', $pdt->quantity) }}" onchange="change_sale_amount()">
                                    <span class="error_message" id="quantity_message" style="display: none">Field is
                                        required</span>
                                </div>

                                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                    <label> Sale Amount</label>
                                    <input type="text" id="amount" name="amount" class="form-control"
                                        value="{{ old('amount', $pdt->sale_amount) }}" onchange="change_sale_amount()">
                                    <div style="display:none;" class="error_message error_sale"></div>
                                </div>

                                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                    <label>GST %</label>
                                    <input type="text" id="taxpersent" name="tax_percentage" class="form-control"
                                    value="{{old('quantity',$pdt->tax_percentage)}}" onchange="change_sale_amount()">

                                </div>
                                             
                                  
                                <div class="form-group col-md-3 col-sm-6 col-lg-3">
                                    <label> Net Amount</label>
                                    <input type="text" id="netamount" name="netamount" class="form-control"
                                        value="{{ old('netamount', $pdt->amount) }}" readonly>

                                </div>
                            </div>

                            <div class="box-footer">

                                <input type="submit" id="save_btn" class="mdm-btn submit-btn  " value="submit">

                            </div>

                    </form>

                </div>

            </div>
        </div>
    </section>
 


@endsection

@section('scripts')


    <script type="text/javascript">
        function change_sale_amount() {
            var qty = parseFloat($('#quantity').val());
            var amt = parseFloat($('#amount').val());
            var tx = parseFloat($('#taxpersent').val());
            var net = (Math.round(((amt * qty) + ((amt * qty * tx) / 100)) * 100)) / 100



            $('#netamount').val(net)
        }
    </script>

@endsection
