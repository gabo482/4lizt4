validate_form_dropdown();
$("#cc_sh_cq_product_image_file").hide();
$("#cq_product_code_drpdwn").on("change",function(){
    var product_id = $(this).val();
    if(product_id!=''){
        $("#existing_product_id").val(product_id);
        $("#cc_sh_cq_product_image_file").show();
    } else {
        $("#cc_sh_cq_product_image_file").hide();
    }
});

$("#cq_product_image_file").on("change",function(){
    readURL(this);
});

$("#btn_cancel").on("click",function(){
    jqueryUIdialogBox("<div></div>", js_lang_label.LBL_GENERAL_ARE_YOU_SURE, {
        title: js_lang_label.LBL_GENERAL_CONFORMATION,
        buttons: [{
            text: js_lang_label.LBL_GENERAL_YES,
            bt_type: "Yes",
            click: function() {
                parent.jQuery.fancybox.close();  
                $(this).remove();
            }
        },{
            text: js_lang_label.LBL_GENERAL_NO,
            bt_type: "cancel",
            click: function() {
                $(this).remove();
            }
        }]
    });
});

function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img_show').show();
            $('#img_show').attr('src', e.target.result);
            $(".upload-input-file").html(e.target.fileName);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$("#img_show").hide();
var product_type = $("input[name='cc_sh_cq_product_type']:checked").val();
if(product_type=='Existing'){
    $("#product_type_text").hide();
    $("#cc_sh_cq_our_parent_code_div").show();
    $("#product_type_dropdown").show();
    $("#cq_product_code_text").rules('remove');
    var product_id = $("#existing_product_id").val();
    if(product_id!=''){
        $("#cc_sh_cq_product_image_file").show();
    } else {
        $("#cc_sh_cq_product_image_file").hide();
    }
} else if (product_type=='New'){
    $("#product_type_dropdown").hide();
    $("#product_type_text").show();
    $("#cc_sh_cq_product_image_file").show();
    $("#cq_product_code_drpdwn").rules('remove');
    $("#img_show").hide();
    $("#cq_product_image_type").val('Local');
    $("#new_product_type").val('New');
}

$(".product_type_radio").on("change",function(){
    var product_type = $(this).val();
    $("#product_type_hidden").val(product_type);
    $("#cq_our_parent_code").val('');
    $("#cq_product_code_text").val('');
    $("#cq_product_description").val('');
    $("#cq_product_color").val('');
    $("#cq_product_technique").val('');
    $("#cq_product_material").val('');
    $("#cq_product_measures").val('');
    $("#cq_product_printing_technique").val('');
    $("#cq_product_image_url").val('');
    $("#cq_product_quantity").val('');
    $("#cq_product_selling_price").val('');
    $("#cq_product_extra_cost").val('');
    $("#cq_product_comment").val('');
    $("#cq_product_extra_charges_comment").val('');
    $("#cq_product_total_price").val('0.00');
    $("#cq_product_grand_total_price").val('0.00');
    $("#img_show").attr("src",'');
    $("#show_image_url").attr("href",'');
    if(product_type=='Existing'){
        $("#product_type_text").hide();
        $("#cq_product_code_text").hide();
        $("#cc_sh_cq_our_parent_code_div").show();
        $("#product_type_dropdown").show();
        $("#cq_product_code_text").rules('remove');
        var product_id = $("#existing_product_id").val();
        if(product_id!=''){
            $("#cc_sh_cq_product_image_file").show();
        } else {
            $("#cc_sh_cq_product_image_file").hide();
        }
    } else if (product_type=='New'){
        $("#cq_product_code_text").show();
        $("#product_type_dropdown").hide();
        $("#product_type_text").show();
        $("#cc_sh_cq_product_image_file").show();
        $("#cq_product_code_drpdwn").tokenInput("clear");
        $(".token-input-dropdown-facebook p").hide();
        $("#cq_product_code_drpdwn").rules('remove');
        $("#cq_product_image_type").val('Local');
        $("#new_product_type").val('New');
        
    }
});

var get_products_url = admin_url+"quotation/quotation/getSearchedProductsList";
if(pid!=''){
    $.get(get_products_url+"?ids="+pid+"&nid="+nid, function(data, status){
        $('#cq_product_code_drpdwn').tokenInput(get_products_url, {
        minChars: 1,
        preventDuplicates: true,
        propertyToSearch: 'val',
        tokenLimit: 1,
        hintText: js_lang_label.LBL_QUTATION_PRODUCT_SEARCH_PRODUCT,
        noResultsText: js_lang_label.LBL_QUTATION_PRODUCT_PRODUCT_NOT_FOUND,
        searchingText: js_lang_label.LBL_QUTATION_PRODUCT_SEARCHING,
        onDelete:function(e){
            clear_product_data();
        },
        onAdd:function(e){
            add_product_data();
        },
        theme: "facebook",
        prePopulate:$.parseJSON(data)
        }); 
        $("#img_show").show();
    });
}else {
    $('#cq_product_code_drpdwn').tokenInput(get_products_url, {
    minChars: 1,
    preventDuplicates: true,
    propertyToSearch: 'val',
    tokenLimit: 1,
    hintText: js_lang_label.LBL_QUTATION_PRODUCT_SEARCH_PRODUCT,
    noResultsText: js_lang_label.LBL_QUTATION_PRODUCT_PRODUCT_NOT_FOUND,
    searchingText: js_lang_label.LBL_QUTATION_PRODUCT_SEARCHING,
    onDelete:function(e){
        clear_product_data();
    },
    onAdd:function(e){
            add_product_data();
        },
    theme: "facebook"
    });
}

function clear_product_data(){
    $("#cq_our_parent_code").val('');
    $("#cq_product_description").val('');
    $("#cq_product_color").val('');
    $("#cq_product_technique").val('');
    $("#cq_product_material").val('');
    $("#cq_product_measures").val('');
    $("#cq_product_printing_technique").val('');
    $("#cq_product_image_url").val('');
    $("#cq_product_quantity").val('');
    $("#cq_product_selling_price").val('');
    $("#cq_product_extra_cost").val('');
    $("#cq_product_total_price").val('0.00');
    $("#cq_product_grand_total_price").val('0.00');
    $("#img_show").hide();
    $("#img_show").attr("src",'');
    $("#show_image_url").attr("href",'');
}

function add_product_data(){
    var code = $("#existing_product_code").val($('#cq_product_code_drpdwn').tokenInput("get")[0]['val']);
    var id = $('#cq_product_code_drpdwn').tokenInput("get")[0]['id'];
    var params = {
        id:id
    };
    $.ajax({
        type:'POST',
        url: admin_url + "quotation/quotation/getProductDetail",
        data:params,
        dataType:'json',
        success:function(res){
          console.log(res);
            if(res.success)
            {
                $("#img_show").show();
                $("#cq_our_parent_code").val(res.data[0].custom_code);
                $("#cq_product_description").val(res.data[0].tLongDescription);
                $("#cq_product_color").val(res.data[0].vColorName);
                $("#cq_product_printing_technique").val(res.data[0].vTechniqueIncluded);
                $("#cq_product_material").val(res.data[0].vMaterialName);
                $("#cq_product_measures").val(res.data[0].vMeasurement);
                $("#cq_product_image_type").val(res.data[0].eImageType);
                var img_type = res.data[0].eImageType;
                if(img_type=='Online'){
                    $("#cq_product_image_url").val(res.data[0].vImageURL);
                    $("#img_show").attr("src",res.data[0].vImageURL);
                    $("#show_image_url").attr("href",res.data[0].vImageURL);
                    $("#cq_provider_id").val(res.data[0].iProviderId);
                    $("#cq_product_image_local").removeAttr("checked");
                    $("#cq_product_image_online").attr("checked",'checked');
                    $("#image_row_online").show();
                } else if(img_type=='Local'){
                    $("#cq_product_image_online").removeAttr("checked");
                    $("#cq_product_image_local").attr("checked",'checked');
                    $("#image_row_online").hide();
                    var image_name = res.data[0].vImage;
                    var img_url = product_path+image_name; 
                    $("#img_show").attr("src",img_url);
                    $("#cq_product_image_url").val(img_url);
                }
                var printing_included = '';
                if(res.data[0].tOfferPrintingNote=='' || res.data[0].tOfferPrintingNote==null){
                    printing_included = "Contactanos para precios y opciones de impresión";
                } else {
                    printing_included = res.data[0].tOfferPrintingNote;
                }
                $("#cq_product_printing_technique").val(printing_included);
            }
            else
            {
                 console.log(res);
            }
        },
        error: function(data){
            console.log(data);
        }
    });
}

$("#cq_product_quantity").keyup(function (e) {
    $("#btn_submit").hide();
});

$("#cq_product_quantity,#cq_product_extra_cost,#cq_product_selling_price").keydown(function (e) {
  if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
    (e.keyCode >= 35 && e.keyCode <= 40)) {
    return;
  }
  if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
      e.preventDefault();
  }
});

$("#cq_product_quantity").on("blur",function(){
    $("#qLoverlay").css('visibility','visible');
    $("#qLbar").css('visibility','visible');
    var qty = $(this).val();
    var product_type = $("input[name='cc_sh_cq_product_type']:checked").val();
    if(product_type=='Existing' && qty !=''){
        var existing_product_id = $("#existing_product_id").val();
        var params = {
            id:existing_product_id,
            qty:qty
        };
        $.ajax({
            type:'POST',
            url: admin_url + "quotation/quotation/getScalePriceRow",
            data:params,
            dataType:'json',
            success:function(res){
                if(res.success)
                {
                    $("#btn_submit").show();
                    $("#qLoverlay").css('visibility','hidden');
                    $("#qLbar").css('visibility','hidden');
                    var selprice=parseFloat(res.data.selling_price);
                    selprice=isNaN(selprice)?0:selprice;
                    $("#cq_product_selling_price").val(selprice.toFixed(2));
                    price_calculation(); 
                }
                else
                {
                    console.log(res);
                }
            },
            error: function(data){
                console.log(data);
            }
        });
    } else {
        $("#qLoverlay").css('visibility','hidden');
        $("#qLbar").css('visibility','hidden');
        $("#btn_submit").show();
        price_calculation();
    }
});

$("#cq_product_selling_price, #cq_product_extra_cost").on("keyup",function(){
    price_calculation();
});

 

$(".custom_radio_product_type").on("change",function(){
    var type = $(this).val();
    if(type=='Local'){
        $("#image_row_online").hide();
    } else if (type=='Online'){
        $("#image_row_online").show();
    }
});

$("#btn_submit").die().live("click",function(){
    setProductValidate();
    submit_inquiry();
});

function submit_inquiry(){
    var vld = $('#frm_product_quotation_product_frm').valid();
    if (!vld) {
        return false;
    } else {            
        $('#frm_product_quotation_product_frm').submit();
    }
}

function setProductValidate(){
    var quotation_frm_handler = $('#frm_product_quotation_product_frm');
    var formData = new FormData(quotation_frm_handler[0]);
    var ptype = $("#product_type_hidden").val();   
    validate_form_dropdown();   
}

function price_calculation(){
    
    var qty = parseFloat($("#cq_product_quantity").val());
    var extra_cost = parseFloat($("#cq_product_extra_cost").val());
    var selling_price = parseFloat($("#cq_product_selling_price").val());   


    qty = isNaN(qty)?0:qty
    extra_cost = isNaN(extra_cost)?0:extra_cost
    selling_price = isNaN(selling_price)?0:selling_price

    var total_amt = (selling_price + extra_cost);
    var total_price = (qty * total_amt);
    $("#cq_product_grand_total_price").val(total_price.toFixed(2));
    $("#cq_product_total_price").val(total_amt.toFixed(2));
}

function validate_form_dropdown(){
    $('#frm_product_quotation_product_frm').validate({
        rules: {
            'cq_product_code_text' : {
                required: true
            },
            'cq_product_code_drpdwn' : {
                required : true
            },
            'cq_product_description' : {
                required :true
            },
            'cq_product_quantity' : {
                required :true,
                min:1,
                max:99999999
            },
            'cq_product_selling_price' : {
                required :true
            },
            'cq_product_total_price' : {
                required :true
            },
            'cq_product_grand_total_price' : {
                required :true
            },
            'contact_no' : {
                required :false,
                digits: false,
            }
        },
        messages: {
            'cq_product_code_text' : {
                required : js_lang_label.LBL_QUTATION_PRODUCT_ERROR_PRODUCT_CODE,
            },
            'cq_product_code_drpdwn' : {
                required : js_lang_label.LBL_QUOTATION_PRODUCT_ERROR_PRODUCT,
            },
            'cq_product_description' : {
                required : js_lang_label.LBL_QUOTATION_PRODUCT_ERROR_DESCRIPTION,
            },
            'cq_product_quantity' : {
                required : js_lang_label.LBL_QUOTATION_PRODUCT_ERROR_QUANTITY,
                min: js_lang_label.LBL_QUOTATION_PRODUCT_ERROR_QUANTITY_PLEASE_ENTER_MIN_QTY,
                max: "Puede ingresar cantidad hasta 99,999,999 solamente"
            },
            'cq_product_selling_price' : {
                required : js_lang_label.LBL_QUOTATION_PRODUCT_ERROR_SELLING,
            },
            'cq_product_total_price' : {
                required : js_lang_label.LBL_QUOTATION_PRODUCT_ERROR_TOTAL, 
            },
            'cq_product_grand_total_price' : {
                required : js_lang_label.LBL_QUOTATION_PRODUCT_ERROR_GRAND_TOTAL, 
            },
            'contact_no' : {
                required :js_lang_label.LBL_CONTACT_PROVIDER_FROM_DISTRIBUTOR_FORM_PLEASE_ENTER_MOBILE_NUMBER,
                digits: js_lang_label.LBL_CONTACT_PROVIDER_FROM_DISTRIBUTOR_FORM_PLEASE_ENTER_ONLY_DIGITS
            }
        },
        errorPlacement: function(error, element) {
            var name = element.attr('name');
            $(error).css({'color':'#d9534f'});
            error.insertAfter($('[name="'+name+'"]'));
        },
        submitHandler:function(form){
            form.submit();
        }
    });
}