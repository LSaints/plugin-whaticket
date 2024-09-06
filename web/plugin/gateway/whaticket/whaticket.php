<?php
defined('_SECURE_') or die('Forbidden');

if (!auth_isadmin()) {
    auth_block();
}

include $core_config['apps_path']['plug'] . "/gateway/whaticket/config.php";

switch(_OP_) {
    case "manage":
        $tpl = array(
            'name' => 'whaticket',
            'vars' => array(
                'DIALOG_DISPLAY' => _dialog(),
                'Manage whaticket API' => _('Manage whaticket API'),
                'Gateway name' => _('Gateway name'),
                'API URL' => _mandatory(_('API URL')),
                'Token' => _mandatory(_('Token')),
                'Save' => _('Save'),
                'Notes' => _('Notes'),
                'status' => $status_active,
                'BUTTON_BACK' => _back('index.php?app=main&inc=core_gateway&op=gateway_list'),
                'api_url' => $plugin_config['whaticket']['api_url'],
                'token' => $plugin_config['whaticket']['token'],
            )
        );
        _p(tpl_apply($tpl));
        // if ($err = TRUE) {
        //     $content = _dialog();
        // }
        // $content .= "
        // <h2>". _('Manage whaticket api') . "</h2>
        // <form action=index.php?app=main&inc=gateway_whaticket&op=manage_save method=post>
        // " . _CSRF_FORM_ . "
        // <table class=playsms-table cellpading=1 cellspacing=2 border=0>
        //     <tbody>
        //         <tr><td class=label-sizer>" . _('Gateway name') . "</td><td>whaticket</td></tr>
        //         <tr><td>" . _('API URL') . "</td><td><input type=text maxlength=250 name=api_url value=http://localhost:8443/api/messages/send></td></tr>
        //         <tr><td>" . _('TOKEN'). "</td><td><input type=text maxlength=250 name=token></td></tr>
        //     </tbody>
        // </table>
        // <p><input type=submit class=button value\"". _('Save') . "\">
        // </form>
        // <br/>
        // ";
        // $content .= _back('index.php?app=main&inc=core_gateway&op=gateway_list');
        // _p($content);
        break;
    case "manage_save":
        $api_url = $_REQUEST['api_url'];
        $token = $_REQUEST['token'];
        $mobile = $_REQUEST['mobile'];

        $db_query = "
            UPDATE " . _DB_PREF_ . "_gatewayWhaticket_config
            SET cfg_api_url='$api_url',
                cfg_token='$token',";
        if (@dba_affected_rows($db_query)) {
            $_SESSION['dialog']['info'][] = _('Gateway module configurations has been saved');
        } else {
            $_SESSION['dialog']['danger'][] = _('Fail to save gateway module configurations');
        }

        header("Location: " . _u('index.php?app=main&inc=gateway_whaticket&op=manage'));
        exit();
        break;
}