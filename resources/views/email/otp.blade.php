
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
{{$nmessagetxt}}
<br><br>
    
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
                    

