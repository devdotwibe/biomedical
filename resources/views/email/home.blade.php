
@extends('email.emailhead')

@section('content')

<!-- header-->
<table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff;">
                                        <tbody>
                                        
                                           	 <tr>
                                                    
                                            	   <td>
                                                   		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; margin-top:10px; color:#000">
                                                            
                                                           <tr>
                                                                <td style="height:30px;"></td>
                                                            </tr>
                                                            <tr>
                                                                
                                                            <td style="text-align:left;">Name</td>
                                                                <td style="text-align:left;">{{$name}} </td>
                                                                
                                                            </tr>
                                                            <tr>
                                                              <td style="text-align:left; height:30px">&nbsp;</td>
                                                              <td style="text-align:left;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                
                                                            <td style="text-align:left;">Email</td>
                                                                <td style="text-align:left;">{{$email}} </td>
                                                                
                                                            </tr>
                                                            <tr>
                                                              <td style="text-align:left; height:30px">&nbsp;</td>
                                                              <td style="text-align:left;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                
                                                            <td style="text-align:left;">Phone</td>
                                                                <td style="text-align:left;">{{$phone}} </td>
                                                                
                                                            </tr>
                                                            <tr> 
                                                              <td style="text-align:left; height:30px">&nbsp;</td>
                                                              <td style="text-align:left;">&nbsp;</td>
                                                            </tr>
                                                            <tr>
                                                                
                                                                <td style="text-align:left;">
                                                  
                                                            Message
                                                                </td>   
                                                                <td style="text-align:left;">
                                                                                                                  
                                                            {{$messages}}
                                                               
                                                            
                                                            
                                                                </td>
                                                                
                                                            </tr>
                                                        </table>
                                                </td>
                                                
                                            </tr>
                                            
                                            
                                       </tbody>
                                    </table>
                                    <!-- header -->

@endsection
                    

