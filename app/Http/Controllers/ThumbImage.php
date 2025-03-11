<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

		class ThumbImage extends Controller
		{
			// *** Class variables
			private $image;
		    private $width;
		    private $height;
			private $imageResized;
		    public  $thumb_width = 150;
	  		public  $thumb_height = 150;
			public  $allowed_image_types = array('image/pjpeg'=>"jpg",'image/jpeg'=>"jpg",'image/jpg'=>"jpg",'image/png'=>"png",'image/x-png'=>"png",'image/gif'=>"gif");
			public  $image_ext = "";
			
			function __construct($fileName)
			{
				// *** Open up the file
				$this->image = $this->openImage($fileName);

			    // *** Get width and height
			    $this->width  = imagesx($this->image);
			    $this->height = imagesy($this->image);
				
				//$this->thumb_width	= 100;
	  			//$this->thumb_height = 100;
				
				$this->image_ext = $this->get_allowed_image_types();
			}

			## --------------------------------------------------------

			private function openImage($file)
			{
				// *** Get extension
				$extension = strtolower(strrchr($file, '.'));

				switch($extension)
				{
					case '.jpg':
					case '.jpeg':
						$img = @imagecreatefromjpeg($file);
						break;
					case '.gif':
						$img = @imagecreatefromgif($file);
						break;
					case '.png':
						$img = @imagecreatefrompng($file);
						break;
					default:
						$img = false;
						break;
				}
				return $img;
			}

			## --------------------------------------------------------

			public function resizeImage($newWidth, $newHeight, $option="auto")
			{
				// *** Get optimal width and height - based on $option
				$optionArray = $this->getDimensions($newWidth, $newHeight, $option);

				$optimalWidth  = $optionArray['optimalWidth'];
				$optimalHeight = $optionArray['optimalHeight'];


				// *** Resample - create image canvas of x, y size
				$this->imageResized = imagecreatetruecolor($optimalWidth, $optimalHeight);
				imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $this->width, $this->height);


				// *** if option is 'crop', then crop too
				if ($option == 'crop') {
					$this->crop($optimalWidth, $optimalHeight, $newWidth, $newHeight);
				}
			}

			## --------------------------------------------------------
			
			private function getDimensions($newWidth, $newHeight, $option)
			{

			   switch ($option)
				{
					case 'exact':
						$optimalWidth = $newWidth;
						$optimalHeight= $newHeight;
						break;
					case 'portrait':
						$optimalWidth = $this->getSizeByFixedHeight($newHeight);
						$optimalHeight= $newHeight;
						break;
					case 'landscape':
						$optimalWidth = $newWidth;
						$optimalHeight= $this->getSizeByFixedWidth($newWidth);
						break;
					case 'auto':
						$optionArray = $this->getSizeByAuto($newWidth, $newHeight);
						$optimalWidth = $optionArray['optimalWidth'];
						$optimalHeight = $optionArray['optimalHeight'];
						break;
					case 'crop':
						$optionArray = $this->getOptimalCrop($newWidth, $newHeight);
						$optimalWidth = $optionArray['optimalWidth'];
						$optimalHeight = $optionArray['optimalHeight'];
						break;
				}
				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function getSizeByFixedHeight($newHeight)
			{
				$ratio = $this->width / $this->height;
				$newWidth = $newHeight * $ratio;
				return $newWidth;
			}

			private function getSizeByFixedWidth($newWidth)
			{
				$ratio = $this->height / $this->width;
				$newHeight = $newWidth * $ratio;
				return $newHeight;
			}

			private function getSizeByAuto($newWidth, $newHeight)
			{
				if ($this->height < $this->width)
				// *** Image to be resized is wider (landscape)
				{
					$optimalWidth = $newWidth;
					$optimalHeight= $this->getSizeByFixedWidth($newWidth);
				}
				elseif ($this->height > $this->width)
				// *** Image to be resized is taller (portrait)
				{
					$optimalWidth = $this->getSizeByFixedHeight($newHeight);
					$optimalHeight= $newHeight;
				}
				else
				// *** Image to be resizerd is a square
				{
					if ($newHeight < $newWidth) {
						$optimalWidth = $newWidth;
						$optimalHeight= $this->getSizeByFixedWidth($newWidth);
					} else if ($newHeight > $newWidth) {
						$optimalWidth = $this->getSizeByFixedHeight($newHeight);
						$optimalHeight= $newHeight;
					} else {
						// *** Sqaure being resized to a square
						$optimalWidth = $newWidth;
						$optimalHeight= $newHeight;
					}
				}

				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function getOptimalCrop($newWidth, $newHeight)
			{

				$heightRatio = $this->height / $newHeight;
				$widthRatio  = $this->width /  $newWidth;

				if ($heightRatio < $widthRatio) {
					$optimalRatio = $heightRatio;
				} else {
					$optimalRatio = $widthRatio;
				}

				$optimalHeight = $this->height / $optimalRatio;
				$optimalWidth  = $this->width  / $optimalRatio;

				return array('optimalWidth' => $optimalWidth, 'optimalHeight' => $optimalHeight);
			}

			## --------------------------------------------------------

			private function crop($optimalWidth, $optimalHeight, $newWidth, $newHeight)
			{
				// *** Find center - this will be used for the crop
				$cropStartX = ( $optimalWidth / 2) - ( $newWidth /2 );
				$cropStartY = ( $optimalHeight/ 2) - ( $newHeight/2 );

				$crop = $this->imageResized;
				//imagedestroy($this->imageResized);

				// *** Now crop from center to exact requested size
				$this->imageResized = imagecreatetruecolor($newWidth , $newHeight);
				imagecopyresampled($this->imageResized, $crop , 0, 0, $cropStartX, $cropStartY, $newWidth, $newHeight , $newWidth, $newHeight);
			}

			## --------------------------------------------------------

			public function saveImage($savePath, $imageQuality="100")
			{
				// *** Get extension
        		$extension = strrchr($savePath, '.');
       			$extension = strtolower($extension);

				switch($extension)
				{
					case '.jpg':
					case '.jpeg':
						if (imagetypes() & IMG_JPG) {
							imagejpeg($this->imageResized, $savePath, $imageQuality);
						}
						break;

					case '.gif':
						if (imagetypes() & IMG_GIF) {
							imagegif($this->imageResized, $savePath);
						}
						break;

					case '.png':
						// *** Scale quality from 0-100 to 0-9
						$scaleQuality = round(($imageQuality/100) * 9);

						// *** Invert quality setting as 0 is best, not 9
						$invertScaleQuality = 9 - $scaleQuality;

						if (imagetypes() & IMG_PNG) {
							 imagepng($this->imageResized, $savePath, $invertScaleQuality);
						}
						break;

					// ... etc

					default:
						// *** No extension - No save.
						break;
				}

				imagedestroy($this->imageResized);
			}


			## --------------------------------------------------------
			
			
			public function resizeThumbImage($image,$width,$height) {
			    $scale=1; //$this->width/$width;
				list($imagewidth, $imageheight, $imageType) = getimagesize($image);
				$imageType = image_type_to_mime_type($imageType);
				$newImageWidth = ceil($width * $scale);
				$newImageHeight = ceil($height * $scale);
				$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
				switch($imageType) {
					case "image/gif":
						$source=imagecreatefromgif($image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						$source=imagecreatefromjpeg($image); 
						break;
					case "image/png":
					case "image/x-png":
						$source=imagecreatefrompng($image); 
						break;
				}
				imagecopyresampled($newImage,$source,0,0,0,0,$newImageWidth,$newImageHeight,$width,$height);
				
				switch($imageType) {
					case "image/gif":
						imagegif($newImage,$image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						imagejpeg($newImage,$image,90); 
						break;
					case "image/png":
					case "image/x-png":
						imagepng($newImage,$image);  
						break;
				}
				
				chmod($image, 0777);
				return $image;
			}
			
			//You do not need to alter these functions
			public function cropThumbImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $rotate=0){
			    $scale = $this->thumb_width/$width;
				
				$scaleX = $this->thumb_width/$width;
				$scaleY = $this->thumb_height/$height;
                                
				
				list($imagewidth, $imageheight, $imageType) = getimagesize($image);
				$imageType = image_type_to_mime_type($imageType);
				
				/*$newImageWidth = ceil($width * $scale); //ceil($this->thumb_width*$scale); //ceil($width * $scale); //$width;
				$newImageHeight = ceil($height * $scale1); //ceil($this->thumb_height*$scale); //ceil($height * $scale); //$height*/
				
				$newImageWidth  = $this->thumb_width;
				$newImageHeight = $this->thumb_height;
				
				
				$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
				
				
				switch($imageType) {
					case "image/gif":
						$source=imagecreatefromgif($image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						$source=imagecreatefromjpeg($image); 
						break;
					case "image/png":
					case "image/x-png":
						$source=imagecreatefrompng($image); 
						break;
				}
                                
                               // $bgcolor 	= imagecolorallocate($newImage, 255, 255, 255);   
				//ImageFilledRectangle($newImage, 0, 0, $imagewidth, $imageheight, $bgcolor);
				//imagealphablending($newImage, true);
                                
                                if($rotate > 0) {
                                    if($rotate == 90) $rotate = -90;
                                    if($rotate == 270) $rotate = -270;
                                    $source = imagerotate($source, $rotate, 0);
                                }
                                //added for png resize transparency
				if ($imageType == "image/png" or $imageType == "image/x-png"){				
					$background = imagecolorallocate($newImage, 0, 0, 0);
                                        imagecolortransparent($newImage, $background);
					imagealphablending($newImage, false);
					imagesavealpha($newImage, true);								
				}else{
					$bgcolor 	= imagecolorallocate($newImage, 255, 255, 255);   
					ImageFilledRectangle($newImage, 0, 0, $imagewidth, $imageheight, $bgcolor);
					imagealphablending($newImage, true);
				}
				
				imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
				switch($imageType) {
					case "image/gif":
						imagegif($newImage,$thumb_image_name); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						imagejpeg($newImage,$thumb_image_name,100); 
						break;
					case "image/png":
					case "image/x-png":
						imagepng($newImage,$thumb_image_name);  
						break;
				}
				chmod($thumb_image_name, 0777);
				return $thumb_image_name;
			}
			
			
			public function createThumb($thumb_image_name, $image, $width, $height, $x1, $y1, $x2, $y2)
			{
				list($imagewidth, $imageheight, $imageType) = getimagesize($image);
				$imageType = image_type_to_mime_type($imageType);
				
				$dst_img=imagecreatetruecolor($this->thumb_width,$this->thumb_height);
				
				//$dst_img1 = imagefilter ( $dst_img , IMG_FILTER_BRIGHTNESS,-255); //IMG_FILTER_SMOOTH
				//$dst_img1 = imagefilter ( $dst_img , IMG_FILTER_CONTRAST,100);
				//$dst_img1 = imagefilter ( $dst_img , IMG_FILTER_SMOOTH, 20);
				
				switch($imageType) {
					case "image/gif":
						$source=imagecreatefromgif($image); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						$source=imagecreatefromjpeg($image); 
						break;
					case "image/png":
					case "image/x-png":
						$source=imagecreatefrompng($image); 
						break;
				}
				
				//$bgcolor 	= imagecolorallocate($dst_img, 255, 255, 255);   
				//ImageFilledRectangle($dst_img, 0, 0, $imagewidth, $imageheight, $bgcolor);
				//imagealphablending($dst_img, true);
                                
                                if ($imageType == "image/png" or $imageType == "image/x-png"){				
					$background = imagecolorallocate($dst_img, 0, 0, 0);
                                        imagecolortransparent($dst_img, $background);
					imagealphablending($dst_img, false);
					imagesavealpha($dst_img, true);								
				}else{
					$bgcolor 	= imagecolorallocate($dst_img, 255, 255, 255);   
					ImageFilledRectangle($dst_img, 0, 0, $imagewidth, $imageheight, $bgcolor);
					imagealphablending($dst_img, true);
				}
				
				imagecopyresampled($dst_img, $source, 0, 0, $x1, $y1, $this->thumb_width, $this->thumb_height, $x2-$x1,$y2-$y1); 
				
				switch($imageType) {
					case "image/gif":
						imagegif($dst_img,$thumb_image_name); 
						break;
					case "image/pjpeg":
					case "image/jpeg":
					case "image/jpg":
						imagejpeg($dst_img,$thumb_image_name,100); 
						break;
					case "image/png":
					case "image/x-png":
						imagepng($dst_img,$thumb_image_name);  
						break;
				}
				chmod($thumb_image_name, 0777);
				return $thumb_image_name;
			}
	
			
			//You do not need to alter these functions
			public function getHeight($image) {
				$size = getimagesize($image);
				$height = $size[1];
				return $height;
			}
			
			//You do not need to alter these functions
			public function getWidth($image) {
				$size = getimagesize($image);
				$width = $size[0];
				return $width;
			}
			
			public function get_allowed_image_types()
			{
				$allowed_image_ext = array_unique($this->allowed_image_types); 
				$image_ext = "";	
				foreach ($allowed_image_ext as $mime_type => $ext) {
    				$image_ext.= strtoupper($ext)." ";
				}
				
				return $image_ext;
			}

		}
?>

