<?php 
wp_enqueue_style('cshcb_active_form');

global $cshcb_options;
$submit_btn_text = 'call back';
if (isset($cshcb_options['submit_btn_text'])) {
    $submit_btn_text = $cshcb_options['submit_btn_text'];
}
?>

<div class="active-form-wrap cshcb-content">
    <form method="POST" class="form cshcb-form">

        <div class="vertical bar-deactive" style="background-color:rgba(45,98,143,1)">
            <span><?php _e( 'Request a CallBack', 'cshcallback' ) ?></span>
            <span class="callback-icon"><img src="<?php echo CSHCB_PLUGIN_ASSETS_URL . 'images/callback.png' ?>"></span>
            </span>
        </div>

        <div class="active-form-content">
            <p>
                <input type="text" class="alert_status" readonly>
            </p>

            <p class="name">
                <input name="cshcb_name" type="text" placeholder="<?php _e( 'Name', 'cshcallback' ) ?>" class="cshcb_name" />
            </p>

            <p class="email">
                <input name="cshcb_email" type="email" placeholder="<?php _e( 'Email', 'cshcallback' ) ?>" class="cshcb_email"/>
            </p>

            <p class="phone">
                <input name="cshcb_phone" type="text" placeholder="<?php _e( 'Phone', 'cshcallback' ) ?>" class="cshcb_phone"/>
            </p>

            <div class="submit">
                <input type="submit" value="<?php echo $submit_btn_text ?>" class="button-blue cshcb-submit" button_for = "active"/>

                <div class="cshcb-loading-wrap">
                    <div class="cshcb-loading">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="wrap-success-alert">
            <input type="text" class="alert_active_success" readonly>
        </div>
        
    </form>
</div>



