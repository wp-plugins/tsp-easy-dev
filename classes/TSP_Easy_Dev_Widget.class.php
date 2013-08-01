<?php
if ( !class_exists( 'TSP_Easy_Dev_Widget' ) )
{
	/**
	 * Class to extend WP_Widget to show widget fields, save and load settings
	 * @package 	TSP_Easy_Dev
	 * @author 		sharrondenice, thesoftwarepeople
	 * @author 		Sharron Denice, The Software People
	 * @copyright 	2013 The Software People
	 * @license 	APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
	 * @version 	1.0
	 */
	abstract class TSP_Easy_Dev_Widget extends WP_Widget 
	{
		/**
		 * The reference to the TSP_Easy_Dev_Options class
         * 
         * @api
		 *
		 * @var TSP_Easy_Dev_Options
		 */
		protected $options;
		/**
		 * A boolean to turn debugging on for this class
		 *
		 * @ignore
		 *
		 * @var boolean
		 */
		private $debugging = false;
		
		/**
		 * Constructor - Inside the constructor of ALL TSP_Easy_Dev_Widget subclasses a call to the add_filter (expand for more details)
		 *
		 * Upon implementation, must contain the following line EXACTLY as it appears: 
		 *
		 * add_filter( get_class()  .'-init', array($this, 'init'), 10, 1 );
		 *
		 * The filter will be applied via apply_filter in the plugin's main file
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param array $globals Required - Sets the global settings for the widget
		 *
		 * @return none
		 */
		public function __construct( $options ) 
		{
			$this->options = $options;
			
	        // Get widget options
	        $widget_options  = array(
	            'classname'  			=> $this->options->get_value('name'),
	            'description'   		=> __( strip_tags($this->options->get_value('Description')), $this->options->get_value('name') )
	        );
	        
	        // Get control options
	        $control_options = array(
	            'width' 				=> $this->options->get_value('widget_width'),
	            'height'				=> $this->options->get_value('widget_height'),
	            'id_base' 				=> $this->options->get_value('name'),
	        );

			$this->load_shortcodes();

	        // Create the widget
			parent::__construct( $this->options->get_value('name'), __( $this->options->get_value('Name'), $this->options->get_value('name') ) , $widget_options, $control_options);
		}//end __construct
	
		/**
		 * Implements update function
		 *
		 * @ignore - WP_Widget::update must be public
		 *
		 * @since 1.0
		 *
	 	 * @param array $new_instance Values just sent to be saved.
		 * @param array $old_instance Previously saved values from database.
		 *
		 * @return array Updated safe values to be saved.
		 */
		public function update( $new_instance, $old_instance ) 
		{
			$widget_fields = get_option( $this->options->get_value('widget-fields-option-name') );
			$defaults = new TSP_Easy_Dev_Data ( $widget_fields );

			if ( !empty ( $new_instance ))
			{
				$defaults->set_values( $new_instance ); // overwrite defaults with new instance (user data)
			}//endif
			else
			{
				$defaults->encode_values();
			}//endelse
			
			$instance = $defaults->get_values();
	        
	        return $instance;
		}//end update
	
	
		/**
		 * widget function can be overriden by the plugin to display plugin widget info to screen
		 *
		 * @ignore - WP_Widget::widget must be public
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none
		 */
		public function widget( $args, $instance )
		{
			$widget_fields = get_option( $this->options->get_value('widget-fields-option-name') );
			$defaults = new TSP_Easy_Dev_Data ( $widget_fields );
			
			if ( !empty ( $instance ))
			{
				$defaults->set_values( $instance );
			}//endif
			else
			{
				$defaults->decode_values();
			}//endelse
			
			$fields = $defaults->get_values();

	        if ( !empty( $args ) )
	        {
	        	extract($args);
	        }//end if
	        
	        // Display data before widget
	        if ( isset( $before_widget ) )
	        {	
	        	echo $before_widget;
	        }//end if
	        
	        $this->display_widget( $fields );
	        
	        // Display data after widget
	        if ( isset( $after_widget ) )
	        {	
	        	echo $after_widget;
	        }//end if
		}//end widget
		
		/**
		 * form function must be expanded by the plugin to display plugin widget info to screen
		 *
		 * @ignore - WP_Widget::form must be public
		 *
		 * @since 1.0
		 *
		 * @param array $instance Required - Data to be displayed on the form
		 *
		 * @return none
		 */
	 	public function form( $instance )
	 	{
			$widget_fields = get_option( $this->options->get_value('widget-fields-option-name') );
			$defaults = new TSP_Easy_Dev_Data ( $widget_fields );

			if ( !empty ( $instance ) )
			{
				$defaults->set_values( $instance );
			}//endif
			else
			{
				$defaults->decode_values();
			}//endelse
						
			$fields = $defaults->get_values( true );

			$this->display_form ( $fields );
	 	}//end form
	
		/**
		 * Process all shorcodes associated with this widget
		 *
		 * @since 1.0
		 *
		 * @param none
		 *
		 * @return none
		 */
	 	private function load_shortcodes()
	 	{
			$shortcodes = $this->options->get_value('shortcodes');
			
			if ( !empty ( $shortcodes ) )
			{
				// add all the associated shortcodes associated with this widget
				foreach ( $shortcodes as $code )
				{
					add_shortcode($code, array( $this, 'process_shortcode') );
				}//endforeach
			}//endif
	 	}//end form

	
		/**
		 * Process shortcodes passed to widget
		 *
		 * @ignore - Must be public, used by WordPress hook
		 *
		 * @since 1.0
		 *
		 * @param array $attributes Optional the arguments passed to the shortcode
		 *
		 * @return none
		 */
		public function process_shortcode( $attributes )
		{
			if ( is_feed() )
				return '[' . $this->options->get_value('name') . ']';
						
			$shortcode_fields = get_option( $this->options->get_value('shortcode-fields-option-name') );
			$defaults = new TSP_Easy_Dev_Data ( $shortcode_fields );

			if (! empty ( $attributes) )
			{
				$fields = $defaults->get_values( true );

				// Update attributes to include old attribute names from short codes
				// Backwards compatibility
				foreach ( $fields as $key => $opts )
				{
					// continue if this label was renamed
					if ( !empty( $opts['old_labels'] ) )
					{
						$new_label = $key;
						// looop through all the old label names
						foreach ( $opts['old_labels'] as $old_label )
						{
							// if the new label isn't being used and the user is using the old label
							// set the new labels value to the old label's value
							if ( !array_key_exists( $new_label, $attributes ) && array_key_exists( $old_label, $attributes ) )
							{
								$attributes[$new_label] = $attributes[$old_label];
								unset($attributes[$old_label]);
							}//end fi
						}//end foreach
					}//end if
				}//end foreach
			}//end if
			
			if ( !empty ( $attributes ))
			{
				$defaults->set_values( $attributes );
			}//endif
			else
			{
				$defaults->decode_values();
			}//endelse
			
			$fields = $defaults->get_values();

			$output = $this->display_widget( $fields, false );
			
			return $output;
		}//end process_shortcode
		
		/**
		 * Required: Must be implemented by the plugin to initialize the widget with global settings - method will be applied to a filter (expand for more details)
		 *
		 * Upon implementation, must contain the following line EXACTLY as it appears: 
		 *
		 * $this->settings = $globals;
		 *
		 * parent::__construct( $this->settings );
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param array $settings Required global settings used by the plugin
		 *
		 * @return none
		 */
		abstract public function init( $settings );

		/**
		 * Required: Must be implemented by the plugin to display the HTML to the screen
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param array $fields Required Data to display to the screen
		 *
		 * @return none
		 */
		abstract public function display_form( $fields );

		/**
		 * Required: Must be implemented by the plugin to display the HTML to the screen
		 *
		 * @api
		 *
		 * @since 1.0
		 *
		 * @param array $fields Required data to display to the screen
		 * @param boolean $echo Optional if true display data to screen
		 *
		 * @return none
		 */
		abstract public function display_widget( $fields, $echo = false );
		
	}//end TSP_Easy_Dev_Widget
}//endif
?>