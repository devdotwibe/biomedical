
<!DOCTYPE HTML>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- So that mobile webkit will display zoomed in -->
    <title>Email Template</title>
    <style type="text/css">
        * { box-sizing: border-box;-moz-box-sizing: border-box;-webkit-box-sizing: border-box; font-family: Arial, Helvetica, sans-serif;}
        /* Resets all body margins and padding to "0" for good measure. */        
        body {margin: 0;padding: 0; background: #eeedf2; }        
        table {padding: 0;border: 0;border-collapse: collapse;font-family: Arial, Helvetica, sans-serif;}        
        td {border: 0;}
        /* Resolves webkit padding issue. */        
        table {border-spacing: 0;}
        /* Resolves the Outlook 2007, 2010, and Gmail td padding issue. */        
        table td {border-collapse: collapse; }        
        body {-webkit-text-size-adjust: none;-ms-text-size-adjust: none;}        
        body {margin: 0;padding: 0;color: #ffffff;}        
        table {border-spacing: 0;}        
        table td {border-collapse: collapse;}
        .footmail p{margin-top:0 !important; margin-bottom:10px!important;}
.footmail p:first-child{margin-top:0 !important; margin-bottom:30px!important;}
    </style>
</head>

<body>

    <!-- <table width="100%" border="0" align="center" cellspacing="0" cellpadding="0" class="deviceWidth" style="font-family: Arial, Helvetica, sans-serif; background-color:#eeedf2;">
        <tbody>
            <tr>
                <td> -->
                    <table width="800" border="0" align="left" cellspacing="0" cellpadding="0" class="container" style=" background-color:#fff;">
                        <tbody>
                            <tr>
                                <td>
                                    <!-- header-->
                                    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0"   class="wrapper" style=" background-color:#fff;">
                                        <tbody>
                                            <tr>
                                            	<td style="height:15px;"></td>
                                            </tr>
                                            <tr>
                                                
                                                 <td class="header" style="text-align: right;">
                                                    <img src="{{ asset('images/techsure.jpg') }}" >

                                                </td>

                                                
                                            </tr>
                                             <tr>
                                                <td style="height:15px;"></td>
                                            </tr>   
                                       </tbody>
                                    </table>
                                    <!-- header -->
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <!-- header-->
                                    <table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
                                        <tbody>
                                            <tr>
                                                <td style="height:2px;background-color:#003d87;"></td>
                                            </tr>
                                         </tbody>
                                    </table>
                                 </td>  
                            </tr>
                            <tr>
                                <td>


                    @yield('content')

                    </td>
                            </tr>
                         </tbody>
                    </table>
                <!-- </td>
            </tr>
    </tbody>
</table> -->
</body>
</html>

