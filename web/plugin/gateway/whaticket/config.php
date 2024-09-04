<?php
defined('_SECURE_') or die('Forbidden');
    $db_query = "SELECT * FROM " . _DB_PREF_ . "_gatewayWhaticket_config";
    $db_result = dba_query($db_query);
    if ($db_row = dba_fetch_array($db_result)) {
        $plugin_config['whaticket']['name'] = 'whaticket';
        $plugin_config['whaticket']['token'] = $db_row['cfg_token'];
    }

    $plugin_config['whaticket']['_smsc_config_'] = array(
        'api_url' => _("Whaticket API"),
        'token' => _("token")
    );
