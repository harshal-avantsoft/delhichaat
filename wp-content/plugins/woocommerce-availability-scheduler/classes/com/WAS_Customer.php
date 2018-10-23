<?php 
class WAS_Customer
{
 public function __construct() 
 {
	 if(is_admin())
		{
			add_action('wp_ajax_was_get_customers_list', array(&$this, 'ajax_get_customer_partial_list'));
		}
 }
 public function ajax_get_customer_partial_list()
 {
	 $customers = $this->get_customer_list($_GET['customer']);
	 echo json_encode( $customers);
	 wp_die();
 }
/*  public function get_user_roles($user_id)
 {
	 global $wpdb;
	$role = $wpdb->get_var("SELECT meta_value FROM {$wpdb->usermeta} WHERE meta_key = 'wp_capabilities' AND user_id = {$user_id}");
	  if(!$role) return 'non-user';
	$rarr = unserialize($role);
	$roles = is_array($rarr) ? array_keys($rarr) : array('non-user');
	return $roles[0];
} */
 public function get_customer_list($search_string = null, $search_by_ids = null)
 {
	 global $wpdb;
	 $additional_where = "";
	 if(is_array($search_by_ids))
	 {
		 $additional_where = " AND customers.ID IN ('".implode("','", $search_by_ids)."') ";
	 }
	 
	 $query_string = "SELECT customers.ID as customer_id, usermeta_name.meta_value as first_name, usermeta_surname.meta_value as last_name, usermeta_email.meta_value as email
						 FROM {$wpdb->users} AS customers
						 LEFT JOIN {$wpdb->usermeta} AS usermeta ON customers.ID = usermeta.user_id
						 LEFT JOIN {$wpdb->usermeta} AS usermeta_name ON customers.ID = usermeta_name.user_id AND usermeta_name.meta_key = 'billing_first_name'
						 LEFT JOIN {$wpdb->usermeta} AS usermeta_surname ON customers.ID = usermeta_surname.user_id AND usermeta_surname.meta_key = 'billing_last_name'
						 LEFT JOIN {$wpdb->usermeta} AS usermeta_email ON customers.ID = usermeta_email.user_id AND usermeta_email.meta_key = 'billing_email'
						 LEFT JOIN {$wpdb->usermeta} AS usermeta_phone ON customers.ID = usermeta_phone.user_id AND usermeta_phone.meta_key = 'billing_phone'
						 LEFT JOIN {$wpdb->postmeta} AS postmeta ON postmeta.meta_value = customers.ID  AND   postmeta.meta_key = '_customer_user'						 
						 WHERE usermeta.meta_key = '{$wpdb->prefix}capabilities'
						 {$this->get_user_role_list_for_sql_query()} 
						 {$additional_where} ";
						 /* AND  (usermeta.meta_value = 'a:1:{s:8:\"customer\";b:1;}'
						       OR   usermeta.meta_value = 'a:1:{s:10:\"subscriber\";b:1;}')
						"; */
	if($search_string)
			$query_string .=  " AND ( customers.ID LIKE '%{$search_string}%' OR usermeta_name.meta_value LIKE '%{$search_string}%' OR  usermeta_surname.meta_value LIKE '%{$search_string}%' OR usermeta_email.meta_value LIKE '%{$search_string}%' OR usermeta_phone.meta_value LIKE '%{$search_string}%')";
	
	$query_string .=  " GROUP BY customers.ID ";
	return $wpdb->get_results($query_string );
 }
 public function get_user_role_list_for_sql_query()
 {
	 /*(usermeta.meta_value = 'a:1:{s:8:\"customer\";b:1;}'
						OR   usermeta.meta_value = 'a:1:{s:10:\"subscriber\";b:1;}')*/
						 
	/* array(1) {
	  ["subscriber"]=>
	  bool(true)
	} */
	
	$roles = null;//WCCM_Options::get_option('allowed_roles');
	$roles_relation =  " OR ";
	if(!isset($roles) || empty($roles))
		//return " AND usermeta.meta_value IS NOT NULL  ";
		return " AND usermeta.meta_value NOT LIKE '%administrator%'  ";
	
    $result= "AND (";	
	$counter = 0;
	foreach($roles as $role)
	{
		if($counter > 0)
			$result .= " {$roles_relation} ";
		$result .= "usermeta.meta_value LIKE '%".serialize($role).serialize(true)."%'"; 
		$counter++;
	}
	return $result .= ")";
 }
}
?>