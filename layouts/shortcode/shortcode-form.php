<?php 
wp_enqueue_style('cshcb_shortcode_form');
$modal_id =  "cshcb-modal-".$cshcb_sc_id;

global $cshcb_options;

$header_text = 'request a callback';
if (isset($cshcb_options['header_text'])) {
	$header_text = $cshcb_options['header_text'];
}

$header_desc = 'Please fill out the form below and we will call you back';
if (isset($cshcb_options['header_desc'])) {
	$header_desc = $cshcb_options['header_desc'];
}

$popup_btn_text = 'call back';
if (isset($cshcb_options['popup_btn_text'])) {
	$popup_btn_text = $cshcb_options['popup_btn_text'];
}

$submit_btn_text = 'call back';
if (isset($cshcb_options['submit_btn_text'])) {
	$submit_btn_text = $cshcb_options['submit_btn_text'];
}

$dynamic_css = "";
if ( !empty($cshcb_options['popup_btn_color']) ) {
    $popup_btn_color = $cshcb_options['popup_btn_color'];
    $dynamic_css .= ".cshcb-open-modal{ background-color: {$popup_btn_color}; }";
}

if ( !empty($cshcb_options['popup_btn_text_color']) ) {
    $popup_btn_text_color = $cshcb_options['popup_btn_text_color'];
    $dynamic_css .= ".cshcb-open-modal{ color: {$popup_btn_text_color}; }";
}

wp_add_inline_style('cshcb_shortcode_form', $dynamic_css);

?>


<div class="shortcode-form-wrap">
	<!-- Trigger the modal with a button -->
	<?php
	if (!empty($cshcb_options['popup_btn_avatar'])) {
		$popup_btn_avatar = $cshcb_options['popup_btn_avatar'];
		?>
		<button type="button" class="cshcb-open-modal" data-toggle="modal" style="border-radius: 100px; padding: 5px; padding-right: 30px;" data-target="<?php echo '#'.$modal_id ?>">
			<img src="<?php echo $popup_btn_avatar ?>"><?php echo $popup_btn_text ; ?>
		</button>
		<?php
	}else{
		?>
		<button type="button" class="cshcb-open-modal" data-toggle="modal" style="border-radius: 3px;padding: 12px;" data-target="<?php echo '#'.$modal_id ?>"><?php echo $popup_btn_text ; ?></button>
		<?php
	} 
	?>


	<!-- Modal -->
	<div class="modal fade cshcb-modal" id="<?php echo $modal_id ?>" role="dialog" style="top: 10%;">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content cshcb-content">

				<div class="modal-body">
					<div class="cshcb-modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<p class="modal-title"><?php echo $header_text; ?></p>
						<p><?php echo $header_desc; ?></p>
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
			                <input type="submit" value="<?php echo $submit_btn_text ?>" class="button-blue cshcb-submit" button_for = "shortcode" />

			                <div class="cshcb-loading-wrap">
			                	<div class="cshcb-loading">
									<i class="fas fa-spinner fa-spin"></i>
								</div>
			                </div>
			            </div>
		        	</form>

				</div>
			</div>
		  
		</div>
	</div>
  
</div>

<div class="wrap-success-alert">
    <input style="display: none;" type="text" class="alert_shortcode_success" readonly>
</div>