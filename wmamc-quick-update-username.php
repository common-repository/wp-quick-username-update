<?php
/*

* Plugin Name: Quick User Profile Update


* Description: This plugin will give admin authority of changing username from admin panel.

* Version: 1.0.0

* Author: Webman Technologies

* Requires at least: 4.4

* Tested up to: 4.9

* Text Domain: WMAMC-wp-quick-username-update

* License: GPLv2 or later



Quick User Profile Update is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Quick User Profile Update is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <https://www.gnu.org/licenses/>


 Copyright (C) 2018  Webman Technologies


 */
 
 defined( 'ABSPATH' ) or exit;
  
class WMAMC_wp_quick_update_username
{
		protected static $instance;
		protected $adminpage;
		protected $add;
	  
	  
		public function __construct()
		{
			register_activation_hook(__FILE__, array( $this, 'WMAMC_wp_quick_update_username_plugin_activate') );
		
			register_deactivation_hook(__FILE__, array( $this, 'WMAMC_wp_quick_update_username_plugin_deactivate') );
			
			add_action('admin_enqueue_scripts' , array($this,'WMAMC_wp_quick_update_username_enqueue_scripts'));
			
			add_action('user_row_actions' , array($this,'WMAMC_wp_quick_update_username_add_quick_edit_link'),10,2);
			
			add_action( 'admin_footer', array( $this, 'WMAMC_wp_quick_update_username_load_quick_edit_view' ), 10 );
			
			add_action( 'wp_ajax_user-inline-save', array( $this, 'WMAMC_wp_quick_update_username_save_inline_edit' ) );
		
		}
		
		
		public function WMAMC_wp_quick_update_username_enqueue_scripts($page) 
		{
			if ( 'users.php' === $page ) 
			{
				 wp_enqueue_style( 'WMAMC-quick-edit-styles', plugins_url( 'assets/css/style.css', __FILE__ ), false);
				 
				wp_enqueue_script( 'WMAMC-quick-edit-scripts', plugins_url( 'assets/js/quick-edit.js', __FILE__ ), array( 'jquery' ), false, true );

				$localize_array = array(
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'nonce'   => wp_create_nonce( 'wp-user-quick-edit' )
				);
				wp_localize_script( 'WMAMC-quick-edit-scripts', 'wpQUserUp', $localize_array );
			}
		}
		
		
		public function WMAMC_wp_quick_update_username_add_quick_edit_link($actions,$user)
		{
			
			if (current_user_can('edit_user',$user->ID)) 
			{

				$actions['inline hide-if-no-js'] = sprintf(
					'<a href="javascript:void(0)" class="user-quick-editinline" data-id="%d" aria-label="%s">%s</a>%s',
					$user->ID,
					esc_attr( sprintf( __( 'Quick edit inline' ), $user->display_name ) ),
					__( 'Quick Edit' ),
					$this->WMAMC_wp_quick_update_username_get_inlineedit_data($user)
				);
			}

			return $actions;
		}
		
		
	
		
		
		public function WMAMC_wp_quick_update_username_get_inlineedit_data($user)
		{
			
			$html  = '<div class="hidden" id="inline_' . $user->ID . '">';
			$html .= '<div class="ID">'. $user->ID. '</div>';
			$html .= '<div class="email">'. $user->user_email. '</div>';
			$html .= '<div class="username">'. $user->user_login. '</div>';
			$html .= '<div class="first_name">'. $user->first_name. '</div>';
			$html .= '<div class="last_name">'. $user->last_name. '</div>';
			$html .= '<div class="nickname">'. $user->nickname. '</div>';
			$html .= '<div class="description">'. $user->description. '</div>';
			$html .= '<div class="url">'. $user->user_url . '</div>';
			$html .= '<div class="display_name">'. $user->display_name. '</div>';
			$html .= '</div>';

			return $html;
		}
		
		
	
		
		
		
		public function WMAMC_wp_quick_update_username_load_quick_edit_view() 
		{
			
			global $current_screen;
			if ( 'users' == $current_screen->base ) 
			{
				require_once dirname( __FILE__ ) . '/views/quick-edit-view.php';
			}
		}
		
		
		public function WMAMC_wp_quick_update_username_save_inline_edit() 
		{
			global $wpdb;
			if ( ! isset( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'wp-user-quick-edit' ) ) {
				wp_send_json_error( __( 'Error: Nonce verification failed', 'WMAMC-wp-quick-username-update' ) );
			}

			if ( ! is_user_logged_in() || ! is_admin() ) {
				wp_send_json_error( __( 'Sorry, access denied', 'WMAMC-wp-quick-username-update' ) );
			}

			if ( ! current_user_can('edit_user') ) {
				wp_send_json_error( __( 'Sorry, you have not permission to edit this user', 'WMAMC-wp-quick-username-update' ) );
			}

			if ( ! isset( $_POST['user_id'] ) || empty( $_POST['user_id'] ) ) {
				wp_send_json_error( __( 'Sorry cannot edit this user', 'WMAMC-wp-quick-username-update' ) );
			}
			
				$uid = intval($_POST['user_id']);
				$result = edit_user($uid);
			
				$user_login = sanitize_user($_POST['username']);
				
				if ( is_wp_error($result )){
					wp_send_json_error( $result->get_error_messages() );
				
				}
				else
				{
					$userinfo = get_userdata($uid);
					$ulogin = $userinfo->user_login;
					if(!empty($user_login))
					{
						if($ulogin != $user_login)
						{
							if (username_exists($user_login))
							{
								wp_send_json_error(array('<strong>Error:</strong> Username Already Exists!' ));
							}
							else
							{
								if (preg_match('/[\'^£$%&*()}{#~?><>,|=_+¬-]/', $user_login))
									{
										wp_send_json_error(array('<strong>Error:</strong> Invalid characters!' ));
									}
									else
									{
										
										$wpdb->update($wpdb->users, array('user_login' => $user_login), array('ID' => $uid ));																				if(isset($_POST['role']) && $_POST['role'] !=''){																						$user_role = sanitize_user($_POST['role']);																							$result = wp_update_user(array('ID'=>$uid, 'role'=>$user_role));											
										}																				$to = esc_attr($userinfo->user_email);
										$subject = 'Username Changed By Admin';

										$headers = "From: " . strip_tags(get_option('admin_email')) . "\r\n";
										$headers .= "Reply-To: ". strip_tags(get_option('admin_email')) . "\r\n";
										$headers .= "MIME-Version: 1.0\r\n";
										$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
										
										$message = '<html><body>';
										$message .= '<h3>Username Changed By Admin</h3>';
										$message .= '<p>Dear User !</p>';
										$message .= '<p>Your username is changed by the admin due to some reasons. </p><p> New Username is <b>'.$user_login.' </b>and your password will be same .</p>';
										$message .= '<p>Thanks Admin</p>';
										$message .= '</body></html>';
										
										mail($to, $subject, $message, $headers);
									
									}
							}
						}
					}
					else
					{
						wp_send_json_error(array('<strong>Error:</strong> Username cannot be empty!' ));
					}
				}
		

			$wp_user_list_table = _get_list_table('WP_Users_List_Table', array( 'screen' => 'users' ) );

			$user_obj = get_userdata($uid);
			
			ob_start();
			echo $wp_user_list_table->single_row( $user_obj, '', '', count_user_posts( $user_obj->ID ) );
		
			$output = ob_get_clean();
			
			wp_send_json_success( $output );
		
			
		}
		
		public function WMAMC_wp_quick_update_username_plugin_activate()
		{
			
			
		}
		
		
		
		public function WMAMC_wp_quick_update_username_plugin_deactivate()
		{
			
		}
		
		 
		
		
		
	  
		public function WMAMC_wp_quick_update_username_instance()
		{
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}
	  
	  
	  
}
  
function WMAMC_wp_quick_update_username() 
{
	return WMAMC_wp_quick_update_username::WMAMC_wp_quick_update_username_instance();
}
WMAMC_wp_quick_update_username();

?>