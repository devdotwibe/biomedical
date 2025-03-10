@extends('layouts.app')

<?php
$title = $product->name;
$description = $product->meta_description != '' ? $product->meta_description : setting('META_DESCRIPTION');
$keywords = $product->meta_keywords != '' ? $product->meta_keywords : setting('METAKEYWORDS');
?>


@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)

<link href="{{ asset('css/product-detail.css') }}" rel="stylesheet">
@section('content')


    <section class="inrpage-menu">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Shop Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $category[0]->name }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-md-5 text-right inrpage-search ml-auto">
                    <form class="searchform" name="frm_searchHead" id="frm_searchHead" method="POST"
                        action="{{ route('search') }}" autocomplete="off">
                        @csrf
                        <input type="text" class="form-control" placeholder="Search for products..." name="search_word"
                            id="search_word">
                        <button class="btn btn-secondary" type="submit">Search</button>
                    </form>

                </div>
            </div>
        </div>
    </section>

    <section class="prdtdtl-wrapper">
        <div class="container">
            <div class="row">
                @if (session()->has('message'))
                    <div class="box no-border">
                        <div class="box-tools">
                            <p class="alert alert-success alert-dismissible">
                                {{ session()->get('message') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                            </p>
                        </div>
                    </div>
                @endif
            </div>
            <!-- <div class="row subhead-row">
                <div class="col-md-12">
                  <h2>Shop {{ $product->name }}</h2>
                </div>
            </div> -->

            <div class="row prdt-dtl-row">
                <div class="col-md-12">
                    <div class="prdt-dtl-lft ">
                        <div class="prdt-dtl-lft-all ">
                            <div class="prdt-dtl-img">
                                @if ($product->image_name != '')
                                    <img src="<?php echo asset("public/storage/products/$product->image_name"); ?>" alt="{{ $product->name }}" title="{{ $product->name }}">
                                @endif
                                @if ($product->image_name == '')
                                    <img src="{{ asset('images/no-image.jpg') }}" alt="No Image" title="No Image">
                                @endif
                            </div>
                            @if (count($product_image) > 0)

                                <div class="row prdt-dtl-gallery">

                                    @foreach ($product_image as $values)
                                        <div class="col-md-3 prdt-gry">
                                            <a href="<?php echo asset("public/storage/product_gallery/$values->image_name"); ?>" data-lightbox="gallery"
                                                data-title="{{ $values->name }}">
                                                <img src="<?php echo asset("public/storage/product_gallery/$values->image_name"); ?>" class="img-fluid img-thumbnail"
                                                    alt="{{ $values->name }}">
                                            </a>
                                        </div>
                                    @endforeach

                                </div>
                            @endif

                            <div class="prdt-codtxt">
                                <span class="cod-clr">{{ $product->item_code }}</span>
                                <span class="cod-no"><i class="fa fa-user" aria-hidden="true"></i>

                                    @if ($product->view_count == '')
                                        0
                                    @else
                                        {{ $product->view_count }}
                                    @endif
                                </span>
                            </div>
                        </div>



                        <div class="prdt-dtl-txt">
                            <h2>{{ $product->name }}</h2>


                            <!---<ul>
                            <li>Designed to support patient during lift and transfer procedures</li>
                            <li>Can be used with any floor-style lift</li>
                            <li>Optional chain/strap is not required.</li>
                            <li><a href="#">More.....</a></li>
                          </ul>-->

                            <div class="avial-size"> {!! html_entity_decode($product->description) !!}
                                @if ($product->image_name1 != '')
                            </div>
                            <div class="dwn_bro">
                                <a href="<?php echo asset("public/storage/products/$product->image_name1"); ?>" download="<?php echo asset("public/storage/products/$product->image_name1"); ?>">Download Brochure</a>
                            </div>
                            @endif



                            <div class="prdt-dtl-request">
                                @guest

                                    @if (session('success'))
                                        <span>{{ session('success') }} </span>
                                    @endif
                                    <div class="prdt-dtl-rgt">
                                        <div class="prdt-login text-center">

                                            <a onclick="add_quote()" class="prdt-order-btn"><span> Request Quote</span></a>
                                            <h4>Call</h4>
                                            <div class="request-btns">
                                                <a href="callto:8921065594" class="prdt-dtl-call">8921065594</a>
                                                <a href="callto:9388878001" class="prdt-dtl-call">9388878001</a>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="prdt-dtl-rgt">
                                        <div class="prdt-login text-center">


                                            @if ($product->unit_price == '')
                                                <!--<button class="btn btn-warning request_quote quotebtn"  data-id="{{ $product->id }}" type="button" >Request Quote</button>-->
                                                @php
                                                    $cart = App\Cart::where([
                                                        ['product_id', $product->id],
                                                        ['status', 'add'],
                                                    ])->get();
                                                @endphp
                                                @if (sizeof($cart) == 0)
                                                    <button data-id={{ $product->id }}
                                                        class="btn btn-success add_cart cartbtn" type="button">Add to
                                                        Cart</button>
                                                @else
                                                    <button disabled data-id={{ $product->id }} class="btn cartinbtn"
                                                        type="button">Already in Cart</button>
                                                @endif
                                            @endif

                                            @if ($product->unit_price != '')
                                                <button type="button" class="btn btn-outline-info unitbtn">Rs.
                                                    {{ $product->unit_price }}</button>
                                                <button class="btn btn-primary orderbtn" type="button">Order Now</button>
                                            @endif
                                        </div>
                                    </div>

                                @endguest

                            </div>


                        </div>
                    </div>





                </div>
            </div>
            <div class="row speci-featr-row">
                <div class="col-md-6">
                    <div class="prdt-speci">
                        <h2>Product Specifications</h2>
                        <div class="prdt-speci-box">

                            <div class="speci-box-full">
                                <span class="speci-box-lft">
                                    <p>Product Short Name</p>
                                </span>
                                <span class="speci-box-rgt">
                                    <p>{{ $product->short_title }}</p>
                                </span>
                            </div>


                            <div class="speci-box-full">
                                <span class="speci-box-lft">
                                    <p>Item Code</p>
                                </span>
                                <span class="speci-box-rgt">
                                    <p>{{ $product->item_code }}</p>
                                </span>
                            </div>

                            <div class="speci-box-full">
                                <span class="speci-box-lft">
                                    <p>Brand</p>
                                </span>
                                <span class="speci-box-rgt">
                                    <p>{{ $product->brand_name }}</p>
                                </span>
                            </div>
                            <!-- <div class="speci-box-full">
                            <span class="speci-box-lft"><p>Unit</p></span>
                            <span class="speci-box-rgt"><p>{{ $product->unit }}</p></span>
                        </div> -->
                            <div class="speci-box-full">
                                <span class="speci-box-lft">
                                    <p>HSN Code</p>
                                </span>
                                <span class="speci-box-rgt">
                                    <p>{{ $product->hsn_code }}</p>
                                </span>
                            </div>

                            <div class="speci-box-full">
                                <span class="speci-box-lft">
                                    <p>Quantity </p>
                                </span>
                                <span class="speci-box-rgt">
                                    <p>{{ $product->quantity }}</p>
                                </span>
                            </div>
                            <div class="speci-box-full">
                                <span class="speci-box-lft">
                                    <p>Warrenty</p>
                                </span>
                                <span class="speci-box-rgt">
                                    <p>{{ $product->warrenty }}</p>
                                </span>
                            </div>
                            <!-- <div class="speci-box-full">
                            <span class="speci-box-lft"><p>Payment</p></span>
                            <span class="speci-box-rgt"><p>{{ $product->payment }}</p></span>
                        </div>
                        <div class="speci-box-full">
                            <span class="speci-box-lft"><p>Validity</p></span>
                            <span class="speci-box-rgt"><p>{{ $product->validity }}</p></span>
                        </div> -->


                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="prdt-speci">

                        <div class="features-box">
                            <h2>Features</h2>


                            {!! html_entity_decode($product->feature) !!}
                        </div>


                    </div>
                </div>

            </div>
        </div>
    </section>



    <section class="prdt-list-outer">
        <div class="container">
            <div class="row subhead-row">
                <div class="col-md-12">
                    <h2>Related Products</h2>
                </div>
            </div>

            <div class="row related-prdt-row">

                @foreach ($related_products as $values)
                    <div class="col-md-3">
                        <div class="prdt-box">
                            <div class="prdt-img">
                                <a href="{{ url('products/' . $values->slug) }}">
                                    @if ($values->image_name != '')
                                        <img src="<?php echo asset("public/storage/products/$values->image_name"); ?>" alt="">
                                    @endif
                                    @if ($values->image_name == '')
                                        <img src="{{ asset('images/no-image.jpg') }}" alt="">
                                    @endif
                                </a>
                                <div class="prdt-codtxt">
                                    <span class="cod-clr">{{ $values->item_code }}</span>
                                    <span class="cod-no"><i class="fa fa-user" aria-hidden="true"></i>
                                        @if ($values->view_count == '')
                                            0
                                        @else
                                            {{ $values->view_count }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="prdt-content">

                                <h2>{{ $values->name }}</h2>
                                {!! html_entity_decode(preg_replace('/\s+?(\S+)?$/', '', substr($values->description, 0, 120))) !!}


                                <!-- <p> {{ substr($values->short_description, 0, 50) }}</p> -->
                                @if (strlen($values->description) > 120)
                                    ...</p>
                                @endif
                                <button class="btn btn-warning quotebtn" type="button"
                                    onclick="window.location.href ='{{ url('products/' . $values->slug) }}'">Read
                                    More</button>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>

    <div class="modal" tabindex="-1" role="dialog" id="quote-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Quote Created</p>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" role="dialog" id="cart-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Added to Cart</p>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <?php /*

    <div class="modal" tabindex="-1" role="dialog" id="request-quote">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Request Quote</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                  <form name="frm_contact" id="frm_contact" method="POST"
                        action="{{ route('contactus.enquirysave') }}" autocomplete="off">
                        @csrf
                        <div class="col-md-12 contact-form">

                            <div class="form-group half-width">
                                <label>Name*</label>
                                <input type="text"class="input-field" name="name" id="name"
                                    value="{{ old('name') }}" maxlength="100" />
                                {{ $errors->first('name') }}
                            </div>

                            <div class="form-group half-width">
                                <label>E-mail*</label>
                                <input type="text" class="input-field" name="email" id="email"
                                    value="{{ old('email') }}" maxlength="100">
                                {{ $errors->first('email') }}
                            </div>

                            <div class="form-group half-width">
                                <label>Phone</label>
                                <input type="text" class="input-field" name="phone" id="phone"
                                    value="{{ old('phone') }}" maxlength="50">
                                {{ $errors->first('phone') }}
                            </div>

                            <input type="hidden" name="message" id="message" value="{{ $product->slug }}" />

                            <div class="form-group full-width submitBtnOuter">
                                <input type="button" class="send-btn" value="Send" id="btn_submit"
                                    name="btn_submit">
                            </div>

                        </div>
                        <input type="hidden" name="from" id="from" value="4" />
                  </form>

                </div>

            </div>
        </div>
    </div>

*/?>






@endsection


@section('scripts')

    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

    <script>
        function add_quote() {
            $("#request-quote").modal("show");
        }
        $(function() {

            $(".request_quote").click(function() {
                var product_id = $(this).attr('data-id');

                var APP_URL = {!! json_encode(url('/')) !!}
                var url = APP_URL + '/admin/addquote';

                $.ajax({
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    //dataType: "json",
                    data: {
                        product_id: product_id
                    },
                    success: function(data) { //alert();
                        $("#quote-modal").modal('show');
                    }
                });


            });

            $(".add_cart").click(function() {
                var product_id = $(this).attr('data-id');

                var APP_URL = {!! json_encode(url('/')) !!}
                var url = APP_URL + '/admin/addcart';
                // alert(product_id);

                $.ajax({
                    url: url,
                    //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'post',
                    //dataType: "json",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "product_id": product_id
                    },
                    success: function(data) { //alert();
                        location.reload(true);
                        // $("#cart-modal").modal('show');
                        //alert('Added to cart');
                    }
                });

            });



            // Single Select
            $("#search_word").autocomplete({
                source: function(request, response) {
                    // Fetch data
                    var APP_URL = {!! json_encode(url('/')) !!}
                    var url = APP_URL + '/admin/searchproducts';
                    var search_word = $("#search_word").val();
                    $.ajax({
                        url: url,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'post',
                        dataType: "json",
                        data: {
                            search_word: search_word
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    // Set selection
                    $('#search_word').val(ui.item.label); // display the selected text

                    return false;
                }
            });



        });

        function split(val) {
            return val.split(/,\s*/);
        }

        function extractLast(term) {
            return split(term).pop();
        }



        function validateContact() {
            //$('#frm_contact').submit();

            var validator = $("#frm_contact").validate({
                ignore: "",
                errorElement: "label",
                rules: {

                    name: {
                        required: true,
                        checkTags: true
                    },

                    email: {
                        required: true,
                        email: true
                    },

                    phone: {
                        checkTags: true,
                    },

                },
                messages: {
                    name: {
                        required: "Name is required!",
                        checkTags: "Name can not allow script tag(s)!"
                    },
                    email: {
                        required: "Email is required!",
                        email: "Please enter a valid email address!"
                    },


                    phone: {
                        checkTags: "Phone can not allow script tag(s)!"
                    },


                }
            });

            if ($("#frm_contact").valid()) {
                $('#frm_contact').submit();
            } else {
                validator.focusInvalid();
                return false;
            }
        }


        $(document).ready(function() {
            var success = "<?php echo session('success'); ?>";

            if (success != '') {

                var msg = '<div class="signup_now_outer">' +
                    '<div class="signup_now_conent"> <br />' +
                    '<center>' + success + '</center>' +
                    '<br />' +
                    '</div>' +
                    '</div>';


                // $.fancybox({
                //       content:msg,
                //       afterLoad:function() {       
                //       },
                //       beforeClose:function() {
                //       }
                // });
            }

            $.validator.addMethod("checkTags", function(value, element, arg) {
                if (value.indexOf('<script>') > -1) {
                    return false;
                } else {
                    return true;
                }
            }, "Can not allow script tag(s)!");

            $('#name').bind('keyup blur', function() {
                var node = $(this);
                node.val(node.val().replace(/[^0-9a-zA-Z\-\.\ ]/g, ''));
            });

            $('#email, #security_code').bind('keyup blur', function(e) {
                var node = $(this);
                if (e.keyCode == 32) {
                    node.val(node.val().replace(/\s/g, ''));
                }
            });

            $.validator.addMethod('nameCheck', function(name, element) {
                return this.optional(element) || name.match(/^[a-zA-Z0-9\-\.\ ]+$/i);
            }, 'Allowed alphabets, period, hyphen and numbers only!');


            $(".input-field").keypress(function(event) {
                if (event.which == 13) {
                    event.preventDefault();
                    validateContact();
                }
            });


            $('#btn_submit').on('click', function() {
                validateContact();
            });
        });
    </script>
@endsection
