
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

To stat using Beczone, just click the verify email button below
<br><br>
<a style="background-color: #0078E6; color: white; text-decoration: none; padding: 9px 17px;" href="https://biomedicalengineeringcompany.com/marketspace/verify/{{$unique_code}}">Verify Account</a>

    
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
                    

