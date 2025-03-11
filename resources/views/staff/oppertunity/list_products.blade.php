@extends('staff/layouts.app')

@section('title', 'Opportunity Products')

@section('content')
@if (session('success'))
<div class="alert alert-success alert-block fade in alert-dismissible show">
    <button type="button" class="close" data-dismiss="alert">×</button>
    <strong>{{ session('success') }}</strong>
</div>
@endif

    <section class="content-header">
        <h1> 
            Manage Opportunity Products
        </h1>
        <ol class="breadcrumb">
            <li><a href="<?php echo URL::to('staff'); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Products</li>
        </ol>
    </section>
    @php
        $staff_id = session('STAFF_ID');
        $adminA = ['33', '36', '37', '31'];
        $adminB = ['39', '30', '32'];
    @endphp

    <!-- Main content -->
    <div class="se-pre-con1"></div>
    <section class="content">
        <div class="row list-oprty">
        <div class="col-md-4 rightside-menu">
                <div class="box box-primary">
                    <div class="panel-body padding-10">
                        <h4 class="bold">
                            Quote History
                        </h4>
                    </div>
                    <table id="qhTable" class="table table-bordered table-striped ">
                        <tr>
                            <td></td>
                            <td>Id</td>
                            <td>Created at</td>
                            <td>Quote</td>
                            <td>Status</td>
                            <td>Preview</td>
                            @if ( $staff_permission || $staff_id == '32' || $staff_id == '35' || $staff_id == '121' ||  in_array($staff_id, $adminA) || in_array($staff_id, $adminB) ||  $staff_id == $op_name->staff_id)
                                <td>Action</td>
                            @endif
                        </tr>
                        @php $i=1; @endphp
                        @if (sizeof($qhistory) > 0)
                            @foreach ($qhistory as $qh)
                                <tr>
                                    <td><input @if($qh->approved_status != 'Y' || $qh->close_status == 'Y') disabled @endif type="radio" value="{{ $qh->id }}" name="quote_id" id="quote_id{{ $qh->id }}"></td>
                                    <td data-th="Id">{{ $i++ }}</td>
                                    <td data-th="Created at">{{ $qh->created_at }}</td>
                                    <td data-th="Quote">{{ $qh->quote_reference_no }}</td>
                                    <td data-th="Status">
                                        <?php
                                        if (optional($qh)->approved_status == 'Y') {
                                            if ($qh->quote_status == 'request') {
                                                echo '<button type="button" class="btn btn-success approvebtn">Approved</button>';
                                            } else {
                                                echo '<button type="button" class="btn btn-info mailsend">Mail Send</button>';
                                            }
                                        } elseif (optional($qh)->approved_status == 'N') {
                                            echo '<button type="button" class="sml-btn cancel-btn notapprovebtn">Not Approve</button>';
                                        }
                                        ?>

                                    </td>
                                    <td data-th="Preview">
                                        @if ($op_name->type == 2)
                                            <a class="btn btn-primary view_products view-btn" target="_blank"
                                                id="btn_contract_preview"
                                                href="{{ url('staff/preview_contract_quote/' . $qh->id) }}"> <i
                                                    class="fa fa-eye" aria-hidden="true"></i></a>
                                        @else
                                            <a class="btn btn-primary view_products view-btn" target="_blank"
                                                id="btn_preview" href="{{ url('staff/preview_quote/' . $qh->id) }}"> <i
                                                    class="fa fa-eye" aria-hidden="true"></i></a>
                                        @endif
                                    </td>
                                    @if ($staff_permission || $staff_id == '32' || $staff_id == '35' || $staff_id == '121'  ||  in_array($staff_id, $adminA) || in_array($staff_id, $adminB) ||  $staff_id == $op_name->staff_id)
                                        <td data-th="Action">
                                            <?php
                      if(optional($qh)->approved_status=='N')
                      {
                        ?>
                                            @if ($op_name->type == 2)
                                                <a href="{{ route('staff.edit_oppertunity', $qh->oppertunity_id) }}?histroy_id={{ $qh->id }}"
                                                    attr-id="{{ $qh->id }}" id="edititem{{ $qh->id }}"
                                                    data-tr="tr_{{ $qh->id }}" title="Edit">
                                                    <img src="{{ asset('images/edit.svg') }}">
                                                </a>
                                            @endif
                                            <a class="delete-btn deleteItemhistory" attr-id="{{ $qh->id }}"
                                                id="deleteItem{{ $qh->id }}" data-tr="tr_{{ $qh->id }}"
                                                title="Delete">
                                                <img src="{{ asset('images/delete.svg') }}" alt="" />
                                            </a>

                                            <?php
                      }
                      ?>
                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>No Record Found</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
            <div class="col-md-12">

                <div class="box">
                        <h3><b>{{ $op_name->oppertunity_name }}</b></h3><br><br>
                        <div class="col-lg-12 margin-tb">
                            @if (( $staff_permission || $staff_id == '32' || $staff_id == '35' || $staff_id == '121'  || $staff_id == '56' || $staff_id == 31 || $staff_id == 35 || $staff_id == 121  || $staff_id == 127  || $staff_id == 29 ||  in_array($staff_id, $adminA) ||  in_array($staff_id, $adminB) ||  $staff_id == $op_name->staff_id)  ) <?php /*&& $op_name->deal_stage < 6  */ ?>
                                <div class="pull-left">

                                    <a class="add-button" href="{{ url('staff/create_oppertunity_product/' . $id) }}"> Add
                                         Products</a>

                                </div>
                            @endif
                            <!-- <div class="pull-right">
                                                            <a href="{{ url('staff/edit_oppertunity/' . $id) }}" class="btn btn-warning btn-sm">Opportunity - {{ $op_name->oppertunity_name }}</a>
                                                          </div> -->

                    </div>

               
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form name="dataForm" id="dataForm" method="post" />
                        @csrf

                        <table id="cmsTable" class="table table-bordered table-striped data-"
                            data-id="{{ $id }}">
                            <thead>
                                <tr class="headrole">
                                    <th><input type="checkbox" name="select_all" value="1" id="select_all"
                                            class="select-checkbox"></th>
                                    <th>No.</th>
                                    <?php /*
                                    <th>Part No</th>

                                    <th>Product Image</th>
                                    */?>
                                     <th>Category</th>

                                     @if ($op_name->type == 2)
                                    <th>Sl.no of Equp.</th>
                                    @endif

                                    <th>Product</th>
                                    <th>Part No</th>
                                    @if ($op_name->type == 2)
                                        <th>PMs</th>
                                        <th>CRs</th>
                                        @if($staff_id == 35 )

                                        <th>Previous Amount</th>
                                        <th>Hike(%)</th>
                                        @endif
                                        
                                        <th>Amount</th>
                                        <th>Total</th>
                                    @else
                                        <th>Quantity</th>
                                        <th>Unit Price </th>
                                        <th>Ext. Price </th>
                                        <th>GST %</th>
                                        <th>GST Tax</th>
                                        <th>Net Amount</th>
                                    @endif
                                    @if (
                                        $staff_permission || $staff_id == '32' || $staff_id == '35' || $staff_id == '121' ||
                                            $staff_id == $op_name->staff_id ||
                                            in_array($staff_id, $adminB) ||
                                            in_array($staff_id, $adminB))
                                        <th class="alignCenter">Action</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>

                                <?php $i = 1;
                                $prototal=0;
                                $oIdsCheck = []; ?>
                                @if (sizeof($products) > 0)
                                    @foreach ($products as $k => $op)

                                        <?php
                                        $oIdsCheck[] = '<input type="checkbox" class="dataCheck-hide" name="ids[]" value="' . $op->id . '" id="h-check' . $op->id . '" data-id="' . $op->id . '" data-prid="' . $op->product_id . '" style="display:none">';
                                        ?>
                                        <tr id="tr_{{ $op->id }}" data-id="{{ $op->id }}"
                                            data-from ="subcategory">

                                            <?php /*
                                            @if ($op->optional == 0)
                                                <td data-th="">
                                                    <input type="checkbox" class="dataCheck"  value="{{ $op->id }}" id="check{{ $op->id }}" data-id="{{ $op->id }}" data-prid="{{ $op->product_id }}">
                                                </td>
                                                <td data-th="No."><span class="slNo">{{ $i }} </span></td>
                                            @else
                                            <td data-th="" >
                                                <input type="checkbox" value="{{ $op->id }}"  style="display: none"  >
                                            </td>
                                            <td data-th="No." >Optional</td>
                                            @endif
                                            */?>

                                            <td data-th="">
                                                <input type="checkbox" class="dataCheck"  value="{{ $op->id }}" id="check{{ $op->id }}" data-id="{{ $op->id }}" data-prid="{{ $op->product_id }}">
                                            </td>

                                            <td data-th="No."><span class="slNo">{{ $i }} </span></td>

                                            @php
                                                if ($op->product_id > 0) {
                                                    $products_det = DB::select("select * from products where id='" . $op->product_id . "'");

                                                    if ($products_det) {
                                                        $prod_name = $products_det[0]->name;
                                                    } else { 
                                                        $prod_name = '';
                                                    }
                                                } else {
                                                    $prod_name = '';
                                                }
                                                $prototal+=$op->amount;
                                            @endphp

                                            <?php /*
                                            <td data-th="Part No">{{$op->part_no }}</td>


                                            <td data-th="Product Image"><img src="{{ asset("public/storage/products/" . optional($op->oppertunityProduct)->image_name) }}" alt="" width="60" ></td>
                                            */?>

                                            <td>{{optional($op->oppertunityProduct)->category_name??"Product Not available"}}</td>

                                            @if ($op_name->type == 2)

                                            <td>{{optional($op->oppertunityProductIb)->equipment_serial_no??"Product Not available"}}</td>

                                            @endif

                                            <td data-th="Product">@empty($prod_name) Product Not available @else
                                                {{ $prod_name }}
                                            @endempty
                                            </td>

                                            <td>{{ optional($op->oppertunityProduct)->part_no??"Product Not available" }}</td>

                                            @if ($op_name->type == 2)
                                                <td data-th="PMs">{{ $op->pm }}</td>
                                                <td data-th="CRs">{{ $op->cr }}</td>

                                                @if($staff_id == 35 )

                                                <td data-th="previous_amount">{{ $op->oldprice }}</td>
                                                <td data-th="hike">{{ $op->hike }}</td>
                                                @endif
                                                <td data-th="Amount">{{ $op->sale_amount }}</td>
                                                <td data-th="Total">{{ $op->amount }}</td>

                                            @else
                                                <td data-th="Quantity">{{ $op->quantity }}</td>
                                                <td data-th="Unit Price">{{ $op->sale_amount }}</td>
                                                <td data-th="Ext. Price">{{ $op->sale_amount *$op->quantity  }}</td>
                                                <th data-th="GST %">{{ $op->tax_percentage }}</th>
                                                <td data-th="GST Tax">{{ round($op->sale_amount *$op->quantity*$op->tax_percentage)/100  }}</td>
                                                <td data-th="Net Amount">{{ $op->amount }}</td>
                                            @endif


                                            @if (
                                                $staff_permission || $staff_id == '32' || $staff_id == '35' || $staff_id == '121' ||
                                                    $staff_id == $op_name->staff_id ||
                                                    in_array($staff_id, $adminB) ||
                                                    in_array($staff_id, $adminB))
                                                <td data-th="Action">
                                                    <a href="{{ url('staff/edit_oppertunity_product/' . $op->id . '/' . $id) }}"
                                                        class="edit-btn"><img src="{{ asset('images/edit.svg') }}"
                                                            alt="" /></a>
                                                            @if($staff_id == 35 )

                                                            <a href="{{ url('staff/delete_oppertunity_eachproduct/' . $op->id . '/' . $id) }}"
                                                                class="delete-btn" onclick="return confirm('Are you sure?')"><img
                                                                    src="{{ asset('images/delete.svg') }}" alt="" /></a>
                                                            @endif
                                                </td>
                                            @endif
                                        </tr>

                            
                                        @php
                                            $opper = \App\Oppertunity_product::where('oppertunity_id',$op->oppertunity_id)->where('main_product_id',$op->product_id)->where('optional','1')->get();
                                        @endphp
                                        @if (sizeof($opper) > 0)
                                            @foreach ($opper as $k => $opp)
                                                <?php
                                                $oIdsCheck[] = '<input type="checkbox" class="dataCheck-hide" name="ids[]" value="' . $op->id . '" id="h-check' . $op->id . '" data-id="' . $op->id . '" data-prid="' . $op->product_id . '" style="display:none">';
                                                ?>
                                                <tr id="tr_{{ $opp->id }}" data-id="{{ $opp->id }}"
                                                    data-from ="subcategory">
                                                    @if ($opp->optional == 0)
                                                        <td data-th=""><input type="checkbox" class="dataCheck"
                                                                value="{{ $opp->id }}" id="check{{ $opp->id }}"
                                                                data-id="{{ $opp->id }}"
                                                                data-prid="{{ $opp->product_id }}">
                                                        </td>
                                                        <td data-th="No."><span class="slNo">{{ $i++ }} </span>   </td>
                                                    @else
                                                        <td data-th="" >
                                                            <input type="checkbox" value="{{ $op->id }}"  style="display: none"  >
                                                        </td>
                                                        <td data-th="No." >Optional</td>
                                                    @endif

                                                    @php
                                                        if ($opp->product_id > 0) {
                                                            $products_det = DB::select("select * from products where id='" . $opp->product_id . "'");

                                                            if ($products_det) {
                                                                $prod_name = $products_det[0]->name;
                                                            } else {
                                                                $prod_name = '';
                                                            }
                                                        } else {
                                                            $prod_name = '';
                                                        }
                                                        $prototal+=$opp->amount;
                                                    @endphp


                                                    <?php /*
                                                    <td data-th="Part No">{{$opp->part_no }}</td>

                                                    <td data-th="Product Image"><img src="{{ asset("public/storage/products/" . optional($op->oppertunityProduct)->image_name) }}" alt="" width="60" ></td>
                                                    */?>

                                                    <td>{{optional($opp->oppertunityProduct)->category_name??"Product Not available"}}</td>

                                                    @if ($op_name->type == 2)
                                                    <td data-th="sl Euip">{{optional($opp->oppertunityProductIb)->equipment_serial_no??"Product Not available"}}</td>
                                                    @endif

                                                    <td data-th="Product">@empty($prod_name) Product Not available @else
                                                        {{ $prod_name }}
                                                    @endempty
                                                    </td>

                                                    <td>{{ optional($op->oppertunityProduct)->part_no??"Product Not available" }}</td>

                                                    @if ($op_name->type == 2)
                                                        <td data-th="PMs">{{ $opp->pm }}</td>
                                                        <td data-th="CRs">{{ $opp->cr }}</td>
                                                        <td data-th="Tax">{{ $opp->tax }} %</td>
                                                        <td data-th="Amount">{{ $opp->single_amount }}</td>
                                                        <td data-th="Total">{{ $opp->amount }}</td>
                                                    @else
                                                        <td data-th="Quantity">{{ $opp->quantity }}</td>
                                                        <td data-th="Unit Price">{{ $opp->sale_amount }}</td>
                                                        <td data-th="Ext. Price">{{ $opp->sale_amount *$opp->quantity  }}</td>
                                                        <th data-th="GST %">{{ $opp->tax_percentage }}</th>
                                                        <td data-th="GST Tax">{{ round($opp->sale_amount *$opp->quantity*$opp->tax_percentage)/100  }}</td>
                                                        <td data-th="Net Amount">{{ $opp->amount }}</td>

                                                        
                                                    @endif
                                                    @if (
                                                        $staff_permission || $staff_id == '32' || $staff_id == '35' || $staff_id == '121' ||
                                                            $staff_id == $op_name->staff_id ||
                                                            in_array($staff_id, $adminA) ||
                                                            in_array($staff_id, $adminB))
                                                        <td data-th="Action">
                                                            <a href="{{ url('staff/edit_oppertunity_product/' . $opp->id . '/' . $id) }}"
                                                                class="edit-btn"><img src="{{ asset('images/edit.svg') }}"
                                                                    alt="" /></a>
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                        @php
                                            $i++
                                        @endphp
                                    @endforeach

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th colspan="7"></th>
                                        <th>Total</th>
                                        <th>{{$prototal}}</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                @else

                                <tbody>
                                    <tr>
                                        <td colspan="11">No Records found</td>
                                    </tr>

                                </tbody>
                                @endif


                            </tbody>
                        </table>
                        <br><br><br>

                        {!! implode('', $oIdsCheck) !!}
                        @if (( $staff_permission || $staff_id == '32' || $staff_id == '35' || $staff_id == '121' || $staff_id == '56' ||  $staff_id == 31 || $staff_id == 35 || $staff_id == 121  || $staff_id == 127  || $staff_id == 29 ||  in_array($staff_id, $adminA) ||  in_array($staff_id, $adminB) || $staff_id == $op_name->staff_id) )
                            <?php if(count($products) > 0) { ?>
                            <div class="row checksec ">

                                <div class="form-check col-md-3">
                                    <label class="form-check-label" for="exampleCheck1">Generate Date</label>
                                    <input type="text" class="form-check-input" id="generate_date" name="generate_date"
                                        value="<?php echo date('Y-m-d'); ?>">

                                </div>

                                @if ($op_name->type == 1)

                                    <div class="form-check col-md-3 pd-top">
                                        <input type="checkbox" class="form-check-input" id="hsn_code" value="Y">
                                        <label class="form-check-label" for="hsn_code">Show HSN code</label>
                                    </div>

                                @endif

                                @if ($op_name->type == 2)
                                <div class="form-check col-md-3 pd-top">
                                    <label>Select Type</label>
                                    <select id="companyType" name="companyType" class="form-control">
                                        <option value="">---Select Type---</option>
                                        <option value="BEC">BEC</option>
                                        <option value="GE">GE</option>
                                    </select>
                                </div>
                                <div class="form-check col-md-3 pd-top text-center">
                                    <label class="form-check-label" for="ge_healthcare">GE Health care</label>
                                    <br>
                                    <input type="checkbox" class="form-check-input" id="ge_healthcare" value="Y" style="transform: scale(1.5);">
                                </div>
                            
                                @else

                                <input type="hidden" class="form-check-input" id="companyType" id="companyType" value="BEC">

                                    <div class="form-check col-md-3 pd-top">
                                        <input type="checkbox" class="form-check-input" id="hide_tech" value="Y">
                                        <label class="form-check-label" for="exampleCheck1">Hide technical bid</label>
                                    </div>
                                    <div class="form-check col-md-3 pd-top">
                                        <input type="checkbox" class="form-check-input" id="show_total" value="Y">
                                        <label class="form-check-label" for="exampleCheck1">Show total</label>
                                    </div>
                                @endif
                            </div>

                            <div class="deleteAll col-md-12">
                                <span class="rows_selected" id="select_count">0 Selected</span><br>
                                <!-- <a class="mdm-btn cancel-btn  "  id="btn_deleteAll" >
                                                                        <span class="glyphicon glyphicon-trash"></span> Delete All Selected
                                                            </a> -->
                                @if ($op_name->quote_status == 0)
                                @endif


                                <a class="mdm-btn submit-btn " id="btn_quote"> <span
                                        class="glyphicon glyphicon-plus"></span>Generate Quote Preview</a>

                            </div>
                            <?php } ?>

                        @endif

                        </form>

                        <div class="button-outer">
                            @if (( $staff_permission || $staff_id == '56' || $staff_id == '32' || $staff_id == '35' ||  $staff_id == '121' ||  $staff_id == 31 || $staff_id == 35 || $staff_id == 121  || $staff_id == 127  || $staff_id == 29 || in_array($staff_id, $adminA) ||  in_array($staff_id, $adminB) || $staff_id == $op_name->staff_id) )


                            @if ($op_name->type ==2)

                                @if(!empty($op_name->quotehistory_id))

                                    @if(optional($op_name->oppertunityquote)->approved_status =='Y')

                                        @if($op_name->close_status !='closed')

                                        <div class="alert alert-danger" style="display:none" id="select_quote" >Select the Quote for close Oppertunity</div>

                                            <button class="btn btn-success" data-id="{{ $op_name->id }}" onclick="CheckRadiobox(this)">
                                                Close Opportunity 
                                            </button>


                                        @else
                                            <button class="btn btn-success" id="closeOpportunitclosed">
                                                Close Opportunity 
                                            </button> 

                                            <div class="alert alert-danger" style="display:none" id="opp_closed" >Oppertunity Closed</div>

                                        @endif

                                    @else

                                        <button class="btn btn-success" id="closeOpportunityBtn">
                                            Close Opportunity
                                        </button>

                                        <div class="alert alert-danger" style="display:none" id="quote_approve" >Quote is not approved</div>

                                    @endif

                                @else

                                    <button class="btn btn-success" id="closegeneratequote">
                                        Close Opportunity
                                    </button>
                                    <div class="alert alert-danger" style="display:none" id="quote_generate">Please Generate Quote</div>

                                @endif

                            @else


                            
                    
                            @if(!empty($op_name->quotehistory_id))

                                @if(optional($op_name->oppertunityquote)->approved_status =='Y')

                                   
                                    <div class="alert alert-danger" style="display:none" id="select_quote" >Select the Quote for close Oppertunity</div> 

                                    <button class="btn btn-success" data-id="{{ $op_name->id }}"  onclick="openCloseOpportunityModal(this)">
                                        Close Opportunity 
                                    </button>&nbsp;

                                @else

                                    <button class="btn btn-success" id="closeOpportunityBtn">
                                        Close Opportunity
                                    </button>

                                    <div class="alert alert-danger" style="display:none" id="quote_approve" >Quote is not approved</div>

                                @endif

                                @else

                                    <button class="btn btn-success" id="closegeneratequote">
                                        Close Opportunity
                                    </button>
                                    <div class="alert alert-danger" style="display:none" id="quote_generate">Please Generate Quote</div>

                                @endif

                           @endif

                                <button class="btn btn-warning" data-toggle="modal" data-target="#loastcancelModal"> Lost
                                    Opportunity</button> &nbsp;
                                <button class="btn btn-danger" data-toggle="modal" data-target="#closecancelModal">
                                    Cancel Opportunity</button>

                        @endif

                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>

            <div class="se-pre-con1"></div>


            <!-- /.col -->
        </div>
        <!-- /.row -->

        <div class="modal fade inprogress-popup" id="mailModal" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Mail</h4>
                    </div>
                    <div class="modal-body" id="contain">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="mdm-btn cancel-btn  " data-dismiss="modal">Close</button>
                        <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="loastcancelModal" role="dialog" aria-labelledby="lostcancelModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="lostcancelModalLabel">Lost Opportunity</h4>
                    </div>
                    <form action="{{ route('staff.oppertunity.closeLost', $op_name->id) }}" id="cancel-form"
                        method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="lost_brand">Brand*</label>
                                        <input type="text" name="lost_brand" id="lost_brand" class="form-control"
                                            value="">

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="loast_equipemnt">Equipemnt*</label>
                                        <input type="text" name="loast_equipemnt" id="loast_equipemnt"
                                            class="form-control" value="">

                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reason">Reason</label>
                                        <textarea name="reason" id="reason" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Lost Opportunity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="closecancelModal" role="dialog" aria-labelledby="clocecancelModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="clocecancelModalLabel">Cancel Opportunity</h4>
                    </div>
                    <form action="{{ route('staff.oppertunity.closeCancell', $op_name->id) }}" id="cancel-form"
                        method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="reason">Reason</label>
                                        <textarea name="reason" id="reason" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Cancel Opportunity</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade " id="closeOpertunityContractModal" role="dialog" aria-labelledby="closeOpertunityContactModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="closeOpertunityContactModalLabel">Are you sure, You want to close this opportunity?</h4>
                    </div>

                    <div class="modal-footer">

                        <input type="hidden" name="opper_fetch_id" id="opper_fetch_id" value="">

                        <input type="hidden" name="fetch_id" id="fetch_id" value="">

                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                        <a  class="btn btn-primary"  id="opp_close_submit" onclick="submitClose()" >Yes</a>

                    </div>

                </div>
            </div>
        </div>


        <div class="modal fade " id="closeOpertunityModal" role="dialog" aria-labelledby="closeOpertunityModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="closeOpertunityModalLabel">Close Opportunity</h4>
                    </div>

                    <?php $i = 1;
                    $prototal=0;
                    $oIdsCheck = []; ?>

                    <form id="opp_closeform" action="{{ route('staff.oppertunity.closeWon', $op_name->id) }}" method="post">
                        @csrf
                        <div class="modal-body" id="contain">
                            <div class="row">

                                <input type="hidden" name="opper_fetch_id" id="opper_sale_fetch_id" value="">

                                <input type="hidden" name="fetch_id" id="fetch_sale_id" value="">
        
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order_no">Order No*</label>
                                        <input type="text" name="order_no" id="order_no" class="form-control"
                                            value="" >

                                        <span class="error" id="order_no_error"> </span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order_date">Order Date*</label>
                                        <input type="text" name="order_date" id="order_date" class="form-control"
                                            value=""  readonly>
                                            <span class="error" id="order_date_error"> </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="order_recive_date">Order Received Date*</label>
                                        <input type="text" name="order_recive_date" id="order_recive_date" class="form-control" value=""  readonly>

                                        <span class="error" id="order_recive_date_error"> </span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="payment-terms">Payment Terms*</label>
                                        <input type="text" name="payment_term" id="payment-terms"
                                            class="form-control" value="" >

                                            <span class="error" id="payment-terms_error"> </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="delivery-date">Delivery Date* (dd-mm-Y)</label>
                                        <input type="text" name="delivery_date" id="delivery_date_remove"
                                            class="form-control" value="" required placeholder="dd-mm-Y" maxlength="10">

                                            <span class="error" id="delivery_date_remove_error"> </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="warranty-rerms">Warrenty Terms*</label>
                                        <input type="text" name="warrenty_terms" id="warranty-rerms"
                                            class="form-control" value="" >

                                        <span class="error" id="warranty-rerms_error"> </span>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supplay">Supply*</label>
                                        <input type="text" name="supplay" id="supplay" class="form-control" readonly
                                            value="" >

                                            <span class="error" id="supplay_error"> </span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="remark">Remark</label>
                                        <textarea name="remark" id="remark" class="form-control"></textarea>
                                    </div>
                                </div>

                                <input type="hidden" value="{{ $op_name->id }}" id="oppertunity_id"
                                name="oppertunity_id">

                                <ul>
                                    @foreach ($quote_products as $k => $op)
                                        @php
                                            if ($op->product_id > 0) {
                                                $products_det = DB::select("select * from products where id='" . $op->product_id . "'");
                                                $prod_name = $products_det ? $products_det[0]->name : '';
                                            } else {
                                                $prod_name = '';
                                            }
                                            // $prototal += $op->amount;
                                        @endphp
                                
                                        <div class="col-md-7 quote_pdt" id="quote_pdt-{{$op->id}}" style="display:none;">

                                            <div class="form-group">

                                      
                                        @php
                                            $addonproducts = \App\Models\QuoteProduct::where('main_product_id', $op->id)->where('addon_ptd', 1)->get();
                                        @endphp

                                    
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>SI No.</th>
                                                                <th>Product Name</th>
                                                                <th>Qty</th>
                                                                <th>Tax</th>
                                                                <th>Sale Amount</th>

                                                                <th>Action</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody class="addonpdt_{{$k}}" id="addonpdt_{{$k}}">
                                                    
                                                            
                                                                <tr id="proid-{{$k}}"  class="main_pdt_{{$k}}" >
                                                                    
                                                                  
                                                                    <td id="mainsl{{$op->id}}">{{ $k+1 }}</td>
                                                                    
                                                                    @empty($prod_name)
                                                                     
                                                                        <td>   Product Not Available</td>
                                                                    @else
                                                                    <td> {{ $prod_name }}</td>
                                                                        
                                                                    @endempty

                                                                    <td>{{ $op->product_quantity }}</td>

                                                                    <td>{{ $op->product_tax_percentage }}</td>
                                                                
                                                                    <td>  {{ number_format($op->product_sale_amount, 2) }} </td>

                                                                    <td>

                                                                        <button type="button" class="btn btn-primary addProductButton addmain_pdt_{{$k}}" id="" data-id="{{$op->product_id}}" data-k="{{$k}}">Add on</button>

                                                                    </td>

                                                                </tr>

                                                                @if(!empty($addonproducts) && count($addonproducts) > 0 )

                                                                    @foreach($addonproducts as $index => $item)

                                                                        <tr class="loaded_pdts" id="loaded_pdts_{{$index}}">

                                                                            <td >{{ chr(96 + ++$index) }}

                                                                                <input type="hidden" name="addonproduct[]" value="{{$item->product_id}}">

                                                                            </td>

                                                                            <td>{{ $item->quoteProduct->name }}</td>

                                                                            <td>{{ $item->product_quantity }}</td>

                                                                            <td>{{ $item->product_tax_percentage }}</td>

                                                                            <td>{{ number_format($item->product_sale_amount, 2) }}</td>

                                                                            <td> 
                                                                                <a class="delete-btn " onclick="deleteOpperPdt('{{$item->id}}',this)" data-id="{{$index}}"  title="Delete">

                                                                                    <img src="{{ asset('images/delete.svg') }}" alt="" />
                                                                                </a>
                                                                            </td>

                                                                        </tr>
                                                                    @endforeach

                                                                @endif

                                                        </tbody>

                                                    </table>

                                               

                                            </div>
                                        </div>

                                    
                
                                            <div id="productDropdownContainer-{{$k}}" data-id="{{$op->product_id}}" class="col-md-12" style="display: none;">
                                                <div class="box-body">

                                                    <div class="row oprty-crete-row">
                                                        <input type="hidden" name="addon_ptd" id="addon_ptd-{{$k}}" value="">
                                                       
                                                            <div class="form-group col-md-6">
                                                                <label>Product*</label>
                                                                <select name="part_no" id="part_no-{{$k}}" class="form-control product_id">
                                                                        <option value="">-- Select Product --</option>
                                                                        <?php
                                                                    foreach($product as $item) {
                                                                        ?>
                                                                    <option value="{{ $item->id }}">{{ $item->name }} [{{ $item->part_no }}]
                                                                    </option>
                                                                    <?php
                                                                    } ?> 

                                                                </select>


                                                                          
                                                                <span class="error_message" id="msp_error_message-{{$k}}" style="display: none">Please update
                                                                    msp value of this product</span>
                                                                <span class="error_message" id="product_id_message-{{$k}}" style="display: none">Field is
                                                                    required</span>
                                                                <!-- <span class="rows_selected" id="select_count" style="display: none"></span><br> -->
                                                            </div>
                                                            <input type="hidden" id="quantity-{{$k}}" name="quantity" class="form-control"
                                                                placeholder="Quantity" value="1">
                                                            <input type="hidden" id="sale_amount-{{$k}}" name="sale_amount" class="form-control"
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
                            
                                                            <div class="form-group col-md-2" id="product_id_show-{{$k}}" style="display:none;">
                                                                <label>Product</label>
                                                                <h3 id="product_id">No products selected </h3>
                                                            </div>
                            
                                                            <div class="form-group col-md-2" id="opp-{{$k}}" style="display:none;">
                                                                <label>Optional Products</label>
                                                                <select name="opt_pdt[]" id="opt_pdt-{{$k}}" class="form-control" multiple="multiple">
                                                                    <option value="">-- Select Product --</option>
                            
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="box-footer col-md-12 pd-lr-none">
                                                            <input type="hidden" name="count_product" id="count_product" value="0">
                                                            <input type="hidden" name="op_id" id="op_id-{{$k}}" value="0">
                                                            <br>
                                                            <span class="error_message" id="alreadytexit-{{$k}}" style="display: none">Product already
                                                                exit</span>
                                                            <button type="button" class="mdm-btn submit-btn  " id="add_opper_product-{{$k}}"
                                                                onclick="add_product('{{$op->id}}','{{$k}}')">Add</button>
                                                        </div>

                                                    </div>
                            
                            
                                                    <table id="cmsTable2-{{$k}}" class="table table-bordered table-striped data-" style="display:none;">
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
                                                        <tbody id="tabledata-{{$k}}" class="added_tabledata">
                            
                                                            <tr data-from ="staffquote">
                            
                                                                <td colspan="4" class="noresult">No result</td>
                                                            </tr>
                                                    </table>
                            
                            
                                                    <div class="box-footer">
                                                        <button type="button" id="save_btn-{{$k}}" style="display:none;"
                                                            class="mdm-btn submit-btn save_btn" data-id="{{$k}}" >Save</button>
                                                    </div>
                                        </div>


                                    @endforeach
                                </ul>

                                <div class="col-md-8">

                                    <span class="error" id="savemessage"> </span>
                                   
                                </div>

                                <?php /*
                                <div class="col-md-4" >
                                    <div class="form-group ">
                                        <h4><strong>Total: {{ number_format($prototal, 2) }}</strong></h4>
                                    </div>
                                </div> */?>

                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button id="closeopport" onclick="oppClose()" type="button" class="btn btn-primary">Close Opportunity</button>

                            <button id="previewclose" onclick="PreviewClose('{{$id}}')" type="button" class="btn btn-secondary">View Products</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>


        <div class="modal fade " id="view_product_modal" role="dialog" aria-labelledby="closeOpertunityModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" onclick="showClose()"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="view_product_modal_label">View Product Details</h4>
                    </div>

                    <form id="" action="{{ route('staff.oppertunity.closeWon', $op_name->id) }}" method="post">
                        @csrf
                        <div class="modal-body" id="contain">

                            <div class="row" id="view_pdt_content">

                              
        
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" onclick="showClose()" data-dismiss="modal">Close</button>
                    
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </section>



@endsection

@section('scripts')
    <script type="text/javascript">

        function CheckRadiobox(el) {

            var isChecked = $('input[name="quote_id"]:checked');

            var oppet_id= $(el).data('id');

            var checked_value = isChecked.val();

            var errorMessage = $('#select_quote');
            if (isChecked.length === 0) {

                errorMessage.show();
            } else {
                errorMessage.hide();

                $('#opper_fetch_id').val(oppet_id);

                $('#fetch_id').val(checked_value);

                $('#closeOpertunityContractModal').modal('show');
            }
        }

        function PreviewClose(id)
        {
            console.log(id,'opper_id');

            var quote_id = $('#fetch_sale_id').val();

            $('#view_pdt_content').empty();

            $.ajax({ 
                url: "{{route('staff.preview_products')}}", 
                type: 'get',         
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), 
                    opper_id: id,
                    quote_id:quote_id
                },
                success: function(response) {
                    
                    if (response.products && response.products.length > 0) {

                        var tableview = '';

                        $.each(response.products, function(index, productGroup) {
                            console.log(`Product Group ${index + 1}:`, productGroup);

                            tableview += `
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>SI No.</th>
                                            <th>Product Name</th>
                                            <th>Qty</th>
                                            <th>Tax</th>
                                            <th>Sale Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>`;

                            $.each(productGroup, function(i, productmain) {
                            
                                var mainProduct = productmain.main_product;

                              
                                tableview += `
                                    <tr>
                                        <td>${i+1}</td>
                                        <td>${mainProduct.quote_product.name}</td>
                                        <td>${mainProduct.product_quantity}</td>
                                         <td>${mainProduct.product_tax_percentage}</td>
                                        <td>${parseFloat(mainProduct.product_sale_amount).toFixed(2)}</td>
                                    </tr>
                                `;

                               
                                var optionalProducts = productmain.optional_product;
                                if (optionalProducts && optionalProducts.length > 0) {
                                 
                                    $.each(optionalProducts, function(i, optionalProduct) {

                                        var letter = String.fromCharCode(97 + i); 

                                        tableview += `
                                            <tr>
                                                 <td>${letter}</td>
                                                 <td>${optionalProduct.quote_product.name}</td>
                                                <td>${optionalProduct.product_quantity}</td>
                                                  <td>${optionalProduct.product_tax_percentage}</td>
                                                <td>${parseFloat(optionalProduct.product_sale_amount).toFixed(2)}</td>
                                            </tr>
                                        `;
                                    });
                                }

                            });

                            tableview += '</tbody></table>';

                        });

                        $('#view_pdt_content').append(tableview);

                        } else {

                        console.log('No products found.');

                        }
                },
                error: function(xhr, status, error) {
                
                }
            });

            $('#closeOpertunityModal').modal('hide');

            $('#view_product_modal').modal('show');
        }

        function showClose()
        {
            $('#view_product_modal').modal('hide');

            $('#closeOpertunityModal').modal('show');
        }

        function openCloseOpportunityModal(el) {

            var isChecked = $('input[name="quote_id"]:checked');

            var oppet_id= $(el).data('id');

            var checked_value = isChecked.val();

            $('.quote_pdt').hide();

            var errorMessage = $('#select_quote');
            if (isChecked.length === 0) {

                errorMessage.show();
            } else {

                errorMessage.hide();

                $('#opper_sale_fetch_id').val(oppet_id);

                $('#fetch_sale_id').val(checked_value);

                $.ajax({ 
                        url: "{{route('staff.get_quote_product')}}", 
                        type: 'get',         
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'), 
                            quote_id: checked_value
                        },
                        success: function(response) {

                            response.forEach(function(v,k)
                            {
                                console.log(v); 
                                
                                $(`#quote_pdt-${v.id}`).show();

                                $(`#mainsl${v.id}`).text(k+1);

                            });
                            
                          
                            $('#closeOpertunityModal').modal('show');
                        },
                        
                    });
            }
        }


        $('input[name="quote_id"]').on('change', function () {
        $('#select_quote').hide();});



        function submitClose(event) {
   
            var opper_id = $('#opper_fetch_id').val();
            var quote_id = $('#fetch_id').val();

            $.ajax({ 
                url: "{{route('staff.pm_order_submit')}}", 
                type: 'post',         
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), 
                    opper_id: opper_id,
                    quote_id: quote_id
                },
                success: function(response) {
                    
                    console.log('Success:', response);
                    if (response.success) {
                        
                        $('#closeOpertunityContractModal').modal('hide'); 

                            window.location.href = "{{ route('staff.pm_order.index') }}";
                        
                    } else {

                    }
                },
                error: function(xhr, status, error) {
                
                }
            });
        }

        $('#cancel-form').submit(function() {
            setTimeout(() => {
                $('#closecancelModal').modal("hide")
            }, 1000);
        })

        jQuery(document).ready(function() {

            $('#order_date').datepicker({
                dateFormat: 'dd-mm-yy',
                maxDate: 0 
            });

            $('#order_recive_date').datepicker({

                dateFormat: 'dd-mm-yy',
                maxDate: 0 
            });

            $('#delivery_date').datepicker({
                dateFormat: 'dd-mm-yy',
                maxDate: 0 
            });

            $('#supplay').datepicker({
                dateFormat: 'dd-mm-yy',
            });

            $('#order_date,#supplay,#delivery_date,#order_recive_date').focus(function() {
                $('#ui-datepicker-div').css('z-index', 1050)
            })
        })

        $(function() {

            $('#closeOpportunityBtn').click(function() {
                $('#quote_approve').show();
            });

            $('#closegeneratequote').click(function() {
                $('#quote_generate').show();
            });

            $('#closeOpportunitsend').click(function() {
                $('#quote_send').show();
            });

            $('#closeOpportunitclosed').click(function() {
            $('#opp_closed').show();
            });


        });

        $('.deleteItemhistory').click(function() {

            if (confirm('Are you confirm to delete')) {

                var id = $(this).attr("attr-id");

                var url = APP_URL + '/staff/delete_quote_history';

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: {
                        id: id
                    },
                    success: function() {
                        alert("Deleted");
                        location.reload(true);
                    }
                });
            }

        });

            function deleteOpperPdt(id,element)
                {
                    if (confirm('Are you confirm to delete')) {

                        var element_id = $(element).data('id');

                        console.log(element_id,'elemsnt id');

                        var prodct_id = id;

                        var url = APP_URL + '/staff/delete_opp_product';
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                prodct_id: id
                            },
                            success: function() {
                               
                                $(`#loaded_pdts_${element_id}`).hide();
                            }
                        });
                    }
                }

        jQuery(document).ready(function() {
            $('#cmsTable').DataTable({
                "bInfo": false,
                "ordering": false,
                createdRow: function(row, data, dataIndex) {

                    console.log(dataIndex, row)
                    $(row).find('input.dataCheck').change(function() {
                        $('#h-check' + $(this).val()).prop('checked', this.checked);

                        if ($('.dataCheck-hide:checked').length == $('.dataCheck-hide').length) {
                            $('#select_all').prop('checked', true);
                        } else {
                            $('#select_all').prop('checked', false);
                        }
                        $("#select_count").html($("input.dataCheck-hide:checked").length +" Selected");
                    })
                    $('#h-check' + $(row).find('input.dataCheck').val()).change(function() {
                        $(row).find('input.dataCheck').prop('checked', this.checked);
                    })
                },
            });
            var oTable = $('#qhTable').DataTable({
                "bInfo": false

            });


            // Add event listener for opening and closing details
            // $('#cmsTable tbody').on('click', 'td.details-control', function () {
            // $('.openTable').on('click', function() {
            //     alert()
            //     var tr = $(this).closest('tr');
            //     var row = oTable.row(tr);

            //     var id = $(tr).attr('data-id');
            //     var from = $(tr).attr('data-from');

            //     if (row.child.isShown()) {
            //         row.child.hide();
            //         tr.removeClass('shown');
            //     } else {
            //         var resp = getRowDetails(id, from, row, tr);
            //     }
            // });

            // Sortable rows


        });
    </script>

    <script>
        $(document).ready(function() {
            $("#qhTable").on("click", ".viewer", function(e) {

                $(".se-pre-con1").fadeIn();
                var data = $(this).data('id');
                // alert(data);
                $.post("{{ url('staff/quote_send') }}", {
                    id: data,
                    "_token": "{{ csrf_token() }}"
                }, function(result, status) {

                    $('#contain').html(result);
                    $('#mailModal').modal();
                    $(".se-pre-con1").fadeOut();
                });
                e.preventDefault();
            });

        });

    </script>

    <script>
        $(document).ready(function() {

            $('#btn_deleteAll').click(function() {

                if (confirm("Are you sure you want to delete this?")) {
                    var id = [];

                    $('.dataCheck-hide:checked').each(function(i) {
                        //id[i] = $(this).data(id);
                        id.push($(this).data('id'));
                    });


                    if (id.length === 0) //tell you if the array is empty
                    {
                        alert("Please Select atleast one checkbox");
                    } else {

                        var url = APP_URL + '/staff/delete_oppertunity_product';
                        var op_id = $('#cmsTable').data('id');
                        $.ajax({
                            url: url,
                            method: 'POST',
                            data: {
                                id: id,
                                op_id: op_id
                            },
                            success: function() {
                                for (var i = 0; i < id.length; i++) {
                                    $('#tr_' + id[i] + '').css('background-color', '#ccc');
                                    $('#tr_' + id[i] + '').fadeOut('slow');
                                }
                                $("#select_count").html(" 0 Selected");
                                location.reload(true);
                            }

                        });
                    }

                } else {
                    return false;
                }
            });

            $('#btn_quote').click(function() {

                console.log('buttonclick');

                if ($('#hide_tech').is(":checked")) {
                    var hide_tech = "Y";
                } else {
                    var hide_tech = "N";
                }

                if ($('#show_total').is(":checked")) {
                    var show_total = "Y";
                } else {
                    var show_total = "N";
                }
                
                if ($('#hsn_code').is(":checked")) {
                    var hsn_code = "Y";
                } else {
                    var hsn_code = "N";
                }

                if ($('#ge_healthcare').is(":checked")) {
                    var ge_healthcare = "Y";
                } else {
                    var ge_healthcare = "N";
                }



                var id = [];
                $('.dataCheck-hide:checked').each(function(i) {
                    //id[i] = $(this).data(id);
                    id.push($(this).data('id'));
                });


                if (id.length === 0) //tell you if the array is empty
                {
                    alert("Please Select atleast one product");
                } else {

                    var generate_date = $("#generate_date").val();
                    var url = APP_URL + '/staff/generate_quote';
                    var op_id = $('#cmsTable').data('id');
                    var company_type = $('#companyType').val();
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            id: id,
                            op_id: op_id,
                            hide_tech: hide_tech,
                            show_total: show_total,
                            ge_healthcare: ge_healthcare,
                            hsn_code: hsn_code,
                            generate_date: generate_date,
                            company_type: company_type,
                        },
                        success: function() {
                            /*$('#btn_quote').hide();
                            $('#btn_preview').show();
                            $('#btn_send').show();*/
                            location.reload(true);
                        }

                    });
                }

            });

        });
    </script>

    <script type="text/javascript">
        $(document).on('click', '#select_all', function() {
            $(".dataCheck-hide").prop("checked", this.checked).change();
            $("#select_count").html($("input.dataCheck-hide:checked").length + " Selected");
        });
        // $(document).on('click', '.dataCheck', function() {
        // });
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

    <script>
        $(document).ready(function() {
            $('#contact').multiselect({
                nonSelectedText: 'Select Contact',
                enableFiltering: true,
                enableCaseInsensitiveFiltering: true,
                buttonWidth: '400px'
            });

        });
    </script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script>
        $('#generate_date').datepicker({
            //dateFormat:'yy-mm-dd',
            dateFormat: 'yy-mm-dd',
            //  minDate: 0
        });
    </script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".addProductButton").forEach(button => {

            button.addEventListener("click", function () {
                const k = this.getAttribute("data-k");
                const id = this.getAttribute("data-id");

                $(`.addmain_pdt_${k}`).hide();

                const dropdownContainer = document.getElementById(`productDropdownContainer-${k}`);

                if (dropdownContainer) {
                    console.log(id);

                    dropdownContainer.setAttribute("data-id", id); 
                    dropdownContainer.style.display = dropdownContainer.style.display === "none" ? "block" : "none"; 
                }
                // $('#closeopport').hide();
            });


        });
    });
</script>






    

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

<script type="text/javascript">
    $(document).ready(function() {
        $('#opt_pdt').multiselect();
        $('#cmsTable2').hide();

        // $("#save_btn").click(function() {
        //     var product_id = $("#part_no").val();
        //     var op_id = $("#op_id").val();
        //     //  alert(op_id)
        //     /*
        //       var url = APP_URL+'/admin/get_product_exit_oppertunity';
        //       $.ajax({
        //                   type: "POST",
        //                   cache: false,
        //                   url: url,
        //                   data:{
        //                     product_id: pid,
        //                   },
        //                   success: function (data)
        //                   {  

        //                   }
        //       });*/
        // });
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
            var pid = $(this).val();
            var k =$(this).data('id');
            $(`#opt_pdt-${k}`).find('option').remove();
            $(`#opt_pdt-${k}`).multiselect('rebuild');
            $(`#add_opper_product-${k}`).attr('disabled', 'disabled');
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

                    $(`#product_id-${k}`).text(proObj[0]['name']);

                    var proObj_msp = JSON.parse(res[1]);
                    if (proObj_msp.length > 0) {
                        $(`#msp_error_message-${k}`).hide();
                        $(`#add_opper_product-${k}`).removeAttr('disabled');
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

                            $(`#opt_pdt-${k}`).find('option').not(':first').remove();

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
                                        $(`#opt_pdt-${k}`).html(option);
                                        $(`#opt_pdt-${k}`).multiselect('rebuild');
                                    } else {
                                        $(`#opp-${k}`).hide();
                                    }

                                }
                            });

                        }
                        /* $("#select_count").show();
                         $("#select_count").html(" Amount : "+amount);*/
                        $(`#samt-${k}`).html(htmlscon);
                        $(`#sale_amount-${k}`).val(amount);
                        //alert(amount);
                    } else {
                        $(`#msp_error_message-${k}`).show();
                        $(`#add_opper_product-${k}`).attr('disabled', 'disabled');
                    }

                }
            });
        });

    });
</script>


<script type="text/javascript">

$('#delivery_date_remove').on('input', function(e) {
        let value = $(this).val();
        
        value = value.replace(/[^0-9-]/g, '');

        if (value.length >= 3 && value.length <= 5 && value[2] !== '-') {
            value = value.slice(0, 2) + '-' + value.slice(2);
        } else if (value.length >= 6 && value.length <= 8 && value[5] !== '-') {
            value = value.slice(0, 5) + '-' + value.slice(5);
        }

        $(this).val(value); 
    });


    var arr_product = [];
    var prd_array = [];
    var old_product = [];
    var opt_product = '';
    var main_product = '';


    function add_product(element,k) {
        console.log(k,'first');
        main_product = element;

        var APP_URL = {!! json_encode(url('/')) !!};
        var url = APP_URL + '/staff/get_multiple_product_all_details';
        $(`#cmsTable2-${k}`).show();
        $(`#contractProduct-${k}`).hide();

        var flag = 1;

        var oldproductolds = $(`#addonpdt_${k} input[name="addonproduct[]"]`).map(function () {

            return $(this).val();
        }).get();

        var addedproductolds = $(`#tabledata-${k} input[name="product_id[]"]`).map(function () {

        return $(this).val();
        }).get();

        var product_id = parseInt($(`#part_no-${k}`).val());

        console.log(oldproductolds,'*',addedproductolds,'*' ,product_id ,'check producnt ');

        if (oldproductolds.includes(product_id.toString())) {
          
            alert("This product ID already exists in the selected products.");

            flag=0;

        } 

        if (oldproductolds.includes(product_id.toString())) {
          
          alert("This product ID already exists in the selected products.");

          flag=0;

        } 
        if (addedproductolds.includes(product_id.toString())) {
          
          alert("This product ID already exists in the selected products.");

          flag=0;

        } 
        

        var quantity = parseInt($(`#quantity-${k}`).val());
        var sale_amount = parseInt($(`#sale_amount-${k}`).val());
        var min_sale = parseInt($(`#min_sales_amt-${k}`).val());
        var max_sale = parseInt($(`#max_sales_amt-${k}`).val());
        var op_pdts = $(`#part_no-${k}`).val();
       

        if (product_id == "") {
            $(`#product_id_message-${k}`).show();
        } else {
            $(`#product_id_message-${k}`).hide();
        }

        if (quantity == "") {
            $(`#quantity_message-${k}`).show();
        } else {
            $(`#quantity_message-${k}`).hide();
        }
        if(flag ==0)
        {
            flag = 0;
        }
        else if (sale_amount == 0) {
            flag = 1;
            $(`#sale_message-${k}`).hide();
        } else if (max_sale != 0 && sale_amount > max_sale) //
        {
            //alert("123");
            flag = 0;
            $(`#sale_message-${k}`).show();

        } else if (min_sale != 0 && sale_amount < min_sale) //
        {
            //alert("456");
            flag = 0;
            $(`#sale_message-${k}`).show();
        } else {
            flag = 1;
            $(`#sale_message-${k}`).hide();
        }


        var count_product = $(`#count_product-${k}`).val();
        if (product_id != '') {

            exit_product = findValueInArray(product_id, arr_product);
            // alert(exit_product+'--'+product_id+','+ arr_product)
            if (exit_product == "1") {
                $(`#alreadytexit-${k}`).show();

            } else {
                $(`#alreadytexit-${k}`).hide();
            }
        }
        if (op_pdts != '') {
            for (var i = 0; i < op_pdts.length; i++) {
                exit_product = findValueInArray(op_pdts[i], arr_product);

                if (exit_product == "1") {
                    $(`#alreadytexit-${k}`).show();
                    return false;
                } else {
                    $(`#alreadytexit-${k}`).hide();
                }
            }

        }
        if (product_id != '' && quantity != '' && exit_product != 1 && flag == 1) {
            $(`#user_id-${k}`).attr('disabled', true);
            arr_product.push(product_id);
            //console.log(arr_product);
            if (op_pdts != '') {
                arr_product = arr_product.concat(op_pdts)
            }

            var add_counts = parseInt(count_product) + 1;
            if (op_pdts != '') {
                add_counts = parseInt(add_counts) + op_pdts.length;
            }
            $(`#count_product-${k}`).val(add_counts);

            var prd_array = [];

            prd_array.push(product_id);
            if (op_pdts != '') {
                prd_array = prd_array.concat(op_pdts)
            }

            //  var newArray=$.merge($(prd_array).not(old_product).get(),$(old_product).not(prd_array).get());
            //  console.log(newArray+'----------');

            var count = 0;
            for (var p = 0; p < old_product.length; ++p) {
                if (old_product[p] == product_id)
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
                    $('.noresult').hide();
                    $(`#preview_btn-${k}`).show();
                    $(`#save_btn-${k}`).show();

                    // var res = data.split("1*1");
                    var proObj = JSON.parse(data);
                    // var proObj_msp = JSON.parse(res[1]);
                    if (proObj.length > 0) {
                        sale_amount = proObj[0]["pro_quote_price"];
                        $(`#save_btn-${k}`).show();
                    } else {
                        alert("Please update msp value of this product");
                        $(`#save_btn-${k}`).hide();
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

                        var op_pdts_items = $(`#opt_pdt-${k}`).val();
                        console.log(op_pdts_items)
                        opt_product = 0;
                        if (proObj[i]["product_id"] == $(`#part_no-${k}`).val()) {
                            opt_product = 0;

                        } else {
                            opt_product = 1;

                        }



                        // main_product = $("#part_no").val();




                        if (i != 0) {
                            quantity = 1;
                            // opt_product = 1;


                            sale_amount = proObj[i]["pro_quote_price"];


                            if (sale_amount == "") {
                                sale_amount = 0;
                            }
                        }
                        else {
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
                          <input type="hidden" name="addon_ptd[]" value="${ opt_product }">
                          <input type="hidden" name="main_pdt[]" value="${ main_product }">
                        </tr>`;

                        $(`#tabledata-${k}`).append(htmlscontent);
                        console.log(htmlscontent);
                        console.log(k);

                        //$("#pdfsec").append(pdfsec);

                    }
                }
            });
        }

    }

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


<script> 
   $(document).ready(function () {
    $('.save_btn').on('click', function () {
        const  k = $(this).data('id');

        const opportunityId = $('#oppertunity_id').val();

        const productIds = $(`#tabledata-${k} input[name="product_id[]"]`).map(function () {
            return $(this).val();
        }).get();

        const quantities = $(`#tabledata-${k} input[name="quantity[]"]`).map(function () {
            return $(this).val();
        }).get();
        const saleAmounts = $(`#tabledata-${k} input[name="sale_amount[]"]`).map(function () {
            return $(this).val();
        }).get();
        const taxPercents = $(`#tabledata-${k} input[name="tax_per[]"]`).map(function () {
            return $(this).val();
        }).get(); 
        const companies = $(`#tabledata-${k} input[name="company[]"]`).map(function () {
            return $(this).val();
        }).get(); 
        const addon_ptd = $(`#tabledata-${k} input[name="addon_ptd[]"]`).map(function () {
            return $(this).val();
        }).get(); 
        const mainProducts = $(`#tabledata-${k} input[name="main_pdt[]"]`).map(function () {
            return $(this).val();
        }).get();
      

        const requestData = {
            product_id: productIds,
            oppertunity_id: opportunityId,
            quantity: quantities,
            sale_amount: saleAmounts,
            tax_per: taxPercents,
            company: companies,
            addon_ptd: addon_ptd,
            main_pdt: mainProducts,
        };

        $.ajax({
            url: '{{ route("staff.addon.product_store") }}', 
            type: 'POST',
            data: requestData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            success: function (response) {
                if (response.success) {

                $(`#cmsTable2-${k}`).hide();

                $('.loaded_pdts').remove();
                $(`.addonptds-${k}`).remove();

                $(`#tabledata-${k}`).empty();  
                //$(`#cmsTable2-${k}`).remove();
                // $('#closeopport').show();
                var addonproduct ='';
                console.log(response.data);

                $.each(response.data, function(i, v)
                    {

                        const formattedSaleAmount = parseFloat(v.product_sale_amount).toFixed(2); 

                        const letter = String.fromCharCode(97 + i); 

                        addonproduct += `
                        <tr class="addonptds-${k}" id="loaded_pdts_${i}" >

                            <td>${letter}
                                <input type="hidden" name="addonproduct[]" value="${v.product_id}">
                            </td> 

                            <td>${v.quote_product.name}</td>

                            <td> ${v.product_quantity}</td>

                            <td> ${v.product_tax_percentage}</td>

                            <td>${formattedSaleAmount}</td>

                            <td> 
                                <a class="delete-btn " onclick="deleteOpperPdt('${v.id}',this)" data-id="${i}"  title="Delete">

                                    <img src="{{ asset('images/delete.svg') }}" alt="" />
                                </a>
                            </td>

                        </tr>

                        `;
                    });

                    $(`#proid-${k}`).after(addonproduct);

                } 
            },
            error: function (xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            },
        });
    });
});





function oppClose()
{
        const opportunityId = $('#oppertunity_id').val();

        const productIds = $('input[name="product_id[]"]').map(function () {
            return $(this).val();
        }).get();

        var order_no = $('#order_no').val();

        var order_date = $('#order_date').val();

        var order_recive_date = $('#order_recive_date').val();

        var payment_terms = $('#payment-terms').val();

        var delivery_date_remove = $('#delivery_date_remove').val();

        var warranty = $('#warranty-rerms').val();

        var supplay = $('#supplay').val();

        var flag = 1;
        
        if (productIds.length > 0) {

            flag=0;

           $('#savemessage').text('Save Products Before Submit').show();

        } else {
         
            flag=1;

            $('#savemessage').hide();
        }

        if(order_no == "")
        {
            flag=0;
            $('#order_no_error').text('the field is required').show();
        }
        else
        {
            flag=1;
            $('#order_no_error').hide();
        }

        
        if(order_date == "")
        {
            flag=0;
            $('#order_date_error').text('the field is required').show();
        }
        else
        {
            flag=1;
            $('#order_date_error').hide();
        }
        
        if(order_recive_date == "")
        {
            flag=0;
            $('#order_recive_date_error').text('the field is required').show();
        }
        else
        {
            flag=1;
            $('#order_recive_date_error').hide();
        }

        if(payment_terms == "")
        {
            flag=0;
            $('#payment-terms_error').text('the field is required').show();
        }
        else
        {
            flag=1;
            $('#payment-terms_error').hide();
        }

        if(delivery_date_remove == "")
        {
            flag=0;
            $('#delivery_date_remove_error').text('the field is required').show();
        }
        else
        {
            flag=1;
            $('#delivery_date_remove_error').hide();
        }

        if(warranty == "")
        {
            flag=0;
            $('#warranty-rerms_error').text('the field is required').show();
        }
        else
        {
            flag=1;
            $('#warranty-rerms_error').hide();
        }

        if(supplay == "")
        {
            flag=0;
            $('#supplay_error').text('the field is required').show();
        }
        else
        {
            flag=1;
            $('#supplay_error').hide();
        }
           

       
        if(flag ==1)
        {
            $('#opp_closeform').submit();
        }
       

        // const quantities = $('input[name="quantity[]"]').map(function () {
        //     return $(this).val();
        // }).get();
        // const saleAmounts = $('input[name="sale_amount[]"]').map(function () {
        //     return $(this).val();
        // }).get();
        // const taxPercents = $('input[name="tax_per[]"]').map(function () {
        //     return $(this).val();
        // }).get(); 
        // const companies = $('input[name="company[]"]').map(function () {
        //     return $(this).val();
        // }).get(); 
        // const addon_ptd = $('input[name="addon_ptd[]"]').map(function () {
        //     return $(this).val();
        // }).get(); 
        // const mainProducts = $('input[name="main_pdt[]"]').map(function () {
        //     return $(this).val();
        // }).get();
      

        // const requestData = {
        //     product_id: productIds,
        //     oppertunity_id: opportunityId,
        //     quantity: quantities,
        //     sale_amount: saleAmounts,
        //     tax_per: taxPercents,
        //     company: companies,
        //     addon_ptd: addon_ptd,
        //     main_pdt: mainProducts,
        // };

        // if(flag ==1)
        // {
        //     $.ajax({
        //             url: '{{ route("staff.addon.product_store") }}', 
        //             type: 'POST',
        //             data: requestData,
        //             headers: {
        //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        //             },
        //             success: function (response) {
        //                 if (response.success) {

        //                     $('#opp_closeform').submit();
        //                 } else {
        //                     alert('Failed: ' + response.message);
        //                 }
        //             },
        //             error: function (xhr) {
        //                 alert('Error: ' + xhr.responseJSON.message);
        //             },
        //         });
        // }
}






    </script>

@endsection
 

 

 