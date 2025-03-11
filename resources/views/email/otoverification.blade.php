
@extends('email.emailhead')

@section('content')


 <!-- header-->
 <table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff;">
                                        <tbody>
                                           	 <tr>
                                                    
                                            	   <td>
                                                   		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; margin-top:10px;">
                                                	<tr>
                                                	<tr>
                                            			<td style="height:30px;"></td>
                                            		</tr>
                                                    
                                                    <tr>

<td style="height:30px;">Hi {{$name}},

<br><br>
We’ve received a email verify request for your Beczone account. To complete your request, enter the following verification code into the security field:
<br><br>
<span style="background-color: #fff; color: #0078E6; text-decoration: none; padding: 9px 17px;border-radius:5px;" >{{$unique_code}}</span>

    
<br><br>

Regards<br>
BEC
</td>

</tr>
                                                    

                                                     <tr>
                                                        <td style="height:30px;"></td>
                                                    </tr>
                                                </table>
                                                </td>
                                                
                                            </tr>
                                            
                                            
                                       </tbody>
                                    </table>
                                    <!-- header -->




@endsection
                    

