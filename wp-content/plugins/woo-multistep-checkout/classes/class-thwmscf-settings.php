<?php
if(!defined('ABSPATH')){ exit; }

if(!class_exists('THWMSCF_Settings')):

class THWMSCF_Settings {
	const WMSC_SETTINGS = 'THWMSC_SETTINGS';
	protected static $_instance = null;	
	private $tabs = '';
	private $settings = '';
	
	private $cell_props = array();
	private $cell_props_L = array();
	private $cell_props_R = array();
	private $cell_props_CB = array(); 

	public function __construct(){
		$this->tabs = array( 'msc_settings' => 'Multistep Checkout');
		
		$this->cell_props = array( 
			'label_cell_props' => 'style="width: 20%;" class="titledesc" scope="row"', 
			'input_cell_props' => 'class="forminp"', 
			'input_width' => '250px', 'label_cell_th' => true 
		);
		$this->cell_props_L = array( 
			'label_cell_props' => 'style="width: 20%;" class="titledesc" scope="row"', 
			'input_cell_props' => 'style="width: 25%;" class="forminp"', 
			'input_width' => '250px', 'label_cell_th' => true 
		);
		$this->cell_props_R = array( 
			'label_cell_props' => 'style="width: 15%;" class="titledesc" scope="row"', 
			'input_cell_props' => 'class="forminp"', 
			'input_width' => '250px', 'label_cell_th' => true 
		);
		//$this->cell_props_R = array( 'label_cell_width' => '13%', 'input_cell_width' => '34%', 'input_width' => '250px' );
		$this->cell_props_CB = array( 'cell_props' => 'colspan="3"' );

		$this->settings = $this->get_settings();

		add_action('admin_menu', array($this, 'admin_menu'));
		add_filter('woocommerce_screen_ids', array($this, 'add_screen_id'));
		
		add_filter('plugin_action_links_'.THWMSCF_BASE_NAME, array($this, 'add_settings_link'));
		
		$this->init();
	}

	public static function instance(){
		if(is_null(self::$_instance)){
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * menu function.
	 */
	public function admin_menu() {
		$this->screen_id = add_submenu_page('woocommerce', __('Woo Multistep Checkout', 'woo-multistep-checkout'), __('Multistep Checkout', 'woo-multistep-checkout'), 
		'manage_woocommerce', 'woo_multistep_checkout', array($this, 'multistep_checkout'));

		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
	}	
	
	public function add_settings_link($links) {
		$settings_link = '<a href="'.admin_url('admin.php?page=woo_multistep_checkout').'">'. __('Settings') .'</a>';
		array_unshift($links, $settings_link);
		return $links;
	}

	function enqueue_admin_scripts($hook) {		
		if(strpos($hook, 'page_woo_multistep_checkout') === false) {
			return;
		}		

		wp_enqueue_style('woocommerce_admin_styles');		
		wp_enqueue_style('thwmscf-admin-style', plugins_url('/assets/css/thwmscf-admin.css', dirname(__FILE__)), THWMSCF_VERSION);  
		wp_enqueue_script('thwmscf-admin-js', THWMSCF_ASSETS_URL.'js/thwmscf-admin.js',array('jquery','wp-color-picker'), THWMSCF_VERSION, true);     
	}

	/**
	 * add_screen_id function.
	 */
	function add_screen_id($ids){
		$ids[] = 'woocommerce_multistep_checkout';
		$ids[] = strtolower(__('WooCommerce', 'woo-multistep-checkout')) .'_multistep_checkout';

		return $ids;
	}

	function multistep_checkout() { 		
		$this->wmsc_design();
	}

	public function get_settings(){		
		$settings_default = array(
			'enable_wmsc' => 'yes',
			'title_login' => 'Login',
			'title_billing' => 'Billing details',
			'title_shipping' => 'Shipping details',
			'title_order_review' => 'Your order',
			'title_test' => 'Test',
			'step_bg_color'   => '#B2B2B0',
			'step_text_color' => '#8B8B8B',
			'step_bg_color_active'   => '#018DC2',
			'step_text_color_active' => '#FFFFFF',
			'tab_panel_bg_color' => '#FBFBFB',
		);
		$saved_settings = $this->get_wmsc_settings();
		
		$settings = !empty($saved_settings) ? $saved_settings : $settings_default ;
		return $settings;

	}

	public function get_tabs(){
		return $this->tabs; 
	}

	function get_current_tab(){
		return isset( $_GET['tab'] ) ? esc_attr( $_GET['tab'] ) : 'msc_settings';
	}

	public function get_settings_fields(){
		$tab_postion = array(
			'align-left' => 'Left',
			'align-center' => 'Center'
		);

		$layout_field = array(
			'enable_wmsc' => array(
				'name'=>'enable_wmsc', 'label'=>'Enable Multi-step', 'type'=>'checkbox', 'value'=>'yes', 'checked'=>1								
			),	
			'title_display_texts' => array('title'=>'Step Display Texts', 'type'=>'separator', 'colspan'=>'4'),		
			'title_login' => array(
				'name'=>'title_login', 'label'=>'Login', 'type'=>'text', 'value'=>'Login'
			),
			'title_billing' => array(
				'name'=>'title_billing', 'label'=>'Billing details', 'type'=>'text', 'value'=>'Billing details'
			),
			'title_shipping' => array(
				'name'=>'title_shipping', 'label'=>'Shipping details', 'type'=>'text', 'value'=>'Shipping details'
			),
			'title_order_review' => array(
				'name'=>'title_order_review', 'label'=>'Your order', 'type'=>'text', 'value'=>'Your order'
			),
			'title_test' => array(
				'name'=>'title_test', 'label'=>'test', 'type'=>'text', 'value'=>'test'
			),
			'title_display_styles' => array('title'=>'Display Styles', 'type'=>'separator', 'colspan'=>'4'),
			'tab_align' => array(  
				'name'=>'tab_align', 'label'=>'Tab Position', 'type'=>'select', 'value'=>'center', 'options'=> $tab_postion										
			),
			'tab_panel_bg_color' => array( 
				'name'=>'tab_panel_bg_color', 'label'=>'Content background color', 'type'=>'colorpicker', 'value'=>'#FBFBFB'
			),
			'step_bg_color' => array( 
				'name'=>'step_bg_color', 'label'=>'Step background color', 'type'=>'colorpicker', 'value'=>'#B2B2B0'
			),  
			'step_text_color' => array(
				'name'=>'step_text_color', 'label'=>'Step text color', 'type'=>'colorpicker', 'value'=>'#8B8B8B'
			),
			'step_bg_color_active' => array(       
				'name'=>'step_bg_color_active', 'label'=>'Step background color - Active', 'type'=>'colorpicker', 'value'=>'#018DC2' 
			),
			'step_text_color_active' => array(    
				'name'=>'step_text_color_active', 'label'=>'Step text color - Active', 'type'=>'colorpicker', 'value'=>'#FFFFFF'
			), 
			// 'payment_txt' => array(
			// 	'name'=>'payment_txt', 'label'=>'Payment Info', 'type'=>'text', 'value'=>'Payment Info'
			// ),	
		); 

		return $layout_field;  
	}

	public function get_wmsc_settings(){
		$settings = get_option(self::WMSC_SETTINGS);
		return empty($settings) ? false : $settings;
	}
	
	public function update_settings($settings){
		$result = update_option(self::WMSC_SETTINGS, $settings);
		return $result;
	}

	public function reset_settings(){ 
		delete_option(self::WMSC_SETTINGS);		
	}

	public function render_tabs_and_details(){
		$tabs = $this->get_tabs();
		$tab  = $this->get_current_tab();
		
		echo '<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">';
		foreach( $tabs as $key => $value ) {
			$active = ( $key == $tab ) ? 'nav-tab-active' : '';
			echo '<a class="nav-tab '.$active.'" href="'.admin_url('admin.php?page=woo_multistep_checkout&tab='.$key).'">'.$value.'</a>';
		}
		echo '</h2>';
		
		//$this->output_premium_version_notice();		
	}

	public function wmsc_design(){
		echo '<div class="wrap woocommerce"><div class="icon32 icon32-attributes" id="icon-woocommerce"><br /></div>';

		$this->render_tabs_and_details();
		$tab  = $this->get_current_tab();

		if($tab == 'msc_settings'){
			$this->general_settings();
		}
		
		echo '</div>';
	}

	function general_settings(){ 
		if(isset($_POST['save_settings']))
			echo $this->save_settings();

		if(isset($_POST['reset_settings']))
			echo $this->reset_settings();

		$fields = $this->get_settings_fields();
		$settings = $this->get_settings();
		
		foreach( $fields as $name => &$field ) { 
			if($field['type'] != 'separator'){
				if(is_array($settings) && isset($settings[$name])){
					if($field['type'] === 'checkbox'){
						if(isset($field['value']) && $field['value'] === $settings[$name]){
							$field['checked'] = 1;
						}else{
							$field['checked'] = 0;
						}
					}else{
						$field['value'] = esc_attr($settings[$name]);
					}
				}
			}
		}

		?>		
		<div style="padding-left: 30px;">               
		    <form id="wmsc_setting_form" method="post" action="">
				<table class="form-table thpladmin-form-table">
                    <tbody>
						<tr>
							<?php          
							$this->render_form_field_element($fields['enable_wmsc'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
						<?php $this->render_form_section_separator($fields['title_display_texts']); ?>
						<tr>
							<?php          
							$this->render_form_field_element($fields['title_login'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['title_billing'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['title_shipping'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['title_order_review'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
                        <tr>
							<?php
							$this->render_form_field_element($fields['title_test'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
                        </tr>
						
						<?php $this->render_form_section_separator($fields['title_display_styles']); ?>
						<tr>
							<?php
							$cell_props = $this->cell_props_L;
							$cell_props['input_width'] = '165px';
							$this->render_form_field_element($fields['tab_align'], $cell_props);
							$this->render_form_field_blank();
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['step_bg_color'], $this->cell_props_L);
							$this->render_form_field_element($fields['step_text_color'], $this->cell_props_R);
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['step_bg_color_active'], $this->cell_props_L);
							$this->render_form_field_element($fields['step_text_color_active'], $this->cell_props_R);
							?>
						</tr>
						<tr>
							<?php          
							$this->render_form_field_element($fields['tab_panel_bg_color'], $this->cell_props_L);
							$this->render_form_field_blank();
							?>
						</tr>
                    </tbody>
                </table>
				                
                <p class="submit">
					<input type="submit" name="save_settings" class="button-primary" value="Save changes">
					<input type="submit" name="reset_settings" class="button-secondary" value="Reset to default"
					onclick="return confirm('Are you sure you want to reset to default settings? all your changes will be deleted.');">
            	</p>
            </form>
    	</div>

	<?php }
	
	public function save_settings(){
		$settings = array();
		$settings_fields = $this->get_settings_fields();
		
		foreach( $settings_fields as $name => $field ) {
			$type = $field['type'];
			if($type != 'separator'){
				$value = '';
				
				if($field['type'] === 'checkbox'){
					$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
				}else if($field['type'] === 'multiselect_grouped'){
					$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
					$value = is_array($value) ? implode(',', $value) : $value;
				}else if($field['type'] === 'text' || $field['type'] === 'textarea'){
					$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
					$value = !empty($value) ? stripslashes(trim($value)) : '';
				}else{
					$value = !empty( $_POST['i_'.$name] ) ? $_POST['i_'.$name] : '';
				}
				
				$settings[$name] = $value;
			}
		}
				
		$result = $this->update_settings($settings);
		if ($result == true) {
			echo '<div class="updated"><p>'. __('Your changes were saved.', 'woo-multistep-checkout') .'</p></div>';
		} else {
			echo '<div class="error"><p>'. __('Your changes were not saved due to an error (or you made none!).', 'woo-multistep-checkout') .'</p></div>';
		}
	}

	public function render_form_section_separator($props, $atts=array()){
		?>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" style="height:10px;"></td></tr>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" class="thpladmin-form-section-title" ><?php echo $props['title']; ?></td></tr>
		<tr valign="top"><td colspan="<?php echo $props['colspan']; ?>" style="height:0px;"></td></tr>
		<?php
	}

	public function render_form_field_element($field, $atts=array(), $render_cell=true){
		if($field && is_array($field)){
			$ftype = isset($field['type']) ? $field['type'] : 'text';
			
			if($ftype == 'checkbox'){
				$this->render_form_field_element_checkbox($field, $atts, $render_cell);
				return true;
			}
		
			$args = shortcode_atts( array(   
				'label_cell_props' => '',
				'input_cell_props' => '',
				'label_cell_th' => false,
				'input_width' => '',
				'rows' => '5',
				'cols' => '100',
				'input_name_prefix' => 'i_'
			), $atts );
			
			$fname  = $args['input_name_prefix'].$field['name'];						
			$flabel = __($field['label'], 'woo-multistep-checkout');
			$fvalue = isset($field['value']) ? $field['value'] : '';
			
			if($ftype == 'multiselect' && is_array($fvalue)){  
				$fvalue = !empty($fvalue) ? implode(',', $fvalue) : $fvalue;
			}
			/*if($ftype == 'multiselect' || $ftype == 'multiselect_grouped'){
				$fvalue = !empty($fvalue) ? explode(',', $fvalue) : $fvalue;
			}*/
						
			$input_width  = $args['input_width'] ? 'width:'.$args['input_width'].';' : '';
			$field_props  = 'name="'. $fname .'" value="'. $fvalue .'" style="'. $input_width .'"';
			$field_props .= ( isset($field['placeholder']) && !empty($field['placeholder']) ) ? ' placeholder="'.$field['placeholder'].'"' : '';
			
			$required_html = ( isset($field['required']) && $field['required'] ) ? '<abbr class="required" title="required">*</abbr>' : '';
			$field_html = '';
			
			if(isset($field['onchange']) && !empty($field['onchange'])){
				$field_props .= ' onchange="'.$field['onchange'].'"';
			}
			
			if($ftype == 'text'){
				$field_html = '<input type="text" '. $field_props .' />';
				
			}else if($ftype == 'number'){
				$field_html = '<input type="number" class="thwmsc_number" '. $field_props .' />';
				
			}else if($ftype == 'textarea'){
				$field_props  = 'name="'. $fname .'" style=""';
				$field_props .= ( isset($field['placeholder']) && !empty($field['placeholder']) ) ? ' placeholder="'.$field['placeholder'].'"' : '';
				$field_html = '<textarea '. $field_props .' rows="'.$args['rows'].'" cols="'.$args['cols'].'" >'.$fvalue.'</textarea>';
				
			}else if($ftype == 'select'){
				$field_props .= 'class="thwmscf_select"';
				$field_html = '<select '. $field_props .' >';
				foreach($field['options'] as $value=>$label){
					
					//$selected = $value === $fvalue ? 'selected' : ''; chnaged for the 900 and 600 to make string
					$selected = $value == $fvalue ? 'selected' : '';
					$field_html .= '<option value="'. trim($value) .'" '.$selected.'>'. __($label, 'woo-multistep-checkout') .'</option>';
				}
				$field_html .= '</select>';
				
			}else if($ftype == 'colorpicker'){
				$field_html = $this->render_form_field_element_colorpicker($field, $args);
			}
			
			$label_cell_props = !empty($args['label_cell_props']) ? ' '.$args['label_cell_props'] : '';
			$input_cell_props = !empty($args['input_cell_props']) ? ' '.$args['input_cell_props'] : '';
			?>
            
			<td <?php echo $label_cell_props ?> > <?php 
				echo $flabel; echo $required_html; 
				
				if(isset($field['sub_label']) && !empty($field['sub_label'])){
					?>
                    <br /><span class="thpladmin-subtitle"><?php $this->_ewcfe($field['sub_label']); ?></span>
					<?php
				}
				?>
            </td>          
           
            
            <td <?php echo $input_cell_props ?> ><?php echo $field_html; ?></td>
            
            <?php
		}
	}

	public function render_form_field_element_checkbox($field, $atts=array(), $render_cell=false){
		$args = shortcode_atts( array( 'cell_props'  => '', 'input_props' => '', 'label_props' => '', 'name_prefix' => 'i_', 'id_prefix' => 'a_f' ), $atts );
		
		$fid    = $args['id_prefix'].$field['name'];
		$fname  = $args['name_prefix'].$field['name'];
		$fvalue = isset($field['value']) ? $field['value'] : '';
		$flabel = __($field['label'], 'woo-multistep-checkout');
		
		$field_props  = 'id="'. $fid .'" name="'. $fname .'"';
		$field_props .= !empty($fvalue) ? ' value="'. $fvalue .'"' : '';
		$field_props .= $field['checked'] ? ' checked' : '';
		$field_props .= $args['input_props'];
		$field_props .= isset($field['onchange']) && !empty($field['onchange']) ? ' onchange="'.$field['onchange'].'"' : '';
		
		
		$field_html = '<td><label for="'. $fid .'" '. $args['label_props'] .' > '. $flabel .'</label></td>';
		//$field_html .= '<td'.$args['cell_props'].'></td>';
		$field_html .= '<td><input type="checkbox" '. $field_props .' /></td>';
		
		if($render_cell){
		?>
			<?php echo $field_html; ?>
		<?php 
		}else{
		?>
			<?php echo $field_html; ?>  
		<?php 
		}
	}

	private function render_form_field_element_colorpicker($field, $atts = array()){
		$field_html = '';
		if($field && is_array($field)){
			$args = shortcode_atts( array(
				'input_width' => '',
				'input_name_prefix' => 'i_'
			), $atts );
			
			$fname  = $args['input_name_prefix'].$field['name'];
			$fvalue = isset($field['value']) ? $field['value'] : '';
			
			$input_width  = $args['input_width'] ? 'width:'.$args['input_width'].';' : '';
			$field_props  = 'name="'. $fname .'" value="'. $fvalue .'" style="'. $input_width .'"';
			$field_props .= ( isset($field['placeholder']) && !empty($field['placeholder']) ) ? ' placeholder="'.$field['placeholder'].'"' : '';
			
			$field_html  = '<span class="thpladmin-colorpickpreview '.$field['name'].'_preview" style=""></span>';
            $field_html .= '<input type="text" '. $field_props .' class="thpladmin-colorpick"/>';
		}
		return $field_html;
	}
	
	public function render_form_field_blank($colspan = 2){
		?>
        <td colspan="<?php echo $colspan; ?>">&nbsp;</td>  
        <?php
	}

	public function init() {		
		if(!is_admin() || (defined( 'DOING_AJAX' ) && DOING_AJAX)){		
			if(is_array($this->settings) && isset($this->settings['enable_wmsc']) && $this->settings['enable_wmsc'] == 'yes'){   
				$this->frontend_design();
			}
		}
	}

	public function frontend_design(){
		add_action( 'wp_enqueue_scripts', array( $this, 'thwmsc_frontend_scripts' ) );	
	    add_filter( 'woocommerce_locate_template', array( $this, 'wmsc_multistep_template' ), 10, 3 );
        remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		add_action('thwmscf_before_checkout_form', 'woocommerce_checkout_login_form');
	}
	
	public function before_checkout_form(){
		if(!is_user_logged_in() && 'yes' === get_option( 'woocommerce_enable_checkout_login_reminder')){
			echo '<div class="thwmscf-tab-panel" id="thwmscf-tab-panel-0">';
			do_action( 'woocommerce_checkout_login_form' );
			echo '</div>';
		}
	}

	public function thwmsc_frontend_scripts(){  
		$in_footer = apply_filters( 'thwmscf_enqueue_script_in_footer', true );

        wp_register_style( 'thwmscf-checkout-css', THWMSCF_ASSETS_URL . 'css/thwmscf-frontend.css', array(), THWMSCF_VERSION );
        wp_register_script('thwmscf-frontend-js', THWMSCF_ASSETS_URL.'js/thwmscf-frontend.js', array(), THWMSCF_VERSION, $in_footer);  

        wp_enqueue_style('thwmscf-checkout-css');    

        $display_prop = $this->get_settings();  

        if($display_prop){      
			$tab_panel_style = '';
			$tab_style = '';
			$tab_style_active = '';
			
			$tab_align = isset($display_prop['tab_align']) && $display_prop['tab_align'] ? 'text-align:'.$display_prop['tab_align'].';' : '';
			
			if(isset($display_prop['tab_panel_bg_color']) && $display_prop['tab_panel_bg_color']){
				$tab_panel_style = 'background:'.$display_prop['tab_panel_bg_color'].' !important;';
			}
			
			if(isset($display_prop['step_bg_color']) && $display_prop['step_bg_color']){
				$tab_style = 'background:'.$display_prop['step_bg_color'].' !important;';
			}
			if(isset($display_prop['step_text_color']) && $display_prop['step_text_color']){
				$tab_style .= $tab_style ? ' color:'.$display_prop['step_text_color'].'' : 'color:'.$display_prop['step_text_color'].'';
				$tab_style .= ' !important';
			}
			
			if(isset($display_prop['step_bg_color_active']) && $display_prop['step_bg_color_active']){
				$tab_style_active = 'background:'.$display_prop['step_bg_color_active'].' !important;';
			}
			if(isset($display_prop['step_text_color_active']) && $display_prop['step_text_color_active']){
				$tab_style_active .= $tab_style_active ? ' color:'.$display_prop['step_text_color_active'].'' : 'color:'.$display_prop['step_text_color_active'].'';
				$tab_style_active .= ' !important';
			}

            $plugin_style = "
                    ul.thwmscf-tabs{ $tab_align }    
                    li.thwmscf-tab a{ $tab_style }                       
                    li.thwmscf-tab a.active { $tab_style_active }
					.thwmscf-tab-panels{ $tab_panel_style }";    
            wp_add_inline_style( 'thwmscf-checkout-css', $plugin_style );  
        }        

        if(is_array($this->settings) && isset($this->settings['enable_wmsc']) && $this->settings['enable_wmsc'] == 'yes'){
       		wp_enqueue_script('thwmscf-frontend-js');  
    	}
	} 

	public function wmsc_multistep_template( $template, $template_name, $template_path ){
        if('checkout/form-checkout.php' == $template_name ){         
        	if(is_array($this->settings) && isset($this->settings['enable_wmsc']) && $this->settings['enable_wmsc'] == 'yes'){  	
        		$template = THWMSCF_TEMPLATE_PATH . 'checkout/form-checkout.php';   
        	}
        }
        return $template;
    }
}

endif;