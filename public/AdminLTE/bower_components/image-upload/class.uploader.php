<?php
/*********************************************************************************************
Author 	: SUMITH
Date	: 28-August-2013
Purpose	: Uploader class for image upload plugin
*********************************************************************************************/

class uploader extends UploadHandler
{
	protected $_properties;
	public $message;
	public $warning;
	//public $uploadfolder;
	//protected $category_id;
	protected $form_parameters	= array();
	
	function __construct($upload_folder)
	{
		$this->form_parameters['category_id'] 	= $_SESSION['category_id'];	
		
		parent::__construct($upload_folder);
		
		$this->_properties	= array();
		$this->message		= '';
		$this->warning		= false;
	}

	function __get($propertyName)
	{
		if (array_key_exists($propertyName, $this->_properties))
		{
			return $this->_properties[$propertyName];
		}

		return null;
	}

	public function __set($propertyName, $value)
	{
		return $this->_properties[$propertyName] = $value;
	}
	
	public function __destruct() 
	{
		unset($this->_properties);
	}
	
	protected function get_file_objects($iteration_method = 'get_file_object') {
        $upload_dir = $this->get_upload_path();
		
        if (!is_dir($upload_dir)) {
            return array();
        }
		
		if($this->upload_folder1 == 'image_gallery')
		{
		   $image_gallery	 = new image_gallery();
		   //$image_array		 =  $image_gallery->get_image_list_by_category($this->category_id);	
		   $image_array		 =  $image_gallery->get_image_list_by_category($this->form_parameters['category_id']);	
		}
		
        return array_values(array_filter(array_map(
            array($this, $iteration_method),
		    $image_array
            //scandir($upload_dir)
        )));
    }
	
	protected function handle_form_data($file, $index) {
        // Handle form data, e.g. $_REQUEST['description'][$index]
	   //return $_REQUEST['category_id'];
    }
	
	protected function handle_form_title($file, $index) {
        //$title= $_REQUEST['title'][$index];
       // return $title;
    }
	
	protected function handle_form_image_gallery_id($file, $index) {
        /*$image_gallery_id= $_REQUEST['image_gallery_id'][$index];
        return $image_gallery_id;*/
    }
	
	//Query execution
	public function query($query) {  		
		$database		= new database(); 
		$result			= $database->query($query);
		return $result;
	}  
	
	//Database entry for each image add
	public function add_img($whichimg)  
	{  
	    if($this->upload_folder1 == 'image_gallery')
		{
		   $image_gallery				 	   = new image_gallery();
		   $image_gallery->image_category_id   = $this->form_parameters['category_id']; //$this->category_id;
		   //$image_gallery->title   		 = $title;
		   $image_gallery->image_name	 = $whichimg;
		   $image_gallery->save();
		}
	}
	
	//Delete from db for each delete
	public function delete_img($delimg)  
	{  
		if($this->upload_folder1 == 'image_gallery')
		{
		   $image_gallery				 		= new image_gallery();
		   $image_gallery->remove_by_image($this->form_parameters['category_id'],$delimg ); //$this->category_id
		}
	}

}
?>