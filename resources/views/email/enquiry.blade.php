@extends('email.emailhead')

@section('content')


 <!-- header-->
 <table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; color:#000">
                                        <tbody>
                                           	 <tr>
                                                    
                                            	   <td>
                                                   		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff; margin-top:10px; color:#000">
                                                	<tr>
                                                	<tr>
                                            			<td style="height:30px;"></td>
                                            		</tr>
                                                    
                                                    <tr>
                                            			<td style="height:30px;">Name {{$name}}</td>
                                            		</tr>
                                                    <tr>
                                            			<td style="height:30px;">Email {{$email}}</td>
                                            		</tr>
                                                    <tr>
                                            			<td style="height:30px;">Phone {{$phone}}</td>
                                            		</tr>
                                                    <tr>
                                            			<td style="height:30px;">Enquiry <a href="{{ url('products/'.$messages) }}">{{ url('products/'.$messages) }}<a></td>
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