<table class="table table-striped contact-list" border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
        <tr>
            <td colspan="2" class='contact-role'>
                <%$row['pcm_role']%> <a class="removecontact delete-icon" href='javascript://' data-id="<%$row['pcm_provider_contacts_id']%>"><i class="fa fa-trash-o"></i></a></td>
        </tr>
        <tr>
            <td class='table-title'><%lang('LBL_SUPPLIER_CONTACT_NAME')%></td>
            <td>
                <%$row['pcm_name']%>
            </td>
        </tr>
        <tr>
            <td class='table-title'><%lang('LBL_SUPPLIER_CONTACT_EMAIL')%></td>
            <td>
                <a href='mailto:<%$row["pcm_email"]%>'>
                    <%$row['pcm_email']%>
                </a>
            </td>
        </tr>
        <tr>
            <td class='table-title'><%lang('LBL_SUPPLIER_CONTACT_PHONE')%></td>
            <td>
                <%$row['pcm_contact_no']%>
            </td>
        </tr>
    </tbody>
</table>