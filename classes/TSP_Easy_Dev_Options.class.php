<?php	
if ( !class_exists( 'TSP_Easy_Dev_Options' ) )
{
	/**
	 * Class to display admin settings in admin area
	 * @package 	TSP_Easy_Dev
	 * @author 		sharrondenice, thesoftwarepeople
	 * @author 		Sharron Denice, The Software People
	 * @copyright 	2013 The Software People
	 * @license 	APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
	 * @version 	1.0
	 */
	abstract class TSP_Easy_Dev_Options
	{
		/**
		 * Does the plugin need a parent page?
		 *
		 * @var boolean
		 */
		public $has_parent_page 		= false;
		/**
		 * Does the plugin save settings options?
		 *
		 * @var boolean
		 */
		public $has_options_page 	= false;
		/**
		 * Does the plugin save widget options?
		 *
		 * @var boolean
		 */
		public $has_widget_options 		= false;
		/**
		 * Does the plugin save shortcode options?
		 *
		 * @var boolean
		 */
		public $has_shortcode_options 	= false;
		/**
		 * The URL link to the settings menu icon
		 *
		 * @var string
		 */
		private $menu_icon;
		/**
		 * The array of global values for the plugin
		 *
		 * @var array
		 */
		private $settings 				= array(); // sub-classes can call directly
		/**
		 * A boolean to turn debugging on for this class
		 *
		 * @ignore
		 *
		 * @var boolean
		 */
		private $debugging 				= false;
				
		/**
		 * Constructor
		 *
		 * @ignore
		 *
		 * @since 1.0
		 *
		 * @param array $settings Required the default plugin settings
		 * @param boolean $has_parent_page Optional does the plugin have a parent/company page - default true
		 * @param boolean $has_options_page Optional does the plugin have an options page - default true
		 *
		 * @return none
		 */
		public function __construct( $settings, $has_parent_page = true, $has_options_page = true ) 
		{
			$this->settings				= $settings;
			
			$this->has_options_page 	= $has_options_page;
			$this->has_parent_page 		= $has_parent_page;
		}//end __construct
				
		/**
		 * Intialize the options class
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none
		 */
		public function init ()
		{
			$this->set_menu_icon( $this->get_value('plugin_icon') );
			add_action( 'admin_menu', 			array( $this, 'add_admin_menu' ) );
			
			if ( $this->has_options_page )
			{
				add_filter( 'plugin_action_links', 	array( $this, 'add_settings_link'), 10, 2 );
			}//end if
			
			self::register_options();
		}//end register_options
					
		/**
		 * Create settings entry in database
		 *
		 * @ignore
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none
		 */
		public function register_options ()
		{
			// Remove old plugin settigns
			if( get_option( $this->get_value('option_prefix_old') ) ) 
			{
				delete_option( $this->get_value('option_prefix_old') );
			}//end if

			$prefix = $this->get_value('option_prefix');
			
			$this->set_value('widget-fields-option-name', 	$prefix.'-widget-fields');
			$this->set_value('shortcode-fields-option-name', $prefix.'-shortcode-fields');
			$this->set_value('settings-fields-option-name', $prefix.'-settings-fields');
			
			// if option was not found this means the plugin is being installed
			if( $this->has_widget_options && !get_option( $this->get_value('widget-fields-option-name') ) ) 
			{
				add_option( $this->get_value('widget-fields-option-name'), $this->get_value('widget_fields') );
			}//end if

			// if option was not found this means the plugin is being installed
			if( $this->has_shortcode_options && !get_option( $this->get_value('shortcode-fields-option-name') ) ) 
			{
				add_option( $this->get_value('shortcode-fields-option-name'), $this->get_value('shortcode_fields') );
			}//end if

			// if option was not found this means the plugin is being installed
			if( $this->has_options_page && !get_option( $this->get_value('settings-fields-option-name') ) ) 
			{
				add_option( $this->get_value('settings-fields-option-name'), $this->get_value('settings_fields') );
			}//end if
		}//end register_options

					
		/**
		 * Remove settings entry in database
		 *
		 * @ignore
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none
		 */
		public function deregister_options ()
		{
			// delete widget fields & data
			if( $this->has_widget_options && get_option( $this->get_value( 'widget-fields-option-name' ) ) ) 
			{
				delete_option( $this->get_value( 'widget-fields-option-name' ) );
			}//end if

			// delete shortcode fields & data
			if( $this->has_shortcode_options && get_option( $this->get_value( 'shortcode-fields-option-name' ) ) ) 
			{
				delete_option( $this->get_value( 'shortcode-fields-option-name' ) );
			}//end if

			// delete settings fields & data
			if( $this->has_options_page && get_option( $this->get_value( 'settings-fields-option-name' ) ) )
			{
				delete_option( $this->get_value( 'settings-fields-option-name' ) );
			}//end if
		}//end deregister_options

		/**
		 * Add settings links to the plugin option links (on plugins page)
		 *
		 * @ignore - Must be public because used in WordPress hooks
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none (can be overriden to remove settings links if they are not required)
		 */
		public function add_settings_link( $links, $file ) 
		{
			//Static so we don't call plugin_basename on every plugin row.
			static $this_plugin;
			if ( ! $this_plugin ) $this_plugin = $this->get_value('base_name');
		
			if ( $file == $this_plugin ){
					 $config_link = '<a href="admin.php?page=' . $this->get_value('name') . '.php">' . __( 'Settings', $this->get_value('name') ) . '</a>';
					 array_unshift( $links, $config_link );
			}
			
			return $links;
		} // end function plugin_action_links

		/**
		 * Add the default setting tab to the side menu to display TSP plugins
		 *
		 * @ignore - Must be public because used in WordPress hooks
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none (extend to add submenus to the parent menu)
		 */
		public function add_admin_menu()
		{
			$parent_slug = $this->get_value('parent_name');
			$menu_slug = $this->get_value('name').'.php';

			if ( !menu_page_url( $parent_slug, false ) && $this->has_parent_page )
			{
				// Make sure that each setting is nested into a company
				// menu area
				add_menu_page( $this->get_value('parent_title'), 
					$this->get_value('parent_title'), 
					'manage_options', 
					$parent_slug, 
					array( $this, 'display_parent_page' ), 
					$this->menu_icon, 
					$this->get_value('menu_pos'));
			}//endif
					
			if ( !menu_page_url( $menu_slug, false ) && $this->has_options_page )
			{				
				// If there is to be no parent menu then add the settings page as the main page
				if ( empty ( $parent_slug ) )
				{
					// Add menu as a stand-alone
					add_menu_page( __( $this->get_value('title_short'), $this->get_value('name') ), 
						__( $this->get_value('title_short'), $this->get_value('name') ), 
						'manage_options', 
						$menu_slug, 
						array( $this, 'display_plugin_options_page' ), 
						$this->menu_icon, 
						$this->get_value('menu_pos'));
				}//end if
				else
				{
					// Add child menu
					add_submenu_page($this->get_value('parent_name'),
						 __( $this->get_value('title_short'), $this->get_value('name') ), 
						 __( $this->get_value('title_short'), $this->get_value('name') ), 
						 'manage_options', 
						 $menu_slug, 
						 array( $this, 'display_plugin_options_page' ));
				}//end else
			}//endif
		}//end add_admin_menu
		
		/**
		 * Get a value from the settings array, recursively
		 *
		 * @since 1.0
		 *
		 * @param string $key Required get the key to return from settings
		 * @param array $arr Optional array to search recursively
		 * @param int $loop_count Optional for testing purposes only
		 *
		 * @return string the setting key value
		 */
		public function get_value ( $find_key, $arr = array(), $loop_count = 0 ) 
		{
			$return_value = null;
			
			// if the loop is just starting and there is no array value set
			// then we are being told to loop through our settings
			if ($loop_count == 0 && empty ( $arr ))
			{
				$arr = $this->settings;
			}//end if
			
			// if $arr is currently set to the find_key then return the array
			if ( array_key_exists( $find_key, $arr ) )
			{
				if ( $this->debugging )
				{
					d( "1. It took $loop_count recursive calls to find $find_key with [" . serialize( $arr[$find_key] ) . "]" );
				}//end if
				
				$return_value = $arr[$find_key];
			}//end elseif
			else
			{
				foreach( $arr as $key => $value) 
				{ 
					// in the previous condition statements we checked the first level of the array for the key
					// since it was not found we only want to look at the values that are arrays now
					if ( is_array( $value ) && !empty ( $value ))
					{
						// If the find_key was found in the second level then return it else
						// we need to recurse the  array
						if ( array_key_exists( $find_key, $value ) )
						{
							if ( $this->debugging )
							{
								d( "2. It took $loop_count recursive calls to find $find_key with [" . serialize( $value[$find_key] ) . "]" );
							}//end if
							
							$return_value = $value[$find_key];
							break; // stop looping
						}//end if
						else
						{
							if ( $this->debugging )
							{
								d( "Looking for $find_key in the $key array..." );
							}//end if
							$return_value = $this->get_value( $find_key, $value, $loop_count++ );
						}//end else
					}//end if
				}//end foreach
			}//end else
			
			return $return_value;
   		} // end function get_value

		
		/**
		 * Set a value given the settings key
		 *
		 * @since 1.0
		 *
		 * @param string $key Required the key to set
		 * @param string value Required the value to set the key to
		 *
		 * @return none
		 */
		public function set_value ( $key, $value ) 
		{
			$this->settings[$key] = $value;
		} // end function set_value

		
		/**
		 * Append a value to the settings array
		 *
		 * @since 1.0
		 *
		 * @param string $key Required the key to set
		 * @param string value Required the value to set the key to
		 *
		 * @return none
		 */
		public function add_value ( $key, $value ) 
		{
			$this->settings[$key][] = $value;
		} // end function add_value

		/**
		 * Add the menu icon to the settings menu
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none (extend to add submenus to the parent menu)
		 */
		private function set_menu_icon( $icon )
		{
			$this->menu_icon = $icon;
		}
		
		/**
		 * Must be implemented by the plugin to include a options page for the plugin, if not required implement empty. Best used for displaying informational data to user (ie Listing company information)
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none
		 */
		abstract public function display_parent_page();

		/**
		 * Must be implemented by the plugin to include a options page for the plugin, if not required implement empty
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none
		 */
		abstract public function display_plugin_options_page();
		
	}//end TSP_Easy_Dev_Options
}//endif
?>