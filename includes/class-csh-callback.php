<?php
class Csh_Callback {
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * @var      class    $public_instance    instance of public Class.
	 */
	protected $public_instance;

	/**
	 * @var      class    $admin_instance    instance of admin Class.
	 */
	protected $admin_instance;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		/* Set plugin information */
		if ( defined( 'CSHCB_PLUGIN_VERSION' ) ) {
			$this->version = CSHCB_PLUGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'csh-callback';

		// Load assets for plugin.
		$this->load_dependencies();

		$this->do_public();
		$this->do_admin();
		$this->build_setting_fields();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Csh_Callback_Loader. Orchestrates the hooks of the plugin.
	 * - Csh_Callback_i18n. Defines internationalization functionality.
	 * - Csh_Callback_Admin. Defines all hooks for the admin area.
	 * - Csh_Callback_Public. Defines all hooks for the public side of the site.
	 *
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once CSHCB_PLUGIN_PUBLIC_DIR . 'class-csh-callback-public.php';
		$this->public_instance = new Csh_Callback_Public( $this->get_plugin_name(), $this->get_version() );

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once CSHCB_PLUGIN_ADMIN_DIR . 'class-csh-callback-admin.php';
		$this->admin_instance = new Csh_Callback_Admin( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Front end handle.
	 */
	public function do_public() {

	}

	/**
	 * Build admin setting page.
	 */
	public function do_admin() {
		
	}

	/**
	 * add fields to page.
	 */
	public function build_setting_fields(){
		$admin_setting = $this->admin_instance;
		//Add Sections.
		$admin_setting->add_section('main-setting', 'Main Settings');
		$admin_setting->add_section('style-setting', 'Style Settings');

		//-------------------------Main settings-----------------------
		$admin_setting->add_field_of_section('main-setting', 'how_to_use', 'How to use?', 'description', array(
		    'desc' => 'Use shortcode [csh_callback] or widget Csh Callback request to add call request form in frontend.'
		));

		$admin_setting->add_field_of_section('main-setting', 'disable_form', 'Disable default form', 'checkbox', array(
		    'desc' => 'Check if you want to disable default callback form in frontend.'
		));

		$admin_setting->add_field_of_section('main-setting', 'header_text', 'Header text', 'text', array(
		    'default' => 'request a callback',
		    'desc' => 'Change header text of callback form.'
		));

		$admin_setting->add_field_of_section('main-setting', 'header_desc', 'Header description', 'text', array(
		    'default' => 'Please fill out the form below and we will call you back',
		    'desc' => 'Change header description of callback form.'
		));

		$admin_setting->add_field_of_section('main-setting', 'popup_btn_text', 'Popup button text', 'text', array(
		    'default' => 'call back',
		    'desc' => 'Change text of button that calls popup form show by shortcode, visual composer.'
		));

		$admin_setting->add_field_of_section('main-setting', 'submit_btn_text', 'Submit button text', 'text', array(
		    'default' => 'call back',
		    'desc' => 'Change text of button in request callback form.'
		));

		$admin_setting->add_field_of_section('main-setting', 'popup_btn_avatar', 'Popup button avatar', 'upload', array(
		    'default' => '',
		    'desc' => 'Select avatar show at popup button, using gif image  for height friendly.'
		));

		//-------------------------Style settings-----------------------

		$admin_setting->add_field_of_section('style-setting', 'form_background', 'Form background color', 'color', array(
		    'default' => '#6b5692',
		    'desc' => 'Select background color of callback form.'
		));

		$admin_setting->add_field_of_section('style-setting', 'form_text_color', 'Form text color', 'color', array(
		    'default' => '#fff',
		    'desc' => 'Text color of callback form.'
		));

		$admin_setting->add_field_of_section('style-setting', 'popup_btn_color', 'Popup button color', 'color', array(
		    'default' => '#6b5692',
		    'desc' => 'Text color of popup button.'
		));

		$admin_setting->add_field_of_section('style-setting', 'popup_btn_text_color', 'Popup button text Color', 'color', array(
		    'default' => '#fff',
		    'desc' => 'Text color of popup button.'
		));

		$admin_setting->add_field_of_section('style-setting', 'submit_btn_color', 'Submit button color', 'color', array(
		    'default' => '#fff',
		    'desc' => 'Text color of form button.'
		));

		$admin_setting->add_field_of_section('style-setting', 'submit_btn_text_color', 'Submit button text Color', 'color', array(
		    'default' => '#000',
		    'desc' => 'Text color of form button.'
		));

	}

}
$plugin = new Csh_Callback; // Control public and admin class.





