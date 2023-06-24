<%if count($provider_contact_details) gt 0%>
    <tr>
        <td colspan="2" id="contacts_listing_inner_wp">
            <%foreach from=$provider_contact_details item=row%>
                <%include file="supplier_details/supplier_contact_row.tpl"%>
                    <%/foreach%>
        </td>
    </tr>
    <%else%>
        <tr>
            <td colspan="2"  id="contacts_listing_inner_wp">
                <div class='norecordfound' id='nocontactfound'>                    
                    <i class='fa fa-users'></i> <%lang('LBL_DISTRIBUTOR_PANEL_CUSTOM_PRODUCTS_SUPPLIER_YOU_HAVE_NOT_ADDED_ANY_CONTACT')%>                    
                </div>
            </td>
        </tr>
        <%/if%>


            <script type="text/javascript">
                function reload_contact_list() {
                    $("#supplier_contact_container").load(admin_url + "custom_products/suppliers/load_contacts");
                }



                $(".contact-form input").die().live("keyup", function(e) {
                    validateinput($(this));
                });

                $("#addnewcontact").die().live("click", function(e) {
                    $.ajax({
                        type: 'POST',
                        url: admin_url + "custom_products/suppliers/add_contact/<%$dataMap[0]['iProviderId']%>",
                        dataType: 'html',
                        success: function(data) {
                            $("#nocontactfound").remove();
                            $("#contacts_listing_inner_wp").append(data);

                            var scroll_obj = $(".scroll_here").last();
                            $('html,body').stop(true, false).animate({
                            scrollTop: (scroll_obj.offset().top-150)
                            }, 800);

                           // $("html, body").animate({ scrollTop: $(document).height() }, 1000);
                        },
                        error: function(data) {
                            console.log(data);
                        }
                    });

                });

                $(".removecontact").die().live("click", function(e) {
                    var obj = $(this);
                    var id = obj.data('id');
                    var data_save = {
                        "id": id
                    };
                    jqueryUIdialogBox("<div></div>", js_lang_label.LBL_GENERAL_ARE_YOU_SURE, {
                        title: js_lang_label.LBL_GENERAL_CONFORMATION,
                        buttons: [{
                            text: js_lang_label.LBL_GENERAL_YES,
                            bt_type: "Yes",
                            click: function() {
                                $(this).remove();
                                $.post(admin_url + "custom_products/suppliers/remove_contact", data_save, function(data, status) {
                                    var resp = $.parseJSON(data);
                                    if($("#contacts_listing_inner_wp table").length >= 2){
                                        obj.closest('table.contact-list').remove();    
                                    } else {
                                        obj.closest('table.contact-list').remove();
                                        $("#contacts_listing_inner_wp").append(resp.empty_data);
                                    }
                                    
                                });
                            }
                        }, {
                            text: js_lang_label.LBL_GENERAL_NO,
                            bt_type: "cancel",
                            click: function() {
                                $(this).remove();
                            }
                        }]
                    });

                });
                var valid = true;
                $(".savedata").die().live("click", function(e) {
                    valid = true;
                    var obj = $(this);
                    var data_save = get_contact_data(obj);
                    if (data_save == false) {
                        return false;
                    }
                    $.post(admin_url + "custom_products/suppliers/update_contact", data_save, function(data, status) {
                        obj.closest('table').remove();
                        $("#contacts_listing_inner_wp").prepend(data);

                    });
                });


                function get_contact_data(obj) {

                    var prtbl = obj.closest('table');
                    var inputs = prtbl.find('input');
                    var data_save = [];


                    $(inputs).each(function(i) {
                        var value = $(this).val().trim();
                        var name = $(this).attr('name');
                        if (validateinput($(this)) == false) {
                            item = {}
                            item["name"] = name;
                            item["value"] = value;
                            data_save.push(item);
                        } else {
                            valid = false;
                        }
                    });

                    if (valid == false) {
                        return false;
                    } else {
                        console.log(data_save);
                        return data_save;
                    }
                }

                function validateEmail(email) {
                  var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                  return re.test(email);
                }

                function validateinput(obj) {
                    var is_required = obj.data("required");
                    var is_email = obj.data("validemail");
                    var value = obj.val().trim();
                    var show_name = obj.data("name");
                    var has_error = false;
                    var err_message = "";
                    if (is_required == true) {
                        if (value == '') {
                            has_error = true;
                            err_message = js_lang_label.LBL_SUPPLIER_CONTACT_PLEASE_ENTER+" "+ show_name;
                        }
                    }

                    if (has_error == false) {
                        if (is_email == true) {
                            if(validateEmail(value)){
                                has_error = false;
                            } else {
                                has_error = true;
                                err_message = js_lang_label.LBL_SUPPLIER_CONTACT_PLEASE_ENTER_VALID+" "+ show_name;
                            }
                        }
                    }

                    if (has_error == true) {
                        obj.closest('td').addClass('has_error');
                        obj.closest('td').find(".err").show();
                        obj.closest('td').find(".err").html(err_message);
                    } else {
                        obj.closest('td').removeClass('has_error');
                        obj.closest('td').find(".err").hide().html('');
                    }
                    return has_error;
                }
            </script>