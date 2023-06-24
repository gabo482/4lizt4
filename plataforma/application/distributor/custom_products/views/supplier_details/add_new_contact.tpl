<table class="table table-striped contact-list contact-form" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td class='table-title  contact-form-lbl'>
                <%lang('LBL_SUPPLIER_CONTACT_ROLE')%>              
                <input type="hidden" name="dpm_provider_id" class="class_dpm_provider_id"  value="<%$provider_id%>">
            </td>  
            <td class='  contact-form-input'>
                <input data-required="true" type="text" data-name="<%lang('LBL_SUPPLIER_CONTACT_ROLE')%>" name="inp_role" class="contactinput">
                <span class="err" style="display:none;"></span>
            </td>
        </tr>
        <tr>
            <td class='table-title contact-form-lbl'><%lang('LBL_SUPPLIER_CONTACT_NAME')%></td>
            <td class='contact-form-input'>
                <input data-required="true" type="text" data-name="<%lang('LBL_SUPPLIER_CONTACT_NAME')%>" name="inp_name" class="contactinput">
                <span class="err" style="display:none;"></span>
            </td>
        </tr>
        <tr>
            <td class='table-title contact-form-lbl'><%lang('LBL_SUPPLIER_CONTACT_EMAIL')%></td>
            <td class='contact-form-input'>
                <input data-required="true" data-validemail="true" data-name="<%lang('LBL_SUPPLIER_CONTACT_EMAIL')%>" type="email" name="inp_email" class="contactinput">
                <span class="err" style="display:none;"></span>
            </td>
        </tr>
        <tr>
            <td class='table-title contact-form-lbl'><%lang('LBL_SUPPLIER_CONTACT_PHONE')%></td>
            <td class='contact-form-input'>
                <input data-required="true" type="text" data-name="<%lang('LBL_SUPPLIER_CONTACT_PHONE')%>" name="inp_telephone" class="contactinput">
                <span class="err" style="display:none;"></span>
            </td>
        </tr>
         <tr>
          
            <td class='table-title contact-form-lbl'></td>
            <td class='contact-form-input'>
                <input data-required="true" type="hidden" data-name="" name="dpm_user_id" class="contactinput" value="<%$this->session->userdata('iAdminId')%>">
                <span class="err" style="display:none;"></span>
            </td>
        </tr>
        <tr>
            <td colspan="2" class='contact-role contact-form-lbl'>
                <p>&nbsp;</p>
                <a class="removecontact delete-icon" href='javascript://'data-id="<%$row['pcm_provider_contacts_id']%>"><i class="fa fa-trash-o"></i></a>
                <a href="javascript://" class="save-icon savedata"><i class="fa fa-save"></i></a>
            </td>
        </tr>
        <div class="scroll_here"></div>
    </tbody>
</table>