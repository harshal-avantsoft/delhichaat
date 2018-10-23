<?php 
class WAS_Wpml
{
	public function __construct()
	{
	}
	public function wpml_is_active()
	{
		return class_exists('SitePress');
	}
	public function get_translated_id( $obj_ids, $type = 'product', $return_original = true)
	{
		//set_error_handler(function() {  });
		$result = array();
		if(!isset($obj_ids))
			return $result;
		$obj_ids = !is_array($obj_ids) ? array(0 => $obj_ids) : $obj_ids ;
		
		if (class_exists('SitePress')) 
		{
			
			$languages = icl_get_languages('skip_missing=0&orderby=code');
			$lang_array = array(); 
			if(!empty($languages))
				foreach($languages as $l)
					//if(!$l['active']) 
					{
						array_push($lang_array, $l['language_code']);
					}
			foreach($obj_ids as $obj_id)
				foreach($lang_array as $lang)
				{
					if(function_exists('icl_object_id'))
						$translation = icl_object_id($obj_id, $type, $return_original, $lang); //product_cat || product
					else
						$item_translated_id = apply_filters( 'wpml_object_id', $obj_id, $type, $return_original, $lang );
					
					if($translation && !in_array($translation, $result) && $translation != $obj_id)
						$result[] = $translation;
				}
		} 
		return $result; //empty if none
	}
}
?>