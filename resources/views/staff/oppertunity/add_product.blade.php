@extends('staff/layouts.app')

@section('title', 'Add Product')

@section('content')

    <section class="content-header">
        <h1>
            Add Product
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ url('staff/list_oppertunity') }}">Manage Opportunity</a></li>
            <li class="active">Add Product ({{ $oppertunity->oppertunity_name }})</li>
        </ol>
    </section>


    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12 crte-oprty-page">
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
                    <h3><b>{{ $oppertunity->oppertunity_name }}</b></h3>
                    <form role="form" name="frm_company" id="frm_company" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="box-body">

                            <div class="row oprty-crete-row">


                                <input type="hidden" value="{{ $oppertunity->id }}" id="oppertunity_id"
                                    name="oppertunity_id">



                                    <div class="form-group col-md-4">
                                        <label>Brand*</label>
                                        <select name="brand_id" id="brand_id" class="form-control">
                                            <option value="">-- Select Brand --</option>
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    
                                <div class="form-group col-md-4">
                                    <label>Product*</label>
                                    <select name="part_no" id="part_no" class="form-control part_no product_id">
                                        <option value="">-- Select Product --</option>
                                        <?php
                                    foreach($products as $item) {
                                        ?>
                                        <option value="{{ $item->id }}">{{ $item->name }} [{{ $item->part_no }}]
                                        </option>
                                        <?php
                                            } ?>
                                    </select>
                                    <span class="error_message" id="msp_error_message" style="display: none">Please update
                                        msp value of this product</span>
                                    <span class="error_message" id="product_id_message" style="display: none">Field is
                                        required</span>
                                    <!-- <span class="rows_selected" id="select_count" style="display: none"></span><br> -->
                                </div>
                                <input type="hidden" id="quantity" name="quantity" class="form-control"
                                    placeholder="Quantity" value="1">
                                <input type="hidden" id="sale_amount" name="sale_amount" class="form-control"
                                    placeholder="sale amount" value="0">

                                <!-- <div class="form-group col-md-3">
                                      <label>Quantity*</label>
                                    
                                      <span class="error_message" id="quantity_message" style="display: none">Field is required</span>
                                    </div>

                                    <div class="form-group col-md-3">
                                      <label>Sale Amount</label>
                                      
                                      <span class="error_message" id="sale_message" style="display: none">Invalid amount. Please contact admin</span>
                                      <div id="samt"></div>
                                    </div>
                                     -->

                                <div class="form-group col-md-4" id="product_id_show" style="display:none;">
                                    <label>Product</label>
                                    <h3 id="product_id">No products selected </h3>
                                </div>

                                <div class="form-group col-md-4" id="opp" style="display:none;">
                                    <label>Optional Products</label>
                                    <select name="opt_pdt[]" id="opt_pdt" class="form-control" multiple="multiple">
                                        <option value="">-- Select Product --</option>

                                    </select>
                                </div>
                            </div>
                            <div class="box-footer col-md-12 pd-lr-none">
                                <input type="hidden" name="count_product" id="count_product" value="0">
                                <input type="hidden" name="op_id" id="op_id" value="0">
                                <br>
                                <span class="error_message" id="alreadytexit" style="display: none">Product already
                                    exit</span>
                                <button type="button" class="mdm-btn submit-btn  " id="add_opper_product"
                                    onclick="add_product()">Add</button>

                            </div>

                        </div>

                        <!-- /.box-body -->




                        <table id="cmsTable" class="table table-bordered table-striped data-" style="display:none;">
                            <thead>
                                <tr>

                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Quantity</th>
                                    <th>Sale Amount</th>
                                    <th>GST %</th>
                                    <th>Net Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="tabledata">

                                <tr data-from ="staffquote">

                                    <td colspan="4" class="noresult">No result</td>
                                </tr>
                        </table>

                        <div class="box-footer">


                            <button type="submit" id="save_btn" style="display:none;"
                                class="mdm-btn submit-btn  ">Save</button>

                        </div>


                    </form>

                </div>

            </div>
        </div>
    </section>



@endsection

@section('scripts')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

    <script type="text/javascript">
        $(document).ready(function() {
            $('#opt_pdt').multiselect();
            $('#cmsTable').hide();

            $("#save_btn").click(function() {
                var product_id = $("#part_no").val();
                var op_id = $("#op_id").val();
                //  alert(op_id)
                /*
                  var url = APP_URL+'/admin/get_product_exit_oppertunity';
                  $.ajax({
                              type: "POST",
                              cache: false,
                              url: url,
                              data:{
                                product_id: pid,
                              },
                              success: function (data)
                              {  

                              }
                  });*/
            });



        });
    </script>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

    <script>
        $('.product_id').select2({
            placeholder: 'Select a product'
        });

        $('.quantity').select2();
    </script>

    <script type="text/javascript">
        var samt;
        $(function() {

            var url = APP_URL + '/staff/get_product_all_details';

            $(".part_no").change(function() {
                var pid = $("#part_no").val();
                $('#opt_pdt').find('option').remove();
                $("#opt_pdt").multiselect('rebuild');
                $("#add_opper_product").attr('disabled', 'disabled');
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: url,
                    data: {
                        product_id: pid,
                    },
                    success: function(data) {
                        var res = data.split("*1*");
                        var proObj = JSON.parse(res[0]);

                        $('#product_id').text(proObj[0]['name']);

                        var proObj_msp = JSON.parse(res[1]);
                        if (proObj_msp.length > 0) {
                            $("#msp_error_message").hide();
                            $("#add_opper_product").removeAttr('disabled');
                            var htmlscon = '';
                            for (var i = 0; i < proObj.length; i++) {
                                var amount = proObj[i]["unit_price"];
                                htmlscon =
                                    '<input type="hidden" id = "min_sales_amt" name="min_sales_amt" value="' +
                                    proObj[i]["min_sale_amount"] +
                                    '"><input type="hidden" name="max_sales_amt" id="max_sales_amt" value="' +
                                    proObj[i]["max_sale_amount"] + '"></tr>';
                            }
                            if (amount == '') {
                                amount = 0;
                            }
                            if (opt_pdt != '') {

                                $('#opt_pdt').find('option').not(':first').remove();

                                // AJAX request 
                                $.ajax({
                                    url: APP_URL + '/staff/loadproductnames/' + pid,
                                    type: 'get',
                                    dataType: 'json',
                                    success: function(response) {

                                        var len = 0;
                                        if (response['data'] != null) {
                                            len = response['data'].length;
                                        }

                                        var option = '';
                                        if (len > 0) {

                                            $('#opp').show();
                                            // Read data and create <option >
                                            for (var i = 0; i < len; i++) {

                                                var id = response['data'][i].id;
                                                var name = response['data'][i].name;

                                                option += "<option value='" + id +
                                                    "'>" + name + "</option>";

                                            }
                                            $("#opt_pdt").html(option);
                                            $("#opt_pdt").multiselect('rebuild');
                                        } else {
                                            $('#opp').hide();
                                        }

                                    }
                                });

                            }
                            /* $("#select_count").show();
                             $("#select_count").html(" Amount : "+amount);*/
                            $("#samt").html(htmlscon);
                            $("#sale_amount").val(amount);
                            //alert(amount);
                        } else {
                            $("#msp_error_message").show();
                            $("#add_opper_product").attr('disabled', 'disabled');
                        }

                    }
                });
            });

        });
    </script>


    <script type="text/javascript">
        var arr_product = [];
        var prd_array = [];
        var old_product = [];
        var opt_product = '';
        var main_product = '';


        function add_product() {

            var APP_URL = {!! json_encode(url('/')) !!};
            var url = APP_URL + '/staff/get_multiple_product_all_details';
            $('#cmsTable').show();
            $('#contractProduct').hide();
            var product_id = parseInt($("#part_no").val());
            var brand_id = parseInt($("#brand_id").val());
            var quantity = parseInt($("#quantity").val());
            var sale_amount = parseInt($('#sale_amount').val());
            var min_sale = parseInt($('#min_sales_amt').val());
            var max_sale = parseInt($('#max_sales_amt').val());
            var op_pdts = $('#opt_pdt').val();
            var flag = 1;

            if (product_id == "") {
                $("#product_id_message").show();
            } else {
                $("#product_id_message").hide();
            }

            if (quantity == "") {
                $("#quantity_message").show();
            } else {
                $("#quantity_message").hide();
            }

            if (sale_amount == 0) {
                flag = 1;
                $("#sale_message").hide();
            } else if (max_sale != 0 && sale_amount > max_sale) //
            {
                //alert("123");
                flag = 0;
                $("#sale_message").show();

            } else if (min_sale != 0 && sale_amount < min_sale) //
            {
                //alert("456");
                flag = 0;
                $("#sale_message").show();
            } else {
                flag = 1;
                $("#sale_message").hide();
            }


            var count_product = $("#count_product").val();
            if (product_id != '') {

                exit_product = findValueInArray(product_id, arr_product);
                // alert(exit_product+'--'+product_id+','+ arr_product)
                if (exit_product == "1") {
                    $("#alreadytexit").show();

                } else {
                    $("#alreadytexit").hide();
                }
            }
            if (op_pdts != '') {
                for (var i = 0; i < op_pdts.length; i++) {
                    exit_product = findValueInArray(op_pdts[i], arr_product);

                    if (exit_product == "1") {
                        $("#alreadytexit").show();
                        return false;
                    } else {
                        $("#alreadytexit").hide();
                    }
                }

            }
            if (product_id != '' && quantity != '' && exit_product != 1 && flag == 1) {
                $("#user_id").attr('disabled', true);
                arr_product.push(product_id);
                //console.log(arr_product);
                if (op_pdts != '') {
                    arr_product = arr_product.concat(op_pdts)
                }

                var add_counts = parseInt(count_product) + 1;
                if (op_pdts != '') {
                    add_counts = parseInt(add_counts) + op_pdts.length;
                }
                $("#count_product").val(add_counts);

                var prd_array = [];

                prd_array.push(product_id);
                if (op_pdts != '') {
                    prd_array = prd_array.concat(op_pdts)
                }

                //  var newArray=$.merge($(prd_array).not(old_product).get(),$(old_product).not(prd_array).get());
                //  console.log(newArray+'----------');

                var count = 0;
                for (var k = 0; k < old_product.length; ++k) {
                    if (old_product[k] == product_id)
                        count++;
                }
                if (count > 0) {
                    for (var i = 0; i < prd_array.length; i++) {
                        if (prd_array[i] === product_id) {
                            prd_array.splice(i, 1);
                            i--;
                        }
                    }

                }


                old_product = old_product.concat(prd_array);;
                var amt = '';
                var ajaxarray = '';

                console.log(prd_array + '----------');
                console.log(old_product + '****');

                var diff = $(old_product).not(prd_array).get();


                /*
                if(diff.length!=0)
                {
                  ajaxarray=diff;
                }
                else{
                  ajaxarray=prd_array;
                }*/

                console.log(diff + 'diff');

                //console.log(old_product+'**');
                $.ajax({
                    type: "POST",
                    cache: false,
                    url: url,
                    data: {
                        product_id: prd_array,
                    },
                    success: function(data) {
                        $(".noresult").hide();
                        $("#preview_btn").show();
                        $("#save_btn").show();

                        // var res = data.split("1*1");
                        var proObj = JSON.parse(data);
                        // var proObj_msp = JSON.parse(res[1]);
                        if (proObj.length > 0) {
                            sale_amount = proObj[0]["pro_quote_price"];
                            $("#save_btn").show();
                        } else {
                            alert("Please update msp value of this product");
                            $("#save_btn").hide();
                            return false;
                        }


                        htmls = '';

                        for (var i = 0; i < proObj.length; i++) {




                            if (proObj[i]["image_name"] == null || proObj[i]["image_name"] == '') {

                                var imgs = "{{ asset('images/') }}/no-image.jpg";

                            } else {

                                var imgs = "{{ asset('storage/app/public/products/') }}/" + proObj[i][
                                    "image_name"
                                ];
                            }

                            var op_pdts_items = $('#opt_pdt').val();
                            console.log(op_pdts_items)
                            opt_product = 0;
                            if (proObj[i]["product_id"] == $("#part_no").val()) {
                                opt_product = 0;

                            } else {
                                opt_product = 1;

                            }



                            main_product = $("#part_no").val();




                            if (i != 0) {
                                quantity = 1;
                                // opt_product = 1;


                                sale_amount = proObj[i]["pro_quote_price"];


                                if (sale_amount == "") {
                                    sale_amount = 0;
                                }
                            } else {
                                var company = proObj[i]["company_id"];
                                // opt_product = 0;
                                //main_product = proObj[i]["id"];
                            }

                            sale_amount = proObj[i]["pro_quote_price"];

                            tax_per = parseFloat(proObj[i]["tax_per"]);


                            amt = quantity * sale_amount;

                            amt = Math.round((amt + ((amt * tax_per) / 100)) * 100) / 100;

                            //pdfsec='<input type="hidden" name="product_id[]" value="'+proObj[i]["id"]+'">';

                            htmlscontent = `
                            <tr class="tr_${proObj[i]["id"]}">
                              <td><img width="100px" height="100px" src="${imgs}"/></td>
                              <td>${proObj[i]["name"]}</td>
                              <td>
                                <input type="text" value="${quantity}" id="qn_${proObj[i]["id"]}" class="quantity" oninput="change_qty(this.value,${proObj[i]["id"]})" data-id="${proObj[i]["id"]}">
                              </td>
                              <td>
                                <input type="text" value="${ sale_amount }" id="sa_${proObj[i]["id"]}" oninput="change_sale_amt(this.value,${proObj[i]["id"] })" class="sale_amt" data-id="${ proObj[i]["id"] }">
                                <div style="display:none;" class="error_message error_sale_${ proObj[i]["id"] }"></div>
                              </td>
                            




                              <td>
    <input type="text" value="${proObj[i]["tax_per"]}" 
           id="tax_${proObj[i]["id"]}" 
           class="tax" 
           oninput="change_gst(this.value, ${proObj[i]["id"]})" 
           data-id="${proObj[i]["id"]}">
</td>




                              <td>
                                <input type="text" value="${ amt }" id="am_${ proObj[i]["id"] }" class="amt" data-id="${ proObj[i]["id"] }" readonly>
                              </td>
                              <td> 
                                <a class="delete-btn " onclick="deletepro(${ proObj[i]["id"] })" data-id="${ proObj[i]["id"] }"  title="Delete">
                                  <img src="{{ asset('images/delete.svg') }}" alt="" />
                                </a>
                              </td>
                              <input type="hidden" name="product_id[]" value="${proObj[i]["id"]}">
                              <input type="hidden" name="quantity[]" value="${ quantity }" class="hqn_${proObj[i]["id"] }">
                              <input type="hidden" name="amount[]" value="${ amt}" class="hamt_${proObj[i]["id"]}">
                              <input type="hidden" name="sale_amount[]" value="${ sale_amount }" class="hsa_${  proObj[i]["id"] }">
                              <input type="hidden" name="tax_per[]" value="${proObj[i]["tax_per"]}" class="htax_${proObj[i]["id"]}">
                              <input type="hidden" name="company[]" value="${ company }">
                              <input type="hidden" name="optional[]" value="${ opt_product }">
                              <input type="hidden" name="main_pdt[]" value="${ main_product }">
                            </tr>`;


                            $("#tabledata").append(htmlscontent);
                            //$("#pdfsec").append(pdfsec);

                        }
                    }
                });



            }

        }


        $(document).ready(function () {
    $("#brand_id").change(function () {
        var brandId = $(this).val();
        $("#part_no").html('<option value="">-- Select Product --</option>');

        if (brandId) {
            $.ajax({
                url: APP_URL + "/staff/getProductsByBrand?brand_id=" + brandId,
                type: "GET",
                success: function (response) {
                    var products = response.products;
                    if (products.length > 0) {
                        let options = products.map(product => 
                            `<option value="${product.id}">${product.name} [${product.part_no}]</option>`
                        ).join('');
                        $("#part_no").append(options);
                    }
                }
            });
        }
    });
});


        function findValueInArray(value, arr) {

            for (var i = 0; i < arr.length; i++) {
                var name = arr[i];
                if (name == value) {
                    var result = '1';
                    break;
                } else {
                    var result = '0';
                }
            }

            if (arr.length) {
                var result = '0';
                //  $("#select_count").html('');
                return result;
            } else {
                return result;
            }


        }

        function deletepro(product_id) {
            $("#part_no").val('');
            $("#select2-product_id-container").html('-- Select Product --');

            //$("#product_id").multiselect('clearSelection');
            console.log(arr_product + '--' + old_product)
            // $("#select_count").html('');
            for (var i = 0; i < old_product.length; i++) {
                if (old_product[i] === product_id) {
                    old_product.splice(i, 1);
                    i--;
                }
            }

            var count_product = $("#count_product").val();

            var add_counts = parseInt(count_product) - 1;
            $("#count_product").val(add_counts);
            $(".tr_" + product_id).remove();
            //alert(arr_product.length);
            for (var i = 0; i <= arr_product.length; i++) {

                if (arr_product[i] == product_id) {
                    // alert(arr_product[i]);
                    arr_product.splice(i, 1);
                }
            }
            if (add_counts == "0") {
                $("#preview_btn").hide();
                $("#save_btn").hide();
                $(".noresult").show();
                $("#user_id").val('');
                $("#part_no").val('');
                $("#user_id").attr('disabled', false);
            }
        }



        function change_qty(qty, product_id) {
            var sale_amount = $("#sa_" + product_id).val();
            var tax_per = $("#tax_" + product_id).val();
            var amt = qty * sale_amount;
            amt = Math.round((amt + ((amt * tax_per) / 100)) * 100) / 100;

            $("#am_" + product_id).val(amt); // Update amount
            $(".hqn_" + product_id).val(qty); // Update hidden quantity
            $(".hsa_" + product_id).val(sale_amount); // Update hidden sale amount
            $(".htax_" + product_id).val(tax_per);


            // var quantity = qty;
            // var product_id = product_id;

            // alert(quantity+'--'+product_id);
            /*
              var url = APP_URL + '/staff/get_product_all_details';
              $.ajax({
                  type: "POST",
                  cache: false,
                  url: url,
                  data: {
                      product_id: product_id,
                  },
                  success: function(data) {

                      var res = data.split("*1*");
                      var proObj = JSON.parse(res[0]);
                      var proObj_msp = JSON.parse(res[1]);

                      for (var i = 0; i < proObj.length; i++) {
                          var sale_amount = $("#sa_" + product_id).val();
                          var amt = quantity * sale_amount;
                          // $("#sa_"+product_id).val(amt);
                          $("#am_" + product_id).val(amt);
                          //   $("#sa_"+product_id).val(amt);
                          $(".hqn_" + product_id).val(qty);
                          //  $(".hamt_"+product_id).val(amt);
                          // $(".hsa_"+product_id).val(amt);
                      }

                  }
              });

              */
        }


        function change_sale_amt(sale_amount, product_id) {

            var qty = $("#qn_" + product_id).val();
    var tax_per = $("#tax_" + product_id).val();
    var amt = qty * sale_amount;
    amt = Math.round((amt + ((amt * tax_per) / 100)) * 100) / 100;

    $("#am_" + product_id).val(amt);              // Update amount
    $(".hqn_" + product_id).val(qty);            // Update hidden quantity
    $(".hsa_" + product_id).val(sale_amount);    // Update hidden sale amount
    $(".htax_" + product_id).val(tax_per);  
            // alert(quantity+'--'+product_id);
            /*
                        var url = APP_URL + '/staff/get_product_all_details';
                        $.ajax({
                            type: "POST",
                            cache: false,
                            url: url,
                            data: {
                                product_id: product_id,
                            },
                            success: function(data) {
                                var res = data.split("*1*");
                                var proObj = JSON.parse(res[0]);
                                var proObj_msp = JSON.parse(res[1]);

                                for (var i = 0; i < proObj.length; i++) {
                                    var amt = quantity * proObj[i]["unit_price"];
                                    var unit_price = proObj[i]["unit_price"];
                                    var max_sale_amount = proObj[i]["max_sale_amount"];
                                    var min_sale_amount = proObj[i]["min_sale_amount"];
                                    //alert(unit_price);

                                    max_sale_amount = parseInt(max_sale_amount);
                                    min_sale_amount = parseInt(min_sale_amount);

                                    unit_price = parseInt(unit_price);
                                    sale_amount = $("#sa_" + product_id).val();
                                    //  alert(sale_amount)
                                    
                                    // $("#sa_"+product_id).val(amt);

                                    var sale_amount = $("#sa_" + product_id).val();
                                    var quantity = $("#qn_" + product_id).val();
                                    var amt = quantity * sale_amount;
                                    // $("#sa_"+product_id).val(amt);
                                    $("#am_" + product_id).val(amt);
                                    $(".hsa_" + product_id).val(amt);

                                }

                            }
                        });
            */

        }

        function change_gst(tax_per, product_id) {
    var qty = $("#qn_" + product_id).val();
    var sale_amount = $("#sa_" + product_id).val();
    var amt = qty * sale_amount;
    amt = Math.round((amt + ((amt * tax_per) / 100)) * 100) / 100;

    $("#am_" + product_id).val(amt);              // Update amount
    $(".hqn_" + product_id).val(qty);            // Update hidden quantity
    $(".hsa_" + product_id).val(sale_amount);    // Update hidden sale amount
    $(".htax_" + product_id).val(tax_per);       // Update hidden tax percentage
}

    </script>
@endsection
