<?php
session_start();
$red = '';

if ($_SESSION['authorized'] != 1) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $req = $_SERVER['REQUEST_URI'];
    $req_out  = explode('/library/elfinder/', $req);
    if($_SERVER['SERVER_NAME'] != 'localhost' && $_SERVER['REMOTE_ADDR'] != '127.0.0.1') {
        $red = $protocol.$_SERVER['SERVER_NAME'].$req_out[0];
    } else {
        $red = $protocol."localhost/".$req_out[0];
    }

    header('Location:'.$red);
}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>elFinder 2.1.x source version with PHP connector</title>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=2" />

		<!-- jQuery and jQuery UI (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
		<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>

		<!-- elFinder CSS (REQUIRED) -->
		<link rel="stylesheet" type="text/css" href="css/elfinder.min.css">
		<link rel="stylesheet" type="text/css" href="css/theme.css">

		<!-- elFinder JS (REQUIRED) -->
		<script src="js/elfinder.min.js"></script>

		<!-- GoogleDocs Quicklook plugin for GoogleDrive Volume (OPTIONAL) -->
		<!--<script src="js/extras/quicklook.googledocs.js"></script>-->

		<!-- elFinder translation (OPTIONAL) -->
		<!--<script src="js/i18n/elfinder.ru.js"></script>-->

		<!-- elFinder initialization (REQUIRED) -->
		<script type="text/javascript" charset="utf-8">
			// Documentation for client options:
			// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
			/*$(document).ready(function() {
				$('#elfinder').elfinder({
					url : 'php/connector.php'  // connector URL (REQUIRED)
					// , lang: 'ru'                    // language (OPTIONAL)
				});
			});*/
		</script>
                
                <script type="text/javascript" charset="utf-8">
			// Helper function to get parameters from the query string.
			function getUrlParam(paramName) {
                            
				var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
				var match = window.location.search.match(reParam) ;

				return (match && match.length > 1) ? match[1] : '' ;
			}

			$().ready(function() {
                            var redirect = '<?php echo $red ?>';
                            if(redirect != '') {
                                window.location = redirect;
                            }
                            
				var funcNum = getUrlParam('CKEditorFuncNum');
                                

				var elf = $('#elfinder').elfinder({
                                        //customData: {name:'hi'},
					url : 'php/connector.php',
					getFileCallback : function(file) {
                                           // window.opener.CKEDITOR.tools.callFunction(funcNum, file);
                                            window.opener.CKEDITOR.tools.callFunction(funcNum, file.url);
                                            //elf.destroy();
                                            window.close();
					},
					resizable: false
				}).elfinder('instance');
			});
		</script>
	</head>
	<body>

		<!-- Element where elFinder will be created (REQUIRED) -->
		<div id="elfinder"></div>

	</body>
</html>
