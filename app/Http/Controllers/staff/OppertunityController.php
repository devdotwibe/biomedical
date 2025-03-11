<?php
namespace App\Http\Controllers\staff;

use App\Asset;
use App\Brand;
use App\Category;
use App\Chatter;
use App\Company;
use App\Contact_person;
use App\ContractProduct;
use App\CoordinatorPermission;
use App\Country;
use App\Customercategory;
use App\Designation;
use App\District;
use App\Hosdeparment;
use App\Hosdesignation;
use App\Http\Controllers\Controller;
use App\Ib;
use App\Models\OppertunityApproveStatus;
use App\Models\OppertunityOrder;
use App\Models\QuoteOptionalProduct;
use App\Models\QuoteProduct;
use App\Models\TargetPermission;
use App\MsaContract;
use App\Oppertunity;
use App\OppertunityTask;
use App\Oppertunity_product;
use App\Product;
use App\Prospectus;
use App\Quotehistory;
use App\SalesContract;
use App\Staff;
use App\StaffCategory;
use App\State;
use App\Subcategory;
use App\Taluk;
use App\User;
use App\User_permission;
use Carbon\Carbon;
use DataTables;
use DateTime;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use PDF;
use stdClass;
use Validator;
use Illuminate\Support\Facades\Auth;

class OppertunityController extends Controller
{

    public function IND_money_format($number)
    {
        $decimal   = (string) ($number - floor($number));
        $money     = floor($number);
        $length    = strlen($money);
        $delimiter = '';
        $money     = strrev($money);

        for ($i = 0; $i < $length; $i++) {
            if (($i == 3 || ($i > 3 && ($i - 1) % 2 == 0)) && $i != $length) {
                $delimiter .= ',';
            }
            $delimiter .= $money[$i];
        }

        $result  = strrev($delimiter);
        $decimal = preg_replace("/0\./i", ".", $decimal);
        $decimal = substr($decimal, 0, 3);

        if ($decimal != '0') {
            $result = $result . $decimal;
        }

        return $result;
    }

    public function index(Request $request)
    {
        if ($request->type == '') {
            return redirect()->route('staff.dashboard');
        }

        $staff_id = session('STAFF_ID');

        $permission = User_permission::where('staff_id', $staff_id)->first();

        $bio_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'bio')->first();

        $bec_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'bec')->first();

        $techsure_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'techsure')->first();


        $msa_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'msa')->first();

        $msa_states = ! empty(optional($msa_permission)->states) ? explode(',', optional($msa_permission)->states) : [];

        if (optional($permission)->opperbio_view != 'view' && optional($permission)->opperbec_view != 'view' && optional($permission)->oppertechsure_view != 'view' && optional($permission)->oppermsa_view != 'view'

            && optional($bio_permission)->opper_view != 'view' && optional($bec_permission)->opper_view != 'view' && optional($techsure_permission)->opper_view != 'view' &&  optional($msa_permission)->opper_view != 'view') {
            return redirect()->route('staff.dashboard');
        }

        if ($request->ajax()) {

            if ($request->type == '' || $request->type == "bio" || $request->type == "bec" || $request->type == "msa" || $request->type == "techsure") {

                $data = Oppertunity::select('oppertunities.*')
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(order_forcast_category,10)+1,'Unqualified',
                'Not addressable',
                'Open',
                'Upside',
                'Committed w/risk',
                'Committed'),'') as order_forcast_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(support,10)+1,'Demo',
                'Application/ clinical support',
                'Direct company support',
                'Senior Engineer Support',
                'Price deviation'),'') as support_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(deal_stage,10)+1,'Lead Qualified/Key Contact Identified',
                'Customer needs analysis',
                'Clinical and technical presentation/Demo',
                'CPQ(Configure,Price,Quote)',
                'Customer Evaluation',
                'Final Negotiation',
                'Closed-Lost',
                'Closed-Cancel',
                'Closed Won - Implement'),'') as deal_stage_name"))
                    ->addSelect(\DB::raw('(select name from staff where staff.id=oppertunities.staff_id) as staff_name'))
                    ->addSelect(\DB::raw('(select business_name from users where users.id=oppertunities.user_id) as users_business_name'));
                // ->where(function ($qry) use ($staff_id) {
                //     $qry->where('staff_id', $staff_id)->orWhere('coordinator_id', $staff_id);
                // })->where(function ($qry) {
                //     $qry->whereIn('id', Quotehistory::where('approved_status', "Y")->groupBy('oppertunity_id')->pluck('oppertunity_id')->all());
                //     $qry->orWhere('created_at', '>=', Carbon::now()->toDateString());
                //     $qry->orWhere('es_order_date', '>=', Carbon::now()->toDateString());
                // });

                if (! empty($msa_states) && optional($msa_permission)->opper_view == 'view' && $request->type == "msa") {
                    $data->orWhere(function ($query) use ($msa_states, $staff_id) {

                        $query->where('created_by_id', $staff_id)
                            ->orWhere(function ($query) use ($msa_states, $staff_id) {
                                $query->where('created_by_id', '!=', $staff_id)
                                    ->whereIn('state', $msa_states);
                            });
                    });

                }

                if ($request->filter_option == 'open') {
                    $data->where('quote_status', 0)
                        ->where(function ($query) {
                            $query->whereNull('quotehistory_id')
                                ->orWhere('quotehistory_id', '');
                        });
                }

                if ($request->filter_option == 'pending') {
                    $data->whereHas('oppertunityquote', function ($query) {

                        $query->where('approved_status', '!=', 'Y');
                        // ->orwhere('quote_status', 'request');

                    });
                }

                // if ($request->filter_option == 'closed') {
                //     $data->whereNotIn('id', function ($subquery) {

                //         $subquery->select('oppertunity_id')
                            // ->from('quotehistories')
                //             ->where('approved_status', 'Y');
                //     });
                // }

                if ($request->filter_option == 'closed') {
                    $data->whereIn('id', function ($subquery) {
                        $subquery->select('oppertunity_id')
                            ->from('quotehistories')
                            ->where('approved_status', 'Y')
                            ->groupBy('oppertunity_id')
                            ->havingRaw('COUNT(CASE WHEN approved_status != "Y" THEN 1 END) = 0');
                    });
                }

                
                if (! empty($request->state)) {
                    $data->where('state', $request->state);
                }

                if (! empty($request->district)) {
                    $data->where('district', $request->district);
                }

                if (! empty($request->account_name)) {
                    $data->where('user_id', $request->account_name);
                }

                if (! empty($request->engineer)) {
                    $data->where('staff_id', $request->engineer);
                }

                if (! empty($request->start_date)) {
                    $start_date = Carbon::createFromFormat('d-m-Y', $request->start_date)->toDateString();

                    $data->WhereDate('created_at', '>=', $start_date);
                }

                if (! empty($request->end_date)) {
                    $end_date = Carbon::createFromFormat('d-m-Y', $request->end_date)->toDateString();

                    $data->WhereDate('created_at', '<=', $end_date);
                }

                if (! empty($request->brand)) {
                    $brand_id = $request->brand;

                    $data->whereIn('id', function ($subquery) use ($brand_id) {
                        $subquery->select('oppertunity_id')
                            ->from('oppertunity_products')
                            ->whereIn('product_id', function ($subqueryinner) use ($brand_id) {
                                $subqueryinner->select('id')
                                    ->from('products')
                                    ->where('brand_id', $brand_id);
                            });
                    });
                }

                if (! empty($request->category)) {

                    $category_id = $request->category;

                    $data->whereIn('id', function ($subquery) use ($category_id) {
                        $subquery->select('oppertunity_id')
                            ->from('oppertunity_products')
                            ->whereIn('product_id', function ($subqueryinner) use ($category_id) {
                                $subqueryinner->select('id')
                                    ->from('products')
                                    ->where('category_id', $category_id);
                            });
                    });
                }

                if (($request->type ?? "") == "bio") {

                    $data->where('company_type', '5')->where('type', '1');

                    if (optional($permission)->opperbio_view == 'view' || optional($bio_permission)->opper_view == 'view') {

                        if (optional($bio_permission)->opper_view == 'view' && optional($permission)->opperbio_view != 'view') {
                            $data->where(function ($qry) use ($staff_id) {
                                $qry->where('created_by_id', '!=', $staff_id);

                            });
                        } else if (optional($bio_permission)->opper_view != 'view' && optional($permission)->opperbio_view == 'view') {
                            $data->where(function ($qry) use ($staff_id) {
                                $qry->where('created_by_id', $staff_id);

                            });
                        }

                    }
                }

                if (($request->type ?? "") == "bec") {

                    $data->where('company_type', '6')->where('type', '1');

                    if (optional($permission)->opperbec_view == 'view' || optional($bec_permission)->opper_view == 'view') {

                        if (optional($bec_permission)->opper_view == 'view' && optional($permission)->opperbec_view != 'view') {
                            $data->where(function ($qry) use ($staff_id) {
                                $qry->where('created_by_id', '!=', $staff_id);

                            });
                        } else if (optional($bec_permission)->opper_view != 'view' && optional($permission)->opperbec_view == 'view') {
                            $data->where(function ($qry) use ($staff_id) {
                                $qry->where('created_by_id', $staff_id);

                            });
                        }

                    }
                }


                if (($request->type ?? "") == "techsure") {

                    $data->where('company_type', '122')->where('type', '1');

                    if (optional($permission)->oppertechsure_view == 'view' || optional($techsure_permission)->opper_view == 'view') {

                        if (optional($techsure_permission)->opper_view == 'view' && optional($permission)->oppertechsure_view != 'view') {
                            $data->where(function ($qry) use ($staff_id) {
                                $qry->where('created_by_id', '!=', $staff_id);

                            });
                        } else if (optional($techsure_permission)->opper_view != 'view' && optional($permission)->oppertechsure_view == 'view') {
                            $data->where(function ($qry) use ($staff_id) {
                                $qry->where('created_by_id', $staff_id);

                            });
                        }

                    }
                }


                if (($request->type ?? "") == "msa") {

                    $data->where('type', '2');

                    if (optional($permission)->oppermsa_view == 'view' || optional($msa_permission)->opper_view == 'view') {

                        if (optional($msa_permission)->opper_view == 'view' && optional($permission)->oppermsa_view != 'view') {
                            $data->where(function ($qry) use ($staff_id) {
                                $qry->where('created_by_id', '!=', $staff_id);

                            });

                        } else if (optional($msa_permission)->opper_view != 'view' && optional($permission)->oppermsa_view == 'view') {
                            $data->where(function ($qry) use ($staff_id) {
                                $qry->where('created_by_id', $staff_id);

                            });
                        }

                    }
                }

                // if ($staff_id == '35') {
                //     $data->where('state', 4);
                // }

            }
            if ($request->type == "hotProspects") {
                $data = Oppertunity::select('oppertunities.*')
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(order_forcast_category,10)+1,'Unqualified',
                'Not addressable',
                'Open',
                'Upside',
                'Committed w/risk',
                'Committed'),'') as order_forcast_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(support,10)+1,'Demo',
                'Application/ clinical support',
                'Direct company support',
                'Senior Engineer Support',
                'Price deviation'),'') as support_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(deal_stage,10)+1,'Lead Qualified/Key Contact Identified',
                'Customer needs analysis',
                'Clinical and technical presentation/Demo',
                'CPQ(Configure,Price,Quote)',
                'Customer Evaluation',
                'Final Negotiation',
                'Closed-Lost',
                'Closed-Cancel',
                'Closed Won - Implement'),'') as deal_stage_name"))
                    ->addSelect(\DB::raw('(select name from staff where staff.id=oppertunities.staff_id) as staff_name'))
                    ->addSelect(\DB::raw('(select business_name from users where users.id=oppertunities.user_id) as users_business_name'));
                $data->where('es_order_date', '>=', Carbon::today()->toDateString());
                $data->where(function ($qry) use ($staff_id) {
                    $qry->where('staff_id', $staff_id)->orWhere('coordinator_id', $staff_id);
                });
                $data->where('deal_stage', '!=', 6);
                $data->where('deal_stage', '!=', 7);
                $data->where('deal_stage', '!=', 8);
                $data->where('es_order_date', '<=', Carbon::now()->addDays(7)->toDateString());

            }

            if ($request->type == "newProspects") {
                $data = Oppertunity::select('oppertunities.*')
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(order_forcast_category,10)+1,'Unqualified',
                'Not addressable',
                'Open',
                'Upside',
                'Committed w/risk',
                'Committed'),'') as order_forcast_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(support,10)+1,'Demo',
                'Application/ clinical support',
                'Direct company support',
                'Senior Engineer Support',
                'Price deviation'),'') as support_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(deal_stage,10)+1,'Lead Qualified/Key Contact Identified',
                'Customer needs analysis',
                'Clinical and technical presentation/Demo',
                'CPQ(Configure,Price,Quote)',
                'Customer Evaluation',
                'Final Negotiation',
                'Closed-Lost',
                'Closed-Cancel',
                'Closed Won - Implement'),'') as deal_stage_name"))
                    ->addSelect(\DB::raw('(select name from staff where staff.id=oppertunities.staff_id) as staff_name'))
                    ->addSelect(\DB::raw('(select business_name from users where users.id=oppertunities.user_id) as users_business_name'));
                $data->where('created_at', '>=', Carbon::now()->subDays(7)->toDateString());
                $data->where(function ($qry) use ($staff_id) {
                    $qry->where('staff_id', $staff_id)->orWhere('coordinator_id', $staff_id);
                });

            }

            if ($request->type == "closedProspects") {
                $data = Oppertunity::select('oppertunities.*')
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(order_forcast_category,10)+1,'Unqualified',
                'Not addressable',
                'Open',
                'Upside',
                'Committed w/risk',
                'Committed'),'') as order_forcast_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(support,10)+1,'Demo',
                'Application/ clinical support',
                'Direct company support',
                'Senior Engineer Support',
                'Price deviation'),'') as support_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(deal_stage,10)+1,'Lead Qualified/Key Contact Identified',
                'Customer needs analysis',
                'Clinical and technical presentation/Demo',
                'CPQ(Configure,Price,Quote)',
                'Customer Evaluation',
                'Final Negotiation',
                'Closed-Lost',
                'Closed-Cancel',
                'Closed Won - Implement'),'') as deal_stage_name"))
                    ->addSelect(\DB::raw('(select name from staff where staff.id=oppertunities.staff_id) as staff_name'))
                    ->addSelect(\DB::raw('(select business_name from users where users.id=oppertunities.user_id) as users_business_name'));
                $data->where('created_at', '>=', Carbon::now()->subDays(14)->toDateString());
                $data->where(function ($qry) use ($staff_id) {
                    $qry->where('staff_id', $staff_id)->orWhere('coordinator_id', $staff_id);
                });
                $data->where('created_at', '<=', Carbon::today()->toDateString());
                $data->where('deal_stage', '=', 8);

            }

            if ($request->type == "otherProspects") {
                $data = Oppertunity::select('oppertunities.*')
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(order_forcast_category,10)+1,'Unqualified',
                'Not addressable',
                'Open',
                'Upside',
                'Committed w/risk',
                'Committed'),'') as order_forcast_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(support,10)+1,'Demo',
                'Application/ clinical support',
                'Direct company support',
                'Senior Engineer Support',
                'Price deviation'),'') as support_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(deal_stage,10)+1,'Lead Qualified/Key Contact Identified',
                'Customer needs analysis',
                'Clinical and technical presentation/Demo',
                'CPQ(Configure,Price,Quote)',
                'Customer Evaluation',
                'Final Negotiation',
                'Closed-Lost',
                'Closed-Cancel',
                'Closed Won - Implement'),'') as deal_stage_name"))
                    ->addSelect(\DB::raw('(select name from staff where staff.id=oppertunities.staff_id) as staff_name'))
                    ->addSelect(\DB::raw('(select business_name from users where users.id=oppertunities.user_id) as users_business_name'));
                $data->where('created_at', '<=', Carbon::now()->subDays(14)->toDateString());
                $data->where(function ($qry) use ($staff_id) {
                    $qry->where('staff_id', $staff_id)->orWhere('coordinator_id', $staff_id);
                });

            }

            if ($request->type == "staleProspects") {
                $data = Oppertunity::select('oppertunities.*')
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(order_forcast_category,10)+1,'Unqualified',
                'Not addressable',
                'Open',
                'Upside',
                'Committed w/risk',
                'Committed'),'') as order_forcast_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(support,10)+1,'Demo',
                'Application/ clinical support',
                'Direct company support',
                'Senior Engineer Support',
                'Price deviation'),'') as support_name"))
                    ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(deal_stage,10)+1,'Lead Qualified/Key Contact Identified',
                'Customer needs analysis',
                'Clinical and technical presentation/Demo',
                'CPQ(Configure,Price,Quote)',
                'Customer Evaluation',
                'Final Negotiation',
                'Closed-Lost',
                'Closed-Cancel',
                'Closed Won - Implement'),'') as deal_stage_name"))
                    ->addSelect(\DB::raw('(select name from staff where staff.id=oppertunities.staff_id) as staff_name'))
                    ->addSelect(\DB::raw('(select business_name from users where users.id=oppertunities.user_id) as users_business_name'));
                $data->where('es_order_date', '<', Carbon::today()->toDateString());
                $data->where(function ($qry) use ($staff_id) {
                    $qry->where('staff_id', $staff_id)->orWhere('coordinator_id', $staff_id);
                });
                $data->where('deal_stage', '!=', 6);
                $data->where('deal_stage', '!=', 7);
                $data->where('deal_stage', '!=', 8);

            }

            if ($request->type != "") {
                $types = $request->type;
            } else {
                $types = "";
            }
            return Datatables::of($data)

                ->addColumn('op_reference_no', function ($data) {
                    if (! empty($data->op_reference_no)) {
                        return $data->op_reference_no;
                    } else {
                        return '';
                    }

                })
                ->addColumn('oppertunity_name', function ($data) {
                    if (! empty($data->oppertunity_name)) {
                        return $buttons = '<span  class="viewer"  >' . $data->oppertunity_name . '</span>';
                    } else {
                    }

                })
                ->addColumn('business_name', function ($data) {
                    if (! empty($data->customer->business_name)) {

                        $custome_name = $data->customer->business_name;

                        $customer_district = $data->customer->userdistrict->name;

                        return $buttons = '<span  class="hoslink">' .$custome_name .' ('. $customer_district .')'.' </span>';

                    } else {
                        return '';
                    }
                })
                ->addColumn('staff_name', function ($data) {
                    if (! empty($data->staff->name)) {
                        return $data->staff->name;
                    } else {
                        return '';
                    }

                })

                ->addColumn('amount', function ($data) {
                    if (! empty($data->amount)) {
                        return $this->IND_money_format($data->amount);
                    } else {
                        return '';
                    }
                })

                ->addColumn('deal_stage', function ($data) {

                    $deal_stage = [
                        'Lead Qualified/Key Contact Identified',
                        'Customer needs analysis',
                        'Clinical and technical presentation/Demo',
                        'CPQ(Configure,Price,Quote)',
                        'Customer Evaluation',
                        'Final Negotiation',
                        'Closed-Lost',
                        'Closed-Cancel',
                        'Closed Won - Implement',
                    ];

                    if ($data->deal_stage != '') {
                        return $deal_stage[$data->deal_stage];
                    } else {
                        return '';
                    }
                })

                ->addColumn('es_order_date', function ($data) {
                    if (! empty($data->es_order_date)) {
                        return date('d-m-Y', strtotime($data->es_order_date));
                    } else {
                        return '';
                    }

                })

                ->addColumn('es_sales_date', function ($data) {

                    if (! empty($data->es_sales_date)) {
                        return date('d-m-Y', strtotime($data->es_sales_date));

                    } else {
                        return '';
                    }

                })

                ->addColumn('order_forcast', function ($data) {
                    $order_forcast = [
                        'Unqualified',
                        'Not addressable',
                        'Open',
                        'Upside',
                        'Committed w/risk',
                        'Committed',
                    ];

                    if ($data->order_forcast_category != '') {
                        return $order_forcast[$data->order_forcast_category];
                    } else {
                        return '';
                    }

                })

                ->addColumn('support', function ($data) {

                    $support = [
                        'Demo',
                        'Application/ clinical support',
                        'Direct company support',
                        'Senior Engineer Support',
                        'Price deviation',
                        "-",
                    ];
                    if ($data->support != '') {
                        return $support[$data->support];
                    } else {
                        return '';
                    }

                })

                ->addColumn('quote_reference_no', function ($data) {

                    return "";
                })

                ->addColumn('type', function ($data) {

                    if (! empty($data->type)) {
                        if ($data->type == 1) {
                            return 'Sales';
                        } else {
                            return 'Contract';
                        }
                    } else {
                        return '';
                    }

                })->filterColumn('users_business_name', function ($data, $keyword) {
                $data->whereHas('customer', function ($qry) use ($keyword) {
                    $qry->where('business_name', "%$keyword%");
                });

            })

                ->filterColumn('quote_reference_no', function ($data, $keyword) {

                    $data->whereIn('id', function ($query) use ($keyword) {
                        $query->select('oppertunity_id')
                            ->from('quotehistories')
                            ->orwhere('quote_reference_no', 'like', '%' . $keyword . '%');
                    });

                })

                ->filterColumn('staff_name', function ($data, $keyword) {
                    $data->whereHas('staff', function ($qry) use ($keyword) {
                        $qry->where('name', "%$keyword%");
                    });
                })

                ->addColumn('action', function ($data) use ($types, $staff_id, $permission, $bio_permission, $bec_permission,$techsure_permission, $msa_permission) {
                    $button = '';

                    $edit = 0;

                    $delete = 0;

                    if ($types == "bio") {

                        if ($data->created_by_id == $staff_id && optional($permission)->opperbio_edit == 'edit') {
                            $edit = 1;
                        } elseif ($data->created_by_id != $staff_id && optional($bio_permission)->opper_edit == 'edit') {
                            $edit = 1;
                        }

                        if ($data->created_by_id == $staff_id && optional($permission)->opperbio_delete == 'delete') {
                            $delete = 1;
                        } elseif ($data->created_by_id != $staff_id && optional($bio_permission)->opper_delete == 'delete') {
                            $delete = 1;
                        }

                    }

                    if ($types == "bec") {

                        if ($data->created_by_id == $staff_id && optional($permission)->opperbec_edit == 'edit') {
                            $edit = 1;
                        } elseif ($data->created_by_id != $staff_id && optional($bec_permission)->opper_edit == 'edit') {
                            $edit = 1;
                        }

                        if ($data->created_by_id == $staff_id && optional($permission)->opperbec_delete == 'delete') {
                            $delete = 1;
                        } elseif ($data->created_by_id != $staff_id && optional($bec_permission)->opper_delete == 'delete') {
                            $delete = 1;
                        }

                    }



                    if ($types == "techsure") {

                        if ($data->created_by_id == $staff_id && optional($permission)->oppertechsure_edit == 'edit') {
                            $edit = 1;
                        } elseif ($data->created_by_id != $staff_id && optional($techsure_permission)->opper_edit == 'edit') {
                            $edit = 1;
                        }

                        if ($data->created_by_id == $staff_id && optional($permission)->oppertechsure_delete == 'delete') {
                            $delete = 1;
                        } elseif ($data->created_by_id != $staff_id && optional($techsure_permission)->opper_delete == 'delete') {
                            $delete = 1;
                        }

                    }


                    if ($types == "msa") {

                        if ($data->created_by_id == $staff_id && optional($permission)->oppermsa_edit == 'edit') {
                            $edit = 1;
                        } elseif ($data->created_by_id != $staff_id && optional($msa_permission)->opper_edit == 'edit') {
                            $edit = 1;
                        }

                        if ($data->created_by_id == $staff_id && optional($permission)->oppermsa_delete == 'delete') {
                            $delete = 1;
                        } elseif ($data->created_by_id != $staff_id && optional($msa_permission)->opper_delete == 'delete') {
                            $delete = 1;
                        }

                    }

                    if ($edit == 1) {

                        if (! $data->oppertunityOppertunityProduct->isEmpty()) {

                            $button .= '<a class="sml-btn-line view_products view-btn view_expand table-icon" title="View Products" data-href="#' . $data->id . '" attr-view-product="' . $data->id . '"><i class="fa fa-eye" aria-hidden="true"></i></a>';
                        }

                        // if ($data->deal_stage < 6) {

                        $button .= '<a href="' . url('staff/list_oppertunity_products/' . $data->id) . '" class="sml-btn-line submit-btn add_prod table-icon" title="Add Products"><i class="fa fa-plus" aria-hidden="true"></i></a>';

                        if ($staff_id == "32" || $staff_id == "35" || $staff_id == "121" || $staff_id == $data->staff_id) {
                            $button .= '<a class="sml-btn-line btn-xs table-icon" href="' . route('staff.edit_oppertunity', "$data->id") . '" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>' .

                            '<a class="text-secondary btn-xs table-icon" onclick="cloneOpurtunity(' . $data->id . ')" title="Clone"><i class="fa fa-clone" aria-hidden="true"></i></span></a>';
                        }

                        // }
                    }

                    if ($delete == 1) {

                        if ($data->close_status != "closed") {
                            $button .= '<a class="delete-btn   deleteItem_opper" data-id="' . $data->id . '" id="deleteItem' . $data->id . '" data-tr="tr_' . $data->id . '" title="Delete"><img src="' . asset('images/delete.svg') . '"></a>';
                        }

                    }

                    if ($data->close_status == 'closed') {

                        $button .= '<div> <span style="color:red" > Closed </span></div>';

                    }

                    return $button;

                    //  if(!$data->oppertunityOppertunityProduct->isEmpty()){
                    //  $button='<a href="'.url('staff/list_oppertunity_products/'.$data->id).'" class="sml-btn-line submit-btn add_prod" title="Add Products">+</a>
                    //   <a class="btn btn-primary view_products view-btn view_expand" data-href="#'.$data->id.'" attr-view-product="'.$data->id.'"><i class="fa fa-eye" aria-hidden="true"></i></a>
                    //  <a class="btn btn-primary btn-xs" href="'.route('staff.edit_oppertunity',"$data->id").'?type='.$types.'" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                    //  ';
                    //  }
                    //  else{
                    //     $button='
                    //     <a href="'.url('staff/list_oppertunity_products/'.$data->id).'" class="sml-btn-line submit-btn add_prod" title="Add Products">+</a>
                    //     <a class="btn btn-primary btn-xs" href="'.route('staff.edit_oppertunity',"$data->id").'?type='.$types.'" title="Edit"><span class="glyphicon glyphicon-pencil"></span></a>
                    //    ';
                    //  }

                    // return $button;
                })
                ->addColumn('created_by', function ($data) {
                    if (! empty($data->created_by_name)) {
                        return $data->created_by_name;
                    } else {
                        return '';
                    }

                })
                ->addColumn('created_time', function ($data) {

                    if (! empty($data->created_at)) {

                        return carbon::parse($data->created_at)->format('d-m-Y H:i:s');
                    } else {

                        return '';
                    }
                })
                ->addColumn('updated_at', function ($data) {

                    if (! empty($data->updated_at)) {
                        return carbon::parse($data->updated_at)->format('d-m-Y H:i:s');
                    } else {
                        return '';
                    }
                })

                ->rawColumns(['action', 'op_reference_no', 'business_name', 'oppertunity_name', 'staff_name', 'amount', 'deal_stage', 'es_order_date', 'es_sales_date', 'order_forcast', 'support', 'type', 'created_by', 'created_time', 'updated_at'])
            //->addIndexColumn()->make(true);

                ->addIndexColumn()

                ->setRowId('id')

                ->setRowAttr([
                    'data-user_id' => '{{ $user_id }}',
                ])
                ->setRowClass(function ($data) {
                    if (count($data->oppertunityOppertunityProduct) == 0) {

                        if ($data->deal_stage == 6 || $data->deal_stage == 7 || $data->deal_stage == 8) {
                            $class = 'black';
                        } else {
                            $class = 'orange';
                        }
                    } else {
                        $date1        = date('Y-m-d', strtotime($data->oppertunityOppertunityProduct[0]->created_at));
                        $date2        = date("Y-m-d");
                        $diff         = strtotime($date2) - strtotime($date1);
                        $date         = new DateTime($date1);
                        $week_date    = $date->format("W");
                        $date_cur     = new DateTime($date2);
                        $current_week = $date_cur->format("W");
                        // return $week_date.'-'.$current_week;
                        if ($week_date != $current_week) {
                            $chatter_det = Chatter::where('oppertunity_id', $data->id)->get();
                            if (count($chatter_det) == 0) {
                                if ($data->deal_stage == 6 || $data->deal_stage == 7 || $data->deal_stage == 8) {
                                    $class = 'black';
                                } else {
                                    $class = 'red';
                                }

                            } else {
                                $class = 'black';
                            }
                        } else {
                            $class = 'black';
                        }

                    }

                    return $class;
                })

                ->make(true);

        }

        $permission = User_permission::where('staff_id', $staff_id)->first();

        $opper = Oppertunity::select('oppertunities.*')
            ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(order_forcast_category,10)+1,'Unqualified',
                'Not addressable',
                'Open',
                'Upside',
                'Committed w/risk',
                'Committed'),'') as order_forcast_name"))
            ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(support,10)+1,'Demo',
                'Application/ clinical support',
                'Direct company support',
                'Senior Engineer Support',
                'Price deviation'),'') as support_name"))
            ->addSelect(\DB::raw("IFNULL(ELT(IFNULL(deal_stage,10)+1,'Lead Qualified/Key Contact Identified',
                'Customer needs analysis',
                'Clinical and technical presentation/Demo',
                'CPQ(Configure,Price,Quote)',
                'Customer Evaluation',
                'Final Negotiation',
                'Closed-Lost',
                'Closed-Cancel',
                'Closed Won - Implement'),'') as deal_stage_name"))
            ->addSelect(\DB::raw('(select name from staff where staff.id=oppertunities.staff_id) as staff_name'))
            ->addSelect(\DB::raw('(select business_name from users where users.id=oppertunities.user_id) as users_business_name'));

        // $opper->where(function ($qry) use ($staff_id) {

        //     $qry->where('staff_id', $staff_id)->orWhere('coordinator_id', $staff_id);

        // });
        if (optional($permission)->oppertechsure_view == 'view' || optional($techsure_permission)->opper_view == 'view') {

            if (optional($techsure_permission)->opper_view == 'view' && optional($permission)->oppertechsure_view != 'view') {
                $opper->where(function ($qry) use ($staff_id) {
                    $qry->where('created_by_id', '!=', $staff_id);

                });
            } else if (optional($techsure_permission)->opper_view != 'view' && optional($permission)->oppertechsure_view == 'view') {
                $opper->where(function ($qry) use ($staff_id) {
                    $qry->where('created_by_id', $staff_id);

                });
            }

        }

        $opper->where(function ($qry) {
            $qry->whereIn('id', Quotehistory::where('approved_status', "Y")->groupBy('oppertunity_id')->pluck('oppertunity_id')->all());
            $qry->orWhere('created_at', '>=', Carbon::now()->toDateString());
            $qry->orWhere('es_order_date', '>=', Carbon::now()->toDateString());
        });

        $stateIds = $opper->pluck('state')->unique()->toArray();

        $districtIds = $opper->pluck('district')->unique()->toArray();

        $userIds = $opper->pluck('staff_id')->unique()->toArray();

        $oppIds = $opper->pluck('id')->unique()->toArray();

        $productIds = Oppertunity_product::whereIn('oppertunity_id', $oppIds)->pluck('product_id')->unique()->toArray();

        $brandIds = Product::whereIn('id', $productIds)->pluck('brand_id')->unique()->toArray();

        $categoryIds = Product::whereIn('id', $productIds)->pluck('category_id')->unique()->toArray();

        $states = State::whereIn('id', $stateIds)->orderBy('name', 'asc')->get();

        $districts = District::whereIn('id', $districtIds)->orderBy('name', 'asc')->get();

        $engineers = Staff::whereIn('id', $userIds)->orderBy('name', 'asc')->get();

        $brands = Brand::whereIn('id', $brandIds)->orderBy('name', 'asc')->get();

        $categorys = Category::whereIn('id', $categoryIds)->orderBy('name', 'asc')->get();

        return view('staff.oppertunity.index', compact('permission', 'states', 'districts', 'engineers', 'brands', 'categorys'));
    }


    public function create()
    {
        $staff_id = session('STAFF_ID');
 

        if (isset($_GET['id'])) {
            $service = $_GET['id'];
        } else {
            $service = '0';
        }
        //  echo $type;
        // die('sssss');
        $user = User::all();

        $product = Product::all();

        $staff = Staff::all();

        $state = State::all();

        $permission = User_permission::where('staff_id', $staff_id)->first();

        $opprbio_create = optional($permission)->opperbio_create;
        $opprbec_create = optional($permission)->opperbec_create;
        $opprtechsure_create = optional($permission)->oppertechsure_create;


        if ($opprbio_create != 'create') {
            $company = Company::where("status", "Y")
                ->where('id', '!=', 5)
                ->orderBy('name', 'asc')->get();
        } elseif ($opprbec_create != 'create') {
            $company = Company::where("status", "Y")
                ->where('id', '!=', 6)
                ->orderBy('name', 'asc')->get();
        }
        else {
            $company = Company::where("status", "Y")->orderBy('name', 'asc')->get();
        }

        $district = DB::table('district')
            ->orderBy('name', 'asc')
            ->get();
        $op_ref = "";
        $limit  = 100000;
        $cnt    = 1;
        do {
            $op_ref = 'op' . sprintf("%0" . (6 + $cnt) . "d", rand(000000, $limit * pow(10, $cnt)));
            $cnt++;
        } while (Oppertunity::where("op_reference_no", $op_ref)->count() > 0);

        return view('staff.oppertunity.create', ['company' => $company, 'customer' => $user, 'product' => $product, 'staff' => $staff, 'state' => $state, 'district' => $district, 'permission' => $permission], compact('op_ref', 'service'));

    }

    public function insert(Request $request)
    {
        $rules = [

            'account_name' => 'required',

            // 'engineer_name' => 'required',

            // 'deal_stage' => 'required',
            // "coordinator_id" => "different:engineer_name",

            // 'sales_date' => 'required',

            //'amount'        => 'required|numeric',

            //'support'       => 'required',

            'type'         => 'required',

            // 'start_date' => 'required_if:type,2',
            // 'end_date' => 'required_if:type,2',

            // 'op_ref' => 'required',

            'state'        => 'required',

            'district'     => 'required',

        ];
        // if (($request->type ?? 0) == 1) {
        //     $rules['company_type'] = 'required';
        // }

        $validation = Validator::make($request->all(), $rules, ["coordinator_id.different" => "The coordinator name and engineer name must be different."]);

        // if ($request->service_type != 'Warranty') {
        //     $validation->after(function ($validation) use ($request) {

        //         if ($request->order_date > $request->sales_date) {

        //             $validation->errors()->add('order_date', 'Es.order date should be less than es.sales date');
        //         }
        //     });
        // }

        if ($validation->fails()) {

            // return $validation->errors()->all();

            return redirect()->back()->withErrors($validation->errors())->withInput();

        }

        $id        = session('ADMIN_ID');
        $type_name = '';
        if ($request->type == 1) {
            $type_name = 'Sales';
        } else {
            $type_name = 'Contract';
        }

        $company_type = '';
        if ($request->company_type == 6) {
            $company_type = 'BEC';
        }

        elseif($request->company_type == 122){
            $company_type = 'TECHSURE';

        }
         else {
            $company_type = 'BIO';
        }

        $user_name = User::where('id', $request->account_name)->withTrashed()->first();

        $oppertunity = new Oppertunity;

        if ($request->type != 1) {
            $oppertunity->contract_start_date = $request->start_date;
            $oppertunity->contract_end_date   = $request->end_date;
            $oppertunity->company_type        = 5;
        } else {
            $oppertunity->company_type = $request->company_type;
        }

        $oppertunity->oppertunity_name = $user_name->business_name . '--' . $type_name . '--' . ($request->type == 2 ? $request->service_type : $company_type);

        if (! empty($request->coordinator_id)) {
            $oppertunity->coordinator_id = $request->coordinator_id;
        }
        $oppertunity->user_id = $request->account_name;

        $oppertunity->staff_id = $request->engineer_name;

        $oppertunity->amount = $request->amount;

        $oppertunity->deal_stage = $request->deal_stage;

        if ($request->deal_stage == 8) {
            $oppertunity->won_date = Carbon::now();
        }

        $order_date = Carbon::parse($request->order_date)->format('Y-m-d');

        $sales_date = Carbon::parse($request->sales_date)->format('Y-m-d');

        $oppertunity->es_order_date = $order_date;

        $oppertunity->es_sales_date = $sales_date;

        $oppertunity->dt = date('Y-m-d');

        $oppertunity->description = $request->description;

        $oppertunity->order_forcast_category = $request->order_forcast;

        $oppertunity->support = $request->support;

        $oppertunity->type = $request->type;

        $oppertunity->service_type = $request->service_type;

        $oppertunity->op_reference_no = $request->op_ref;

        $oppertunity->state = $request->state;

        $oppertunity->district = $request->district;

        $oppertunity->service_id = $request->service_id;

        $staff_id                   = session('STAFF_ID');
        $oppertunity->created_by_id = $staff_id;

        $oppertunity->created_by_name = DB::table('staff')->where('id', $staff_id)->first()->name;

        $oppertunity->save();

        $request->session()->flash('success', 'Opportunity added Successfully');

        if ($request->type == 1) {
            if ($request->company_type == 6) {

                return redirect()->route('staff.list_oppertunity', ['type' => 'bec']);
            } 
            elseif($request->company_type == 122){
                return redirect()->route('staff.list_oppertunity', ['type' => 'techsure']);
            }
            else {
                return redirect()->route('staff.list_oppertunity', ['type' => 'bio']);
            }

        }

        if ($request->type == 2) {
            return redirect()->route('staff.list_oppertunity', ['type' => 'msa']);

        }

        return redirect('staff/list_oppertunity');
    }

    public function edit($id)
    {

        $user = User::all();

        $product = Product::all();

        $staff = Staff::all();

        $oppertunity = Oppertunity::find($id);

        $state = State::all();
        if (isset($_GET['histroy_id'])) {
            $histroy_id = $_GET['histroy_id'];
            $quote_his  = Quotehistory::where('id', $histroy_id)->first();
            if (strpos($quote_his->product_id, ',') !== false) {
                $product_arr = explode(',', $quote_his->product_id);
            } else {
                $product_arr[] = $quote_his->product_id;
            }
        } else {
            $product_arr = [];
        }

        //$district     = District::all();
        $district = DB::table('district')
            ->orderBy('name', 'asc')
            ->get();

        $pdt        = Oppertunity_product::with('oppertunityProduct')->where('oppertunity_id', $id)->get();
        $quote_list = Quotehistory::where('oppertunity_id', $id)->get();

        $company = Company::where("status", "Y")->orderBy('name', 'asc')->get();
        return view('staff.oppertunity.edit', ['company' => $company, 'product_arr' => $product_arr, 'quote_list' => $quote_list, 'customer' => $user, 'product' => $product, 'staff' => $staff, 'opp' => $oppertunity, 'op_pdt' => $pdt, 'id' => $id, 'state' => $state, 'district' => $district]);

    }

    public function oppertunityClone(Request $request)
    {
        $validation = Validator::make($request->all(), [
            "id" => 'required',
        ]);

        if ($validation->fails()) {
            throw ValidationException::withMessages($validation->errors()->all());
        }

        $op_ref = "";
        $limit  = 100000;
        $cnt    = 1;
        do {
            $op_ref = 'op' . sprintf("%0" . (6 + $cnt) . "d", rand(000000, $limit * pow(10, $cnt)));
            $cnt++;
        } while (Oppertunity::where("op_reference_no", $op_ref)->count() > 0);

        $oppertunity = Oppertunity::find($request->id);

        $newopp                  = $oppertunity->replicate();
        $newopp->created_at      = Carbon::now();
        $newopp->won_parent      = $oppertunity->id;
        $newopp->op_reference_no = $op_ref;
        $newopp->save();
        foreach (Oppertunity_product::where('oppertunity_id', $request->id)->get() as $oppro) {
            $newoppro                 = $oppro->replicate();
            $newoppro->created_at     = Carbon::now();
            $newoppro->oppertunity_id = $newopp->id;
            $newoppro->save();
        }

        foreach (Quotehistory::where('oppertunity_id', $request->id)->get() as $quote) {
            $newquote                 = $quote->replicate();
            $newquote->created_at     = Carbon::now();
            $newquote->oppertunity_id = $newopp->id;
            $newquote->save();
            foreach (QuoteProduct::where('oppertunity_id', $request->id)->where('quote_id', $quote->id)->get() as $quotepro) {

                $newquotepro                 = $quotepro->replicate();
                $newquotepro->created_at     = Carbon::now();
                $newquotepro->oppertunity_id = $newopp->id;
                $newquotepro->quote_id       = $newquote->id;
                $newquotepro->status         = "Won";
                $newquotepro->save();

                foreach (QuoteOptionalProduct::where('oppertunity_id', $request->id)->where('quote_id', $quote->id)->where('quote_products_id', $quotepro->id)->get() as $quoteopro) {

                    $newquoteopro                    = $quoteopro->replicate();
                    $newquoteopro->created_at        = Carbon::now();
                    $newquoteopro->oppertunity_id    = $newopp->id;
                    $newquoteopro->quote_id          = $newquote->id;
                    $newquoteopro->quote_products_id = $newquotepro->id;
                    $newquoteopro->status            = "Won";
                    $newquoteopro->save();

                }
            }
        }

        $request->session()->flash('success', 'Opportunity Clone Successfully');

        return redirect('staff/list_oppertunity');
    }
    public function quotewonupdate(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "quote" => 'required',
        ]);
        $validation->after(function ($validation) use ($request) {

            if (! is_array($request->quote)) {
                $validation->errors()->add('quote', 'Quote is required');
            }
        });
        if ($validation->fails()) {
            throw ValidationException::withMessages($validation->errors()->all());
        }
        $oppertunity        = Oppertunity::find($id);
        $wonlist            = [];
        $wonoptlist         = [];
        $wonquotelist       = [];
        $wonquoteprolist    = [];
        $wonquoteoptprolist = [];
        foreach ($request->quote as $qte) {
            $pid    = "product_id_$qte";
            $amount = "product_amount_$qte";

            if (isset($request->$pid) && isset($request->$amount)) {
                foreach ($request->$amount as $i => $iamount) {
                    if (isset($request->$pid[$i])) {
                        array_push($wonlist, [
                            "id"                  => $qte . "_" . $request->$pid[$i],
                            "quote_id"            => $qte,
                            "product_id"          => $request->$pid[$i],
                            "product_sale_amount" => $request->$amount[$i],
                        ]);
                        array_push($wonquotelist, $qte);
                        if (! isset($wonquoteprolist[$qte])) {
                            $wonquoteprolist[$qte] = [];
                        }
                        $wonquoteprolist[$qte][] = $request->$pid[$i];
                        $optpid                  = $pid . "_opt_" . $request->$pid[$i];
                        $optamount               = $amount . "_opt_" . $request->$pid[$i];
                        if (isset($request->$optpid)) {
                            $wonquoteoptprolist[$qte . '_opt_' . $request->$pid[$i]] = [];
                            foreach ($request->$optamount as $j => $jamount) {
                                if (isset($request->$optpid[$j])) {
                                    array_push($wonoptlist, [
                                        "id"                  => $qte . "_" . $request->$pid[$i] . "_" . $request->$optpid[$j],
                                        "quote_id"            => $qte,
                                        "product_id"          => $request->$pid[$i],
                                        "product_opt_id"      => $request->$optpid[$j],
                                        "product_sale_amount" => $request->$optamount[$j],
                                    ]);
                                    $wonquoteoptprolist[$qte . '_opt_' . $request->$pid[$i]][] = $request->$optpid[$j];
                                }
                            }
                        }

                    }
                }
            }

        }
        if (empty($wonquotelist)) {
            throw ValidationException::withMessages(["product", "Product and amount required"]);
        } else {
            $newopp             = $oppertunity->replicate();
            $newopp->created_at = Carbon::now();
            $newopp->won_parent = $oppertunity->id;
            $newopp->deal_stage = 8;
            $newopp->save();
            foreach (Oppertunity_product::where('oppertunity_id', $id)->get() as $oppro) {
                $newoppro                 = $oppro->replicate();
                $newoppro->created_at     = Carbon::now();
                $newoppro->oppertunity_id = $newopp->id;
                $newoppro->save();
            }

            foreach (Quotehistory::where('oppertunity_id', $id)->whereIn('id', $wonquotelist)->get() as $quote) {
                $newquote                 = $quote->replicate();
                $newquote->created_at     = Carbon::now();
                $newquote->oppertunity_id = $newopp->id;
                $newquote->save();
                foreach (QuoteProduct::where('oppertunity_id', $id)->where('quote_id', $quote->id)->whereIn('id', $wonquoteprolist[$quote->id])->get() as $quotepro) {
                    $key                              = array_search($quote->id . "_" . $quotepro->id, array_column($wonlist, 'id'));
                    $newquotepro                      = $quotepro->replicate();
                    $newquotepro->created_at          = Carbon::now();
                    $newquotepro->oppertunity_id      = $newopp->id;
                    $newquotepro->quote_id            = $newquote->id;
                    $newquotepro->product_sale_amount = isset($wonlist[$key]) ? $wonlist[$key]['product_sale_amount'] : $quotepro->product_sale_amount;
                    $newquotepro->status              = "Won";
                    $newquotepro->save();

                    if (! empty($wonquoteoptprolist[$quote->id . "_opt_" . $quotepro->id])) {
                        foreach (QuoteOptionalProduct::where('oppertunity_id', $id)->where('quote_id', $quote->id)->where('quote_products_id', $quotepro->id)->whereIn('id', $wonquoteoptprolist[$quote->id . "_opt_" . $quotepro->id])->get() as $quoteopro) {
                            $key                             = array_search($quote->id . "_" . $quotepro->id . "_" . $quoteopro->id, array_column($wonlist, 'id'));
                            $newquoteopro                    = $quoteopro->replicate();
                            $newquoteopro->created_at        = Carbon::now();
                            $newquoteopro->oppertunity_id    = $newopp->id;
                            $newquoteopro->quote_id          = $newquote->id;
                            $newquoteopro->quote_products_id = $newquotepro->id;
                            $newquoteopro->sale_amount       = isset($wonoptlist[$key]) ? $wonoptlist[$key]['product_sale_amount'] : $quoteopro->sale_amount;
                            $newquoteopro->status            = "Won";
                            $newquoteopro->save();
                        }
                    }
                }
            }
        }
        $request->session()->flash('success', 'Opportunity updated Successfully');

        return redirect('staff/list_oppertunity');
    }

    public function wonCloseOppertunity(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "order_no"       => 'required',
            "order_date"     => 'required',
            "payment_term"   => 'required',
            "delivery_date"  => 'required',
            "warrenty_terms" => 'required',
            "supplay"        => 'required',
        ]);

        $quote_id = $request->fetch_id;

        if ($validation->fails()) {
            return response()->json($validation->errors()->all());
        }

        $oppertunity             = Oppertunity::find($id);
        $oppertunity->deal_stage = 8;
        $oppertunity->won_date   = Carbon::now();

        $oppertunity->close_status = "quote_closed";

        $oppertunity->quote_close_id = $quote_id;

        $oppertunity->save();

        $quote_history = Quotehistory::find($quote_id);

        $quote_history->close_status = 'Y';

        $quote_history->save();

        $order                    = new OppertunityOrder;
        $order->order_no          = $request->order_no;
        $order->order_date        = $request->order_date;
        $order->order_recive_date = $request->order_recive_date;
        $order->payment_term      = $request->payment_term;
        $order->delivery_date     = $request->delivery_date;
        $order->warrenty_terms    = $request->warrenty_terms;
        $order->supplay           = $request->supplay;
        $order->remark            = $request->remark;
        $order->oppertunity_id    = $oppertunity->id;
        $order->save();

        $salescontract = new SalesContract;

        $salescontract->oppertunity_id = $oppertunity->id;

        $salescontract->contract_status = 'pending';

        $salescontract->company_type = $oppertunity->company_type;

        $salescontract->opp_order_id = $order->id;

        $salescontract->quote_id = $quote_history->id;

        $salescontract->in_ref_no = 'sa' . rand(1000, 100000);

        $salescontract->save();

        $request->session()->flash('success', 'Opportunity Updated Successfully');
        return redirect('staff/list_oppertunity');
    }

    public function addon_product_store(Request $request)
    {

        $id = $request->oppertunity_id;

        $opportunity_product = "";

        $total_amount = "";

        $addonproducts = "";

        if (! empty($request->product_id) && count($request->product_id) > 0) {

            foreach ($request->product_id as $key => $pdt) {
                $quantity       = $request->quantity[$key] ?? 0;
                $sale_amount    = $request->sale_amount[$key] ?? 0;
                $tax_percentage = $request->tax_per[$key] ?? 0;

                if ($quantity == 0 || $sale_amount == 0) {
                    continue;
                }

                $base_amount  = floatval($sale_amount) * floatval($quantity);
                $total_amount = round($base_amount + ($base_amount * floatval($tax_percentage) / 100), 2);

                $opportunity_product                         = new QuoteProduct();
                $opportunity_product->product_id             = $pdt;
                $opportunity_product->product_quantity       = $quantity;
                $opportunity_product->product_sale_amount    = $sale_amount;
                $opportunity_product->amount                 = $total_amount;
                $opportunity_product->product_tax_percentage = floatval($tax_percentage);
                // $opportunity_product->company_id = $request->company[$key] ?? null;
                $opportunity_product->addon_ptd      = 1;
                $opportunity_product->oppertunity_id = $id;

                $product = Product::find($pdt);
                if ($product) {
                    $opportunity_product->part_no = $product->part_no;
                }

                $opportunity_product->main_product_id = $request->main_pdt[$key] ?? null;

                $opportunity_product->save();
            }

            $addonproducts = QuoteProduct::with('quoteProduct')->where('main_product_id', $opportunity_product->main_product_id)->where('addon_ptd', 1)->get();

        }

        $total_amount = DB::table("oppertunity_products")
            ->where([['oppertunity_id', $id], ['addon_ptd', 0]])
            ->sum('amount');

        return response()->json(['success' => true, 'data' => $addonproducts, 'message' => 'Add-on product added successfully.']);

    }

    public function lossCloseOppertunity(Request $request, $id)
    {
        $validation = Validator::make($request->all(), [
            "lost_brand"      => 'required',
            "loast_equipemnt" => 'required',
        ]);

        if ($validation->fails()) {
            return response()->json($validation->errors()->all());
        }
        $oppertunity                  = Oppertunity::find($id);
        $oppertunity->deal_stage      = 6;
        $oppertunity->lost_brand      = $request->lost_brand;
        $oppertunity->loast_equipemnt = $request->loast_equipemnt;
        $oppertunity->reason          = isset($request->reason) ? $request->reason : "";
        $oppertunity->won_date        = Carbon::now();
        $oppertunity->save();

        $request->session()->flash('success', 'Opportunity Updated Successfully');
        return redirect('staff/list_oppertunity');
    }

    public function cancellCloseOppertunity(Request $request, $id)
    {
        $oppertunity             = Oppertunity::find($id);
        $oppertunity->deal_stage = 7;
        $oppertunity->won_date   = Carbon::now();
        $oppertunity->reason     = isset($request->reason) ? $request->reason : "";
        $oppertunity->save();
        $request->session()->flash('success', 'Opportunity Updated Successfully');
        return redirect('staff/list_oppertunity');
    }

    public function update(Request $request, $id)
    {
        $rules = [

            'op_name'        => 'required',

            'account_name'   => 'required',

            'engineer_name'  => 'required',

            "coordinator_id" => "different:engineer_name",

            'deal_stage'     => 'required',

            'order_date'     => 'required',

            'sales_date'     => 'required',

            'order_forcast'  => 'required',

            //'support'       => 'required',

            //'amount'        => 'required|numeric',

            'type'           => 'required',

            'op_ref'         => 'required',

            // 'state'          => 'required',

            //'district'       => 'required',

        ];

        $validation = Validator::make($request->all(), $rules, ["coordinator_id.different" => "The coordinator name and engineer name must be different."]);

        $validation->after(function ($validation) use ($request) {

            if ($request->order_date > $request->sales_date) {

                $validation->errors()->add('order_date', 'Es.order date should be less than es.sales date');

            }

        });

        if ($validation->fails()) {

            // return $validation->errors()->all();

            return redirect()->back()->withErrors($validation->errors());

        }

        $oppertunity = Oppertunity::find($id);

        $oppertunity->oppertunity_name = $request->op_name;

        $oppertunity->user_id = $request->account_name;

        $oppertunity->staff_id = $request->engineer_name;

        $oppertunity->district = $request->district;

        $oppertunity->state = $request->state;
        if ($oppertunity->type == 1) {

            $oppertunity->company_type = $request->company_type;

        }

        if (! empty($request->coordinator_id)) {
            $oppertunity->coordinator_id = $request->coordinator_id;
        }
        $oppertunity->amount = $request->amount;

        $oppertunity->deal_stage = $request->deal_stage;
        if ($request->deal_stage == 8) {
            $oppertunity->won_date = Carbon::now();
        }

        $oppertunity->es_order_date = date('Y:m:d', strtotime($request->order_date));

        $oppertunity->es_sales_date = date('Y:m:d', strtotime($request->sales_date));

        $oppertunity->dt = date('Y-m-d');

        $oppertunity->description = $request->description;

        $oppertunity->order_forcast_category = $request->order_forcast;

        $oppertunity->support = $request->support;

        // $oppertunity->type = $request->type;

        $oppertunity->op_reference_no = $request->op_ref;

        // $oppertunity->state                 = $request->state;

        // $oppertunity->district              = $request->district;

        $oppertunity->save();

        $request->session()->flash('success', 'Opportunity updated Successfully');

        return redirect('staff/list_oppertunity?type=' . $request->page_type);

    }

    public function delete_oppertunity(Request $request)
    {
        $ids = $request->id;
        Oppertunity::destroy($ids);

        Oppertunity_product::where('oppertunity_id', $ids)->delete();

        return response(['success' => true]);
        //$request->session()->flash('success', 'Opportunity deleted Successfully');
        // return redirect()->back();
    }

    public function delete(Request $request)
    {

        $ids = $request->id;

        foreach ($ids as $key => $id) {

            Oppertunity::destroy($id);

        }

        $request->session()->flash('success', 'Opportunity deleted Successfully');

        return redirect('staff/list_oppertunity');

    }

    public function oppertunity_product_detail(Request $request)
    {

        $data1 = json_decode($request->input('data'));

        $op_id = $data1[id];

    }

    public function list_products($id)
    {
        $opper = Oppertunity::find($id);

        $staff_id = session('STAFF_ID');

        $staff_permission = false;

        $permission = User_permission::where('staff_id', $staff_id)->first();

        $bio_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'bio')->first();

        $bec_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'bec')->first();

        $techsure_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'techsure')->first();


        $msa_permission = CoordinatorPermission::where('staff_id', $staff_id)->where('type', 'msa')->first();

        $falg = false;

        if ($staff_id == $opper->created_by_id && $opper->company_type == '5' && optional($permission)->opperbio_edit != 'edit') {
            $falg = true;

        } elseif ($staff_id != $opper->created_by_id && $opper->company_type == '5' && optional($bio_permission)->opper_edit != 'edit') {
            $falg = true;

        } elseif ($staff_id == $opper->created_by_id && $opper->company_type == '6' && optional($permission)->opperbec_edit != 'edit') {
            $falg = true;

        } elseif ($staff_id != $opper->created_by_id && $opper->company_type == '6' && optional($bec_permission)->opper_edit != 'edit') {
            $falg = true;

        } elseif ($staff_id == $opper->created_by_id && $opper->company_type == '122' && optional($permission)->oppertechsure_edit != 'edit') {
            $falg = true;

        } elseif ($staff_id != $opper->created_by_id && $opper->company_type == '122' && optional($techsure_permission)->opper_edit != 'edit') {
            $falg = true;

        }elseif ($staff_id == $opper->created_by_id && $opper->company_type == '6' && $opper->type == '2' && optional($permission)->oppermsa_edit != 'edit') {
            $falg = true;

        } elseif ($staff_id != $opper->created_by_id && $opper->type == '2' && optional($msa_permission)->opper_edit != 'edit') {
            $falg = true;

        } else {
            $falg = false;
        }

        if ($falg) {
            return redirect()->route('staff.dashboard');
        }

        $qh = Quotehistory::where('oppertunity_id', $id)->orderBy('id', 'desc')->get();

        $list_products = Oppertunity_product::where('oppertunity_id', $id)->whereNull('main_product_id')->

            orderBy('id', 'asc')->get();

        $quote_products = QuoteProduct::where('oppertunity_id', $id)->whereNull('main_product_id')->

            orderBy('id', 'asc')->get();

        $op_name = Oppertunity::where('id', $id)->first();

        $product = Product::orderBy('name', 'asc')->get();

        $staff_permission = true;

        return view('staff.oppertunity.list_products', ['staff_permission' => $staff_permission, 'products' => $list_products, 'quote_products' => $quote_products, 'product' => $product, 'id' => $id, 'op_name' => $op_name, 'qhistory' => $qh]);

    }
 
    public function preview_products(Request $request)
    {
        $id = $request->opper_id;

        $quote_id = $request->quote_id;

        // $qh = Quotehistory::find($quote_id);

        // $product_ids = $qh->product_id;

        // $products = explode(',', $product_ids);

        $main_pdt_single_collection = collect();

        $list_products = QuoteProduct::with('quoteProduct')->where('quote_id', $quote_id)->where('oppertunity_id', $id)->whereNull('main_product_id')->orderBy('id', 'asc')->get();

        foreach ($list_products as $item) {

            $product_details = new \stdClass();

            $product_details->main_product = $item;

            $optional_pdts = QuoteProduct::with('quoteProduct')->where('main_product_id', $item->id)->orderBy('id', 'asc')->get();

            $optional_products_array = [];

            foreach ($optional_pdts as $optional_pdt) {

                $optional_products_array[] = $optional_pdt;
            }

            $product_details->optional_product = $optional_products_array;

            $main_pdt_single_collection->push($product_details);
        }

        $main_products_collection = collect();

        $main_products_collection->push($main_pdt_single_collection);

        return response()->json(['products' => $main_products_collection]);
    }

    public function get_quote_product(Request $request)
    {
        // $qh = Quotehistory::find($request->quote_id);

        // $product_ids = $qh->product_id;

        // $products = explode(',', $product_ids);

        $oppertunity_product = QuoteProduct::where('quote_id', $request->quote_id)->get();

        return response()->json($oppertunity_product);

    }

    public function delete_opp_product(Request $request)
    {
        $opp_pdt = QuoteProduct::find($request->prodct_id);
        $opp_pdt->delete();

        return response()->json(['success' => 'oppertunity product deleted successfully']);
    }

    public function update_product_status(Request $request)
    {

        if (! empty($request->id) && ! empty($request->type)) {
            $oppertunity_pro = Oppertunity_product::findorFail($request->id);

            if (! empty($oppertunity_pro)) {
                $oppertunity_pro->type = $request->type;

                $oppertunity_pro->save();

                return response()->json(['success' => 'Product Type Updated']);

            } else {
                return response()->json(['error' => 'Failed to Update']);
            }
        } else {
            return response()->json(['error' => 'Failed to Update']);
        }
    }

    public function add_product($id)
    {
        $asset = 0;

        $oppertunity = Oppertunity::where('id', $id)->first();

        $productDetails = Oppertunity_product::where('oppertunity_id', $id)->get();

        if ($oppertunity->type == 2) {

            $serialIds = [];

            foreach ($productDetails as $opp) {
                $serialIds[] = $opp->oppertunityProductIb->equipment_serial_no;
            }

            $product = Ib::has('ibProduct')->with('ibProduct', 'ibEquipmentStatus')
                ->whereHas('ibEquipmentStatus', function ($query) {
                    $query->whereNotNull('name');
                })
                ->whereHas('ibProduct', function ($query) {
                    $query->whereNotNull('name');
                })
                ->whereNotIn('equipment_serial_no', $serialIds)

                ->where('user_id', $oppertunity->user_id)->orderBy('warrenty_end_date', 'ASC')->get();
            $asset = 1;
            return view('staff.oppertunity.add_op_contract', ['products' => $product, 'oppertunity' => $oppertunity, 'asset' => $asset, 'id' => $id]);

        } else { 

            $brands = Brand::orderBy('name', 'asc')->get();
            $product = Product::orderBy('name', 'asc')->get();
            return view('staff.oppertunity.add_product', ['products' => $product, 'oppertunity' => $oppertunity, 'asset' => $asset, 'brands' => $brands,]);
        }

    }

    public function getProductsByBrand(Request $request)
    {
        $products = Product::where('brand_id', $request->brand_id)->orderBy('name', 'asc')->get();
        return response()->json(['products' => $products]);
    }
    
       
    public function oppertunityContractProduct(Request $request)
    {

        $productDetails = Oppertunity_product::where('oppertunity_id', $request->oppertunity_id)->get();

        $serialIds = [];

        foreach ($productDetails as $opp) {
            $serialIds[] = $opp->oppertunityProductIb->equipment_serial_no;
        }

        $products = Ib::has('ibProduct')->with('ibProduct', 'ibEquipmentStatus')->whereIn('id', $request->product)
            ->whereNotIn('equipment_serial_no', $serialIds)->get();

        return response()->json(['products' => $products]);
    }

 

    public function oppertunityContractListProduct(Request $request)
    {

        $productDetails = Oppertunity_product::where('oppertunity_id', $request->oppertunity_id)->get();

        $serialIds = [];

        foreach ($productDetails as $opp) {
            $serialIds[] = $opp->oppertunityProductIb->equipment_serial_no;
        }

        if (!empty($request->product)) {
            $products_list = Ib::with('ibProduct', 'ibEquipmentStatus')
            ->whereHas('ibProduct', function ($query) {
                $query->whereNotNull('name');
            })
                ->whereHas('ibEquipmentStatus', function ($query) {
                    $query->whereNotNull('name');
                })
                ->where('user_id', $request->oppertunity_user_id)
                ->whereNotIn('id', $request->product)
                ->whereNotIn('equipment_serial_no', $serialIds)->orderBy('warrenty_end_date', 'ASC')->get();
        } else {
            $products_list = Ib::with('ibProduct', 'ibEquipmentStatus')->whereHas('ibProduct', function ($query) {
                $query->whereNotNull('name');
            })
                ->whereHas('ibEquipmentStatus', function ($query) {
                    $query->whereNotNull('name');
                })
                ->where('user_id', $request->oppertunity_user_id)
                ->whereNotIn('equipment_serial_no', $serialIds)->orderBy('warrenty_end_date', 'ASC')->get();

        }

        return response()->json(['products_list' => $products_list]);
    }





    public function oppertunityContractProductStore(Request $request)
    {
        //echo $request->oppertunity_id;
        //print_r($request->ib_id);
        $products_exit = Oppertunity_product::where('oppertunity_id', $request->oppertunity_id)->whereIn('product_id', $request->ib_id)->get();
        $staff_id = session('STAFF_ID');

        if (count($products_exit) > 0) {
            return redirect()->back()->with('error_message', 'Product already exit.')

                ->withInput();

        }

        if (sizeof($request->ib_id) > 0) {
            foreach ($request->ib_id as $key => $pdt) {

                // echo $request->total[$key];
                // echo $request->amount[$key];

                $oppertunity_pdt = new Oppertunity_product;

                $oppertunity_pdt->product_id = $request->product_ids[$key];

                $oppertunity_pdt->quantity = 1;
                $oppertunity_pdt->optional = 0;

                $oppertunity_pdt->amount = $request->amount[$key];

                $oppertunity_pdt->sale_amount = $request->amount[$key];

                $oppertunity_pdt->company_id = 5;

                $oppertunity_pdt->oppertunity_id = $request->oppertunity_id;

                $oppertunity_pdt->pm = $request->pm[$key];

                $oppertunity_pdt->cr = $request->cr[$key];

                if($staff_id == 35){
                $oppertunity_pdt->oldprice = $request->oldprice[$key];

                $oppertunity_pdt->hike = $request->hike[$key];


                }
               
                $oppertunity_pdt->single_amount = $request->amount[$key];

                $oppertunity_pdt->ib_id = $pdt;

                $oppertunity_pdt->start_date = date('Y-m-d', strtotime($request->start_date[$key]));
                $oppertunity_pdt->end_date = date('Y-m-d', strtotime($request->end_date[$key]));
                                      


                $oppertunity_pdt->save();

            }
        }
        $opp_id = $request->oppertunity_id;
        return redirect('staff/list_oppertunity_products/' . $opp_id);
    }

    public function insert_product(Request $request, $id)
    {

        // echo '222';exit;

        $products_exit = DB::select("select * from oppertunity_products where `oppertunity_id`='" . $id . "' AND  `product_id`='" . $request->product_id[0] . "'");

        if (count($products_exit) > 0) {

            return redirect()->back()->with('error_message', 'Product already exit.')

                ->withInput();

        }

        if (sizeof($request->product_id) > 0) {

            $quantity        = $request->quantity;
            $amount          = $request->amount;
            $sale_amount     = $request->sale_amount;
            $company_id      = $request->company;
            $optional        = $request->optional;
            $main_product_id = $request->main_pdt;

            foreach ($request->product_id as $key => $pdt) {

                $product = Product::find($pdt);

                $oppertunity_pdt              = new Oppertunity_product;
                $oppertunity_pdt->product_id  = $pdt;
                $oppertunity_pdt->quantity    = $quantity[$key];
                $oppertunity_pdt->sale_amount = $sale_amount[$key];

                // $oppertunity_pdt->amount = $amount[$key];
                $base_amount             = floatval($sale_amount[$key]) * floatval($quantity[$key]);
                $oppertunity_pdt->amount = $base_amount;

                $oppertunity_pdt->tax_percentage = $request->tax_per[$key];
                $tax_percentage                  = floatval($oppertunity_pdt->tax_percentage);
                $oppertunity_pdt->amount         = round(($base_amount + ($base_amount * $tax_percentage / 100)), 2);

                if (! empty($product)) {
                    $oppertunity_pdt->part_no = $product->part_no;

                    // $msp=Msp::where('product_id',$pdt)->orderBy('id','DESC')->first();
                    // if(!empty($msp)){
                    //     $oppertunity_pdt->tax=$msp->tax_per;
                    // }
                }
                $oppertunity_pdt->brand_id = $product->brand_id;

                $oppertunity_pdt->company_id = $company_id[$key];

                $oppertunity_pdt->optional = $optional[$key];

                $oppertunity_pdt->oppertunity_id = $id;

                if ($optional[$key] != 0) {

                    $oppertunity_pdt->main_product_id = $main_product_id[$key];

                }

                $oppertunity_pdt->save();

            }

            $tot_amt = DB::table("oppertunity_products")

                ->SelectRaw('sum(amount) as tot_amt')

                ->where([['oppertunity_id', $id], ['optional', 0]])

                ->get();

            $oppertunity = Oppertunity::where('id', $id)->first();

            $oppertunity->quote_status = 0;

            $oppertunity->quote_send_status = 'request';

            $oppertunity->amount = ($tot_amt[0]->tot_amt != '') ? $tot_amt[0]->tot_amt : 0;

            $oppertunity->save();

        }

        return redirect('staff/list_oppertunity_products/' . $id);

    }

    public function viewOppertunityProductModal(Request $request)
    {

        // $result = Oppertunity_product::where('oppertunity_id',$request->product_id)->pluck('product_id');
        // $productsResult = Product::whereIn('id',$result)->get();

        // return response()->json([ 'productsResult' => $productsResult ]);

        $result              = Oppertunity_product::where('oppertunity_id', $request->product_id)->pluck('product_id');
        $productsResult      = Product::whereIn('id', $result)->get();
        $oppertunity_product = Oppertunity_product::where('oppertunity_id', $request->product_id)->get();
        $chatter             = Chatter::where('oppertunity_id', $request->product_id)->get();
        $data                = [];
        $data['chatter']     = Chatter::where('oppertunity_id', $request->product_id)->get();
        // $data['productsResult']    =$productsResult;
        $data['oppertunity_product'] = Oppertunity_product::where('oppertunity_id', $request->product_id)->get();
        $data['oppertunity']         = Oppertunity::find($request->product_id);
        $data['oppertunity_order']   = OppertunityOrder::where('oppertunity_id', $request->product_id)->get();
        return view('staff.oppertunity.product_and_chatter_detail', $data);

    }

    public function delete_product(Request $request)
    {

        $ids = $request->id;

        foreach ($ids as $key => $id) {

            $pdt = Oppertunity_product::find($id);

            $opt_pdt = Oppertunity_product::where([['main_product_id', $pdt->product_id], ['oppertunity_id', $pdt->oppertunity_id], ['optional', 1]])->get();

            if (sizeof($opt_pdt) > 0) {

                foreach ($opt_pdt as $opdt) {

                    Oppertunity_product::destroy($opdt->id);

                }

            }

            Oppertunity_product::destroy($id);

        }

        $tot_amt = DB::table("oppertunity_products")

            ->SelectRaw('sum(amount) as tot_amt')

            ->where([['oppertunity_id', $id], ['optional', 0]])

            ->get();

        $op_id = $request->op_id;

        $oppertunity = Oppertunity::where('id', $op_id)->first();

        $oppertunity->quote_status = 0;

        $oppertunity->quote_send_status = 'request';

        $oppertunity->amount = ($tot_amt[0]->tot_amt != '') ? $tot_amt[0]->tot_amt : 0;

        $oppertunity->save();

        $request->session()->flash('success', 'Product deleted Successfully');

        //return redirect('staff/list_oppertunity_products/'.$request->op_id);

    }


    public function delete_oppertunity_eachproduct($pd_id, $op_id)
    {

        $id = $pd_id;

        /*    foreach ($ids as $key => $id)

        {*/

        $pdt = Oppertunity_product::find($id);

        $opt_pdt = Oppertunity_product::where([['main_product_id', $pdt->product_id], ['oppertunity_id', $pdt->oppertunity_id], ['optional', 1]])->get();

        if (sizeof($opt_pdt) > 0) {

            foreach ($opt_pdt as $opdt) {

                Oppertunity_product::destroy($opdt->id);

            }

        }

        Oppertunity_product::destroy($id);

        //}

        $tot_amt = DB::table("oppertunity_products")

            ->SelectRaw('sum(amount) as tot_amt')

            ->where([['oppertunity_id', $id], ['optional', 0]])

            ->get();

        $op_id = $op_id;

        $oppertunity = Oppertunity::where('id', $op_id)->first();

        $oppertunity->quote_status = 0;

        $oppertunity->quote_send_status = 'request';

        $oppertunity->amount = ($tot_amt[0]->tot_amt != '') ? $tot_amt[0]->tot_amt : 0;

        $oppertunity->save();

        //$request->session()->flash('success', 'Product deleted Successfully');

        return redirect('staff/list_oppertunity_products/' . $op_id);

    }




    public function generate_quote(Request $request)
    {
        // if(session('STAFF_ID') ==56)
        // {
        //     dd($request);
        // }

        $ids = $request->id;

        $op_id = $request->op_id;

        $company_ar = [];

        $pdt = DB::table("oppertunity_products")->where('oppertunity_id', $op_id)->get();

        if (sizeof($pdt) > 0) {

            foreach ($pdt as $pt) {

                $company_ar[] = $pt->company_id;

            }

        }

        //
        $quoteAmount = 0;
        if (count($company_ar) > 0) {

            $cmp_unq = array_unique($company_ar);

            foreach ($cmp_unq as $cmp) {

                $prod = DB::table("oppertunity_products")->where([['company_id', $cmp], ['oppertunity_id', $op_id]])->whereIn('id', $ids)->get();

                $pid = [];

                $op_pdt_id = [];

                if (sizeof($prod) > 0) {

                    foreach ($prod as $pd) {
                        $productDetails = Oppertunity_product::where('oppertunity_id', $op_id)->where('id', $pd->id)->first();
                        $pid[]          = $pd->product_id;
                        $quoteAmount    = $quoteAmount + $productDetails->amount;
                        $op_pdt_id[]    = $pd->id;
                    }

                    // $rand = 'qt' . mt_rand(000000, 999999);

                    // $customer = Oppertunity::find($op_id)->customer;

                    // $business_name = $customer ? $customer->business_name : ''; 

                    $quote_last_no = Quotehistory::latest()->first()->last_no;

                    if(empty($quote_last_no))
                    {
                        $quote_last_no =01;
                    }
                    else
                    {
                        $quote_last_no++;
                    }
                    $formatted_last_no = sprintf('%04d', $quote_last_no);

                    $now_year = date('Y');

                    $now_month = date('m');

                    $quote_no = 'Qtn' . $now_year . $now_month . $formatted_last_no;

                    $pd_id = implode(',', $pid);

                    $op_pdt_ids = implode(',', $op_pdt_id);

                    $qh = new Quotehistory;

                    $qh->oppertunity_id = $op_id;

                    $qh->product_id = $pd_id;

                    $qh->history_status = 'new';

                    $qh->opper_product_id = $op_pdt_ids;

                    $qh->quote_reference_no = $quote_no;

                    $qh->hide_tech = $request->hide_tech;

                    $qh->hsn_code = $request->hsn_code;

                    $qh->show_total = $request->show_total;

                    $qh->last_no = $quote_last_no;

                    $qh->ge_healthcare = $request->ge_healthcare;


                    $qh->generate_date = $request->generate_date;

                    $qh->company_type = $request->company_type;

                    $qh->quote_amount = $quoteAmount;

                    $qh->save();

                    // ->where('optional', "!=", 1)

                    foreach (Oppertunity_product::where('oppertunity_id', $op_id)->whereIn('id', $op_pdt_id)->get() as $oputunityproduct) {
                        try {
                            $productDetail = Product::find($oputunityproduct->product_id);

                            $quoteproduct                        = new QuoteProduct;
                            $quoteproduct->quote_id              = $qh->id;
                            $quoteproduct->oppertunity_id        = $op_id;
                            $quoteproduct->product_id            = $oputunityproduct->product_id;
                            $quoteproduct->product_name          = $productDetail->name;
                            $quoteproduct->product_category_id   = $productDetail->category_id;
                            $quoteproduct->product_brand_id      = $productDetail->brand_id;
                            $quoteproduct->product_company_id    = $productDetail->company_id;
                            $quoteproduct->product_category_name = optional($productDetail)->category_name;
                            $quoteproduct->product_company_name  = $productDetail->company_name;
                            $quoteproduct->product_brand_name    = $productDetail->brand_name;
                            $quoteproduct->product_hsn_code      = $productDetail->hsn_code;
                            $quoteproduct->product_item_code     = $productDetail->item_code;
                            // $quoteproduct->product_tax_percentage = $productDetail->tax_percentage;
                            $quoteproduct->product_category_type_id = $productDetail->category_type_id;
                            $quoteproduct->product_image_name       = $productDetail->image_name;

                            $quoteproduct->amount = $productDetail->amount;

                            $quoteproduct->main_product_id = $productDetail->main_product_id;

                            $quoteproduct->addon_ptd = $productDetail->addon_ptd;
                            $quoteproduct->part_no   = $productDetail->part_no;

                            $quoteproduct->product_quantity       = $oputunityproduct->quantity;
                            $quoteproduct->product_sale_amount    = $oputunityproduct->sale_amount;
                            $quoteproduct->product_tax_percentage = $oputunityproduct->tax_percentage;

                            $quoteproduct->start_date    = $oputunityproduct->start_date;
                            $quoteproduct->end_date    = $oputunityproduct->end_date;

                            $quoteproduct->product_unit_price          = $productDetail->unit_price;
                            $quoteproduct->product_image_name1         = $productDetail->image_name1;
                            $quoteproduct->product_warrenty            = $productDetail->warrenty;
                            $quoteproduct->product_unit                = $productDetail->unit;
                            $quoteproduct->product_validity            = $productDetail->validity;
                            $quoteproduct->product_related_product     = $productDetail->related_product;
                            $quoteproduct->product_competition_product = $productDetail->competition_product;
                            $quoteproduct->status                      = "Created";
                            $quoteproduct->save();

                            foreach (Oppertunity_product::where('main_product_id', $oputunityproduct->product_id)->where('oppertunity_id', $oputunityproduct->oppertunity_id)->where('optional', 1)->get() as $optionalProduct) {
                                $oproductDetail = Product::find($optionalProduct->product_id);

                                $quoteoptproduct                    = new QuoteOptionalProduct;
                                $quoteoptproduct->quote_id          = $qh->id;
                                $quoteoptproduct->quote_products_id = $quoteproduct->id;
                                $quoteoptproduct->oppertunity_id    = $op_id;
                                $quoteoptproduct->product_id        = $optionalProduct->product_id;
                                $quoteoptproduct->name              = $oproductDetail->name;
                                $quoteoptproduct->category_id       = $oproductDetail->category_id;
                                $quoteoptproduct->brand_id          = $oproductDetail->brand_id;
                                $quoteoptproduct->company_id        = $oproductDetail->company_id;
                                $quoteoptproduct->category_name     = optional($oproductDetail)->category_name;
                                $quoteoptproduct->company_name      = $oproductDetail->company_name;
                                $quoteoptproduct->brand_name        = $oproductDetail->brand_name;
                                $quoteoptproduct->hsn_code          = $oproductDetail->hsn_code;
                                $quoteoptproduct->item_code         = $oproductDetail->item_code;
                                // $quoteoptproduct->tax_percentage = $oproductDetail->tax_percentage;
                                $quoteoptproduct->category_type_id = $oproductDetail->category_type_id;
                                $quoteoptproduct->image_name       = $oproductDetail->image_name;
                                $quoteoptproduct->unit_price       = $oproductDetail->unit_price;

                                $quoteoptproduct->quantity       = $optionalProduct->quantity;
                                $quoteoptproduct->sale_amount    = $optionalProduct->sale_amount;
                                $quoteoptproduct->tax_percentage = $optionalProduct->tax_percentage;

                                $quoteoptproduct->start_date    = $optionalProduct->start_date;
                                $quoteoptproduct->end_date    = $optionalProduct->end_date;

                                $quoteoptproduct->unit_price          = $oproductDetail->unit_price;
                                $quoteoptproduct->image_name1         = $oproductDetail->image_name1;
                                $quoteoptproduct->warrenty            = $oproductDetail->warrenty;
                                $quoteoptproduct->unit                = $oproductDetail->unit;
                                $quoteoptproduct->validity            = $oproductDetail->validity;
                                $quoteoptproduct->related_product     = $oproductDetail->related_product;
                                $quoteoptproduct->competition_product = $oproductDetail->competition_product;
                                $quoteoptproduct->status              = "Created";
                                $quoteoptproduct->save();
                            }
                        } catch (\Throwable $th) {
                            //throw $th;
                        }

                    }

                }

            }

            $op = Oppertunity::find($op_id);

            $op->quote_status = 1;

            $op->quote_products = implode(',', $request->id);

            $op->quotehistory_id = $qh->id;

            $op->save();

        }

        $request->session()->flash('success', 'Quote Generated Successfully');
        // return redirect('staff/list_oppertunity_products/'.$op_id);

    }

    public function edit_product($pd_id, $op_id)
    {

        $product = Product::all();

        $op_name = Oppertunity::where('id', $op_id)->first();

        $pdt = Oppertunity_product::find($pd_id);

        if ($op_name->type == 2) {
            return view('staff.oppertunity.edit_contract_product', ['products' => $product, 'op_name' => $op_name, 'pdt' => $pdt]);
        } else {
            return view('staff.oppertunity.edit_product', ['products' => $product, 'op_name' => $op_name, 'pdt' => $pdt]);
        }

    }

    public function update_product(Request $request, $pd_id, $op_id)
    {
        $staff_id = session('STAFF_ID');

        $oppertunity_pdt = Oppertunity_product::find($pd_id);

        $oppertunity_pdt->product_id  = $request->product_id;
        $oppertunity_pdt->quantity    = $request->quantity;
        $oppertunity_pdt->sale_amount = $request->amount;
        $oppertunity_pdt->amount      = $request->quantity * $request->amount;

        $oppertunity_pdt->tax_percentage = $request->tax_percentage;
        $amount                          = floatval($oppertunity_pdt->amount);
        $tax_percentage                  = floatval($oppertunity_pdt->tax_percentage);
        $oppertunity_pdt->amount         = round(($amount + (($amount * $tax_percentage) / 100)), 2);

        $product = Product::find($request->product_id);
        if (! empty($product)) {
            $oppertunity_pdt->part_no = $product->part_no;
        }

        $oppertunity_pdt->save();

        $opportunity                    = Oppertunity::find($op_id);
        $opportunity->commission_status = "New Orders";
        $opportunity->save();

        foreach (OppertunityApproveStatus::where("oppertunity_id", $opportunity->id)->where("approve_status", "Approve")->where('status', "Y")->get() as $sts) {
            $sts->staff_id  = $staff_id;
            $sts->status    = "N";
            $sts->closed_at = Carbon::now()->toDateTimeString();
            $sts->save();
        }

        $request->session()->flash('success', 'Product updated Successfully');

        return redirect('staff/list_oppertunity_products/' . $op_id);

    }

    public function updateContractProduct(Request $request)
    {

        $staff_id = session('STAFF_ID');


        $oppertunity_pdt = Oppertunity_product::find($request->pdt_id);

        $oppertunity_pdt->quantity = $request->quantity;

        $oppertunity_pdt->sale_amount = $request->amount;

        $oppertunity_pdt->pm = $request->pm;

        $oppertunity_pdt->cr = $request->cr;


        if($staff_id == 35){
            $oppertunity_pdt->oldprice = $request->oldprice;

            $oppertunity_pdt->hike = $request->hike;
        }

            
        $oppertunity_pdt->amount = $request->quantity * $request->amount;

        $oppertunity_pdt->save();

        $request->session()->flash('success', 'Product updated Successfully');

        return redirect('staff/list_oppertunity_products/' . $request->op_id);

    }

    public function prospectus($id)
    {

        $opp_user = Oppertunity::where('id', $id)->first()->user_id;

        $contact_person = Contact_person::where('user_id', $opp_user)->get();

        $prospectus = Prospectus::where('oppertunity_id', $id)->get();

        return view('staff.oppertunity.prospectus', ['prospectus' => $prospectus, 'contact' => $contact_person, 'id' => $id]);

    }

    public function delete_prospectus(Request $request)
    {

        $ids = $request->id;

        foreach ($ids as $key => $id) {

            Prospectus::destroy($id);

        }

        $request->session()->flash('success', 'Prospectus deleted Successfully');

        return redirect('staff/prospectus/' . $request->op_id);

    }

    public function update_prospectus($id)
    {

        $opp_user = Oppertunity::where('id', $id)->first()->user_id;

        $contact_person = Contact_person::where('user_id', $opp_user)->get();

        $oppertunity = Oppertunity::find($id);

        $user = User::find($opp_user);

        return view('staff.oppertunity.update_prospectus', ['oppertunity' => $oppertunity, 'contact' => $contact_person, 'id' => $id, 'user' => $user]);

    }

    public function store_prospectus(Request $request, $id)
    {

        $request->validate([

            'company_name'   => 'required',

            'contact'        => 'required|array',

            'summary'        => 'required',

            'next_steps'     => 'required',

            'next_step_date' => 'required',

            'deal_stage'     => 'required',

            'order_forcast'  => 'required',

        ]);

        $prospectus = new Prospectus;

        $prospectus->oppertunity_id = $id;

        $prospectus->contact_person_id = implode(',', $request->contact);

        $prospectus->summary = $request->summary;

        $prospectus->next_step = $request->next_steps;

        $prospectus->next_step_date = date('Y:m:d', strtotime($request->next_step_date));

        $prospectus->deal_stage = $request->deal_stage;

        $prospectus->order_forecast_category = $request->order_forcast;

        $prospectus->dt = date('Y-m-d');

        if (isset($request->cancel_stat)) {
            $prospectus->deal_cancel_status = $request->cancel_stat;
        }

        $prospectus->save();

        $request->session()->flash('success', 'Prospectus updated Successfully');

        return redirect('staff/prospectus/' . $id);

    }

    public function chatterdetail(Request $request)
    {

        //$data1              =   json_decode($request->input('datas'));

        $data = [];

        $data['id'] = $request->input('data');

        $data['uid'] = $request->input('user_id');

        // $data['data']       =   $data2;

        //dd($data2);

        $id = $data['id'];

        $data['chatter'] = Chatter::where('oppertunity_id', $data['id'])->get();

        $data['contact'] = Contact_person::where('user_id', $data['uid'])->get();

        $data['opp'] = Oppertunity::find($id);

        return view('staff.oppertunity.chatter_detail', $data);

    }

    public function chattersave(Request $request)
    {

        $oppertunity = Oppertunity::find($request->op_id);

        $current1 = $oppertunity->image;

        if (isset($request->image_name1)) {

            $imageName1 = time() . $request->image_name1->getClientOriginalName();

            $imageName1 = preg_replace("/[^a-z0-9\_\-\.]/i", '', $imageName1);

            $path = storage_path();

            $img_path = $request->image_name1->storeAs('public/chatter', $imageName1);

            $path = $path . '/app/' . $img_path;

            chmod($path, 0777);

            $path = storage_path() . '/app/public/chatter/';

            \File::delete($path . $current1);

        } else {

            $imageName1 = $current1;

        }

        $chatter = new Chatter;

        $chatter->oppertunity_id = $request->op_id;

        if (isset($request->contact)) {
            $chatter->contact_person_id = implode(',', $request->contact);
        }

        $chatter->comment = $request->comment;

        $chatter->deal_stage = $request->deal_stage;

        $chatter->es_order_date = date('Y:m:d', strtotime($request->order_date));

        $chatter->es_sales_date = date('Y:m:d', strtotime($request->sales_date));

        $chatter->order_forcast_category = $request->order_forcast;

        $chatter->support = $request->support;

        $chatter->image = isset($imageName1) ? $imageName1 : '';

        if (isset($request->email_status)) {
            $chatter->email_status = 'Yes';
        }

        if (isset($request->call_status)) {
            $chatter->call_status = 'Yes';
        }

        if (isset($request->visit_status)) {
            $chatter->visit_status = 'Yes';
        }

        $chatter->save();

        $oppertunity->deal_stage = $request->deal_stage;

        $oppertunity->es_order_date = date('Y:m:d', strtotime($request->order_date));

        $oppertunity->es_sales_date = date('Y:m:d', strtotime($request->sales_date));

        $oppertunity->order_forcast_category = $request->order_forcast;

        $oppertunity->support = $request->support;

        $oppertunity->image = isset($imageName1) ? $imageName1 : '';

        $oppertunity->save();

        $random_num  = rand(12345, 199999) + rand(123, 999);
        $timestamp   = time();
        $unique_code = $random_num . ($timestamp + rand(10, 999));
        $cron_repeat = DB::select("select * from task where unique_code = '" . $unique_code . "'");
        if (count($cron_repeat) > 0) {
            $unique_code = time() . $unique_code;
        } else {
            $unique_code = $unique_code;
        }

        $op_task = new OppertunityTask;

        $op_task->name = $oppertunity->oppertunity_name;

        $op_task->unique_code = $unique_code;

        $op_task->company_id = $oppertunity->company_type;

        if (isset($request->email_status)) {
            $op_task->email_status = 'Yes';
        }

        if (isset($request->call_status)) {
            $op_task->call_status = 'Yes';
        }

        if (isset($request->visit_status)) {
            $op_task->visit_status = 'Yes';
        }

        $op_task->deal_stage = $request->deal_stage;

        $op_task->order_forcast = $request->order_forcast;

        $op_task->support = $request->support;

        $op_task->oppertunity_id = $request->op_id;

        $op_task->chatter_id = $chatter->id;

        if ($request->contact != '') {
            $op_task->contact = implode(',', $request->contact);
        }

        $op_task->followers = 127;

        $op_task->user_id = $oppertunity->user_id;

        $op_task->description = $request->comment;

        $staff_id = session('STAFF_ID');

        $op_task->staff_id = $staff_id;

        $op_task->created_by_name = "staff";

        $op_task->created_by_id = $staff_id;

        $op_task->save();

        $request->session()->flash('success', 'Chatter detail added Successfully');

        return redirect('staff/list_oppertunity');

    }

    public function contractQuotePdf(Request $request, $id)
    {

        $quote = Quotehistory::where('id', $id)->first();{
            // die($quote);
            $oppertunity = Oppertunity::with('customer')->where('id', $quote->oppertunity_id)->first();

            if ($quote->history_status != 'new') {
                $productId = explode(',', $quote->product_id);

                $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('product_id', $productId)->get();
            } else {
                $quoteIds = explode(',', $quote->opper_product_id);

                $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('id', $quoteIds)->get();
            }

            // dd($productDetails);
            // print_r($productDetails);
            //die();
            //echo $quote->company_type;
            $data = [
                'products'      => $productDetails,
                'hospital_desi' => $oppertunity->customer->hospital_desi,
                'customer'      => $oppertunity->customer->business_name,
                'district_name' => $oppertunity->customer->district_name,
                'address'       => $oppertunity->customer->address1,
                'start_date'    => $oppertunity->contract_start_date,
                'end_date'      => $oppertunity->contract_end_date,
                'quote'         => $quote,
                'service_type'  => $oppertunity->service_type,
            ];

            if ($quote->company_type == "GE") {
                if (session('STAFF_ID') == 56 || session('STAFF_ID') == 55 || session('STAFF_ID') == 29 || session('STAFF_ID') == 32 || session('STAFF_ID') == 35 || session('STAFF_ID') == 121) {
                    $pdf = PDF::loadView('staff.oppertunity.contract_ge_pdf', $data);
                    return $pdf->stream('ge-contract.pdf');
                } else {
                    return view('staff.oppertunity.contract_ge_pdf', $data);
                }

            } else {
                if (session('STAFF_ID') == 56 || session('STAFF_ID') == 55 || session('STAFF_ID') == 29 || session('STAFF_ID') == 32 || session('STAFF_ID') == 35 || session('STAFF_ID') == 121) {
                    $pdf = PDF::loadView('staff.oppertunity.contract_bec_pdf', $data);
                    return $pdf->stream('bec-contract.pdf');
                } else {
                    return view('staff.oppertunity.contract_bec_pdf', $data);
                }
            }
        }
    }

    public function quotepdf(Request $request, $id)
    {
        if (QuoteProduct::where('quote_id', $id)->count() > 0) {

            $quote            = Quotehistory::find($id);
            $quoteProduct     = QuoteProduct::with('quoteProduct')->where('quote_id', $id)->get();
            $hospital_name    = "";
            $hospital_pincode = '';
            $district_name    = '';
            $hospital_desi    = '';
            $opurtunity       = Oppertunity::find($quote->oppertunity_id);
            if (isset($opurtunity) && $opurtunity->user_id > 0) {
                $user_det         = User::withTrashed()->find($opurtunity->user_id);
                $hospital_name    = $user_det->business_name;
                $hospital_desi    = $user_det->name;
                $hospital_pincode = $user_det->zip;
                $district_name    = $user_det->district_name;
                $gst              = $user_det->gst;
            }

            $opp_type = $opurtunity->company_type;

            if (session('STAFF_ID') == 55 || session('STAFF_ID') == 29 || session('STAFF_ID') == 32 || (isset($request->pdf) && $request->pdf == 1)) {

                if ($opurtunity->company_type == 5) {
                    $pdf = PDF::loadView("staff.quote.pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
                } 
                elseif($opurtunity->company_type == 6){
                    $pdf = PDF::loadView("staff.quote.bec_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));

                }
                
                else {
                    $pdf = PDF::loadView("staff.quote.techsure_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
                }

                if (isset($request->download) && $request->download == 1) {
                    return $pdf->download(strtoupper($quote->quote_reference_no) . date('Ymd') . ".pdf");
                } else {
                    return $pdf->stream();
                }
                } else {

                $opp_type = $opurtunity->company_type;

                if ($opurtunity->company_type == 5) {
                    $html = view("staff.quote.pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'))->render();
                }
                
                elseif($opurtunity->company_type == 6){
                    $html = view("staff.quote.bec_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'))->render();

                }
                else {
                    $html = view("staff.quote.techsure_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'))->render();
                }

                $html .= "<script>document.getElementById('headbartable').style='background-color: #91d7f8;  padding:0; margin-top:0;margin-bottom: 35px;';document.getElementById('headbartableimg').style='margin:0;padding:0;width:100%!important';document.getElementById('body-html').style='width: 210mm;    height: 297mm;margin:auto;';</script>";
                return response(str_replace(public_path("/"), asset("public") . "/", $html), 200)->header('Content-Type', 'text/html');
            }
        } else {
            return $this->old_quote_pdf($request, $id);
        }
    }

    public function old_quote_pdf(Request $request, $id)
    {

        $quote = Quotehistory::where('id', $id)->first();

        //$product_id   = $quote->product_cart_id;

        $pid_pro = explode(",", $quote->product_id);
        $pid     = [];
        if (strpos($quote->product_id, ',') !== false) {

            foreach ($pid_pro as $val) {
                $op_pdt_main = Oppertunity_product::where([['oppertunity_id', $quote->oppertunity_id], ['product_id', $val]])->first();
                if (isset($op_pdt_main->sale_amount)) {
                    $pid[] = $val;
                }

            }
        } else {
            $pid[] = $quote->product_id;
        }

        $op_id = $quote->oppertunity_id;

        $hospital_name    = "";
        $hospital_pincode = '';
        $district_name    = '';
        $hospital_desi    = '';

        if ($op_id > 0) {

            $opper_det = Oppertunity::find($op_id);

            if ($opper_det->user_id > 0) {

                $user_det = User::withTrashed()->find($opper_det->user_id);

                $hospital_name    = $user_det->business_name;
                $hospital_desi    = $user_det->name;
                $hospital_pincode = $user_det->zip;
                $district_name    = $user_det->district_name;
                $gst              = $user_det->gst;
            }

        }

        // print_r($pid);die;

        // $products_det = Product::whereIn('id', $pid)->orderBy('id', 'asc')->get();
        $products_det = Product::whereIn('id', $pid)->orderByRaw('FIELD(id,' . implode(',', $pid) . ')')->get();
        // $company_id   = $products_det[0]->company_id;

        // print_r($products_det);exit;

        $filename = 'quote' . time() . '.pdf';

        $path = $_SERVER['DOCUMENT_ROOT'] . '/beczone/pdf/' . $filename;

        $data = 10;

        if ($quote->generate_date != '') {

            $quote_date = $quote->generate_date;

        } else {

            $quote_date = date("Y-m-d");

        }

        $html = '



          <html>

          <head>

              <style>

              @page {size :794.993324432577px 1123.7650200267px; margin: 134.15220293725px 0px 114.15220293725px 0px;}

              .termp{page-break-inside: always;}
              .termp p{width:100%;color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:22px;}
              .otherpages {margin:0px 3% 0px 3%;color:#000;

				font-family:Arial, Helvetica, sans-serif;

				font-size:15px;

                font-weight:normal; }

              .firstpage{page-break-inside: never;}

              #body {color:#000;

				font-family:Arial, Helvetica, sans-serif;

				font-size:15px;

				font-weight:normal;display:inline-block; margin-top:114.15220293725px; margin-right:19.9599465954606px; margin-bottom:72.0961281708945px; margin-left:0;}



              ._page:after { content: counter(page);}

              .header { position: fixed; top:-125px; left: -1.5%; right:0; height:145px; }

			   .footer { position: fixed; left:0; bottom:-120px; right:0; height:80px; background:#003f86;}

              .footer-wrap{width:94%; margin-left:3%; margin-right:3%;page-break-inside: never;}

			  .footer-wrap h3{top:-40px !important;position:relative;}

			 	ul{

				margin:20px 0 0 0!important;

				padding:0 !important;

				}

			 ul li, li, ol li{

				color:#000;

				font-family:Arial, Helvetica, sans-serif;

				font-size:15px;

				font-weight:normal;

				margin:0 0 5px 15px!important;

				line-height:22px;

				padding:0 !important;



			 }
       .h3tag h3{
        color:#003f86;
        font-family:Arial, Helvetica, sans-serif;
        font-size:19px;
        font-weight:normal;
        margin:30px 0 20px 0;
        line-height:normal;
        padding:0;
        text-align:left;
       }
       .descsec p{
        page-break-inside: always;
       }

              </style>

          </head>

          <body id="body-html">

              <!-- Define header and footer blocks before your content -->

             <div class="footer" id="footer-html">

			  <div class="footer-wrap">

              <h3 style="color:#666; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:normal; margin:0;line-height:normal;text-transform:uppercase; padding:0 0 10px 0;text-align:left; width:100%;">Thank You For Your Business</h3>

              <table width="100%"  cellpadding="0" cellspacing="0" border="0" style="  padding:0; margin-top:-20px !important; ">



                             <tr>

                                    <td align="left" height="22"><p style="float:left; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal; text-align:left;">39/878A2 Palarivattom, Kochi- 682025</p>





                                    </td>

                                    <td align="left" height="22"><p style="float:left; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal; text-align:left;">0484 2887208, 0484 2887200</p>





                                    </td>

                              </tr>

                              <tr>



                                  <td align="left" height="22">

                                  <p style="float:left; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 ;line-height:normal;text-align:left;"> sales@biomedicalengineeringcompany.com</p>

                                  </td>

                                  <td align="left" height="22">

                                  <p style="float:left; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 ;line-height:normal;text-align:left;"> www.biomedicalengineeringcompany.com</p>

                                  </td>

                              </tr>







          </table>



              </div>

      		</div>





              <!-- Wrap the content of your PDF inside a main tag -->

              <main>

              <div class="firstpage">



  <table width="100%" cellpadding="0" cellspacing="0" border="0"  align="center" style="">

                    <tr>

                        <td>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="">

								<tr>

                                    <td>

                                        <table width="100%"   cellpadding="0" cellspacing="0" border="0" id="headbartable" style="background-color: #91d7f8;  padding:0; margin-top:-125px;">

                                            <tr valign="top">

                                                <td align="left" >

                                                    <img src="' . public_path("images/head-main.png") . '" id="headbartableimg" alt="" width="800"   style="margin:0;padding:0;">

                                                </td>



                                            </tr>

                                        </table>

                                    </td>

                                </tr>



                                <tr>

                                    <td>

                                        <table width="100%"  cellpadding="0" cellspacing="0" border="0" style="background-color: #fff;  padding:0; margin-top:-35px;">

                                            <tr valign="top">

                                                <td align="left" width="69%"  style="background-color:#73c6f0; padding:7px 0 7px 5%;

    vertical-align: middle; height:150px;" >

												<p style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:14px;font-weight:normal; margin: 0;line-height:24px; text-align:left;  ">

													39/878 A2(YMJ Stadium Link Road) Opp. JLN International Stadium Metro Station, <br>

													Palarivattom Kochi PIN 682025 Kerala, India. <br>

													Email: sales@biomedicalengineeringcompany.com Tele: 0484 2887208, 0484 2887200, Mobile: 8921065594 <br>

													TIN 32071362164, DL 20 B # KL-EKM111891 DL 21B # KL-EKM-111892 <br>

													GST32AAGFB1151K1ZV

												</p>



                                                </td>

                                                <td align="left" width="31%" style="background-color:#003f86; padding:7px 0 7px 8%;

    vertical-align: middle; height:150px;" >

                                                    <p style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:14px;font-weight:normal; margin: 0;line-height:24px; text-align:left;">

														To,<br>



                                                        ' . $hospital_desi . '      <br>
                                                        ' . $hospital_name . '<br>
                                                        ' . $district_name . '<br>
                                                        ' . $hospital_pincode . '<br>

													</p>

                                                </td>

                                            </tr>

                                        </table>

                                    </td>

                                </tr>

								<tr>

                                  <td height="10"></td>

								</tr>

                                <tr>

                                  <td>

                                      <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style=" margin-left:-3%;" >

                                          <tr>

                                              <td align="left"  width="72%" style="padding:10px 0;vertical-align: bottom;" >

											  <h3 style="color:#003f86; font-family:Arial, Helvetica, sans-serif; font-size:19px;font-weight:normal; margin:0;line-height:normal; padding:15px 0 15px 30px;text-align:left;"> Quotation for Medical Equipment, Supplies, Accessories & Parts</h3>

                                              </td>

                                              <td align="left"  width="20%" style="padding-left:5%;padding-right:0; padding-top:10px;padding-bottom:10px;vertical-align: bottom" >

											  		<h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; line-height:normal; padding:5px 0; margin:0;text-align:left;">Quote: ' . ucfirst($quote->quote_reference_no) . '</h3>

                                                  <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px; font-weight:normal; line-height:normal; padding:5px 0; margin:0; text-align:left;">Dated: ' . date("d-m-Y", strtotime($quote_date)) . '</h3>

                                              </td>



                                          </tr>

                                      </table>

                                  </td>

                              </tr>



                              <tr>

                                  <td height="20">

                                  </td>

                              </tr>';

        $html .= '  </table>

                              </td>

                          </tr>

                     </table>





                     </div>';

        $html .= '<div class="otherpages"  >';

        $html .= '



                                    <table width="100%" cellpadding="0" cellspacing="0" border="0"  >

                                       <tr>

                                          <th align="center"  width="35" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767;background:#676767;">Sl No</th>

                                          <th align="left"  style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px;border: 1px solid #676767; background:#676767;">Item</th>

                                          <th align="center" width="20" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">Qty</th>

                                          <th align="center" width="40" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">Rate</th>

                                          <th align="center" width="30" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px;border: 1px solid #676767; background:#676767;">Tax %</th>';
        if ($gst == '') {
            $html .= '  <th align="center" width="30" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px;border: 1px solid #676767; background:#676767;">Cess %</th>';
        }
        $html .= '  <th align="center" width="75" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">Amount of Quantity (Tax Included)</th>

                                       </tr>';

        $html .= '   <tr ><td colspan="6" style="height:10px;background-color:white;"> </td></tr>';

        $p = 1;

        $tot = 0;

        $c = 0;

        foreach ($products_det as $values) {

            $op_pdt_main = Oppertunity_product::where([['oppertunity_id', $op_id], ['product_id', $values->id]])->first();

            if (isset($op_pdt_main->quantity)) {

                $quantity = $op_pdt_main->quantity;

            } else {

                $quantity = 0;

            }

            if (isset($op_pdt_main->sale_amount)) {

                $sale_amount = (float) $op_pdt_main->sale_amount;

            } else {

                $sale_amount = 0;

            }

            $sale_amount_cal = $sale_amount * $quantity;

            $tax = $op_pdt_main->tax_percentage;

            $tax_cal = $sale_amount_cal * ($tax / 100);

            $totamount_tax = $tax_cal + $sale_amount_cal;

            if ($gst == '') {
                $cess          = 0;
                $tax_cess      = $sale_amount_cal;
                $tax_inc_cess  = $tax_cess * $cess / 100;
                $totamount_tax = $totamount_tax + $tax_inc_cess;
            } else {
                $cess = 0;
            }

            $tot += $totamount_tax;

            $html .= ' <tr >

                                          <td align="center"  width="35"  style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $p . '</td>

                                          <td align="left" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $values->name . '

                                          </td>

                                          <td align="center"  width="20" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $quantity . '</td>

                                          <td align="center" width="30" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . number_format($sale_amount, 2) . '</td>

                                          <td align="center"  width="30" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $tax . '%</td>';
            if ($gst == '') {
                $html .= ' <td align="center"  width="30" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $cess . '%</td>';
            }
            $html .= ' <td align="center" width="75" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . number_format($totamount_tax, 2) . '</td>

                                       </tr>';

            $opt_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['main_product_id', $values->id], ['optional', 1]])->get();

            if (sizeof($opt_pdt)) {

                $html .= ' <tr>



                                                <td align="left" colspan="2" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:13px;font-weight:bold; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#ccc;"><span>Optional item addon to the above equipment at additional cost</span>

                                                </td>

                                                <td align="left" colspan="4" style="background:#fff;border-right: 1px solid #ccc;"></td>



                                             </tr>';

                $y = 0;

                $order_arr = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

                foreach ($opt_pdt as $opd) {

                    // $prod_details = Product::where('id',$opd->product_id);

                    $prod_details_sub = Product::find($opd->product_id);

                    $sale_amount_cal = $opd->quantity * $opd->sale_amount;

                    $tax = $opd->tax_percentage;

                    $tax_cal = $sale_amount_cal * $tax / 100;

                    $totamount_tax = $tax_cal + $sale_amount_cal;

                    if ($gst == '') {
                        $cess          = 0;
                        $tax_cess      = $sale_amount_cal;
                        $tax_inc_cess  = $tax_cess * $cess / 100;
                        $totamount_tax = $totamount_tax + $tax_inc_cess;
                    } else {
                        $cess = 0;
                    }

                    $tot += $totamount_tax;

                    //

                    // $tot +=$tax_cal;

                    $html .= ' <tr>

                                          <td align="center" width="35" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $p . '.' . $order_arr[$y] . '.</td>

                                          <td align="left"   style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $prod_details_sub->name . '</td>

                                          <td align="center"  width="20" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $opd->quantity . '</td>

                                          <td align="center" width="30" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . number_format($opd->sale_amount, 2) . '</td>

                                          <td align="center"  width="30" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $prod_details_sub->tax_percentage . '%</td>';
                    if ($gst == '') {
                        $html .= '<td align="center"  width="30" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $cess . '%</td>';
                    }
                    $html .= '  <td align="center" width="75" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #999;background:#fff;">' . number_format($totamount_tax, 2) . '</td>

                                       </tr>';

                    $y++;

                }

            }

            $p++;

            $c++;

            $html .= '   <tr ><td colspan="6" style="height:10px;background-color:white;"> </td></tr>';

        }

        $html .= '  </table>

                                 ';

        if ($quote->show_total == "Y") {

            $html .= '

                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style="">

                                       <tr>



                                          <td align="left" colspan="3" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:bold; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;"><span style="padding-left:50px;">Total Amount including Tax</span></td>

                                          <td align="center" width="75" style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:bold; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">' . $tot . '</td>

                                       </tr>

                                    </table>

                                ';

        }

        $html .= '

';

        $html .= '

                                       <br>

                                       <div class="h3tag">
                                       <div class="termp" style="">';

        if ($quote->terms_condition == "") {
            $html .= setting('QUOTE_TERM');
        } else {
            $html .= $quote->terms_condition;
        }

        $html .= ' </div>
                                </div>


                                            ';

        if ($quote->hide_tech == "N") {

            $i = 1;

            $j = 0;

            foreach ($products_det as $values) {

                //  $products_det =  DB::select("select * from products where `id`='".$values."' order by id desc");

                $op_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['product_id', $values->id]])->first();

                if ($values->subcategory_id > 0) {

                    $subcat = Subcategory::find($values->subcategory_id);

                    $sub_cat_name = $subcat->name;

                } else {

                    $sub_cat_name = '';

                }

                $evenodd = $i % 2;

                //  $html.='<p>eee'.count($products_det).'-- </p>';

                if ($i == 1) {

                    $html .= '



                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style="margin-left:0;">


                                    <tr>

                                        <td align="left" width="100%"  >

                                            <h3 style="color:#003f86; font-family:Arial, Helvetica, sans-serif; font-size:19px;font-weight:normal; margin:30px 0 20px 0;line-height:normal; padding:0;text-align:left;"> Detailed Quotation</h3>

                                        </td>

                                    </tr>

                                </table>

                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style="border-bottom:1px solid #0098da;">


                                        <tr>

                                            <td width="5%" valign="top" bgcolor="#e6e7e8" style="padding:30px 2% 10px 2%; text-align:center;">

                                                 <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">' . $i . '</p>

                                            </td>

                                      <td width="34%" valign="top" bgcolor="#e8f6fd" style="padding:30px 3% 30px 5%;">

                                           <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Product Name:</p>

                                                <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $values->name . '</h3><br/>

                                                <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Brand:</p>

                                                <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $values->brand_name . '</h3><br/>

                                                <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Category Name:</p>

                                                <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $values->category_name . '</h3><br/>

                                                <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Sub Category:</p>

                                                <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $sub_cat_name . '</h3>

                                            </td>



                                      <td width="27%" valign="middle" bgcolor="#ffffff" style="padding:10px 6%; text-align:center">';

                    if ($values->image_name != '') {

                        $html .= '  <img  src="' . public_path("storage/products/" . $values->image_name) . '"  style="width:180px;height:auto;"   alt="">  ';

                    } else {

                        $html .= '  <img src="' . public_path("images/no-image.jpg") . '"  style="width:180px;height:auto;" alt="">  ';

                    }

                    $html .= ' </td>

                                      <td width="27%" valign="top" bgcolor="#ffffff" style="padding:0;">

            <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="  ">

                                                  <tr bgcolor="#e6e7e8">

                                                       <td bgcolor="#e6e7e8" width="7%"></td>

                                              <td width="50%" valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                                   <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Unit</p>

                                                        </td>

                                                    <td width="39%" valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                                    <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $values->unit . ' No</p></td>

                                </tr>

                                                  <tr bgcolor="#fff">

                                                       <td bgcolor="#fff" width="7%"></td>

            <td valign="middle" bgcolor="#fff" style="padding:10px 2% 10px 2%; text-align:left;">

                                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Box Quality</p>

                                                    </td>

                                                        <td valign="middle" bgcolor="#fff" style="padding:10px 2% 10px 2%; text-align:left;">

                                                    <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $op_pdt->quantity . ' No</p></td>

                                                    </tr>

                                                    <tr>

                                                        <td bgcolor="#e6e7e8" width="7%"></td>

                          <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Unit Price</p>

                                                        </td>

                                                        <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">';
                    if (isset($op_pdt->sale_amount)) {
                        $op_pdt_sale_amount = $op_pdt->sale_amount;
                    } else {
                        $op_pdt_sale_amount = 0;
                    }

                    $html .= '<p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . number_format($op_pdt_sale_amount, 2) . '</p></td>

                                                    </tr>

                                                    <tr>

                                                        <td bgcolor="#ffffff" width="7%"></td>

                          <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Tax Percentage </p>

                                                        </td>

                                                        <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                                        <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $op_pdt->tax_percentage . '</p></td>

                                                    </tr>

                                                    <tr>

                                                        <td bgcolor="#e6e7e8" width="7%"></td>

                          <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">HSN Code </p>

                                                        </td>

                                                        <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                                        <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $values->hsn_code . '</p></td>

                                                    </tr>

                                                    <tr>

                                                        <td bgcolor="#ffffff" width="7%"></td>

                          <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Warranty</p>

                                                        </td>

                                                        <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                                        <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $values->warrenty . '</p></td>

                                                    </tr>

                                                    <tr>





                                                     </tr>

                                              </table>

                                            </td>



                                      </tr>



                                    </table>











                               ';

                    break;

                }

                $i++;

                $j++;

            }

            $i = 1;

            foreach ($products_det as $values) {

                //  $products_det =  DB::select("select * from products where `id`='".$values."' order by id desc");

                $op_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['product_id', $values->id]])->first();

                if ($values->subcategory_id > 0) {

                    $subcat = Subcategory::find($values->subcategory_id);

                    $sub_cat_name = $subcat->name;

                } else {

                    $sub_cat_name = '';

                }

                if ($i == 1) {

                    $html .= '<div class="descsec" style="page-break-inside: always;" ><p style="width:100%;padding:20px 0 20px 0;color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:22px;">' . $values->description . '</p></div>';

                } else if ($i != 1) {

                    $html .= '





             <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style="border-bottom:1px solid #0098da;">

                 <tr>

                     <td width="5%" valign="top" bgcolor="#e6e7e8" style="padding:30px 2% 10px 2%; text-align:center;">

                          <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">' . $i . '</p>

                     </td>

               <td width="34%" valign="top" bgcolor="#e8f6fd" style="padding:30px 3% 30px 5%;">

                    <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Product Name:</p>

                         <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $values->name . '</h3><br/>

                         <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Brand:</p>

                         <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $values->brand_name . '</h3><br/>

                         <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Category Name:</p>

                         <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $values->category_name . '</h3><br/>

                         <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Sub Category:</p>

                         <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $sub_cat_name . '</h3>

                     </td>



               <td width="27%" valign="middle" bgcolor="#ffffff" style="padding:10px 6%; text-align:center">';

                    if ($values->image_name != '') {

                        $html .= '  <img  src="' . public_path("storage/products/" . $values->image_name) . '"  style="width:180px;height:auto;"   alt="">  ';

                    } else {

                        $html .= '  <img src="' . public_path("images/no-image.jpg") . '"  style="width:180px;height:auto;" alt="">  ';

                    }

                    $html .= ' </td>

               <td width="27%" valign="top" bgcolor="#ffffff" style="padding:0;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style=" ">

                           <tr bgcolor="#e6e7e8">

                                <td bgcolor="#e6e7e8" width="7%"></td>

                       <td width="50%" valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Unit</p>

                                 </td>

                             <td width="39%" valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                             <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $values->unit . ' No</p></td>

         </tr>

                           <tr bgcolor="#fff">

                                <td bgcolor="#fff" width="7%"></td>

<td valign="middle" bgcolor="#fff" style="padding:10px 2% 10px 2%; text-align:left;">

                                     <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Box Quality</p>

                             </td>

                                 <td valign="middle" bgcolor="#fff" style="padding:10px 2% 10px 2%; text-align:left;">

                             <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $op_pdt->quantity . ' No</p></td>

                             </tr>

                             <tr>

                                 <td bgcolor="#e6e7e8" width="7%"></td>

   <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                     <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Unit Price</p>

                                 </td>

                                 <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">';

                    if (isset($op_pdt->sale_amount)) {
                        $op_pdt_sale_amount = $op_pdt->sale_amount;
                    } else {
                        $op_pdt_sale_amount = 0;
                    }
                    $html .= '<p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . number_format($op_pdt_sale_amount, 2) . '</p></td>

                             </tr>

                             <tr>

                                 <td bgcolor="#ffffff" width="7%"></td>

   <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                     <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Tax Percentage </p>

                                 </td>

                                 <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                 <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $op_pdt->tax_percentage . '</p></td>

                             </tr>

                             <tr>

                                 <td bgcolor="#e6e7e8" width="7%"></td>

   <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                     <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">HSN Code </p>

                                 </td>

                                 <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                 <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $values->hsn_code . '</p></td>

                             </tr>

                             <tr>

                                 <td bgcolor="#ffffff" width="7%"></td>

   <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                     <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Warranty</p>

                                 </td>

                                 <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                 <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $values->warrenty . '</p></td>

                             </tr>

                             <tr>





                              </tr>

                       </table>

                     </td>



               </tr>



             </table> ';

                    $html .= '<div class="descsec" style="page-break-inside: always;">   <p style="width:100%;padding:20px 3% 20px 3%;color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:22px;">' . $values->description . '</p></div>

             ';

                }

                $opt_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['main_product_id', $values->id], ['optional', 1]])->get();

                if (sizeof($opt_pdt)) {

                    $j = 1;

                    //$html.=' <h4 style="color:#003f86; font-family:Arial, Helvetica, sans-serif; font-size:19px;font-weight:normal; margin:0;line-height:normal; padding:15px 0 15px 0;text-align:left;">Optional Products</h4>';

                    /*************************************************/

                    $k = 0;

                    $order_arr = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];

                    foreach ($opt_pdt as $opd) {

                        $products_det_opp = Product::where('id', $opd->product_id)->first();

                        $html .= '





               <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style="border-bottom:1px solid #0098da;">

                <tr>

                <td width="100%" valign="top" bgcolor="#fff" style="text-align:left;">
                  <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style="">

                   <tr>
                      <td width="100%" valign="top" bgcolor="#fff" style="text-align:left;">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style="">
                          <tr>
                            <td width="100%" valign="top" bgcolor="#fff" style=" text-align:left;">
                              <h4 style="color:#003f86; font-family:Arial, Helvetica, sans-serif; font-size:19px;font-weight:normal; margin:0;line-height:normal; padding:15px 0 15px 0;text-align:left;">';
                        if ($k == 0) {
                            $html .= '   Optional Products';
                        }

                        $html .= ' </h4>
                            </td>
                          </tr>
                        </table>

                       </td>
                    </tr>
                    <tr>
                      <td width="100%" valign="top" bgcolor="#fff" style="">
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff" style="">
                          <tr>
                             <td width="5%" valign="top" bgcolor="#e6e7e8" style="padding:30px 2% 10px 2%; text-align:center;">

                            <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">' . $i . '.' . $order_arr[$k] . '.</p>

                       </td>

                 <td width="34%" valign="top" bgcolor="#e8f6fd" style="padding:30px 3% 30px 5%;">

                      <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Product Name:</p>

                           <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $products_det_opp->name . '</h3><br/>

                           <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Brand:</p>

                           <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $products_det_opp->brand_name . '</h3><br/>

                           <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Category Name:</p>

                           <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $products_det_opp->category_name . '</h3>';
                        if ($sub_cat_name != '') {
                            $html .= '<br/> <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0 0 3px 0;line-height:normal;">Sub Category:</p>

                           <h3 style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:17px;font-weight:bold; margin:0;line-height:normal;">' . $sub_cat_name . '</h3>';
                        }

                        $html .= ' </td>



                 <td width="27%" valign="middle" bgcolor="#ffffff" style="padding:10px 6%; text-align:center">';

                        if ($products_det_opp->image_name != '') {

                            $html .= '  <img  src="' . public_path("storage/products/" . $products_det_opp->image_name) . '"  style="width:180px;height:auto;"   alt="">  ';

                        } else {

                            $html .= '  <img src="' . public_path("images/no-image.jpg") . '"  style="width:180px;height:auto;" alt="">  ';

                        }

                        $html .= ' </td>

                 <td width="27%" valign="top" bgcolor="#ffffff" style="padding:0;">

  <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style=" ">

                             <tr bgcolor="#e6e7e8">

                                  <td bgcolor="#e6e7e8" width="7%"></td>

                         <td width="50%" valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                              <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Unit</p>

                                   </td>

                               <td width="39%" valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                               <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $products_det_opp->unit . ' No</p></td>

           </tr>

                             <tr bgcolor="#fff">

                                  <td bgcolor="#fff" width="7%"></td>

  <td valign="middle" bgcolor="#fff" style="padding:10px 2% 10px 2%; text-align:left;">

                                       <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Box Quality</p>

                               </td>

                                   <td valign="middle" bgcolor="#fff" style="padding:10px 2% 10px 2%; text-align:left;">

                               <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $products_det_opp->quantity . ' No</p></td>

                               </tr>

                               <tr>

                                   <td bgcolor="#e6e7e8" width="7%"></td>

     <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                       <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Unit Price</p>

                                   </td>

                                   <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">';

                        if (isset($opd->sale_amount)) {
                            $op_pdt_sale_amount = $opd->sale_amount;
                        } else {
                            $op_pdt_sale_amount = 0;
                        }
                        $html .= ' <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . number_format($op_pdt_sale_amount, 2) . '</p></td>

                               </tr>

                               <tr>

                                   <td bgcolor="#ffffff" width="7%"></td>

     <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                       <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Tax Percentage </p>

                                   </td>

                                   <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                   <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $op_pdt->tax_percentage . '</p></td>

                               </tr>

                               <tr>

                                   <td bgcolor="#e6e7e8" width="7%"></td>

     <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                       <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">HSN Code </p>

                                   </td>

                                   <td valign="middle" bgcolor="#e6e7e8" style="padding:10px 2% 10px 2%; text-align:left;">

                                   <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $products_det_opp->hsn_code . '</p></td>

                               </tr>

                               <tr>

                                   <td bgcolor="#ffffff" width="7%"></td>

     <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                       <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">Warranty</p>

                                   </td>

                                   <td valign="middle" bgcolor="#ffffff" style="padding:10px 2% 10px 2%; text-align:left;">

                                   <p style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;">: ' . $products_det_opp->warrenty . '</p></td>

                               </tr>



                         </table>

                       </td>
                          </tr>
                        </table>
                      </td>









                 </tr>



               </table>
             </td>
             </tr>
             </table>
          ';
                        if ($products_det_opp->description != '') {
                            $html .= '<div style="page-break-inside: always;">   <p style="width:100%;padding:20px 3% 20px 3%;color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:22px;">' . $products_det_opp->description . '</p></div>

                    ';
                        }

                        $k++;

                    }
                    $html .= '
             ';
                    /************************************** */

                    /*  $html.=' <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#fff"  style="padding-left:0;padding-right:0;">

                <thead>

                <tr>

                <th align="left" width="8%" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">Sl No</th>

                <th align="left" width="70%" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">Product Name</th>

                <th align="left" width="14%" style="color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #676767; background:#676767;">Sale Amount</th>

                </tr>

                </thead>

                <tbody>';

                foreach($opt_pdt as $opd)

                {

                $pname = Product::where('id',$opd->product_id)->first()->name;

                $html.='<tr>

                <td  style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">'. $j++.'</td>

                <td  style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">'. $pname.'</td>

                <td  style="color:#000; font-family:Arial, Helvetica, sans-serif; font-size:15px;font-weight:normal; margin:0;line-height:normal;padding:7px 10px; border: 1px solid #ccc;background:#fff;">'. $opd->sale_amount.'</td>

                </tr>

                ';

                }

                $html.= '</tbody>

                </table>

                ';*/

                }

                $i++;

            }

        } //hide tech details

        $html .= '</div>';

        $html .= '



                    </main>



                    <div class="header">

            <img src="' . public_path("images/innerpage-head.png") . '" width="820"  alt="">



                    </div>



                </body>

            </html>

        ';
        $pdf = PDF::loadHTML($html);

        /*

        $url=url('/');

        $data = array(

        'house_name'=> 'house_name',

        'candidate_name'=>'candidate_name',

        'email'=>'email',

        'mobile_no'=> 'mobile_no',

        'desc'=> 'mobile_no',

        'url'=>$url

        );

        $email_send='anoopkjhonson@gmail.com';

        $success=    Mail::send('email.contactus', $data, function($message) use ($pdf,$data) {

        $message->to('anoopkjhonson@gmail.com', ' Verification Mail')->subject

        (' Verification Mail');

        $message->from('sales@biomedicalengineeringcompany.com','beczone');

        //  $message->attachData($pdf->output(), "Proposal/Quotation.pdf");

        });

         */

        if (session('STAFF_ID') == 55 || session('STAFF_ID') == 29 || session('STAFF_ID') == 32) {
            return $pdf->stream();
        } else {
            $html .= "<script>document.getElementById('headbartable').style='background-color: #91d7f8;  padding:0; margin-top:0;margin-bottom: 35px;';document.getElementById('headbartableimg').style='margin:0;padding:0;width:100%!important';document.getElementById('body-html').style='width: 210mm;    height: 297mm;margin:auto;';</script>";
            return response(str_replace(public_path("/"), asset("public") . "/", $html), 200)->header('Content-Type', 'text/html');
        }

    }

    public function sendquote(Request $request, $id)
    {

        $quote = Quotehistory::find($id);

        $quote->quote_send_status = 'receive';

        // $quote->save();

        $request->session()->flash('success', 'Quote Sent Successfully');

        return redirect('staff/list_oppertunity_products/' . $quote->oppertunity_id);

    }

    public function quote_send(Request $request)
    {

        $id = $request->id;

        $quote = Quotehistory::find($id);

        $op_id = $quote->oppertunity_id;

        $opp_user = Oppertunity::where('id', $op_id)->first();

        $data['id']      = $id;
        $data['user_id'] = $opp_user->user_id;
        $data['contact'] = Contact_person::where('user_id', $opp_user->user_id)->get();
        $data['staff']   = Staff::where('id', $opp_user->staff_id)->get();
        $data['state']   = State::where('id', $opp_user->state)->first();

        $data['opp_type'] = $opp_user->company_type;

        $data['hospital'] = User::where('id', $opp_user->user_id)->first();

        if (optional($opp_user)->type == 2) {

            return view('staff.oppertunity.send_mail_contract', $data);
        } else {
            return view('staff.oppertunity.send_mail', $data);
        }

    }

    public function send_mail(Request $request)
    {

        $id = $request->qh_id;

        $quote = Quotehistory::find($id);

        $quote->quote_status = 'receive';

        $quote->contact = "";

        $quote->description = $request->comment;

        $quote->subject = $request->subject;

        $quote->save();

        $contact_list = [];

        if (! empty($request->contact)) {
            $contact_list = $request->contact;

            $quote->contact = implode(',', $request->contact);
        }

        $quote     = Quotehistory::where('id', $id)->first();
        $opper_det = Oppertunity::find($quote->oppertunity_id);

        $staff_arr   = [];
        $staff_arr[] = 29;
        $staff_arr[] = 32;
        $staff_arr[] = 30;
        if($opper_det->company_type != 122){

        if ($opper_det->state == '4') {
            $staff_arr[] = 35;
        }

        if ($opper_det->type == '2' && $opper_det->state == '5') {
            $staff_arr[] = 121;
        }}

        $staff_arr[] = $opper_det->staff_id;

        $engineer = Staff::where('id', $opper_det->staff_id)->first();

        $customer = User::withTrashed()->find($opper_det->user_id);

        $mail_users = [];

        if (! empty($request->customer_mail)) {

            $customer_type = explode(',', $request->customer_mail);

            foreach ($customer_type as $item) {
                if (! empty($item)) {
                    $mail_users[] = $item;
                }
            }
        }

        foreach ($contact_list as $k => $values_con) {

            if ($values_con == 'customer') {

                if (! empty($customer)) {
                    $mail_users[] = $customer->email;
                }
            } else {
                $contact_all_details = Contact_person::where('id', $values_con)->first();

                if ($contact_all_details->designation > 0) {

                    $desig = Hosdesignation::find($contact_all_details->designation);

                } else {
                    $desig = '';
                }

                if (! empty($contact_all_details->email)) {
                    $mail_users[] = $contact_all_details->email;
                }

            }

        }

        /*********************************************Staff Mail************************************ */

        foreach ($staff_arr as $values_con) {

                $contact_all_details = Staff::where('id', $values_con)->first();

                if($opper_det->company_type != 122){

                if ($values_con == '35') {
                    $contact_all_details->email = "service@bechealthcare.com";
                }

                if ($values_con == '121') {
                    $contact_all_details->email = "servicetn@bechealthcare.com";
                }
            }
                $desig = '';

                if (! empty($contact_all_details->email)) {
                    $mail_users[] = $contact_all_details->email;
                }

                /************* ****************************************/

        }
            if($opper_det->company_type == 122){
                $mail_users[] = 'mail@techsureindia.com';

            }



        $quote = Quotehistory::where('id', $id)->first();

        //$product_id   = $quote->product_cart_id;
        $pid_pro = explode(",", $quote->product_id);

        $pid = [];

        if (strpos($quote->product_id, ',') !== false) {

            foreach ($pid_pro as $val) {
                $op_pdt_main = Oppertunity_product::where([['oppertunity_id', $quote->oppertunity_id], ['product_id', $val]])->first();
                if (isset($op_pdt_main->sale_amount)) {
                    $pid[] = $val;
                }

            }
        } else {
            $pid[] = $quote->product_id;
        }

        $op_id = $quote->oppertunity_id;

        $hospital_name    = "";
        $hospital_pincode = '';
        $district_name    = '';
        $hospital_desi    = '';

        if ($op_id > 0) {

            $opper_det = Oppertunity::find($op_id);

            if ($opper_det->user_id > 0) {

                $user_det = User::withTrashed()->find($opper_det->user_id);

                $hospital_name    = $user_det->business_name;
                $hospital_desi    = $user_det->name;
                $hospital_pincode = $user_det->zip;
                $district_name    = $user_det->district_name;

            }

        }

        //print_r($pid);die;

        //$products_det = Product::whereIn('id', $pid)->orderBy('id', 'asc')->get();
        $products_det = Product::whereIn('id', $pid)->orderByRaw('FIELD(id,' . implode(',', $pid) . ')')->get();
        // $company_id   = $products_det[0]->company_id;

        // print_r($products_det);exit;

        $filename = 'quote' . time() . '.pdf';

        $path = $_SERVER['DOCUMENT_ROOT'] . '/beczone/pdf/' . $filename;

        $data = 10;

        if ($quote->generate_date != '') {

            $quote_date = $quote->generate_date;

        } else {

            $quote_date = date("Y-m-d");

        }

        if ($opper_det->type == 2) {

            $oppertunity = Oppertunity::with('customer')->where('id', $quote->oppertunity_id)->first();

            if ($quote->history_status != 'new') {
                $productId = explode(',', $quote->product_id);

                $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('product_id', $productId)->get();
            } else {
                $quoteIds = explode(',', $quote->opper_product_id);

                $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('id', $quoteIds)->get();
            }
            //print_r($productDetails);
            //die();
            //echo $quote->company_type;
            $contractdata = [
                'products'      => $productDetails,
                'hospital_desi' => $oppertunity->customer->hospital_desi,
                'customer'      => $oppertunity->customer->business_name,
                'district_name' => $oppertunity->customer->district_name,
                'address'       => $oppertunity->customer->address1,
                'start_date'    => $oppertunity->contract_start_date,
                'end_date'      => $oppertunity->contract_end_date,
                'quote'         => $quote,
                'service_type'  => $oppertunity->service_type,
            ];

            if ($quote->company_type == "GE") {
                $pdf = PDF::loadView('staff.oppertunity.contract_ge_pdf', $contractdata);
            } else {
                $pdf = PDF::loadView('staff.oppertunity.contract_bec_pdf', $contractdata);
            }
        } else {

            $quoteProduct     = QuoteProduct::where('quote_id', $id)->get();
            $hospital_name    = "";
            $hospital_pincode = '';
            $district_name    = '';
            $hospital_desi    = '';
            $gst              = '';
            $opurtunity       = Oppertunity::find($quote->oppertunity_id);

            $opurtunity_product = Oppertunity_product::where('oppertunity_id', $quote->oppertunity_id)->first();

            if ($quote->history_status != 'new') {
                $productId = explode(',', $quote->product_id);

                $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('product_id', $productId)->get();
            } else {
                $quoteIds = explode(',', $quote->opper_product_id);

                $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('id', $quoteIds)->get();
            }

            if (isset($opurtunity) && $opurtunity->user_id > 0) {
                $user_det         = User::withTrashed()->find($opurtunity->user_id);
                $hospital_name    = $user_det->business_name;
                $hospital_desi    = $user_det->name;
                $hospital_pincode = $user_det->zip;
                $district_name    = $user_det->district_name;
                $gst              = $user_det->gst;
            }
            $opp_type = $opper_det->company_type;

            if ($opper_det->company_type == 5) {

                $pdf = PDF::loadView("staff.quote.pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
            } 
             elseif($opurtunity->company_type == 6){
                $pdf = PDF::loadView("staff.quote.bec_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));

            }
            else {
                $pdf = PDF::loadView("staff.quote.techsure_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
            }

            // $pdf = PDF::loadHTML($html);

        }

        if ($opper_det->company_type == 5 || $opper_det->type == 2) {

            $url = url('/');

        } elseif($opper_det->company_type == 6) {
            $url = "https://bechealthcare.com";
        }

        else{
            $url = "https://techsureindia.com";

        }

        $data = [

            'subject' => $request->subject,

            'desc'    => $request->comment,

            'name'    => $contact_all_details->name,

            'url'     => $url,

        ];

        // dd($mail_users);

        $email_send = $mail_users[0] ?? $mail_users[1] ?? '';

        $subject = $request->subject;

        $arr_img = [];
        if (count($products_det) > 0) {
            foreach ($products_det as $values) {
                $op_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['product_id', $values->id]])->first();
                if ($values->image_name1 != '') {

                    if (is_file(storage_path("app/public/products/" . $values->image_name1))) {
                        $arr_img[] = storage_path("app/public/products/" . $values->image_name1);
                    }
                }

                $opt_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['main_product_id', $values->id], ['optional', 1]])->get();
                if (count($opt_pdt) > 0) {
                    foreach ($opt_pdt as $opd) {
                        $products_det_opp = Product::where('id', $opd->product_id)->first();
                        if ($values->image_name1 != '') {

                            if (is_file(storage_path("app/public/products/" . $products_det_opp->image_name1))) {
                                $arr_img[] = storage_path("app/public/products/" . $products_det_opp->image_name1);
                            }

                        }

                    }
                }

            }
        }

        $contact_list = [];

        if (! empty($request->contact)) {
            $contact_list = $request->contact;
        }

        $arr_contact_name  = [];
        $arr_contact_email = [];

        $arr_contact_name[]  = $engineer->name;
        $arr_contact_email[] = $engineer->email;
        if (count($contact_list) > 0) {
            foreach ($contact_list as $values_con) {

                if ($values_con != 'customer') {
                    $contact_all_details = Contact_person::where('id', $values_con)->first();
                    $arr_contact_name[]  = $contact_all_details->name;
                    $arr_contact_email[] = $contact_all_details->email;
                }
            }
        }
        // dd($mail_users);
        if ($opper_det->type == 2) {

            $state_id = $opper_det->state;

            $success = Mail::send('email.contract_qoute', $data, function ($message) use ($mail_users, $opper_det, $state_id, $pdf, $data, $email_send, $subject, $arr_img, $arr_contact_name, $arr_contact_email) {
                //$email_send
                $message->from('sales@biomedicalengineeringcompany.com', 'Biomedical Engineering Company');

                $message->to($email_send, $subject)->subject

                    ($subject);

                if (count($mail_users) > 1) {

                    $bcc_emails = array_slice($mail_users, 1);

                    $message->bcc($bcc_emails);
                }

                if ($state_id == '4') {
                    $message->replyTo('service@bechealthcare.com', 'Contract');
                }

                if ($state_id == '5') {
                    $message->replyTo('servicetn@bechealthcare.com', 'Contract');
                }

                $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');

                if (count($arr_contact_email) > 0) {
                    foreach ($arr_contact_email as $keys => $val) {
                        $message->replyTo($arr_contact_email[$keys], $arr_contact_name[$keys]);
                    }
                }

                $message->attachData($pdf->output(), "Quotation.pdf");

            });

        } else {

            if ($opper_det->company_type == 5) {

                $state_id = $opper_det->state;

                $success = Mail::send('email.quote', $data, function ($message) use ($state_id, $mail_users, $opper_det, $pdf, $data, $email_send, $subject, $arr_img, $arr_contact_name, $arr_contact_email) {
                    //$email_send
                    $message->to($email_send, $subject)->subject

                        ($subject);

                    if (count($mail_users) > 1) {

                        $bcc_emails = array_slice($mail_users, 1);

                        $message->bcc($bcc_emails);
                    }

                    $message->from('sales@biomedicalengineeringcompany.com', 'Biomedical Engineering Company');

                    if ($state_id == '4') {
                        $message->replyTo('service@bechealthcare.com', 'Sales');
                    }

                    $message->replyTo('sales@biomedicalengineeringcompany.com', 'Sales');
                    $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                    // $message->replyTo('vshaji7@gmail.com', 'Shaji Varghese');
                    $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');

                    if (count($arr_contact_email) > 0) {
                        foreach ($arr_contact_email as $keys => $val) {
                            $message->replyTo($arr_contact_email[$keys], $arr_contact_name[$keys]);
                        }
                    }

                    $message->attachData($pdf->output(), "Quotation.pdf");
                    for ($j = 0; $j < count($arr_img); $j++) {
                        if (is_file($arr_img[$j])) {
                            $message->attach($arr_img[$j]);
                        }

                    }

                });

            } elseif($opper_det->company_type == 6) {

                $state_id = $opper_det->state;

                $success = Mail::send('email.quote_bec', $data, function ($message) use ($state_id, $mail_users, $pdf, $data, $email_send, $subject, $arr_img, $arr_contact_name, $arr_contact_email) {
                    //$email_send
                    $message->to($email_send, $subject)->subject

                        ($subject);

                    if (count($mail_users) > 1) {

                        $bcc_emails = array_slice($mail_users, 1);

                        $message->bcc($bcc_emails);
                    }

                    $message->from('sales@biomedicalengineeringcompany.com', 'BEC Health Care');

                    if ($state_id == '4') {
                        $message->replyTo('service@bechealthcare.com', 'Sales');
                    }

                    $message->replyTo('sales@biomedicalengineeringcompany.com', 'Sales');
                    $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                    // $message->replyTo('vshaji7@gmail.com', 'Shaji Varghese');
                    $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');

                    if (count($arr_contact_email) > 0) {
                        foreach ($arr_contact_email as $keys => $val) {
                            $message->replyTo($arr_contact_email[$keys], $arr_contact_name[$keys]);
                        }
                    }

                    $message->attachData($pdf->output(), "Quotation.pdf");
                    for ($j = 0; $j < count($arr_img); $j++) {
                        if (is_file($arr_img[$j])) {
                            $message->attach($arr_img[$j]);
                        }

                    }

                });

            } else {
                $state_id = $opper_det->state;
                $success = Mail::send('email.quote_techsure', $data, function ($message) use ($state_id, $mail_users, $pdf, $data, $email_send, $subject, $arr_img, $arr_contact_name, $arr_contact_email) {
                    //$email_send

                    $message->to($email_send, $subject)->subject


                        ($subject);

                    if (count($mail_users) > 1) { 

                        $bcc_emails = array_slice($mail_users, 1);

                        $message->bcc($bcc_emails); 
                    }

                    $message->from('sales@biomedicalengineeringcompany.com', 'Techsure Medical Devices Pvt. Ltd');



                    // if ($state_id == '4') {
                    //     $message->replyTo('service@bechealthcare.com', 'Sales');
                    // }

                    $message->replyTo('mail@techsureindia.com', 'Techsure Medical Devices Pvt. Ltd');
                    $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                    $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');
                    $message->replyTo('becproductquotes@gmail.com', 'Sales');

                    // // $message->replyTo('vshaji7@gmail.com', 'Shaji Varghese');


                    if (count($arr_contact_email) > 0) {
                        foreach ($arr_contact_email as $keys => $val) {
                            $message->replyTo($arr_contact_email[$keys], $arr_contact_name[$keys]);
                        }
                    }

                    $message->attachData($pdf->output(), "Quotation.pdf");
                    for ($j = 0; $j < count($arr_img); $j++) {
                        if (is_file($arr_img[$j])) {
                            $message->attach($arr_img[$j]);
                        }

                    }

                });

            }

        }

        $request->session()->flash('success', 'Quote Sent Successfully');

        return redirect('staff/list_oppertunity_products/' . $quote->oppertunity_id);

    }

    public function oldsend_mail(Request $request)
    {

        $id = $request->qh_id;

        $quote = Quotehistory::find($id);

        $quote->quote_status = 'receive';

        $quote->contact = implode(',', $request->contact);

        $quote->description = $request->comment;

        $quote->subject = $request->subject;

        $quote->save();

        $contact_list = $request->contact;

        $staff_arr   = [];
        $staff_arr[] = 29;
        $staff_arr[] = 32;
        $staff_arr[] = 30;

        $quote     = Quotehistory::where('id', $id)->first();
        $opper_det = Oppertunity::find($quote->oppertunity_id);

        if ($opper_det->type == '2' && $opper_det->state == '4') {
            $staff_arr[] = 35;
        }
        if ($opper_det->type == '2' && $opper_det->state == '5') {
            $staff_arr[] = 121;
        }

        //$staff_arr[]=56;

        $staff_arr[] = $opper_det->staff_id;

        $engineer = Staff::where('id', $opper_det->staff_id)->first();

        $customer = User::withTrashed()->find($opper_det->user_id);

        if (! empty($customer->email)) {
            $contact_list[] = 'user';
        }

        foreach ($contact_list as $values_con) {

            if ($values_con == 'user') {
                $contact_all_details = new stdClass();

                $contact_all_details->email = $customer->email;

                $contact_all_details->name = $customer->business_name;

                $contact_all_details->title = "";

                $contact_all_details->last_name = "";

            } else {

                $contact_all_details = Contact_person::where('id', $values_con)->first();

                if ($contact_all_details->designation > 0) {

                    $desig = Hosdesignation::find($contact_all_details->designation);

                } else {
                    $desig = '';
                }
            }

            /************* ****************************************/

            $quote = Quotehistory::where('id', $id)->first();

            //$product_id   = $quote->product_cart_id;

            $pid_pro = explode(",", $quote->product_id);

            $pid = [];

            if (strpos($quote->product_id, ',') !== false) {

                foreach ($pid_pro as $val) {
                    $op_pdt_main = Oppertunity_product::where([['oppertunity_id', $quote->oppertunity_id], ['product_id', $val]])->first();
                    if (isset($op_pdt_main->sale_amount)) {
                        $pid[] = $val;
                    }

                }
            } else {
                $pid[] = $quote->product_id;
            }

            $op_id = $quote->oppertunity_id;

            $hospital_name    = "";
            $hospital_pincode = '';
            $district_name    = '';
            $hospital_desi    = '';

            if ($op_id > 0) {

                $opper_det = Oppertunity::find($op_id);

                if ($opper_det->user_id > 0) {

                    $user_det = User::withTrashed()->find($opper_det->user_id);

                    $hospital_name    = $user_det->business_name;
                    $hospital_desi    = $user_det->name;
                    $hospital_pincode = $user_det->zip;
                    $district_name    = $user_det->district_name;
                    $gst              = $user_det->gst;
                }

            }

            //print_r($pid);die;

            // $products_det = Product::whereIn('id', $pid)->orderBy('id', 'asc')->get();
            $products_det = Product::whereIn('id', $pid)->orderByRaw('FIELD(id,' . implode(',', $pid) . ')')->get();
            // $company_id   = $products_det[0]->company_id;

            // print_r($products_det);exit;

            $filename = 'quote' . time() . '.pdf';

            $path = $_SERVER['DOCUMENT_ROOT'] . '/beczone/pdf/' . $filename;

            $data = 10;

            if ($quote->generate_date != '') {

                $quote_date = $quote->generate_date;

            } else {

                $quote_date = date("Y-m-d");

            }

            if ($opper_det->type == 2) {
                $oppertunity = Oppertunity::with('customer')->where('id', $quote->oppertunity_id)->first();

                if ($quote->history_status != 'new') {
                    $productId = explode(',', $quote->product_id);

                    $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('product_id', $productId)->get();
                } else {
                    $quoteIds = explode(',', $quote->opper_product_id);

                    $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('id', $quoteIds)->get();
                }
                //print_r($productDetails);
                //die();
                //echo $quote->company_type;
                $contractdata = [
                    'products'      => $productDetails,
                    'hospital_desi' => $oppertunity->customer->hospital_desi,
                    'customer'      => $oppertunity->customer->business_name,
                    'district_name' => $oppertunity->customer->district_name,
                    'address'       => $oppertunity->customer->address1,
                    'start_date'    => $oppertunity->contract_start_date,
                    'end_date'      => $oppertunity->contract_end_date,
                    'quote'         => $quote,
                    'service_type'  => $oppertunity->service_type,
                ];
                if ($quote->company_type == "GE") {
                    $pdf = PDF::loadView('staff.oppertunity.contract_ge_pdf', $contractdata);
                } else {
                    $pdf = PDF::loadView('staff.oppertunity.contract_bec_pdf', $contractdata);
                }
            } else {

                $quoteProduct     = QuoteProduct::where('quote_id', $id)->get();
                $hospital_name    = "";
                $hospital_pincode = '';
                $district_name    = '';
                $hospital_desi    = '';
                $gst              = '';
                $opurtunity       = Oppertunity::find($quote->oppertunity_id);

                $opurtunity_product = Oppertunity_product::where('oppertunity_id', $quote->oppertunity_id)->first();

                if ($quote->history_status != 'new') {
                    $productId = explode(',', $quote->product_id);

                    $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('product_id', $productId)->get();
                } else {
                    $quoteIds = explode(',', $quote->opper_product_id);

                    $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('id', $quoteIds)->get();
                }

                if (isset($opurtunity) && $opurtunity->user_id > 0) {
                    $user_det         = User::withTrashed()->find($opurtunity->user_id);
                    $hospital_name    = $user_det->business_name;
                    $hospital_desi    = $user_det->name;
                    $hospital_pincode = $user_det->zip;
                    $district_name    = $user_det->district_name;
                    $gst              = $user_det->gst;
                }
                $opp_type = $opper_det->company_type;

                if ($opper_det->company_type == 5) {

                    $pdf = PDF::loadView("staff.quote.pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
                }
                 elseif($opurtunity->company_type == 6){
                    $pdf = PDF::loadView("staff.quote.bec_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
    
                } else {
                    $pdf = PDF::loadView("staff.quote.techsure_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
                }

                // $pdf = PDF::loadHTML($html);
            }

            if ($opper_det->company_type != 6 || $opper_det->type == 2) {

                $url = url('/');

            } else {
                $url = "https://bechealthcare.com";
            }

            $data = [

                'subject' => $request->subject,

                'desc'    => $request->comment,

                'name'    => $contact_all_details->title . ' ' . $contact_all_details->name . ' ' . $contact_all_details->last_name,

                'url'     => $url,

            ];

            $email_send = $contact_all_details->email;

            $subject = $request->subject;

            $arr_img = [];
            if (count($products_det) > 0) {
                foreach ($products_det as $values) {
                    $op_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['product_id', $values->id]])->first();
                    if ($values->image_name1 != '') {
                        if (is_file(storage_path("app/public/products/" . $values->image_name1))) {
                            $arr_img[] = storage_path("app/public/products/" . $values->image_name1);
                        }
                    }

                    $opt_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['main_product_id', $values->id], ['optional', 1]])->get();
                    if (count($opt_pdt) > 0) {
                        foreach ($opt_pdt as $opd) {
                            $products_det_opp = Product::where('id', $opd->product_id)->first();
                            if ($values->image_name1 != '') {
                                if (is_file(storage_path("app/public/products/" . $products_det_opp->image_name1))) {
                                    $arr_img[] = storage_path("app/public/products/" . $products_det_opp->image_name1);
                                }
                            }

                        }
                    }

                }
            }

            if ($opper_det->type == 2) {

                $state_id = $opper_det->state;

                $success = Mail::send('email.contract_qoute', $data, function ($message) use ($state_id, $pdf, $data, $email_send, $subject, $arr_img, $engineer) {
                    //$email_send
                    $message->from('sales@biomedicalengineeringcompany.com', 'Biomedical Engineering Company');

                    $message->to($email_send, $subject)->subject

                        ($subject);

                    if ($state_id == '4') {

                        $message->replyTo('service@bechealthcare.com', 'Contract');
                    }

                    if ($state_id == '5') {
                        $message->replyTo('servicetn@bechealthcare.com', 'Contract');
                    }

                    $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                    $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');

                    if ($engineer != null) {
                        $message->replyTo($engineer->email, ucfirst($engineer->name));
                    }

                    $message->attachData($pdf->output(), "Proposal/Quotation.pdf");

                });

            } else {

                if ($opper_det->company_type != 6) {

                    $success = Mail::send('email.quote', $data, function ($message) use ($pdf, $data, $email_send, $subject, $arr_img, $engineer) {
                        //$email_send
                        $message->to($email_send, $subject)->subject

                            ($subject);

                        $message->from('sales@biomedicalengineeringcompany.com', 'Biomedical Engineering Company');
                        $message->replyTo('sales@biomedicalengineeringcompany.com', 'Sales');
                        $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                        // $message->replyTo('vshaji7@gmail.com', 'Shaji Varghese');
                        $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');
                        if ($engineer != null) {
                            $message->replyTo($engineer->email, ucfirst($engineer->name));
                        }

                        $message->attachData($pdf->output(), "Proposal/Quotation.pdf");

                        for ($j = 0; $j < count($arr_img); $j++) {
                            $message->attach($arr_img[$j]);
                        }
                    });
                } else {

                    $success = Mail::send('email.quote_bec', $data, function ($message) use ($pdf, $data, $email_send, $subject, $arr_img, $engineer) {
                        //$email_send
                        $message->to($email_send, $subject)->subject

                            ($subject);

                        $message->from('sales@biomedicalengineeringcompany.com', 'BEC Health Care');

                        $message->replyTo('sales@biomedicalengineeringcompany.com', 'Sales');
                        $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                        // $message->replyTo('vshaji7@gmail.com', 'Shaji Varghese');
                        $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');
                        if ($engineer != null) {
                            $message->replyTo($engineer->email, ucfirst($engineer->name));
                        }

                        $message->attachData($pdf->output(), "Proposal/Quotation.pdf");

                        for ($j = 0; $j < count($arr_img); $j++) {
                            $message->attach($arr_img[$j]);
                        }
                    });

                }

            }

            // return $pdf->stream();

            /*************************************************** */

        }

        /*********************************************Staff Mail************************************ */

        foreach ($staff_arr as $values_con) {

            $contact_all_details = Staff::where('id', $values_con)->first();

            if ($values_con == '35') {
                $contact_all_details->email = "service@bechealthcare.com";
            }

            if ($values_con == '121') {
                $contact_all_details->email = "servicetn@bechealthcare.com";
            }

            // if($contact_all_details->designation>0)

            // {

            //     $desig=Hosdesignation::find($contact_all_details->designation);

            // }
            $desig = '';

            /************* ****************************************/

            $quote = Quotehistory::where('id', $id)->first();

            //$product_id   = $quote->product_cart_id;

            $pid = [];

            if (strpos($quote->product_id, ',') !== false) {

                foreach ($pid_pro as $val) {
                    $op_pdt_main = Oppertunity_product::where([['oppertunity_id', $quote->oppertunity_id], ['product_id', $val]])->first();
                    if (isset($op_pdt_main->sale_amount)) {
                        $pid[] = $val;
                    }

                }
            } else {
                $pid[] = $quote->product_id;
            }

            $op_id = $quote->oppertunity_id;

            $hospital_name    = "";
            $hospital_pincode = '';
            $district_name    = '';
            $hospital_desi    = '';

            if ($op_id > 0) {

                $opper_det = Oppertunity::find($op_id);

                if ($opper_det->user_id > 0) {

                    $user_det = User::withTrashed()->find($opper_det->user_id);

                    $hospital_name    = $user_det->business_name;
                    $hospital_desi    = $user_det->name;
                    $hospital_pincode = $user_det->zip;
                    $district_name    = $user_det->district_name;

                }

            }

            //print_r($pid);die;

            //  $products_det = Product::whereIn('id', $pid)->orderBy('id', 'asc')->get();
            $products_det = Product::whereIn('id', $pid)->orderByRaw('FIELD(id,' . implode(',', $pid) . ')')->get();
            // $company_id   = $products_det[0]->company_id;

            // print_r($products_det);exit;

            $filename = 'quote' . time() . '.pdf';

            $path = $_SERVER['DOCUMENT_ROOT'] . '/beczone/pdf/' . $filename;

            $data = 10;

            if ($quote->generate_date != '') {

                $quote_date = $quote->generate_date;

            } else {

                $quote_date = date("Y-m-d");

            }

            if ($opper_det->type == 2) {
                $oppertunity = Oppertunity::with('customer')->where('id', $quote->oppertunity_id)->first();

                if ($quote->history_status != 'new') {
                    $productId = explode(',', $quote->product_id);

                    $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('product_id', $productId)->get();
                } else {
                    $quoteIds = explode(',', $quote->opper_product_id);

                    $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('id', $quoteIds)->get();
                }
                //print_r($productDetails);
                //die();
                //echo $quote->company_type;
                $contractdata = [
                    'products'      => $productDetails,
                    'hospital_desi' => $oppertunity->customer->hospital_desi,
                    'customer'      => $oppertunity->customer->business_name,
                    'district_name' => $oppertunity->customer->district_name,
                    'address'       => $oppertunity->customer->address1,
                    'start_date'    => $oppertunity->contract_start_date,
                    'end_date'      => $oppertunity->contract_end_date,
                    'quote'         => $quote,
                    'service_type'  => $oppertunity->service_type,
                ];

                if ($quote->company_type == "GE") {
                    $pdf = PDF::loadView('staff.oppertunity.contract_ge_pdf', $contractdata);
                } else {
                    $pdf = PDF::loadView('staff.oppertunity.contract_bec_pdf', $contractdata);
                }
            } else {

                $quoteProduct     = QuoteProduct::where('quote_id', $id)->get();
                $hospital_name    = "";
                $hospital_pincode = '';
                $district_name    = '';
                $hospital_desi    = '';
                $gst              = '';
                $opurtunity       = Oppertunity::find($quote->oppertunity_id);

                $opurtunity_product = Oppertunity_product::where('oppertunity_id', $quote->oppertunity_id)->first();

                if ($quote->history_status != 'new') {
                    $productId = explode(',', $quote->product_id);

                    $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('product_id', $productId)->get();
                } else {
                    $quoteIds = explode(',', $quote->opper_product_id);

                    $productDetails = Oppertunity_product::with('oppertunityProductIb')->where('oppertunity_id', $quote->oppertunity_id)->whereIn('id', $quoteIds)->get();
                }

                if (isset($opurtunity) && $opurtunity->user_id > 0) {
                    $user_det         = User::withTrashed()->find($opurtunity->user_id);
                    $hospital_name    = $user_det->business_name;
                    $hospital_desi    = $user_det->name;
                    $hospital_pincode = $user_det->zip;
                    $district_name    = $user_det->district_name;
                    $gst              = $user_det->gst;
                }
                $opp_type = $opper_det->company_type;

                if ($opper_det->company_type == 5) {

                    $pdf = PDF::loadView("staff.quote.pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
                } 
                elseif($opurtunity->company_type == 6){
                    $pdf = PDF::loadView("staff.quote.bec_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
    
                }
                else {
                    $pdf = PDF::loadView("staff.quote.techsure_pdf", compact('opp_type', 'quote', 'quoteProduct', 'hospital_name', 'hospital_desi', 'hospital_pincode', 'district_name', 'gst'));
                }

                // $pdf = PDF::loadHTML($html);

            }

            if ($opper_det->company_type != 6 || $opper_det->type == 2) {

                $url = url('/');

            } else {
                $url = "https://bechealthcare.com";
            }

            $data = [

                'subject' => $request->subject,

                'desc'    => $request->comment,

                'name'    => $contact_all_details->name,

                'url'     => $url,

            ];

            $email_send = $contact_all_details->email;

            $subject = $request->subject;

            $arr_img = [];
            if (count($products_det) > 0) {
                foreach ($products_det as $values) {
                    $op_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['product_id', $values->id]])->first();
                    if ($values->image_name1 != '') {
                        if (is_file(storage_path("app/public/products/" . $values->image_name1))) {
                            $arr_img[] = storage_path("app/public/products/" . $values->image_name1);
                        }
                    }

                    $opt_pdt = Oppertunity_product::where([['oppertunity_id', $op_id], ['main_product_id', $values->id], ['optional', 1]])->get();
                    if (count($opt_pdt) > 0) {
                        foreach ($opt_pdt as $opd) {
                            $products_det_opp = Product::where('id', $opd->product_id)->first();
                            if ($values->image_name1 != '') {
                                if (is_file(storage_path("app/public/products/" . $products_det_opp->image_name1))) {
                                    $arr_img[] = storage_path("app/public/products/" . $products_det_opp->image_name1);
                                }
                            }

                        }
                    }

                }
            }

            $contact_list      = $request->contact;
            $arr_contact_name  = [];
            $arr_contact_email = [];

            $arr_contact_name[]  = $engineer->name;
            $arr_contact_email[] = $engineer->email;
            if (count($contact_list) > 0) {
                foreach ($contact_list as $values_con) {
                    $contact_all_details = Contact_person::where('id', $values_con)->first();
                    $arr_contact_name[]  = $contact_all_details->name;
                    $arr_contact_email[] = $contact_all_details->email;
                }
            }

            if ($opper_det->type == 2) {

                $state_id = $opper_det->state;

                $success = Mail::send('email.contract_qoute', $data, function ($message) use ($arr_contact_name, $state_id, $pdf, $data, $email_send, $subject, $arr_contact_email, $engineer) {
                    // $email_send
                    $message->from('sales@biomedicalengineeringcompany.com', 'Biomedical Engineering Company');

                    $message->to($email_send, $subject)->subject

                        ($subject);

                    if ($state_id == '4') {

                        $message->replyTo('service@bechealthcare.com', 'Contract');
                    }

                    if ($state_id == '5') {
                        $message->replyTo('servicetn@bechealthcare.com', 'Contract');
                    }

                    $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                    $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');

                    if (count($arr_contact_email) > 0) {
                        foreach ($arr_contact_email as $keys => $val) {
                            $message->replyTo($arr_contact_email[$keys], $arr_contact_name[$keys]);
                        }
                    }

                    $message->attachData($pdf->output(), "Quotation.pdf");

                });

            } else {

                if ($opper_det->company_type != 6) {

                    $success = Mail::send('email.quote', $data, function ($message) use ($pdf, $data, $email_send, $subject, $arr_img, $arr_contact_name, $arr_contact_email) {
                        //$email_send
                        $message->to($email_send, $subject)->subject

                            ($subject);

                        $message->from('sales@biomedicalengineeringcompany.com', 'Biomedical Engineering Company');
                        $message->replyTo('sales@biomedicalengineeringcompany.com', 'Sales');
                        $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                        // $message->replyTo('vshaji7@gmail.com', 'Shaji Varghese');
                        $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');
                        if (count($arr_contact_email) > 0) {
                            foreach ($arr_contact_email as $keys => $val) {
                                $message->replyTo($arr_contact_email[$keys], $arr_contact_name[$keys]);
                            }
                        }

                        $message->attachData($pdf->output(), "Quotation.pdf");
                        for ($j = 0; $j < count($arr_img); $j++) {
                            if (is_file($arr_img[$j])) {
                                $message->attach($arr_img[$j]);
                            }
                        }

                    });

                } else {

                    $success = Mail::send('email.quote_bec', $data, function ($message) use ($pdf, $data, $email_send, $subject, $arr_img, $arr_contact_name, $arr_contact_email) {
                        //$email_send
                        $message->to($email_send, $subject)->subject

                            ($subject);

                        $message->from('sales@biomedicalengineeringcompany.com', 'BEC Health Care');

                        $message->replyTo('sales@biomedicalengineeringcompany.com', 'Sales');
                        $message->replyTo('ajithgm007@hotmail.com', 'Ajith George Mathew');
                        // $message->replyTo('vshaji7@gmail.com', 'Shaji Varghese');
                        $message->replyTo('josephrenji_80@hotmail.com', 'Renjith Joseph Mathew');
                        if (count($arr_contact_email) > 0) {
                            foreach ($arr_contact_email as $keys => $val) {
                                $message->replyTo($arr_contact_email[$keys], $arr_contact_name[$keys]);
                            }
                        }

                        $message->attachData($pdf->output(), "Quotation.pdf");
                        for ($j = 0; $j < count($arr_img); $j++) {
                            if (is_file($arr_img[$j])) {
                                $message->attach($arr_img[$j]);
                            }
                        }

                    });

                }

            }

            // return $pdf->stream();

            /*************************************************** */

        }
        $request->session()->flash('success', 'Quote Sent Successfully');

        return redirect('staff/list_oppertunity_products/' . $quote->oppertunity_id);

    }

    public function loadproductnames($id)
    {

        $product = Product::where('id', $id)->first()->optional_products;

        $op_pdt_id = explode(',', $product);

        $op_pdt['data'] = Product::select('id', 'name')->whereIn('id', $op_pdt_id)->get();

        /*foreach($op_pdt as $row)

        {

        echo "<option value='$row->id'>$row->name</option>";

        }*/

        echo json_encode($op_pdt);

        exit;

    }

    public function view_customer($id)
    {

        $user = User::find($id);

        $country = $user->country_id != '' ? Country::where('id', $user->country_id)->first()->name : '';

        $state = $user->state_id != '' ? State::where('id', $user->state_id)->first()->name : '';

        $district = $user->district_id != '' ? District::where('id', $user->district_id)->first()->name : '';

        $taluk = $user->taluk_id != '' ? Taluk::where('id', $user->taluk_id)->first()->name : '';

        $staff = $user->staff_id != '' ? Staff::where('id', $user->staff_id)->first()->name : '';

        $customer_category = $user->customer_category_id != '' ? Customercategory::where('id', $user->customer_category_id)->first()->name : '';

        $oppertunity = Oppertunity::where('user_id', $id)->get();

        return view('staff.oppertunity.view_customer', ['user' => $user, 'country' => $country, 'state' => $state, 'taluk' => $taluk, 'staff' => $staff, 'customer_category' => $customer_category, 'district' => $district, 'oppertunity' => $oppertunity]);

    }

    public function view_contact($id)
    {

        $user = User::find($id);

        $country = Country::all();

        $oppertunity = Oppertunity::where('user_id', $id)->get();

        $hosdeparment = Hosdeparment::all();

        $hosdesignation = Hosdesignation::all();

        $contact_person = DB::select('select * from contact_person where  `user_id`="' . $id . '" ORDER BY `created_at` DESC');

        return view('staff.oppertunity.view_contact', compact('user', 'country', 'contact_person', 'hosdeparment', 'hosdesignation', 'oppertunity'));

    }

    public function productdetail(Request $request)
    {

        $data = DB::table('oppertunity_products')

            ->join('products', 'oppertunity_products.product_id', '=', 'products.id')

            ->where('oppertunity_products.oppertunity_id', $request->data)->select('products.name')->get();

        return view('staff.oppertunity.product_list_popup', ['products' => $data]);

    }

    public function approve_quote(Request $request)
    {
        //$qh                       = Quotehistory::where('quote_status','request')->orderBy('id','desc')->get();

        if ($request->ajax()) {

            $data = Quotehistory::where('quotehistories.quote_status', 'request')
                ->join('oppertunities', 'quotehistories.oppertunity_id', '=', 'oppertunities.id')
                ->join('staff', 'oppertunities.staff_id', '=', 'staff.id')
                ->select('quotehistories.*', 'staff.name as staff_name');

            if (session('STAFF_ID') == '32') {
                $opportunityIds = Oppertunity::where('type', '!=', '2')->pluck('id')->toArray();
                $data->whereIn('oppertunity_id', $opportunityIds);
            }

            if (session('STAFF_ID') == '130' ||session('STAFF_ID') == '129' ) {

                // $staff_ids =Staff::where('category_id',8)->pluck('id')->toArray();

                // $opportunityIds = Oppertunity::whereIn('created_by_id',$staff_ids)->pluck('id')->toArray();
                // $data->whereIn('oppertunity_id', $opportunityIds);
            }

            if (session('STAFF_ID') == '121') {
                $opportunityIds = Oppertunity::where('type', '2')->where('state', '5')->pluck('id')->toArray();
                $data->whereIn('oppertunity_id', $opportunityIds);
            }

            $data->orderBy('id', 'desc')->get();

            return Datatables::of($data)

                ->addColumn('name', function ($data) {
                    $oppertunity = Oppertunity::find($data->oppertunity_id);
                    if ($oppertunity) {
                        $staff = Staff::find($oppertunity->staff_id);

                        if ($staff) {
                            return $staff->name;
                        } else {
                            return '';
                        }
                    } else {
                        return '';
                    }
                    // return optional($data->quotestaff->staff)->name??"";

                })
                ->addColumn('user_name', function ($data) {
                    $oppertunity = Oppertunity::find($data->oppertunity_id);
                    if ($oppertunity) {
                        $user = User::find($oppertunity->user_id);
                        if ($user) {
                            return $user->business_name;
                        } else {
                            return '';
                        }

                    } else {
                        return '';
                    }

                })
                //   ->addColumn('district',function($data){
                //       $oppertunity         = Oppertunity::find($data->oppertunity_id);
                //       if($oppertunity)
                //       {
                //         $district = District::find($oppertunity->district_id);
                //         if($district){ return $district->name;}else{ return '';}
                //       }
                //       else{
                //           return '';
                //       }

                //      })
                ->addColumn('quote_no', function ($data) {
                    return $data->quote_reference_no;
                })
                ->addColumn('created_at_time', function ($data) {

                    return $data->created_at ? Carbon::parse($data->created_at)->format('d-m-Y H:i:s') : '';
                })

                ->addColumn('opper_amount', function ($data) {
                    // $oppertunity         = Oppertunity::find($data->oppertunity_id);
                    if ($data->quote_amount) {
                        return $this->IND_money_format($data->quote_amount);

                    } else {
                        return 0;
                    }

                })

                ->addColumn('oppertunity_name', function ($data) {
                    $oppertunity = Oppertunity::find($data->oppertunity_id);

                    if ($oppertunity) {
                        return '<a target="_blank" class="btn btn-primary btn-xs" href="' . route('staff.edit_oppertunity', "$oppertunity->id") . '" title="Edit">' . $oppertunity->oppertunity_name . '</a>';

                    } else {
                        return '';
                    }

                })

                ->addColumn('action', function ($data) {

                    $quet_content = '
                           CONTRACT PERIOD: 1 YEAR<br>
                            PAYMENT TERMS: 100% ADVANCE<br>
                            FOR Biomedical Engineering Company<br>
                            AUTHORISED SIGNATORY<br>
                        ';

                    $button = '';

                    $oppertunity = Oppertunity::find($data->oppertunity_id);

                    if (! empty($oppertunity->type) && $oppertunity->type == 2) {
                        $button .= '
                        <a class="btn btn-sm btn-default table-icon" title="Preview" target="_blank" id="btn_preview" href="' . url('staff/preview_contract_quote/' . $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                        ';
                        } else {
                            $button .= '
                            <a class="btn btn-sm btn-default table-icon" title="Preview" target="_blank" id="btn_preview" href="' . url('staff/preview_quote/' . $data->id) . '"><i class="fa fa-eye" aria-hidden="true"></i></a>
                            ';
                    }

                    if ($data->approved_status == 'Y') {
                        if ($data->quote_status == 'request') {
                            $button .= ' <a class="viewer btn btn-sm btn-warning table-icon" title="Send" data-id="' . $data->id . '" ><i class="fa fa-paper-plane-o" aria-hidden="true"></i></a> ';
                        } else {
                            $button .= ' <a class="btn btn-sm btn-warning table-icon" title="Already Sent" disabled> </span><i class="fa fa-check-square-o" aria-hidden="true"></i></a> ';
                        }

                    }

                    if ($data->approved_status == 'N') {

                        $button .= ' <a class="viewer btn btn-sm btn-warning table-icon" id="view_btn_' . $data->id . '" style="display:none;" title="Send" data-id="' . $data->id . '" >
                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i></a> ';

                        $button .= ' <a class=" btn btn-sm btn-warning approve table-icon" title="Approve" data-id="' . $data->id . '" > <i class="fa fa-check" aria-hidden="true"></i></a>  ';
                    }

                    if (! empty($oppertunity->type) && $oppertunity->type == 2) {

                        if ($data->terms_condition == '') {
                            $button .= ' <a class="btn btn-sm btn-warning edit_terms table-icon" title="Edit Terms" data-id="' . $data->id . '" data-desc="' . $quet_content . '" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  ';
                        } else {
                            $button .= ' <a class="btn btn-sm btn-warning edit_terms table-icon" title="Edit Terms" data-id="' . $data->id . '" data-desc="' . $data->terms_condition . '" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  ';
                        }

                    }
                     else {
                        if ($data->terms_condition == '') {
                            if (optional($oppertunity)->company_type == 5) {

                                $button .= ' <a class="btn btn-sm btn-warning edit_terms table-icon" title="Edit Terms" data-id="' . $data->id . '" data-desc="' . setting('QUOTE_TERM') . '" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  ';
                            } elseif(optional($oppertunity)->company_type == 6) {

                                $button .= ' <a class="btn btn-sm btn-warning edit_terms table-icon" title="Edit Terms" data-id="' . $data->id . '" data-desc="' . setting('BEC_QUOTE') . '" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  ';

                            }

                            if( optional($oppertunity)->company_type == 122) {
                                $button .= ' <a class="btn btn-sm btn-warning edit_terms table-icon" title="Edit Terms" data-id="' . $data->id . '" data-desc="' . setting('TECHSURE_QUOTE') . '" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  ';

                            }

                        } else {

                            if (optional($oppertunity)->company_type == 5) {

                                $button .= ' <a class="btn btn-sm btn-warning edit_terms table-icon" title="Edit Terms" data-id="' . $data->id . '" data-desc="' . $data->terms_condition . '" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  ';
                            } elseif(optional($oppertunity)->company_type == 6) {

                                $button .= ' <a class="btn btn-sm btn-warning edit_terms table-icon" title="Edit Terms" data-id="' . $data->id . '" data-desc="' . $data->terms_condition . '" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  ';

                            }
                            if( optional($oppertunity)->company_type == 122) {
                                $button .= ' <a class="btn btn-sm btn-warning edit_terms table-icon" title="Edit Terms" data-id="' . $data->id . '" data-desc="' . $data->terms_condition . '" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  ';

                            }
                            // $button .= ' <a class="btn btn-sm btn-warning edit_terms table-icon" title="Edit Terms" data-id="' . $data->id . '" data-desc="' . $data->terms_condition . '" ><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>  ';
                        }
                    }

                    if (session('STAFF_ID') == '35' || session('STAFF_ID') == '121') {
                        $button .= '
                        <a class="btn btn-sm  btn-secondary table-icon" title="Edit" target="_blank" id="btn_oppertunity" href="' . url('staff/list_oppertunity_products/' . $oppertunity->id) . '"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                        ';
                    }

                    return $button;
                })

                ->rawColumns(['name', 'opper_amount', 'action', 'oppertunity_name'])->addIndexColumn()->make(true);
            }

        return view('staff.oppertunity.approve_quote');

        //return view('admin.oppertunity.approve_quote',array('qhistory'=>$qh));

    }

    public function approve_quote_history(Request $request)
    {
        $quote_his                  = Quotehistory::find($request->id);
        $quote_his->approved_status = 'Y';
        $quote_his->save();
    }

    public function delete_quote_history(Request $request)
    {
        $opper = Oppertunity::where('quotehistory_id', $request->id)->first();

        Quotehistory::destroy($request->id);

        if (! empty($opper)) {
            $quete = Quotehistory::where('oppertunity_id', $opper->id)->latest()->first();
            if (! empty($quete)) {
                $opper->quotehistory_id = $quete->id;
                $opper->save();
            }
        }

    }

    public function save_quote_terms(Request $request)
    {
        $quote_his                  = Quotehistory::find($request->id);
        $quote_his->terms_condition = $request->terms_condition;
        $quote_his->save();

        return response()->json(['success'=> 'term saved scuccessfully']);
    }


public function saveComment(Request $request )
{
    
    $staff_id = session('STAFF_ID');
    if($staff_id == 35){

    $oppertunity = Oppertunity::find($request->product_id);

    if ($oppertunity) {
        $oppertunity->comments = $request->comment;
        $oppertunity->save();

        return response()->json(['success' => true, 'message' => 'Comment saved successfully!', 'comment' => $oppertunity->comments]);
    }

    return response()->json(['success' => false, 'message' => 'oppertunity not found.']);
}
}


}
