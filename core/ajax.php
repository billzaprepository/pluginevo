<?php
function evolution_handle_remote_request() {
    $action = isset($_POST['action_name']) ? sanitize_text_field($_POST['action_name']) : '';
    $method = isset($_POST['method']) ? sanitize_text_field($_POST['method']) : 'POST';
    $id = isset($_POST['id']) ? sanitize_text_field($_POST['id']) : '';
    if (empty($action)) {
        wp_send_json_error('Action not provided');
        return;
    }
    $body = json_encode(['action' => $action, 'id' => $id]);
    $response = evolution_remote_request($action, $body, $method);
    if (is_wp_error($response)) {
        wp_send_json_error($response->get_error_message());
    } else {
        wp_send_json_success($response);
    }
}
add_action('wp_ajax_evolution_remote_request', 'evolution_handle_remote_request');
add_action('wp_ajax_nopriv_evolution_remote_request', 'evolution_handle_remote_request');
function evolution_ajax_create_instance() {
	if(get_option('evolution_active')==true){ 
    global $wpdb;
    $hash = hash('sha256', uniqid('', true));
    $hash = substr($hash, 0, 32);
    $json = ['instanceName' => $hash, 'token' => $hash];
    $response = evolution_remote_request("instance/create/", json_encode($json), "POST");
    $response = json_decode($response);
    if ($response && $response->instance->status == "created") {
        $table_name = $wpdb->prefix . 'evolution_clientes';
        $wpdb->update($table_name, ['instancia' => $hash], ['id' => $_POST['id']]);
    }
    die();
	}
}
add_action('wp_ajax_evolution_ajax_create_instance', 'evolution_ajax_create_instance');
add_action('wp_ajax_nopriv_evolution_ajax_create_instance', 'evolution_ajax_create_instance');
function evolution_ajax_delete_instance() {
	if(get_option('evolution_active')==true){ 
    global $wpdb;
    $response = evolution_remote_request("instance/delete/" . $_POST['id'], "", "DELETE");
    $response = json_decode($response);
    if ($response && $response->status == "SUCCESS") {
        $table_name = $wpdb->prefix . 'evolution_clientes';
        $table_name_typebot = $wpdb->prefix . 'evolution_typebot';
        $wpdb->update($table_name, ['instancia' => ''], ['instancia' => $_POST['id']]);
        $wpdb->delete($table_name_typebot, ['instancia' => $_POST['id']]);
    }
    die();
	}
}
add_action('wp_ajax_evolution_ajax_delete_instance', 'evolution_ajax_delete_instance');
add_action('wp_ajax_nopriv_evolution_ajax_delete_instance', 'evolution_ajax_delete_instance');
function evolution_request_specific_data($table_name) {
    global $wpdb;
	$table_name = $wpdb->prefix."".$table_name;
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE instancia = %s", $_POST['id']);
    $results = $wpdb->get_results($sql);
    if (!empty($results)) {
        echo json_encode($results);
    } else {
        return false;
    }
    die();
}
add_action('wp_ajax_evolution_request_typebot', function() { evolution_request_specific_data( 'evolution_typebot'); });
add_action('wp_ajax_nopriv_evolution_request_typebot', function() { evolution_request_specific_data('evolution_typebot'); });
add_action('wp_ajax_evolution_request_chatwoot', function() { evolution_request_specific_data('evolution_chatwoot'); });
add_action('wp_ajax_nopriv_evolution_request_chatwoot', function() { evolution_request_specific_data('evolution_chatwoot'); });
add_action('wp_ajax_evolution_request_preferences', function() { evolution_request_specific_data('evolution_preferences'); });
add_action('wp_ajax_nopriv_evolution_request_preferences', function() { evolution_request_specific_data('evolution_preferences'); });
function evolution_set_typebot() {
	if(get_option('evolution_active')==true){ 
    global $wpdb;
    $dados = $_POST['form'];
    $insert = [];
    foreach ($dados as $dado) {
        $name = sanitize_text_field($dado['name']);
        $value = sanitize_text_field($dado['value']);
        $insert[$name] = $value;
    }
    $table_name = $wpdb->prefix . 'evolution_typebot';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE instancia = %s", $insert['instancia']);
    $results = $wpdb->get_results($sql);
    if ($results) {
        $wpdb->update($table_name, [
            "url" => $insert['url'],
            "nome_do_fluxo" => $insert['nome_do_fluxo'],
            "palavra_de_finalizacao" => $insert['palavra_de_finalizacao'],
            "tempo_de_expiracao" => $insert['tempo_de_expiracao'],
            "tempo_de_digitacao" => $insert['tempo_de_digitacao'],
            "mensagem_desconhecida" => $insert['mensagem_desconhecida'],
            "status" => $insert['statusTypebot']
        ], ["instancia" => $insert['instancia']]);
    } else {
        $wpdb->insert($table_name, [
            "instancia" => $insert['instancia'],
            "url" => $insert['url'],
            "nome_do_fluxo" => $insert['nome_do_fluxo'],
            "palavra_de_finalizacao" => $insert['palavra_de_finalizacao'],
            "tempo_de_expiracao" => $insert['tempo_de_expiracao'],
            "tempo_de_digitacao" => $insert['tempo_de_digitacao'],
            "mensagem_desconhecida" => $insert['mensagem_desconhecida'],
            "status" => $insert['statusTypebot']
        ]);
    }
    $statusConvertido = ($insert['statusTypebot'] == 1);
    $json = [
        'enabled' => $statusConvertido,
        'url' => $insert['url'],
        'typebot' => $insert['nome_do_fluxo'],
        'expire' => (int)$insert['tempo_de_expiracao'],
        'keyword_finish' => $insert['palavra_de_finalizacao'],
        'delay_message' => (int)$insert['tempo_de_digitacao'],
        'unknown_message' => $insert['mensagem_desconhecida'],
        'listening_from_me' => false
    ];
    evolution_remote_request("typebot/set/" . $insert['instancia'], json_encode($json), "POST");
    die();
	}
}
add_action('wp_ajax_evolution_set_typebot', 'evolution_set_typebot');
add_action('wp_ajax_nopriv_evolution_set_typebot', 'evolution_set_typebot');
function evolution_set_chatwoot() {
	if(get_option('evolution_active')==true){ 
	global $wpdb;
    $dados = $_POST['form'];
    $insert = [];
    foreach ($dados as $dado) {
        $name = sanitize_text_field($dado['name']);
        $value = sanitize_text_field($dado['value']);
        $insert[$name] = $value;
    }
    $table_name = $wpdb->prefix . 'evolution_chatwoot';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE instancia = %s", $insert['instancia']);
    $results = $wpdb->get_results($sql);
    if ($results) {
        $wpdb->update($table_name, [
            "instancia" => $insert['instancia'],
            "url" => $insert['url'],
            "id_da_conta" => $insert['id_da_conta'],
            "token_da_conta" => $insert['token_da_conta'],
            "limite_dias" => $insert['limite_dias'],
            "assinar_mensagem" => $insert['assinar_mensagem'],
            "separador_assinatura" => $insert['separador_assinatura'],
            "reabrir_conversa" => $insert['reabrir_conversa'],
            "iniciar_conversa_pendente" => $insert['iniciar_conversa_pendente'],
            "importar_contatos" => $insert['importar_contatos'],
            "importar_mensagens" => $insert['importar_mensagens'],
            "status" => $insert['statusChatwoot'],
		], ["instancia" => $insert['instancia']]);
    } else {
        $wpdb->insert(
		$table_name,
		[
            "instancia" => $insert['instancia'],
            "url" => $insert['url'],
            "id_da_conta" => $insert['id_da_conta'],
            "token_da_conta" => $insert['token_da_conta'],
            "limite_dias" => $insert['limite_dias'],
            "assinar_mensagem" => $insert['assinar_mensagem'],
            "separador_assinatura" => $insert['separador_assinatura'],
            "reabrir_conversa" => $insert['reabrir_conversa'],
            "iniciar_conversa_pendente" => $insert['iniciar_conversa_pendente'],
            "importar_contatos" => $insert['importar_contatos'],
            "importar_mensagens" => $insert['importar_mensagens'],
            "status" => $insert['statusChatwoot'],
		]);
    }
    $json = [
        'enabled' => (bool)$insert['statusChatwoot'],
        'account_id' => $insert['id_da_conta'],
        'token' => $insert['token_da_conta'],
        'url' => $insert['url'],
        'sign_msg' => (bool)$insert['assinar_mensagem'],
        'sign_delimiter' => $insert['separador_assinatura'],
        'reopen_conversation' => (bool)$insert['reabrir_conversa'],
        'conversation_pending' => (bool)$insert['iniciar_conversa_pendente'],
        'import_contacts' => (bool)$insert['importar_contatos'],
        'import_messages' => (bool)$insert['importar_mensagens'],
        'days_limit_import_messages' => (int)$insert['limite_dias'],
        'auto_create' => true
    ];
    $retorno = evolution_remote_request("chatwoot/set/" . $insert['instancia'], json_encode($json), "POST");
	print_r($retorno);
    die();
	}
}
add_action('wp_ajax_evolution_set_chatwoot', 'evolution_set_chatwoot');
add_action('wp_ajax_nopriv_evolution_set_chatwoot', 'evolution_set_chatwoot');
function evolution_set_preferences() {
	if(get_option('evolution_active')==true){ 
    global $wpdb;
    $dados = array_map('sanitize_text_field', $_POST['form']);
    $table_name = $wpdb->prefix . 'evolution_preferences';
    $sql = $wpdb->prepare("SELECT * FROM $table_name WHERE instancia = %s", $dados['instancia']);
    $results = $wpdb->get_results($sql);
    if ($results) {
        $wpdb->update($table_name, $dados, ["instancia" => $dados['instancia']]);
    } else {
        $wpdb->insert($table_name, $dados);
    }
    $json = [
        'reject_call' => (bool)$dados['rejeitar_chamadas'],
        'msg_call' => $dados['mensagem_rejeicao'],
        'groups_ignore' => (bool)$dados['ignorar_grupos'],
        'always_online' => (bool)$dados['sempre_online'],
        'read_messages' => (bool)$dados['marcar_lidas'],
        'read_status' => (bool)$dados['marcar_visto'],
        'sync_full_history' => (bool)$dados['sincronizar']
    ];
    evolution_remote_request("settings/set/" . $dados['instancia'], json_encode($json), "POST");
    die();
	}
}
add_action('wp_ajax_evolution_set_preferences', 'evolution_set_preferences');
add_action('wp_ajax_nopriv_evolution_set_preferences', 'evolution_set_preferences');
function evolution_list_instances_ajax() {
	if(get_option('evolution_active')==true){ 
    global $wpdb;
    $current_user = wp_get_current_user();
    $user_email = $current_user->user_email;
    $table_name = $wpdb->prefix . 'evolution_clientes';
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE cliente = %s", $user_email ), OBJECT );
    if ( $results ) {
        echo '<div class="custom-grid">';
        foreach ( $results as $index => $result ) {
            if ( $index % 4 === 0 && $index !== 0 ) {
                echo '</div><div class="custom-grid">';
            }
            echo '<div class="custom-grid-item">';
            if ( $result->instancia ) {
                $retorno = evolution_remote_request("instance/connectionState/".$result->instancia, "", "GET");
                $retorno = json_decode($retorno);
                echo "<p style='text-align:center;font-family:rubik;font-weight:400;color:#999;font-size:12px;text-transform:uppercase;'>".__('Expires in', 'evolution-api')." ".$result->expiracao."</p>";
                if ( $retorno->instance->state == "open" ) {
                    echo "<p style='text-align:center;font-family:rubik;font-weight:700;color:#007657;font-size:18px;text-transform:uppercase;'>".__('Instance connected', 'evolution-api')."</p>";
                    echo "<div class='copyInstance'>";
                    echo "<input style='text-align:center;font-family:rubik;font-weight:400;color:#333;font-size:12px;' value ='".$result->instancia."' />";
                    echo "<button><i class='fa fa-clipboard'></i></button>";
                    echo "</div>";
                    echo "<p style='text-align:center;font-family:rubik;font-weight:400;color:#333;font-size:12px;text-transform:uppercase;margin-top:0px;'>".__('Settings', 'evolution-api')."</p>";
                    echo "<div class='buttonsConfig'>";
                    echo do_shortcode('[evolution_preferences_button id="'.$result->instancia.'"]');
                    echo do_shortcode('[evolution_typebot_button id="'.$result->instancia.'"]');
                    echo do_shortcode('[evolution_chatwoot_button id="'.$result->instancia.'"]');
                    echo do_shortcode('[evolution_logout_button id="'.$result->instancia.'"]');
                    echo "</div>";
                } else {
                    $qrcode = evolution_remote_request("instance/connect/".$result->instancia, "", "GET");
                    $qrcode = json_decode($qrcode);
                    echo "<p style='text-align:center;'><img src='".$qrcode->base64."' /></p>";
                    echo "<div class='buttons'>";
                    echo do_shortcode('[evolution_restart_button id="'.$result->instancia.'"]');
                    echo do_shortcode('[evolution_delete_button id="'.$result->instancia.'"]');
                    echo "</div>";
                }
            } else {
                echo "<p style='text-align:center;font-family:rubik;font-weight:400;color:#999;font-size:12px;text-transform:uppercase;'>".__('Expires in', 'evolution-api')." ".$result->expiracao."</p>";
                echo "<p style='text-align:center;font-family:rubik;font-weight:700;color:#007657;font-size:18px;text-transform:uppercase;'>".__('Instance available', 'evolution-api')."</p>";
                echo "<p style='text-align:center;font-family:rubik;font-weight:400;color:#333;font-size:13px;text-transform:uppercase;'>".__('To generate the QR Code, click the button below.', 'evolution-api')."</p>";
                echo "<div class='buttons'>";
                echo do_shortcode('[evolution_create_button id="'.$result->id.'"]');
                echo "</div>";    
            }
            echo '</div>';
        }
        echo '</div>'; 
    } else {
        echo __('No records found.', 'evolution-api');
    }   
	die();
	}
}
add_action('wp_ajax_evolution_list_instances_ajax', 'evolution_list_instances_ajax');
add_action('wp_ajax_nopriv_evolution_list_instances_ajax', 'evolution_list_instances_ajax');
function evolution_api_check_api_status() {
	$response = evolution_remote_request("instance/connectionState/".$_POST['token'], "", "GET");
	print_r($response);
	die();
}
add_action('wp_ajax_check_api_status', 'evolution_api_check_api_status');