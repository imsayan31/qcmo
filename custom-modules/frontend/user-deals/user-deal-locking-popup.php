<?php
/*
 * User Deal Locking Popup
 * 
 */
?>
<div id="user_deal_locking_popup" role="dialog" class="modal fade modal-cs inputModal" aria-hidden="true">
    <div class="modal-dialog reset-pop">
        <div class="modal-content">
            <div class="modal-header">
                <a class="close" data-dismiss="modal">
                    <svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" width="18px" height="18px" version="1.1" height="512px" viewBox="0 0 64 64" enable-background="new 0 0 64 64">
                        <g>
                            <path fill="#000000" d="M28.941,31.786L0.613,60.114c-0.787,0.787-0.787,2.062,0,2.849c0.393,0.394,0.909,0.59,1.424,0.59   c0.516,0,1.031-0.196,1.424-0.59l28.541-28.541l28.541,28.541c0.394,0.394,0.909,0.59,1.424,0.59c0.515,0,1.031-0.196,1.424-0.59   c0.787-0.787,0.787-2.062,0-2.849L35.064,31.786L63.41,3.438c0.787-0.787,0.787-2.062,0-2.849c-0.787-0.786-2.062-0.786-2.848,0   L32.003,29.15L3.441,0.59c-0.787-0.786-2.061-0.786-2.848,0c-0.787,0.787-0.787,2.062,0,2.849L28.941,31.786z"/>
                        </g>
                    </svg>
                </a>  
                <h2><?php _e('Get Your Deal Shareable Link', THEME_TEXTDOMAIN); ?></h2>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="deal-details-msg" style="display:none;"><?php _e("link é copiado na sua área de transferência", THEME_TEXTDOMAIN)?></div>
<!--                        <div class="deal-details-link"></div>-->
                            <div class="deal-details-link"><input readonly="true" type="text" id="deal_details_link" value="abc" /></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="text-center"><button type="button" class="copy-btn" onclick="myCopyFunction()"><?php _e("Copy", THEME_TEXTDOMAIN); ?></button>
                       <!-- <div class="text-center"><button type="button" class="copy-btn1" ><?php _e("Copy", THEME_TEXTDOMAIN); ?></button></div>-->
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?php _e('If your deal is unloacked then your shareable link will be visible by your shared person. Otherwise it will be closed.', THEME_TEXTDOMAIN); ?>
            </div>
        </div>
    </div>    
</div>
<script>
/*jQuery(document).ready(function(){
  $(".copy-btn1").on('click',function(){
   var copyText = document.getElementById("deal_details_link");
   copyText.select();
    document.execCommand("copy");
     jQuery('.deal-details-msg').show();
  });
});*/
    function myCopyFunction() {
        
        /* Get the text field */
        var copyText = document.getElementById("deal_details_link");

        /* Select the text field */
        copyText.select();

        /* Copy the text inside the text field */
        document.execCommand("copy");

        /* Alert the copied text */
        jQuery('.deal-details-msg').show();

        //var input = document.getElementById(elementId);
        var isiOSDevice = navigator.userAgent.match(/ipad|iphone/i);

        if (isiOSDevice) {
          
            var editable = copyText.contentEditable;
            var readOnly = copyText.readOnly;

            copyText.contentEditable = true;
            copyText.readOnly = false;

            var range = document.createRange();
            range.selectNodeContents(copyText);

            var selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);

            copyText.setSelectionRange(0, 999999);
            copyText.contentEditable = editable;
            copyText.readOnly = readOnly;

        } else {
            copyText.select();
        }

        document.execCommand('copy');
    }
    jQuery(document).ready(function ($) {

        // Detect ios 11_x_x affected
        // NEED TO BE UPDATED if new versions are affected 
        /*(function iOS_CaretBug() {

            var ua = navigator.userAgent,
                    scrollTopPosition,
                    iOS = /iPad|iPhone|iPod/.test(ua),
                    iOS11 = /OS 11_0_1|OS 11_0_2|OS 11_0_3|OS 11_1|OS 11_1_1|OS 11_1_2|OS 11_2|OS 11_2_1/.test(ua);

            // ios 11 bug caret position
            if (iOS && iOS11) {

                $(document.body).on('show.bs.modal', function (e) {
                    if ($(e.target).hasClass('inputModal')) {
                        // Get scroll position before moving top
                        scrollTopPosition = $(document).scrollTop();

                        // Add CSS to body "position: fixed"
                        $("body").addClass("iosBugFixCaret");
                    }
                });

                $(document.body).on('hide.bs.modal', function (e) {
                    if ($(e.target).hasClass('inputModal')) {
                        // Remove CSS to body "position: fixed"
                        $("body").removeClass("iosBugFixCaret");

                        //Go back to initial position in document
                        $(document).scrollTop(scrollTopPosition);
                    }
                });

            }
        })();*/
    });
</script>
<?php
