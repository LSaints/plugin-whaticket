<?php
defined('_SECURE_') or die('Forbidden');

function whaticket_hook_sendsms($smsc, $sms_sender, $sms_footer, $sms_to, $sms_msg, $uid = '', $gpid = 0, $smslog_id = 0, $sms_type = 'text', $unicode = 0) {
    global $plugin_config;

    _log("enter smsc:" . $smsc . " smslog_id:" . $smslog_id . " uid:" . $uid . " to:" . $sms_to, 3, "whaticket_hook_sendsms");

    $plugin_config = gateway_apply_smsc_config($smsc, $plugin_config);

    $url = htmlspecialchars_decode($plugin_config['whaticket']['api_url']);
    $token = $plugin_config['whaticket']['token'];

    $sms_footer = stripslashes($sms_footer);
    $sms_msg = stripslashes($sms_msg);
    $ok = false;

    _log("sendsms start", 3, "whaticket_hook_sendsms");

    if ($sms_footer) {
        $sms_msg .= $sms_footer;
    }

    $data = array(
        'number' => $sms_to,
        'body' => $sms_msg
    );
    $jsonData = json_encode($data);

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $jsonData,
        CURLOPT_HEADER => true,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData),
            'Authorization: Bearer ' . $token
        ),
    ]);

    // Execute the request
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    curl_close($ch);

    // Extract header and body
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);

    _log("response json: " . $body, 3, "response_json");
    _log("send url: [" . $url . "]", 3, "whaticket_hook_sendsms");

    if ($http_code == 200) {
        // Decode the JSON response
        $response_data = json_decode($body);
        
        if ($response_data) {
            $c_status = $response_data->status;
            $c_message_id = $response_data->sid;
            $c_error_text = $c_status . '|' . (isset($response_data->code) ? $response_data->code : 'N/A') . '|' . (isset($response_data->message) ? $response_data->message : 'N/A');
            _log("sent smslog_id:" . $smslog_id . " message_id:" . $c_message_id . " status:" . $c_status . " error:" . $c_error_text . " smsc:[" . $smsc . "]", 2, "whaticket_hook_sendsms");

            $db_query = "
                INSERT INTO " . _DB_PREF_ . "_gatewayWhaticket_log (local_smslog_id, remote_smslog_id)
                VALUES ('$smslog_id', '$c_message_id')";
            $id = @dba_insert_id($db_query);
            
            if ($id) {
                $ok = true;
                $p_status = 1;
                $update_query = "
                UPDATE " . _DB_PREF_ . "_messages 
                SET status = 'sent' 
                WHERE id = '$smslog_id'";
            @dba_query($update_query);
            dlr($smslog_id, $uid, $p_status);
            } else {
                $ok = false;
                $p_status = 0;
                dlr($smslog_id, $uid, $p_status);
            }
        } else {
            _log("Failed to decode JSON response or missing 'status'", 3, "whaticket_hook_sendsms");
        }
    } else {
        _log("HTTP Error Code: " . $http_code, 3, "whaticket_hook_sendsms");
    }

    if (!$ok) {
        $p_status = 2;
        dlr($smslog_id, $uid, $p_status);
    }

    _log("sendsms end", 3, "whaticket_hook_sendsms");

    return $ok;
}
