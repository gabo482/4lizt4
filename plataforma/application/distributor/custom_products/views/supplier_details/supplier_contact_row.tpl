<table class="table table-striped contact-list" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td colspan="2" class='contact-role'>
                <%$row['dpc_role_provider_contact']%> <a class="removecontact delete-icon" href='javascript://' data-id="<%$row['dpc_provider_contact_id']%>"><i class="fa fa-trash-o"></i></a></td>
        </tr>        
        <tr>
            <td class='table-title'><%lang('LBL_SUPPLIER_CONTACT_NAME')%></td>
            <td>
                <%$row['dpc_name_provider_contact']%>
            </td>
        </tr>
        <tr>
            <td class='table-title'><%lang('LBL_SUPPLIER_CONTACT_EMAIL')%></td>
            <td>
                <a href='mailto:<%$row["dpc_email_provider_contact"]%>'>
                    <%$row['dpc_email_provider_contact']%>
                </a>
            </td>
        </tr>
        <tr>
            <td class='table-title'><%lang('LBL_SUPPLIER_CONTACT_PHONE')%></td>
            <td>
                <%$row['dpc_phone_provider_contact']%>
            </td>
        </tr>
       
    </tbody>
</table>