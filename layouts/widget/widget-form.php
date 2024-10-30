<?php 
wp_enqueue_style('cshcb_widget_form');

$wg_header_text  = $instance['header_text'];
$wg_header_desc = $instance['header_desc'];

global $cshcb_options;
$submit_btn_text = 'call back';
if (isset($cshcb_options['submit_btn_text'])) {
    $submit_btn_text = $cshcb_options['submit_btn_text'];
}
?>


<div class="widget-form-wrap cshcb-content">
    <div class="cshcb-widget-header">
        <p class="widget-title"><?php echo $wg_header_text; ?></p>
        <p><?php echo $wg_header_desc; ?></p>
    </div>
    <form method="POST" class="form cshcb-form">
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
            <input type="submit" value="<?php echo $submit_btn_text ?>" class="button-blue cshcb-submit" button_for = "widget" />

            <div class="cshcb-loading-wrap">
                <div class="cshcb-loading">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="wrap-success-alert">
    <input type="text" class="alert_widget_success" readonly>
</div>