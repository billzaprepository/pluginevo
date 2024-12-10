<?php
function evolution_cliente_manager_add_cliente($cliente, $instancia, $expiracao, $ref) {
	if(get_option('evolution_active')==true){ 
    global $wpdb;
    $table_name = $wpdb->prefix . 'evolution_clientes';
    $wpdb->insert(
        $table_name,
        array(
            'cliente' => sanitize_text_field($cliente),
            'instancia' => sanitize_text_field($instancia),
            'expiracao' => sanitize_text_field($expiracao),
            'ref' => sanitize_text_field($ref)
        )
    );
	}
}
function evolution_cliente_manager_update_cliente($id, $cliente, $instancia, $expiracao, $ref) {
	if(get_option('evolution_active')==true){ 
    global $wpdb;
    $table_name = $wpdb->prefix . 'evolution_clientes';
    $wpdb->update(
        $table_name,
        array(
            'cliente' => sanitize_text_field($cliente),
            'instancia' => sanitize_text_field($instancia),
            'expiracao' => sanitize_text_field($expiracao),
            'ref' => sanitize_text_field($ref)
        ),
        array('id' => intval($id))
    );
	}
}
function evolution_cliente_manager_process_orders($order_id) {
	if(get_option('evolution_active')==true){ 
    if (!class_exists('WooCommerce')) {
        return;
    }
    $order = wc_get_order($order_id);
    if ($order && $order->get_status() === 'processing') {
        $customer_email = $order->get_billing_email();
        $date_created = $order->get_date_created();
        foreach ($order->get_items() as $item) {
            $product_quantity = $item->get_quantity();
            for ($i = 0; $i < $product_quantity; $i++) {
				$prazo = get_post_meta($item->get_product_id(), 'evolution_api_prazo', true);
				if($prazo){
					$expiration_date = date_add(date_create($date_created), date_interval_create_from_date_string($prazo.' days'));
					evolution_cliente_manager_add_cliente($customer_email, "", $expiration_date->format('Y-m-d'), $order_id);
				}
            }
        }
    }
	}
}
add_action('woocommerce_order_status_processing', 'evolution_cliente_manager_process_orders', 10, 1);
function evolution_remote_request($action, $body, $method = 'POST') {
	if(get_option('evolution_active')==true){ 
    $url = EVOLUTION_URL . $action;
    $args = [
        'headers' => [
            'Content-Type' => 'application/json',
            'apikey' => EVOLUTION_API,
        ],
        'body' => $body,
        'timeout' => 30,
        'method' => $method,
    ];
    $response = wp_remote_request($url, $args);
    if (is_wp_error($response)) {
        return 'Request Error: ' . $response->get_error_message();
    }
    $response_body = wp_remote_retrieve_body($response);
    return $response_body;
	}
}
function evolution_custom_database_widget_enqueue_scripts() {
	if(get_option('evolution_active')==true){ 
    wp_enqueue_style('evolution-style', EVOLUTION_PLUGIN_URL . 'assets/css/style.css',array(),wp_rand(111,9999),'all');
    wp_enqueue_script('evolution-api-script', EVOLUTION_PLUGIN_URL . 'assets/js/script.js', ['jquery'], wp_rand(111,9999), false);
    wp_localize_script('evolution-api-script', 'customAjax', [
        'ajaxurl' => admin_url('admin-ajax.php'),
        'apikey' => EVOLUTION_API,
    ]);
    $custom_css = "
    :root {
        --primary-color: ".get_option('evolution_color_primary','#000').";
        --background-box: ".get_option('evolution_background_box','#000').";
        --border-color-box: ".get_option('evolution_border_box','#000').";
        --text-color: ".get_option('evolution_text_color','#000').";
    }";
    wp_add_inline_style('evolution-style', $custom_css);
	}
}
add_action('wp_enqueue_scripts', 'evolution_custom_database_widget_enqueue_scripts');
function evolution_enqueue_admin_scripts($hook_suffix) {
    wp_enqueue_script('evolution-admin-script', EVOLUTION_PLUGIN_URL . 'assets/js/admin-script.js', ['jquery'], wp_rand(111,9999), true);
    wp_localize_script('evolution-admin-script', 'evolutionAdminAjax', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('evolution-color-picker', EVOLUTION_PLUGIN_URL.'assets/js/color-picker.js', array('wp-color-picker'), false, true);
}
add_action('admin_enqueue_scripts', 'evolution_enqueue_admin_scripts');

function evolution_api_add_custom_product_field() {
	if(get_option('evolution_active')==true){ 
    woocommerce_wp_text_input(
        array(
            'id'          => 'evolution_api_prazo',
            'label'       => __('Deadline (in days)', 'evolution-api'),
            'placeholder' => 'Enter the deadline',
            'desc_tip'    => 'true',
            'description' => __('Enter the product expiration deadline.', 'evolution-api'),
            'type'        => 'number'
        )
    );
	}
}
add_action('woocommerce_product_options_general_product_data', 'evolution_api_add_custom_product_field');
function evolution_api_save_custom_product_field($post_id) {
    $prazo = isset($_POST['evolution_api_prazo']) ? sanitize_text_field($_POST['evolution_api_prazo']) : '';
    update_post_meta($post_id, 'evolution_api_prazo', $prazo);
}
add_action('woocommerce_process_product_meta', 'evolution_api_save_custom_product_field');
function evolution_api_add_cron_interval($schedules) {
    $schedules['daily'] = array(
        'interval' => 86400,
        'display' => __('Diariamente')
    );
    return $schedules;
}
add_filter('cron_schedules', 'evolution_api_add_cron_interval');
if (!wp_next_scheduled('evolution_api_cron')) {
    wp_schedule_event(time(), 'daily', 'evolution_api_cron');
}
add_action('rest_api_init', 'evolutionWebhook');
function evolutionWebhook() {
    register_rest_route('evolution/v1', '/webhook', array(
        'methods' => 'POST',
        'callback' => 'evolutionWebhookCallback',
    ));
}
function evolution_api_cron() {
	global $wpdb;
	$current_date = date('Y-m-d');
	$query = $wpdb->prepare(
		"SELECT * FROM ".$wpdb->prefix."evolution_clientes WHERE expiracao < %s",
		$current_date
	);
	$expired_clients = $wpdb->get_results($query);
	if (!empty($expired_clients)) {
		foreach ($expired_clients as $client) {
			$order = wc_get_order( $client->ref );
			if($client->instancia){
				$response = evolution_remote_request("instance/logout/" . $client->instancia, "", "DELETE");
				$response = json_decode($response);
				if ($response && $response->status == "SUCCESS") {
					$return = evolution_remote_request("instance/delete/" . $client->instancia, "", "DELETE");
					$return = json_decode($return);
					if ($return && $return->status == "SUCCESS") {
						$wpdb->delete(
							$wpdb->prefix.'evolution_preferences',
							array('instancia' => $client->instancia), 
							array('%s')
						);
						$wpdb->delete(
							$wpdb->prefix.'evolution_typebot',
							array('instancia' => $client->instancia), 
							array('%s')
						);
						$wpdb->delete(
							$wpdb->prefix.'evolution_chatwoot',
							array('instancia' => $client->instancia), 
							array('%s')
						);
						$wpdb->delete(
							$wpdb->prefix.'evolution_clientes',
							array('id' => $client->id), 
							array('%s')
						);
						$order->update_status( 'completed' );
					}
				}else{
					$return = evolution_remote_request("instance/delete/" . $client->instancia, "", "DELETE");
					$return = json_decode($return);
					if ($return && $return->status == "SUCCESS") {
						$wpdb->delete(
							$wpdb->prefix.'evolution_preferences',
							array('instancia' => $client->instancia), 
							array('%s')
						);
						$wpdb->delete(
							$wpdb->prefix.'evolution_typebot',
							array('instancia' => $client->instancia), 
							array('%s')
						);
						$wpdb->delete(
							$wpdb->prefix.'evolution_chatwoot',
							array('instancia' => $client->instancia), 
							array('%s')
						);
						$wpdb->delete(
							$wpdb->prefix.'evolution_clientes',
							array('id' => $client->id), 
							array('%s')
						);
						$order->update_status( 'completed' );
					}
				}
			}else{
				$wpdb->delete(
					$wpdb->prefix.'evolution_clientes',
					array('id' => $client->id), 
					array('%s')
				);
				$order->update_status( 'completed' );
			}
		}
	} else {
		echo 'Nenhum cliente encontrado com a data de expiração já passada.';
	}
}
add_action('evolution_api_cron', 'evolution_api_cron');
function evolution_api_connect_license() {
    if (isset($_POST['email'])) {
        $email = sanitize_email($_POST['email']);
        $url = esc_url($_POST['url']);
        $response = wp_remote_get('https://www.mestresdowp.com.br/evolution.php', array(
            'body' => array(
                'action' => 'verify',
                'url' => $url,
                'email' => $email,
            )
        ));
        if (is_wp_error($response)) {
            echo __('An error occurred.', 'evolution-api');
        } else {
            $body = wp_remote_retrieve_body($response);
			$retorno = json_decode($body);
			if(isset($retorno[0]->id)){
				update_option('evolution_active','true');
				update_option('evolution_license_id',$retorno[0]->id);
				update_option('evolution_license_email',$retorno[0]->email);
				update_option('evolution_license_expired',$retorno[0]->expired);
				return true;
			}else{
				
			}
			
        }
    } else {
        echo __('Email is required.', 'evolution-api');
    }
    wp_die();
}
function evolution_api_remove_license() {
    $id = get_option("evolution_license_id");
    $response = wp_remote_get('https://www.mestresdowp.com.br/evolution.php', array(
        'body' => array(
			'action' => 'remove',
			'id' => $id
		)
    ));
    if (is_wp_error($response)) {
        echo __('An error occurred.', 'evolution-api');
    } else {

    }
    wp_die();
}
add_action('wp_ajax_evolution_api_connect_license', 'evolution_api_connect_license');
add_action('wp_ajax_evolution_api_remove_license', 'evolution_api_remove_license');


function evolutionWebhookCallback(WP_REST_Request $request) {
	update_option('evolution_active','');
	update_option('evolution_license_id','');
	update_option('evolution_license_email','');
	update_option('evolution_license_expired','');
}