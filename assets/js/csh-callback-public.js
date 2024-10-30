jQuery(document).ready(function($) {

    //shortcode-js
    $('.cshcb-open-modal').click(function (e) {
        $(".alert_status").hide();
        $('.cshcb-loading').hide();
        $('.cshcb-form').removeData('validator');
        $('.cshcb-form').removeData('unobtrusiveValidation');
    });

    $('.cshcb-submit').click(function (e) {
        var button_for = $(this).attr('button_for');
        e.preventDefault();
        e.stopPropagation();

        var wrap = $(this).parents('.cshcb-form');
        var sc_wrap = $(this).parents('.shortcode-form-wrap');
        wrap.validate({
            invalidHandler: function(form, validator) {
                var errors = validator.numberOfInvalids();
                if (errors) {
                    $('.alert_status',wrap).show();  
                    $('.alert_status',wrap).val(validator.errorList[0].message);
                    validator.errorList[0].element.focus(); //Set Focus
                }
            },
            rules: {
                cshcb_name: {
                    required: true,
                },
                cshcb_email: {
                    required: true,
                },
                cshcb_phone: {
                    required: true,
                }
            },
            messages: {
                cshcb_name: {required: "Enter your name"},
                cshcb_email: {required: "Enter your email"},
                cshcb_phone: {required: "Enter your phone"}                
            },
            errorPlacement: function(error, element) {
                //Nothing
            }
        });

        if (wrap.valid()) {
            $('.cshcb-loading',wrap).show();
            var name      = $('input[name="cshcb_name"]',wrap).val();
            var email     = $('input[name="cshcb_email"]',wrap).val();
            var phone     = $('input[name="cshcb_phone"]',wrap).val();

            $.ajax({
                type: 'POST',
                data: {
                    'action'   : 'cshcb_submit',
                    'name'     : name,
                    'email'    : email,
                    'phone'    : phone
                },
                url: cshcb_jsPassVar.ajax_url,
                success: function(data) {
                    if (data.callback_status == 'OK') {
                        arlet_string = 'Sent call request successfully! ';
                        $(".alert_status",wrap).val(arlet_string);
                        $(".alert_status",wrap).show();
                        if (button_for == 'widget') {
                            setTimeout(function(){
                                $('.widget-form-wrap').hide();
                                $(".alert_widget_success").val(arlet_string);
                                $(".alert_widget_success").show();
                            }, 1500); 
                        }else if(button_for == 'active'){
                            setTimeout(function(){
                                $(".alert_status",wrap).hide();
                                $(".bar-active").click();
                                $('.active-form-content').hide();
                                $(".alert_active_success").val(arlet_string);
                                $(".alert_active_success").show();
                            }, 1500);           
                        }else{
                            setTimeout(function(){
                                $(".alert_status",wrap).hide();
                                $(".cshcb-modal-header .close").click();
                                $(".cshcb-open-modal",sc_wrap).hide();
                                $(".alert_shortcode_success").val(arlet_string);
                                $(".alert_shortcode_success").show();
                            }, 1500);              
                        }
                        
                        $('input[name="cshcb_name"]',wrap).val("");
                        $('input[name="cshcb_email"]',wrap).val("");
                        $('input[name="cshcb_phone"]',wrap).val("");
                    }else{
                        alert('Sorry! Cant Sent call request now.');
                        $('.cshcb-loading',wrap).hide();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    alert('Sorry! Cant Sent call request now.');
                }
            });
        }
        
    });

    //active-js
    $(document).on('click', ".bar-deactive", function() {
        var wrap = $(this).parents('.cshcb-form');
        $(".alert_status",wrap).hide();
        $('.cshcb-loading',wrap).hide();
        $(this).removeClass('bar-deactive');
        $(this).addClass('bar-active');
        $('.active-form-wrap').removeClass('cb-hide');
        $('.active-form-wrap').addClass('cb-show');
    });

    $(document).on('click', ".bar-active", function() {
        var wrap = $(this).parents('.cshcb-form');
        $(".alert_status",wrap).hide();
        $('.cshcb-loading',wrap).hide();
        $(this).removeClass('bar-active');
        $(this).addClass('bar-deactive');
        $('.active-form-wrap').removeClass('cb-show');
        $('.active-form-wrap').addClass('cb-hide');
    });
   
});


