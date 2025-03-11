@extends('layouts.app')

<?php
$title       = "Quote";
$description = "Quote";;
$keywords    ="Quote";;
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
               <div class="col-md-6 mr-auto">
                  <nav aria-label="breadcrumb">
                     <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Quote</li>
                     </ol>
                  </nav>
               </div>
            </div>
         </div>
      </section>
      <section class="myorder-wrapper">
         <div class="container">
         <div class="row">
            <div class=" col-md-5 ml-auto filterhd">
               <div class="dataTables_length" id="activeTabList_length">
                
               </div>
               
            </div>
         </div>
         <div class="row">
            <div class="col-md-12 mr-auto ">
               <div role="main" class="component-container">
                  <div class="lw-sub-component-page" id="elementtoScrollToID">
                     <div ng-controller="MyOrderListController as MyOrderListCtrl" class="ng-scope">
                        <div class="order-tabs">
                           <ul class="nav nav-tabs lw-tabs" role="tablist" id="adminOrderList">
                              <li role="presentation" class="activeTab nav-item active"> <a href="#activeTab" class="nav-link active" lwonloadclicker="" aria-controls="activeTab" role="tab" data-toggle="tab" title="Active" aria-selected="true">Quote Request</a> </li>
                              <li role="presentation" class="cancelled nav-item"> <a href="#cancelled" class="nav-link" lwonloadclicker="" aria-controls="cancelled" role="tab" data-toggle="tab" title="Cancelled" aria-selected="false">Recive Quote</a> </li>
 
                           </ul>
                           <br> 
                           <div class="tab-content">
                              <div role="tabpanel" class="tab-pane fade in activeTab active show" id="activeTab">
                                 <div id="activeTabList_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                       <div class="col-sm-12">
                                          <table class="menu_confect table table-bordered table-striped data-" id="tablequote">
                                             <thead>
                                                <tr>
                                                   <th>Quote Id</th>
                                                    <th>Placed On</th>
                                                   <th>Action</th>
                                                </tr>
                                             </thead>
                                             <tbody>

                                             @foreach ($quote_request as $values)

                                                <tr class="grey-bg">
                                                   <td>{{$values->quote_id}}
                                                     
                                                   </td>
                                                  
                                                   <td>{{$values->created_at}}</td>
                                                   <td><a href="{{ url('quote_details/'.$values->product_slug) }}" class="tble-detals-btn">View Details</a></td>
                                                </tr>
                                               @endforeach
                                            
                                                
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div role="tabpanel" class="tab-pane fade in activeTab " id="cancelled">
                                 <div id="activeTabList_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                       <div class="col-sm-12">
                                          <table class="menu_confect  table table-bordered table-striped data-"  id="tablerequest">
                                             <thead>
                                                <tr>
                                                   <th>Quote Id</th>
                                                 
                                                   <th>Recived On</th>
                                                   <th>Action</th>
                                                </tr>
                                             </thead>
                                             <tbody>
                                             @foreach ($quote_recive as $values)

                                            <tr class="grey-bg">
                                              <td>{{$values->quote_id}}
                                                
                                              </td>
                                              
                                              <td>{{$values->updated_at}}</td>
                                              <td><a target="_blank" href="{{ url('quotepdf/'.$values->id) }}" class="tble-detals-btn">View Details</a></td>
                                            </tr>
                                            @endforeach
                                               
                                               
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
        


      </section>




@endsection


<!-- DataTables -->
<script src="{{ asset('AdminLTE/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

@section('scripts')
<script>
    jQuery(document).ready(function() {
         $('#tablerequest').DataTable({
        });
       $('#tablequote').DataTable({
        });
      });

</script>
@endsection

