@extends('layouts.app')

<?php
$title       = "My Cart";
$description = "My Cart";;
$keywords    ="My Cart";;
?>


@section('title', $title)
@section('meta_keywords', $description)
@section('meta_description', $keywords)
<link href="{{ asset('css/my-order.css') }}" rel="stylesheet">
 <!--Datatables -->
 <link rel="stylesheet" href="{{ asset('AdminLTE/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
  
@section('content')


  <section class="inrpage-menu">
      <div class="container">
        <div class="row">
          <div class="col-md-6">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Shop Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Cart</li>
              </ol>
            </nav>
          </div>
          <div class="col-md-5 text-right inrpage-search ml-auto">
          <form  class="searchform" name="frm_searchHead" id="frm_searchHead" method="POST" action="http://dentaldigital.in/beczone/search" autocomplete="off">
                    <input type="hidden" name="_token" value="I7yEEMqzeADywAP0Sbj2fN55ymLKa19u6oe12HDd">              <input type="text" class="form-control" placeholder="Search for products..." name="search_word" id="search_word">
               <button class="btn btn-secondary" type="submit" >Search</button>
          </form>


          </div>
        </div>
       </div>
    </section>

  <section class="cart-wrapper">
    <div class="container">
      <div class="row">
          @if(session()->has('message'))
            <div class="box no-border">
                <div class="box-tools">
                    <p class="alert alert-success alert-dismissible">
                        {{ session()->get('message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </p>
                </div>
            </div>
          @endif
      </div>

      <div class="row cart-row">
        <table class="cart-table">
          <tbody>
              
              @if(sizeof($cart)>0)                              
              @foreach ($cart as $values)
                <tr class="cart_items" data-id="{{$values->product_id}}">
                  <td class="cart-td">
                      <div class="row cartlist-outer">
                          <div class="col-md-3 leftcart">
                              <!--<a href="#"><img width="300" height="300" src="http://dentaldigital.in/beczone/storage/app/public/products/1587364770AgiliaVPpic.jpg" ></a>-->
                              @if($values->product->image_name!='')
                                <a href="#"><img width="300" height="300" src="<?php echo asset("storage/app/public/products/".$values->product->image_name) ?>" alt=""></a>
                              @else
                                <a href="#"><img width="300" height="300" src="{{ asset('images/no-image.jpg') }}" alt=""></a>
                              @endif
                          </div>
                          <div class="col-md-9 rightcart">
                            <div class="product-remove cmn-class">
                              <!--<a class="removebutton" href="{{url('/removecart/'.$values->id)}}" onclick="return funpas()" title="Delete item">Remove From Cart</a>-->
                              <a href="#myModal" class="removebutton trigger-btn" data-toggle="modal">Remove From Cart</a>
                            </div>
                            <div class="product-name cmn-class" data-title="Product">
                              <h3>Product:</h3>
                              <a href="#">{{$values->product->name}}</a>
                            </div>
                          </div>
                        </div>
                    </td>
                </tr>
                @endforeach
                
                
                <tr class="button-tr">
                  <td>
                      <div class="row buttons-outer">
                          <div class="col-md-9 ml-auto">
                              <button class="continue-btn" ><a href="{{url('/products')}}" style="color:#fff;">Continue Shopping</a></button>
                              <button class="request-btn request_quote">Request Quote</button>
                            </div>
                      </div>
                      
                    </td>
                </tr>
                
                @else
                  <h3>No Items in the cart</h3>
                @endif
            </tbody>
        </table>
             <div class="tbl-botom-btns">
             
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

    <!-- Modal HTML -->
    @if(sizeof($cart)>0) 
        <div id="myModal" class="modal fade">
          <div class="modal-dialog modal-confirm">
            <div class="modal-content">
              <div class="modal-header">
                        
                <h4 class="modal-title">Are you sure?</h4>  
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              </div>
              <div class="modal-body">
                <p>Do you really want to delete these record from cart? This process cannot be undone.</p>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
                <a href="{{url('/removecart/'.$values->id)}}" type="button" class="btn btn-danger">Delete</a>
              </div>
            </div>
          </div>
        </div>  
    @endif


@endsection




@section('scripts')

<!-- DataTables -->
<script src="{{ asset('AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


<script>
  function funpas()
  {  
      return confirm('Are you sure do you want to remove item from cart? Click Ok to continue.');
  }

</script>

<script>

$( function() {
    $( ".request_quote" ).click(function() {
     var product_id = [];
        $('.cart_items').each(function () {
            product_id.push($(this).attr('data-id'));
        });

     //alert(product_id);
     var jsonString = JSON.stringify(product_id);
     //alert(jsonString);
     var APP_URL = {!! json_encode(url('/')) !!}
     var url = APP_URL+'/admin/cartaddquote';
     
     $.ajax({
        url: url,
        //headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
        type: 'post',
        //dataType: "json",
        data: {
          "_token": "{{ csrf_token() }}",
          product_id    : jsonString
        },
        success: function( data ) {//alert();
           window.location.reload();
        //  $("#quote-modal").modal('show');
        }
      });
 
    });

    // Single Select
$( "#search_word" ).autocomplete({
 source: function( request, response ) {
  // Fetch data
  var APP_URL = {!! json_encode(url('/')) !!}
  var url = APP_URL+'/admin/searchproducts';
  var search_word=$("#search_word").val();
  $.ajax({
    url: url,
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    dataType: "json",
    data: {
      search_word:search_word
    },
    success: function( data ) {
      response( data );
    }
    });
 },
 select: function (event, ui) {
  // Set selection
  $('#search_word').val(ui.item.label); // display the selected text
 
  return false;
 }
});

  });

</script>
@endsection

