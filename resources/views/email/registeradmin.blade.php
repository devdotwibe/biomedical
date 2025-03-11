

@extends('email.emailhead')

@section('content')



<style>
    .MsoNormal span, .MsoNormal {
    color: #000 !important;
}

</style>

 <table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff;">

                                        <tbody>

                                           	 <tr>

                                                    

                                            	   <td>

                                                   		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; margin-top:10px;">

                                                	<tr>

                                                	<tr>

                                            			<td style="height:30px;">Hi Admin,

													<br><br>

													New user registered.Here are the details <br><br>

													</td>

                                            		</tr>

                                                    

                                                    <tr>

                                            			<td style="height:30px;">Name: {{$name}}</td>

                                            		</tr>

                                                    <tr>

                                            			<td style="height:30px;">Email: {{$email}}</td>

                                            		</tr>

													<tr>

                                                        <td style="height:30px;">User Type: {{$user_want}}</td>

                                                        </tr>

													

                                                     <tr>

                                                        <td style="height:30px;">
														<br>
													Best Wishes
													<br>
													Beczone
													</td>

                                                    </tr>

                                                </table>

                                                </td>

                                                

                                            </tr>

                                            

                                            

                                       </tbody>

                                    </table>


@endsection

                    


