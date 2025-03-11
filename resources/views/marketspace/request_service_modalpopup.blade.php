<div class="modal fade" id="acceptmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="hiretitle">Apply For Job</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form name="accept_form_approve" id="accept_form_approve">
            <p id="date"></p>
            <p id="desc"></p>
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Your Quote </label>
                <input type="number" class="form-control" id="accept_quote_price" name="accept_quote_price" placeholder="RS.1000">
                <input type="hidden" class="form-control" id="accept_id" name="accept_id">
              </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Select Possible Date</label>
            <input type="text" class="form-control" id="accept_date" name="accept_date">
          
          </div>
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Select Possible Time</label>
            <input type="time" class="form-control" id="accept_time" name="accept_time">
        
          </div>
         
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="approve_accept_job">Approve</button>
        <div class="acceptjob_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
      </div>
    </div>
    </div>
    </div>
    
    <div class="modal fade" id="successmodal_accept" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                     </div>
                    
                    <div class="modal-body">
                       
                        <div class="thank-you-pop">
                            <img src="{{ asset('images/accepted-icon.svg') }}" alt="" width="100" height="100">
                            <div class="modal-pop-up-content">
                              <h1>Job Accepted!</h1>
                            <p>Job accepted is send to customer.</p>
                            </div>
                            
                            
                         </div>
                         
                    </div>
                    
                </div>
            </div>
        </div>
    
    <div class="modal fade" id="acceptmodal_customer" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="hiretitle_customer"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
            </div>
      <div class="modal-body">
        <form name="accept_form_approve_customer" id="accept_form_approve_customer">
            <p id="date_customer"></p>
            <p id="desc_customer"></p>
            <p id="accept_customer_date"></p>
            <form name="accept_form_approve_cus" id="accept_form_approve_cus">
            <input type="hidden" class="form-control" id="customer_accept_id" name="customer_accept_id">
       
         
            </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="approve_accept_job_customer">Approve</button>
        <div class="acceptjob_cust_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
      </div>
    </div>
    </div>
    </div>
    
    <div class="modal fade" id="successmodal_accept_cust" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                     </div>
                    
                    <div class="modal-body">
                       
                        <div class="thank-you-pop">
                            <img src="{{ asset('images/accepted-icon.svg') }}" alt="" width="100" height="100">
                            <div class="modal-pop-up-content">
                            <h1>Job Approved!</h1>
                            <p>Your job accepted service staff will contact soon.</p>
                          </div>
                         </div>
                         
                    </div>
                    
                </div>
            </div>
        </div>
    
    <div class="modal fade" id="confirm_complete" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
            <h5 class="modal-title" >Confirm</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
                    
                     <div class="modal-body">
                Are you sure want complete this job?
            </div>
            <div class="modal-footer">
                <input type="hidden" name="job_complete_id" id="job_complete_id">
                <div class="completejob_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="job_complete">Complete</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
                    
                </div>
            </div>
        </div>
     
    <div class="modal fade" id="successmodal_complete_req" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                     </div>
                    
                    <div class="modal-body">
                       
                        <div class="thank-you-pop">
                            <img src="{{ asset('images/accepted-icon.svg') }}" alt="" width="100" height="100">
                            <div class="modal-pop-up-content">
                            <h1>Job Completed!</h1>
                            <!-- <p>Your job accepted service staff will contact soon.</p> -->
                          </div>
                         </div>
                         
                    </div>
                    
                </div>
            </div>
        </div>
      
        <div class="modal fade" id="confirm_reject_staff" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
            <h5 class="modal-title" >Confirm</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
                    
                     <div class="modal-body">
                Are you sure want reject this job?

             

            </div>
            <div class="modal-footer">
                <input type="hidden" name="job_reject_staff_id" id="job_reject_staff_id">
                <div class="rejectjob_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="job_reject_staff">Reject</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
                    
                </div>
            </div>
        </div>

    <div class="modal fade" id="confirm_reject" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
            <h5 class="modal-title" >Confirm</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
                    
                     <div class="modal-body">
                Are you sure want reject this job?

              

            </div>
            <div class="modal-footer">
                <input type="hidden" name="job_reject_id" id="job_reject_id">
                <div class="rejectjob_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="job_reject_customer_service">Reject</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
                    
                </div>
            </div>
        </div>
      
    <div class="modal fade" id="successmodal_reject_req" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                     </div>
                    
                    <div class="modal-body">
                       
                        <div class="thank-you-pop">
                            <img src="{{ asset('images/cancel-icon.svg') }}" alt="" width="100" height="100">
                            <div class="modal-pop-up-content">
                            <h1>Job Rejected!</h1>
                            <!-- <p>Your job accepted service staff will contact soon.</p> -->
                            </div>
                         </div>
                         
                    </div>
                    
                </div>
            </div>
        </div>
     
    <div class="modal fade" id="confirm_accept_job_auth" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
            <h5 class="modal-title" >Confirm</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
                    
                     <div class="modal-body">
                Are you sure want accept this job?
            </div>
            <div class="modal-footer">
                <input type="hidden" name="job_auth_accept_id" id="job_auth_accept_id">
                <div class="rejectjob_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="job_accept_auth">Accept</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
                    
                </div>
            </div>
        </div>
      
    <div class="modal fade" id="confirm_reject_job_auth" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                <div class="modal-header">
            <h5 class="modal-title" >Confirm</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
                    
                     <div class="modal-body">
                Are you sure want reject this job?
            </div>
            <div class="modal-footer">
                <input type="hidden" name="job_auth_reject_id" id="job_auth_reject_id">
                <div class="rejectjob_gif" style="display:none;"><img src="{{ asset('images/wait.gif') }}"></div>
                <button type="button" data-dismiss="modal" class="btn btn-primary" id="job_reject_auth">Reject</button>
                <button type="button" data-dismiss="modal" class="btn">Cancel</button>
            </div>
                    
                </div>
            </div>
        </div>
       
    <div class="modal fade" id="successmodal_accept_auth_req" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label=""><span>×</span></button>
                     </div>
                    
                    <div class="modal-body">
                       
                        <div class="thank-you-pop">
                            <img src="{{ asset('images/accepted-icon.svg') }}" alt="" width="100" height="100">
                            <div class="modal-pop-up-content">
                            <h1>Job Accepted!</h1>
                            <!-- <p>Your job accepted service staff will contact soon.</p> -->
                            </div>
                         </div>
                         
                    </div>
                    
                </div>
            </div>
        </div>