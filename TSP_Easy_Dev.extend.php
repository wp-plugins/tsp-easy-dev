<?php				
if ( !class_exists( 'TSP_Easy_Dev_Options_Easy_Dev' ) )
{
	/**
	 * TSP_Easy_Dev_Options_Easy_Dev - Extends the TSP_Easy_Dev_Options Class
	 * @package TSP_Easy_Dev
	 * @author sharrondenice, thesoftwarepeople
	 * @author Sharron Denice, The Software People
	 * @copyright 2013 The Software People
	 * @license APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
	 * @version $Id: [FILE] [] [DATE] [TIME] [USER] $
	 */
	
	/**
	 * @method void display_parent_page()
	 */
	class TSP_Easy_Dev_Options_Easy_Dev extends TSP_Easy_Dev_Options
	{
		/**
		 * Display all the plugins that The Software People has released
		 *
		 * @since 1.1.0
		 *
		 * @param none
		 *
		 * @return output to stdout
		 */
		public function display_parent_page()
		{
			$active_plugins			= get_option('active_plugins');
			$all_plugins 			= get_plugins();
		
			$free_active_plugins 	= array();
			$free_installed_plugins = array();
			$free_recommend_plugins = array();
			
			$pro_active_plugins 	= array();
			$pro_installed_plugins 	= array();
			$pro_recommend_plugins 	= array();
			
			$json 					= file_get_contents( $this->get_value('plugin_list') );
			$tsp_plugins 			= json_decode($json);
			
			foreach ( $tsp_plugins->{'plugins'} as $plugin_data )
			{
				if ( $plugin_data->{'type'} == 'FREE' )
				{
					if ( in_array($plugin_data->{'name'}, $active_plugins ) )
					{
						$free_active_plugins[] = (array)$plugin_data;
					}//endif
					elseif ( array_key_exists($plugin_data->{'name'}, $all_plugins ) )
					{
						$free_installed_plugins[] = (array)$plugin_data;
					}//end elseif
					else
					{
						$free_recommend_plugins[] = (array)$plugin_data;
					}//endelse
				}//endif
				elseif ( $plugin_data->{'type'} == 'PRO' )
				{
					if ( in_array($plugin_data->{'name'}, $active_plugins ) )
					{
						$pro_active_plugins[] = (array)$plugin_data;
					}//endif
					elseif ( array_key_exists($plugin_data->{'name'}, $all_plugins ) )
					{
						$pro_installed_plugins[] = (array)$plugin_data;
					}//endelseif
					else
					{
						$pro_recommend_plugins[] = (array)$plugin_data;
					}//endelse
				}//endelseif
			}//endforeach
			
			$free_active_count									= count($free_active_plugins);
			$free_installed_count 								= count($free_installed_plugins);
			$free_recommend_count 								= count($free_recommend_plugins);
	
			$free_total											= $free_active_count + $free_installed_count + $free_recommend_count;
	
			$pro_active_count									= count($pro_active_plugins);
			$pro_installed_count 								= count($pro_installed_plugins);
			$pro_recommend_count 								= count($pro_recommend_plugins);
			
			$pro_total											= $pro_active_count + $pro_installed_count + $pro_recommend_count;
					
			// Display settings to screen
			$smarty = new TSP_Easy_Dev_Smarty( $this->get_value('smarty_template_dirs'), 
				$this->get_value('smarty_cache_dir'), 
				$this->get_value('smarty_compiled_dir'), true );
				
			$smarty->assign( 'free_active_count',		$free_active_count);
			$smarty->assign( 'free_installed_count',	$free_installed_count);
			$smarty->assign( 'free_recommend_count',	$free_recommend_count);
	
			$smarty->assign( 'pro_active_count',		$pro_active_count);
			$smarty->assign( 'pro_installed_count',		$pro_installed_count);
			$smarty->assign( 'pro_recommend_count',		$pro_recommend_count);
			
			$smarty->assign( 'free_active_plugins',		$free_active_plugins);
			$smarty->assign( 'free_installed_plugins',	$free_installed_plugins);
			$smarty->assign( 'free_recommend_plugins',	$free_recommend_plugins);
	
			$smarty->assign( 'pro_active_plugins',		$pro_active_plugins);
			$smarty->assign( 'pro_installed_plugins',	$pro_installed_plugins);
			$smarty->assign( 'pro_recommend_plugins',	$pro_recommend_plugins);
	
			$smarty->assign( 'free_total',				$free_total);
			$smarty->assign( 'pro_total',				$pro_total);
	
			$smarty->assign( 'title',					"WordPress Plugins by The Software People");
			$smarty->assign( 'contact_url',				$this->get_value('contact_url'));
	
			$smarty->display( 'easy-dev-parent-page.tpl' );
		}//end ad_menu
		
		/**
		 * Implements the settings_page to display settings specific to this plugin
		 *
		 * @since 1.1.0
		 *
		 * @param none
		 *
		 * @return output to screen
		 */
		function display_plugin_options_page(){}
		
	}//end TSP_Easy_Dev_Options_Easy_Dev
}//end if
?>