<?php

class Csh_Callback_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	protected $fields = array(); // attribute of all fields.
	protected $sections = array(); // attribute of all sections.

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Database process.
        require_once(CSHCB_PLUGIN_INCLUDES_DIR.'class-csh-callback-database.php');
        $this->db = new Csh_Callback_Database();

		add_action('admin_enqueue_scripts', array($this, 'cshcb_admin_register_style'));
        add_action('admin_enqueue_scripts', array($this, 'cshcb_admin_register_script'));

		add_filter( 'admin_init', array( $this, 'create_section_and_fields' ) );

		//Create some menus at admin dashboard.
		add_action( 'admin_menu', array( $this, 'create_settings_menu' ) );

		add_action('wp_ajax_delete_callback', array($this, 'delete_callback'));
		add_action('wp_ajax_change_to_called', array($this, 'change_to_called'));
		add_action('wp_ajax_change_to_notcall', array($this, 'change_to_notcall'));
	}



	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	
	public function cshcb_admin_register_style(){
        wp_register_style('cshcb_admin_style', CSHCB_PLUGIN_ASSETS_URL . 'css/csh-callback-admin.css');
        wp_enqueue_style('fontawesome', CSHCB_PLUGIN_ASSETS_URL . 'css/fontawesome.min.css');
        wp_enqueue_style('cshcb_admin_style');
    }

	public function cshcb_admin_register_script(){
        wp_enqueue_script('jquery');

        wp_register_script('cshcb_admin_script', CSHCB_PLUGIN_ASSETS_URL . 'js/csh-callback-admin.js');
        wp_enqueue_script('cshcb_admin_script');
    }

	/*-------------------------------------
	One page -> one Form -> one Submit Button -> one Group setting -> display some sections and it's fields:
		do_settings_sections($sectionId);
		settings_fields( 'groupSetting' ); 
		submit_button();
	-------------------------------------*/

	public function add_section($sectionId, $title) {
		$input = array( 'sectionId'  =>	$sectionId,
				   		'title'     =>	$title);
		array_push($this->sections, $input);
	}

	//Fields of Sections.
	public function add_field_of_section($sectionId, $fieldId, $title, $typeInput, $xData = array()) {
		$input = array('sectionId'	=>	$sectionId,
					   'fieldId'	=>	$fieldId,
					   'title'		=>	$title,
					   'typeInput'	=>	$typeInput,
					   'xData'		=>	$xData);
		array_push($this->fields, $input);
	}


	public function create_section_and_fields() {
		
		foreach ($this->sections as $key => $value) {
			add_settings_section(
			$this->sections[$key]['sectionId'], // ID
			$this->sections[$key]['title'], // Title
			'', // Section can no need callback function.
			$this->sections[$key]['sectionId'] // Let page same sectionId to unique.
			);
		}

		// Render fields loop.
		foreach ($this->fields as $key => $value) {
			$callback = array($this, 'fields_callback');
			add_settings_field(
			$this->fields[$key]['fieldId'], // ID
			$this->fields[$key]['title'], // Title 
			$callback, // Callback
			$this->fields[$key]['sectionId'], // Same Page
			$this->fields[$key]['sectionId'], // Belong to Section id
			array ('fieldId'   => $this->fields[$key]['fieldId'],
				 'typeInput' => $this->fields[$key]['typeInput'],
				 'xData'     => $this->fields[$key]['xData']
				)         
			);
		}
	}

	public function create_settings_menu() {
		//Main setting menu.
		register_setting(
			$this->plugin_name, //group of setting.
			$this->plugin_name //name of setting.
		);

		$menu_callback = array( $this, 'call_request_list');
		add_menu_page ( 'main setting', //page_title
						'Call Request', //menu_title
						'manage_options', //capability
						'call-request-list', //menu_slug
						$menu_callback,
						'dashicons-phone', //icon
						75 //position
			 		 );
		$sub_menu_callback = array( $this, 'call_request_settings');
		add_submenu_page( 'call-request-list', 
						  'Settings', 
						  'Settings',
    					  'manage_options', 
    					  'call-request-settings',
    					  $sub_menu_callback );

	}
	public function call_request_list() {
		$list_callback = $this->db->fetch('csh_callback_request');
		$called = array(0 => 'No',1=> 'Yes');
		?>
		<div class="callback-wrap">
			<h1>Call Request List</h1>
			<table class="wp-list-table widefat fixed striped posts">
				<tr>
					<th><?php _e('Name','cshcallback'); ?></th>
					<th><?php _e('Phone','cshcallback'); ?></th>
					<th><?php _e('Email','cshcallback'); ?></th>
					<th><?php _e('Request Date','cshcallback'); ?></th>
					<th><?php _e('Action','cshcallback'); ?></th>
				</tr>
				<?php
				if($list_callback):
					foreach ($list_callback as $k => $v) {
						?>
						<tr callback-id = "<?php echo $v['id'] ?>">
							<td><?php echo $v['name'];?></td>
							<td><?php echo $v['phone'];?></td>
							<td><?php echo $v['email'];?></td>
							<td><?php echo $v['date'];?></td>
							<?php 
							if ($v['called'] == 0) {
								?>
								<td>
									<div class="cshcb-action-icon">
										<i class="fas fa-phone cshcb-not-call"></i>
										<i class="fas fa-trash-alt cshcb-trash"></i>
									</div>
								</td>
								<?php
							}else{
								?>
								<td>
									<div class="cshcb-action-icon">
										<i class="fas fa-phone cshcb-called"></i>
										<i class="fas fa-trash-alt cshcb-trash"></i>
									</div>
								</td>
								<?php
							}
							?>
						</tr>
						<?php
					}
				endif;
				?>
			</table>
		</div>
		<?php
	}

	public function call_request_settings() {
		wp_enqueue_media();
		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style('wp-color-picker');
		
		?>	
		<div id="cshcb-setting-wrap">

			<h1 style="margin-bottom: 50px;">Call Request Setting</h1>

			<form method="post" action="options.php">
					<?php
					foreach ($this->sections as $key => $value) {
						do_settings_sections( $this->sections[$key]['sectionId'] );
						settings_fields( $this->plugin_name );
					}
					submit_button();
					?>
			</form>

		</div>

		 <div class="cshcb_setting_premium">
            <h2><span style="color: red;">Get Csh Callback Premium</span></h2>
            <ul>
            	<li>
                    <strong>+ Add Message and callback Time fields</strong>
                </li>

                <li>
                    <strong>+ Enable, Disable input fields in request form</strong>
                </li>

                <li>
                    <strong>+ Notice email to admin after a request success</strong>
                </li>

            	<li>
                    <strong>+ Add Google reCAPTCHA to callback form</strong>
                </li>

                <li>
                    <strong>+ Support for <span style="color: blue;">Visual Composer</span> </strong>
                </li>

                 <li>
                    <strong>+ 24/7 support</strong>
                    <br>
                </li>
               
            </ul>

            <div class="link_premium">
                <a id="cshcb-premium-button" class="button button-primary" href="https://cmssuperheroes.com/wordpress-plugins/csh-callback/" target="_blank">Get Csh Callback Premium now!</a>
                <br>
                <small style="color: red; font-style: italic;">Price is lower than 15$, Extend support to 12 months</small>
            </div>

        </div>
		<?php 
		
	}

	public function delete_callback(){

        if(!isset($_POST['request_id']))
            return;
        $id = $_POST['request_id'];
        $update_db = $this->db->fetch('csh_callback_request','id = '.$id);
        if(empty($update_db))
            return;
        $this->db->delete('csh_callback_request', array('id' => $id));
        $ajax_status = array(
            'status' => 'OK',
        );
        wp_send_json($ajax_status);
        exit();
	}

	public function change_to_called(){

        if(!isset($_POST['request_id']))
            return;
        $id = $_POST['request_id'];
        $update_db = $this->db->fetch('csh_callback_request','id = '.$id);
        if(empty($update_db))
            return;
        $this->db->update('csh_callback_request', array('called' => 1), array('id' => $id));
        $ajax_status = array(
            'status' => 'OK',
        );
        wp_send_json($ajax_status);
        exit();
	}

	public function change_to_notcall(){

        if(!isset($_POST['request_id']))
            return;
        $id = $_POST['request_id'];
        $update_db = $this->db->fetch('csh_callback_request','id = '.$id);
        if(empty($update_db))
            return;
        $this->db->update('csh_callback_request', array('called' => 0), array('id' => $id));
        $ajax_status = array(
            'status' => 'OK',
        );
        wp_send_json($ajax_status);
        exit();
	}

	public function fields_callback( $args) {
		$arrGlobalData = get_option($this->plugin_name);
		switch ($args['typeInput']) {
			case 'radio':
				$name = $this->plugin_name.'['.$args['fieldId'].']';

				foreach ($args['xData']['options'] as $key => $value) {
					$checked_default = '';
					$enable_checked = '';
					// check default.
					if ($args['xData']['default'][$key] == '1') {
						$checked_default = 'checked';
						$enable_checked = $checked_default;
					}else{
						$checked_default = '';
						$enable_checked = $checked_default;
					}

					//not default.
					if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
						$value_data = esc_attr( $arrGlobalData[$args['fieldId']] );
						if ($value == $value_data) {
							$enable_checked = 'checked';
						}else{
							$enable_checked = '';
						}
					}

					?>
					<div style="display: inline-table; margin-right: 15px;">
						<input type="radio" id="<?php echo $args['fieldId']; ?>" 
						name="<?php echo esc_attr($name); ?>"
						value="<?php echo esc_attr($value); ?>" <?php echo $enable_checked ?>> <?php echo $value;?>
					</div>
					<?php
				}
				break;
			case 'select':
				$name = $this->plugin_name.'['.$args['fieldId'].']';

				$selected = '';
				$desc = $args['xData']['desc'];
				?>
				<select id="<?php echo $args['fieldId']; ?>" name="<?php echo esc_attr($name); ?>">
				<?php
				foreach ($args['xData']['options'] as $key => $value) {
					if ( ( isset($arrGlobalData[$args['fieldId']]) && $arrGlobalData[$args['fieldId']] !='') ){
						$value_data = esc_attr( $arrGlobalData[$args['fieldId']] );
						if ($value == $value_data) {
							$selected = 'selected';
						}else{
							$selected = '';
						}
					}

					?>
					<option value="<?php echo $value ?>" <?php echo $selected ?>><?php echo $value; ?> </option>
					<?php
				}
				?>
				</select>
				<p><?php echo $desc; ?></p>
				<?php
				break;
			case 'text':
				$name = $this->plugin_name.'['.$args['fieldId'].']';
				$value = "";

				if (isset($args['xData']['default'])) {
					$value = $args['xData']['default'];
				}

				$desc = "";
				if (isset($args['xData']['desc'])) {
					$desc = $args['xData']['desc'];
				}

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}

				?>
				<input
				type="text" 
				class="regular-text" 
				id="<?php echo $args['fieldId']; ?>" 
				name="<?php echo esc_attr($name); ?>" 
				value="<?php echo $value ?>" />

				<p><?php echo $desc; ?></p>
				<?php
				break;
			case 'color':
				$value = $args['xData']['default'];
				$name = $this->plugin_name.'['.$args['fieldId'].']';
				$desc = $args['xData']['desc'];

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}

				?>
				<input
				name="<?php echo esc_attr($name); ?>" id="<?php echo $args['fieldId']; ?>" 
				type="text" 
				value="<?php echo $value ?>" 
				class="csh_color_picker" />
				<p> <?php echo $desc; ?></p>

				<script type="text/javascript">
					jQuery(document).ready(function($) {
						// color type.
   						$(".csh_color_picker").wpColorPicker();
					});
				</script>

				<?php
				break;
			case 'upload':
				$name = $this->plugin_name.'['.$args['fieldId'].']';
				$value = "";

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}

				?>
				<div>
					<!-- avatar -->
					<p><?php echo $args['xData']['desc'] ?></p><br>
					<div class="avatar-wrap">

						<input type="hidden" class="avatar-value" name="<?php echo esc_attr($name); ?>"  value="<?php echo $value ?>" />
						<div class="show-avatar">
							<?php if ($value != ""): ?>
								<img width = "80px" src="<?php echo $value ?>">
							<?php endif ?>
						</div>
						<?php 
						if ($value == "") {
							?>
							<a href="#" class="btn-select-avatar">Select avatar</a>
							<a href="#" class="btn-remove-avatar" style="display: none;">Remove avatar</a>
							<?php
						}else{
							?>
							<a href="#" class="btn-select-avatar" style="display: none;">Select avatar</a>
							<a href="#" class="btn-remove-avatar">Remove avatar</a>
							<?php
						}
						?>
						
					</div>
				</div>

				<script type="text/javascript">
					jQuery(document).ready(function($) {
						// cause avatar.
						$('.btn-select-avatar').click(function(e) {
							e.preventDefault();
							var $this = $(this);
							var image = wp.media({
									title: 'Upload avatar',
									// mutiple: true if you want to upload multiple files at once
									multiple: false
								}).open()
								.on('select', function(e) {
									// This will return the selected image from the Media Uploader, the result is an object
									var uploaded_image = image.state().get('selection').first();
									// We convert uploaded_image to a JSON object to make accessing it easier
									// Output to the console uploaded_image
									var image_url = uploaded_image.toJSON().url;
									// Let's assign the url value to the input field
									$this.parents('.avatar-wrap').find('.avatar-value').val(image_url);
									$('.show-avatar').empty();
									$('.show-avatar').append('<img width = "80px" src = "'+image_url+ '">');
					                $('.btn-select-avatar').hide();
					                $('.btn-remove-avatar').show();
								});	
						});

						$('.btn-remove-avatar').click(function(e) {
					        e.preventDefault();
							$(this).parents('.avatar-wrap').find('.avatar-value').val("");
							$('.show-avatar').empty();
					        $('.btn-remove-avatar').hide();
					        $('.btn-select-avatar').show();
						});
					});
				</script>
				<?php
				break;
			case 'number':
				$name = $this->plugin_name.'['.$args['fieldId'].']';
				$value = $args['xData']['default'];

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}

				?>
				<input 
				style="width: 8%;"
				type="number"  
				id="<?php echo $args['fieldId']; ?>" 
				name="<?php echo esc_attr($name); ?>" 
				value="<?php echo $value ?>" />
				<?php
				echo $args['xData']['desc'];
				break;
			case 'description':
				echo htmlspecialchars($args['xData']['desc']);
				break;
			case 'checkbox':
				$name = $this->plugin_name.'['.$args['fieldId'].']';
				$checked = '';
				$desc = $args['xData']['desc'];

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] !='')){
					$checked = 'checked';
				}else{
					$checked = '';
				}

				?>
				<input type="checkbox" id="<?php echo $args['fieldId']; ?>"
				name="<?php echo esc_attr($name); ?>"<?php echo $checked ?>> <?php echo $desc; ?>
				<?php
				break;
			case 'textarea':
				$name = $this->plugin_name.'['.$args['fieldId'].']';
				$value = "";

				$label = "";
				if (isset($args['xData']['label'])) {
					$label = $args['xData']['label'];
				}

				$width = "";
				if (isset($args['xData']['width'])) {
					$width = $args['xData']['width'];
				}

				$height = "";
				if (isset($args['xData']['height'])) {
					$height = $args['xData']['height'];
				}

				if ((isset( $arrGlobalData[$args['fieldId']] )&&$arrGlobalData[$args['fieldId']] != '' )){
					$value = esc_attr( $arrGlobalData[$args['fieldId']] );
				}
				?>
				<p><?php echo $label;?></p>
				<textarea style="width: <?php echo $width ?>; height: <?php echo $height ?>;"
				id="<?php echo $args['fieldId']; ?>" 
				name="<?php echo esc_attr($name); ?>"/><?php echo $value ?></textarea>
				<?php
				break;
			default:
		}
	}// end of call back.

	

}// End of AdminSettings.

?>