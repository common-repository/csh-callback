<?php
if (!session_id()) {
    session_start();
}
/**
 * Class to handle all custom post type definitions for Restaurant Reservations
 */
if (!defined('ABSPATH'))
    exit;

class Csh_Callback_Public {

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


	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Database process.
        require_once(CSHCB_PLUGIN_INCLUDES_DIR.'class-csh-callback-database.php');
        $this->db = new Csh_Callback_Database();
		//--------------------Action------------------//
		add_action('wp_ajax_cshcb_submit', array($this, 'cshcb_submit'));
        add_action('wp_ajax_nopriv_cshcb_submit', array($this, 'cshcb_submit'));

        add_action('wp_enqueue_scripts', array($this, 'cshcb_public_register_style'));
        add_action('wp_enqueue_scripts', array($this, 'cshcb_public_register_script'));

        //------------------Shortcode------------------//
        add_shortcode('csh_callback', array($this, 'do_cshcb_shortcode'));

        global $cshcb_options;

        if (!isset($cshcb_options['disable_form'])) {
        	add_action( 'wp_footer', array($this, 'show_callback_form_active') );
        }

	}

	public function cshcb_public_register_style(){
		wp_enqueue_style('bootstrap', CSHCB_PLUGIN_ASSETS_URL . 'css/bootstrap.min.css');
		wp_enqueue_style('fontawesome-css', CSHCB_PLUGIN_ASSETS_URL . 'css/fontawesome.min.css');
        wp_register_style('cshcb_public_style', CSHCB_PLUGIN_ASSETS_URL . 'css/csh-callback-public.css');
        wp_enqueue_style('cshcb_public_style');

        // dynamic style settings.
        global $cshcb_options;
        $dynamic_css = "";

	    if ( !empty($cshcb_options['form_background']) ) {
	        $form_background = $cshcb_options['form_background'];
	        $dynamic_css .= ".cshcb-content { background-color: {$form_background}; }";
	    }

	    if ( !empty($cshcb_options['form_text_color']) ) {
	        $form_text_color = $cshcb_options['form_text_color'];
	        $dynamic_css .= ".cshcb-content, .time label { color: {$form_text_color}; }";
	    }

	    if ( !empty($cshcb_options['submit_btn_color']) ) {
	        $submit_btn_color = $cshcb_options['submit_btn_color'];
	        $dynamic_css .= ".cshcb-form .submit .cshcb-submit{ background: {$submit_btn_color}; }";
	    }

	    if ( !empty($cshcb_options['submit_btn_text_color']) ) {
	        $submit_btn_text_color = $cshcb_options['submit_btn_text_color'];
	        $dynamic_css .= ".cshcb-form .submit .cshcb-submit{ color: {$submit_btn_text_color}; }";
	    }

	    wp_add_inline_style('cshcb_public_style', $dynamic_css);

        // Load style template.
        // -----shortcode------
	    $shortcode_form_css = get_stylesheet_directory().'/pl_templates/csh-callback/layouts/shortcode/shortcode-form.css';
		if (file_exists( $shortcode_form_css )) {
			wp_register_style('cshcb_shortcode_form', get_stylesheet_directory_uri().'/pl_templates/csh-callback/layouts/shortcode/shortcode-form.css');
		}else {
			wp_register_style('cshcb_shortcode_form', CSHCB_PLUGIN_LAYOUTS_URL . 'shortcode/shortcode-form.css');
		}

		// -----active------
		$active_form_css = get_stylesheet_directory().'/pl_templates/csh-callback/layouts/active/active-form.css';
		if (file_exists( $active_form_css )) {
			wp_register_style('cshcb_active_form', get_stylesheet_directory_uri().'/pl_templates/csh-callback/layouts/active/active-form.css');
		}else {
			wp_register_style('cshcb_active_form', CSHCB_PLUGIN_LAYOUTS_URL . 'active/active-form.css');
		}
    }

	public function cshcb_public_register_script(){
        wp_enqueue_script('jquery');
        wp_enqueue_script('bootstrap-js', CSHCB_PLUGIN_ASSETS_URL . 'js/bootstrap.min.js');
        wp_enqueue_script('jquery-validate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js');

        wp_register_script('cshcb_public_script', CSHCB_PLUGIN_ASSETS_URL . 'js/csh-callback-public.js');
        wp_enqueue_script('cshcb_public_script');
        // Pass Data to Js.
	    wp_localize_script('cshcb_public_script', 'cshcb_jsPassVar', array(
	        'ajax_url' => admin_url('admin-ajax.php')
        ));
    }


	public function do_cshcb_shortcode($atts, $content = null) {
		ob_start();
		STATIC $cshcb_sc_id = 1;
        // Load template.
	    $template_file = get_stylesheet_directory().'/pl_templates/csh-callback/layouts/shortcode/shortcode-form.php';
		if (file_exists( $template_file )) {
			require $template_file;
		}else {
			require CSHCB_PLUGIN_LAYOUTS_DIR . '/shortcode/shortcode-form.php';
		}
		$cshcb_sc_id++;
		return ob_get_clean();
	}

	public function show_callback_form_active() {
		
		wp_enqueue_style('cshcb_public_style');

        // Load template.
	    $template_file = get_stylesheet_directory().'/pl_templates/csh-callback/layouts/active/active-form.php';
		if (file_exists( $template_file )) {
			require $template_file;
		}else {
			require CSHCB_PLUGIN_LAYOUTS_DIR . '/active/active-form.php';
		}

	}



	public function cshcb_submit(){
		if (isset($_POST)) {
			$name      = sanitize_text_field( $_POST['name'] );
			$email     = sanitize_text_field( $_POST['email'] );
			$phone     = sanitize_text_field( $_POST['phone'] );
			$message   = sanitize_text_field( $_POST['message'] );
			$from_time = sanitize_text_field( $_POST['from_time'] );
			$to_time   = sanitize_text_field( $_POST['to_time'] );
			$today         = date("Y-m-d H:i:s");
    
			$last_id  = $this->db->insert('csh_callback_request', 
							array(
								'name'      => $name,
								'email'     => $email,
								'phone'     => $phone,
								'date'      => $today,
								'called'    => 0
							)
						);
			if ($last_id) {
				$callback_status = array(
		            'callback_status' => 'OK',
		        );
		        wp_send_json($callback_status);
			}
	        exit();
            
        }
	}
}
