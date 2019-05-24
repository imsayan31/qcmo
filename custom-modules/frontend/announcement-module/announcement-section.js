jQuery(document).ready(function ($) {

    /* State and City populated */
    $('.state-selection').on('change', function() {
        var _Id = $(this).data('target');
        var _this_val = $(this).val();
        var data = {
            action: 'state_selection',
            state_id: _this_val
        };
        $.post(AnnouncementSection.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $('#'+_Id).html(resp.msg);
                $('#'+_Id).trigger("chosen:updated");
                $('#'+_Id).trigger("liszt:updated");
            } else {

            }
        }, 'json');
    });

    /* Announcement Start Date */
    $('#announcement_create_date, #announcement_renewal_create_date').datepicker({
        dateFormat: 'dd-mm-yy',
        changeMonth: true,
        changeYear: true,
        minDate: 0
    });
    
    /* Announcement File Uploader */
    $('#drag-and-drop-zone').dmUploader({
        url: AnnouncementSection.drag_n_drop_admin_url,
        maxFileSize: 3000000,
        maxFiles: 200,
        allowedTypes: 'image/*',
        extFilter: ["jpg", "jpeg", "png", "gif"],
        onDragEnter: function () {
            // Happens when dragging something over the DnD area
            this.addClass('active');
        },
        onDragLeave: function () {
            // Happens when dragging something OUT of the DnD area
            this.removeClass('active');
        },
        onInit: function () {
            // Plugin is ready to use
            ui_add_log('Penguin initialized :)', 'info');
        },
        onComplete: function () {
            // All files in the queue are processed (success or error)
            ui_add_log('All pending tranfers finished');
        },
        onNewFile: function (id, file) {
            // When a new file is added using the file selector or the DnD area
            ui_add_log('New file added #' + id);
            ui_multi_add_file(id, file);
        },
        onBeforeUpload: function (id) {
            // about tho start uploading a file
            ui_add_log('Starting the upload of #' + id);
            ui_multi_update_file_status(id, 'uploading', 'Uploading...');
            ui_multi_update_file_progress(id, 0, '', true);
        },
        onUploadCanceled: function (id) {
            // Happens when a file is directly canceled by the user.
            ui_multi_update_file_status(id, 'warning', 'Canceled by User');
            ui_multi_update_file_progress(id, 0, 'warning', false);
        },
        onUploadProgress: function (id, percent) {
            // Updating file progress
            ui_multi_update_file_progress(id, percent);
        },
        onUploadSuccess: function (id, data) {
            console.log(id);
            var resp = jQuery.parseJSON(data);
            console.log(resp.attachids);
            var old_val = $('input.property_main_images').val();
            if (old_val != '') {
                $('input.property_main_images').val(old_val + ',' + resp.attachids);
            } else {
                $('input.property_main_images').val(resp.attachids);
            }

            // A file was successfully uploaded
            ui_add_log('Server Response for file #' + id + ': ' + JSON.stringify(data));
            ui_add_log('Upload of file #' + id + ' COMPLETED', 'success');
            ui_multi_update_file_status(id, 'success', 'Upload Concluido');
            ui_multi_update_file_progress(id, 100, 'success', false);
        },
        onUploadError: function (id, xhr, status, message) {
            ui_multi_update_file_status(id, 'danger', message);
            ui_multi_update_file_progress(id, 0, 'danger', false);
        },
        onFallbackMode: function () {
            // When the browser doesn't support this plugin :(
            ui_add_log('Plugin cant be used here, running Fallback callback', 'danger');
        },
        onFileSizeError: function (file) {
            ui_add_log('File \'' + file.name + '\' cannot be added: size excess limit', 'danger');
        }
    });

    /* Announcement Plan Selection */
    $('.announement-plan-selection').on('click', function (e) {
        var _this_val = $(this).val();
        var announcement_period = $('#announcement_period').val();
        var announcement_start_date = $('#announcement_create_date').val();
        if (!announcement_period) {
            announcement_period = $('#announcement_renewal_period').val();
        }
        if (!announcement_start_date) {
            announcement_start_date = $('#announcement_renewal_create_date').val();
        }
        var data = {
            action: 'get_announcement_price',
            announcement_plan: _this_val,
            announcement_start_date: announcement_start_date,
            announcement_period: announcement_period
        };
        $('.loader').show();
        $.post(AnnouncementSection.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                $('.show-announce-plan-price').html(resp.msg);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });
    
    /* Announcement Creation */
    $('#announcementCreationFrm').on('submit', function () {
        var announcement_name = $('#announcement_name').val();
        /*var announcement_create_date = $('#announcement_create_date').val();*/
        var announcement_period = $('#announcement_period').val();
        var announcment_price = $('#announcment_price').val();
        var announcement_category = $('.announcement_category')[0].selectedIndex;
        var announcement_name_check = isNumeric(announcement_name);
        var price_validation = priceValidation(announcment_price);

        if (!announcement_name) {
            $('#announcement_name').siblings('div.input-error-msg').text('Digite o nome do seu anúncio.');
            $('div.input-error-msg').not($('#announcement_name').siblings('div.input-error-msg')).text('');
        } else if (announcement_name_check == true) {
            $('#announcement_name').siblings('div.input-error-msg').text('O nome do anúncio não pode ser numérico.');
            $('div.input-error-msg').not($('#announcement_name').siblings('div.input-error-msg')).text('');
        } else if (announcement_category < 0) {
            $('.announcement_category').siblings('div.input-error-msg').text('Selecione sua categoria de anúncio.');
            $('div.input-error-msg').not($('.announcement_category').siblings('div.input-error-msg')).text('');
        } /*else if (!announcement_create_date) {
            $('#announcement_create_date').siblings('div.input-error-msg').text('Select your announcement start date.');
            $('div.input-error-msg').not($('#announcement_create_date').siblings('div.input-error-msg')).text('');
        } */else if (!announcement_period) {
            $('#announcement_period').siblings('div.input-error-msg').text('Insira seu período de aviso em dias.');
            $('div.input-error-msg').not($('#announcement_period').siblings('div.input-error-msg')).text('');
        } 
        /*else if (!announcment_price) {
            $('#announcment_price').siblings('div.input-error-msg').text('Insira o preço do seu anúncio.');
            $('div.input-error-msg').not($('#announcment_price').siblings('div.input-error-msg')).text('');
        }*/ else if (announcment_price && price_validation == false) {
            $('#announcment_price').siblings('div.input-error-msg').text('Formato do valor do preço do anúncio: 1,00 ou 10,00 ou 100,00 ou 1000,00');
            $('div.input-error-msg').not($('#announcment_price').siblings('div.input-error-msg')).text('');
        } else {
            $('div.input-error-msg').text('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('announcementCreationSbmt'));
            l.start();
            $.post(AnnouncementSection.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    site_announcement_redirect(resp.url);
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json').always(function () {
                l.stop();
            });
        }
    });


    /* Announcement Update */
    $('#announcementUpdateFrm').on('submit', function () {
        var announcement_name = $('#announcement_updated_name').val();
        var announcement_category = $('.announcement_category')[0].selectedIndex;
        var announcment_updated_price = $('#announcment_updated_price').val();
        var announcement_name_check = isNumeric(announcement_name);
        var price_validation = priceValidation(announcment_updated_price);

        if (!announcement_name) {
            $('#announcement_updated_name').siblings('div.input-error-msg').text('Digite o nome do seu anúncio.');
            $('div.input-error-msg').not($('#announcement_updated_name').siblings('div.input-error-msg')).text('');
        } else if (announcement_name_check == true) {
            $('#announcement_name').siblings('div.input-error-msg').text('O nome do anúncio não pode ser numérico.');
            $('div.input-error-msg').not($('#announcement_name').siblings('div.input-error-msg')).text('');
        } else if (announcement_category < 0) {
            $('.announcement_category').siblings('div.input-error-msg').text('Selecione sua categoria de anúncio.');
            $('div.input-error-msg').not($('.announcement_category').siblings('div.input-error-msg')).text('');
        } /*else if (!announcment_updated_price) {
            $('#announcment_updated_price').siblings('div.input-error-msg').text('Insira o preço do seu anúncio.');
            $('div.input-error-msg').not($('#announcment_updated_price').siblings('div.input-error-msg')).text('');
        } */else if (announcment_updated_price && price_validation == false) {
            $('#announcment_updated_price').siblings('div.input-error-msg').text('Formato do valor do preço do anúncio: 1,00 ou 10,00 ou 100,00 ou 1000,00');
            $('div.input-error-msg').not($('#announcment_updated_price').siblings('div.input-error-msg')).text('');
        } else {
            $('div.input-error-msg').text('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('announcementUpdateSbmt'));
            l.start();
            $.post(AnnouncementSection.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    site_announcement_redirect(resp.url);
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json').always(function () {
                l.stop();
            });
        }
    });

    /* Announcement Renewal */
    $('#announcementRenewalFrm').on('submit', function () {
        var announcement_name = $('#announcement_renewal_name').val();
        var announcement_create_date = $('#announcement_renewal_create_date').val();
        var announcement_period = $('#announcement_renewal_period').val();
        var announcment_renewal_price = $('#announcment_renewal_price').val();
        var announcement_category = $('.announcement_category')[0].selectedIndex;
        var announcement_name_check = isNumeric(announcement_name);
        var price_validation = priceValidation(announcment_renewal_price);

        if (!announcement_name) {
            $('#announcement_renewal_name').siblings('div.input-error-msg').text('Digite o nome do seu anúncio .');
            $('div.input-error-msg').not($('#announcement_renewal_name').siblings('div.input-error-msg')).text('');
        } else if (announcement_name_check == true) {
            $('#announcement_renewal_name').siblings('div.input-error-msg').text('O nome do anúncio não pode ser numérico.');
            $('div.input-error-msg').not($('#announcement_renewal_name').siblings('div.input-error-msg')).text('');
        } else if (announcement_category < 0) {
            $('.announcement_category').siblings('div.input-error-msg').text('Selecione sua categoria de anúncio.');
            $('div.input-error-msg').not($('.announcement_category').siblings('div.input-error-msg')).text('');
        } 
        /* else if (!announcement_create_date) {
            $('#announcement_renewal_create_date').siblings('div.input-error-msg').text('Select your announcement start date.');
            $('div.input-error-msg').not($('#announcement_renewal_create_date').siblings('div.input-error-msg')).text('');
        } */
        else if (!announcement_period) {
            $('#announcement_renewal_period').siblings('div.input-error-msg').text('Insira seu período de aviso em dias.');
            $('div.input-error-msg').not($('#announcement_renewal_period').siblings('div.input-error-msg')).text('');
        } 
        /*else if (!announcment_renewal_price) {
            $('#announcment_renewal_price').siblings('div.input-error-msg').text('Insira o preço do seu anúncio.');
            $('div.input-error-msg').not($('#announcment_renewal_price').siblings('div.input-error-msg')).text('');
        } */
         else if (announcment_renewal_price && price_validation == false) {
            $('#announcment_renewal_price').siblings('div.input-error-msg').text('Formato do valor do preço do anúncio: 1,00 ou 10,00 ou 100,00 ou 1000,00');
            $('div.input-error-msg').not($('#announcment_renewal_price').siblings('div.input-error-msg')).text('');
        } else {
            $('div.input-error-msg').text('');
            var data = $(this).serialize();
            var l = Ladda.create(document.getElementById('announcementRenewalSbmt'));
            l.start();
            $.post(AnnouncementSection.ajaxurl, data, function (resp) {
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    site_announcement_redirect(resp.url);
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json').always(function () {
                l.stop();
            });
        }
    });

    /* Announcement Image Delete */
    $('.delete-announcement-image').on('click', function () {
        var _this = $(this);
        var _this_announcement_image = _this.data('img');
        var _this_announcement = _this.data('announcement');
        var data = {
            action: 'announcement_image_delete',
            announcement_image: _this_announcement_image,
            announcement: _this_announcement
        };
        $('.loader').show();
        $.post(AnnouncementSection.ajaxurl, data, function (resp) {
            $('.loader').hide();
            if (resp.flag == true) {
                _this.parent('div.indiv-announcement-img').remove();
                $('.property_main_images').val(resp.images);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json');
    });

    /* Announcement Enabling */
    $('.announcement-enabling').on('click', function () {
        var _this = $(this);
        var _this_announcement = _this.data('announcement');
        var r = confirm("Tem certeza de que deseja ativar este anúncio?");
        if (r == true) {
            var data = {
                action: 'announcement_activate',
                announcement: _this_announcement
            };
            $('.loader').show();
            $.post(AnnouncementSection.ajaxurl, data, function (resp) {
                $('.loader').hide();
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    site_announcement_redirect(resp.url);
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json');
        } else {

        }
    });

    /* Announcement Disabling */
    $('.announcement-disabling').on('click', function () {
        var _this = $(this);
        var _this_announcement = _this.data('announcement');
        var r = confirm("Tem certeza de que deseja desativar este anúncio?");
        if (r == true) {
            var data = {
                action: 'announcement_deactivate',
                announcement: _this_announcement
            };
            $('.loader').show();
            $.post(AnnouncementSection.ajaxurl, data, function (resp) {
                $('.loader').hide();
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    site_announcement_redirect(resp.url);
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json');
        } else {

        }
    });
    
    /*Announcement Delete*/
    $('a.announcement-delete').confirmation({
        rootSelector: '[data-toggle=confirmation]',
        placement: 'left',
        popout : true,
        title : 'Are you sure, you want to delete?',
        onConfirm: function () {
            var _data = {
                action : 'delete_user_announcement',
                ID : $(this).data('announcement')
            }
            
            $('.loader').show();
            $.post(AnnouncementSection.ajaxurl, _data, function (resp) {
                $('.loader').hide();
                if (resp.flag == true) {
                    $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                    site_announcement_redirect(resp.url);
                } else {
                    $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
                }
            }, 'json');
        }
    });

    /* Click to Announcement Payment */
    $('.click-announce-pay').on('click', function(){
        var _this_announce = $(this).data('announcement');
        $('#selected_announcement_plan').val(_this_announce);
    });

    /* Announcement Payment */
    $('#usr_announcement_payment_frm').on('submit', function () {
        var data = $(this).serialize();
        var l = Ladda.create(document.getElementById('usr_announcement_payment_sbmt'));
        l.start();
        $.post(UserPlanPurchase.ajaxurl, data, function (resp) {
            if (resp.flag == true) {
                $.notify({message: resp.msg}, {type: 'success', z_index: 20000, close: true, delay: 3000});
                site_redirect(resp.url);
            } else {
                $.notify({message: resp.msg}, {type: 'danger', z_index: 20000, close: true, delay: 5000});
            }
        }, 'json').always(function () {
            l.stop();
        });
    });
    
    $('div.plan-marker').on('click', function(){
       var _this = $(this);
       $('div.plan-marker').removeClass('active');
       _this.addClass('active');
       var _plan_type = _this.prop('id');
       $('input#'+_plan_type+'_plan').trigger('click');
       
       
    });
    
   $('#announcement_period').on('blur',function(){
             
        if ($(this).val() != '' && $('.announement-plan-selection').is(':checked')) {
            
            $('.announement-plan-selection').each(function(){
                if($(this).is(':checked')) {
                    $(this).trigger('click');
                }
            });   
         }

    });

});

/* Check only numeric */
function isNumeric(str) {
    return /^[0-9()]+$/.test(str);
}

/* Site redirection */
function site_announcement_redirect(url) {
    if (url === undefined)
        url = '';
    setTimeout(function () {
        window.location.href = url;
    }, 1000);
}

/* Check if is price */
function priceValidation(price) {
    var valid = /^\d{0,100}(\.\d{0,2})?$/.test(price),
            val = price;
    if (!valid) {
        //alert("Invalid input!");
        //this.value = val.substring(0, val.length - 1);
        return false;
    } else {
        return true;
    }
}