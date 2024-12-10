<?php
function evolution_list_instances_shortcode() {
	if(get_option('evolution_active')==true){ 
	$html = "";
	$html .=  "<div class='evolution_content_instances'>";
    global $wpdb;
    $current_user = wp_get_current_user();
    $user_email = $current_user->user_email;
    $table_name = $wpdb->prefix . 'evolution_clientes';
    $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $table_name WHERE cliente = %s", $user_email ), OBJECT );
    if ( $results ) {
        $html .= '<div class="custom-grid">';
        foreach ( $results as $index => $result ) {
            if ( $index % 4 === 0 && $index !== 0 ) {
                $html .= '</div><div class="custom-grid">';
            }
            $html .= '<div class="custom-grid-item">';
            if ( $result->instancia ) {
                $retorno = evolution_remote_request("instance/connectionState/".$result->instancia, "", "GET");
                $retorno = json_decode($retorno);
                $html .= "<p style='text-align:center;font-family:rubik;font-weight:400;color:#999;font-size:12px;text-transform:uppercase;'>".__('Expires in', 'evolution-api')." ".$result->expiracao."</p>";
                if ( $retorno->instance->state == "open" ) {
                    $html .= "<p style='text-align:center;font-family:rubik;font-weight:700;color:#007657;font-size:18px;text-transform:uppercase;'>".__('Instance connected', 'evolution-api')."</p>";
                    $html .= "<div class='copyInstance'>";
                    $html .= "<input style='text-align:center;font-family:rubik;font-weight:400;color:#333;font-size:12px;' value ='".$result->instancia."' />";
                    $html .= "<button data-id='".$result->instancia."' onclick='copy_instance(this)'><i class='fa fa-clipboard'></i></button>";
                    $html .= "</div>";
                    $html .= "<p style='text-align:center;font-family:rubik;font-weight:400;color:#333;font-size:12px;text-transform:uppercase;margin-top:0px;'>".__('Settings', 'evolution-api')."</p>";
                    $html .= "<div class='buttonsConfig'>";
                    $html .= do_shortcode('[evolution_preferences_button id="'.$result->instancia.'"]');
                    $html .= do_shortcode('[evolution_typebot_button id="'.$result->instancia.'"]');
                    $html .= do_shortcode('[evolution_chatwoot_button id="'.$result->instancia.'"]');
                    $html .= do_shortcode('[evolution_logout_button id="'.$result->instancia.'"]');
                    $html .= "</div>";
                } else {
                    $qrcode = evolution_remote_request("instance/connect/".$result->instancia, "", "GET");
                    $qrcode = json_decode($qrcode);
                    $html .= "<p style='text-align:center;'><img src='".$qrcode->base64."' /></p>";
                    $html .= "<div class='buttons'>";
                    $html .= do_shortcode('[evolution_restart_button id="'.$result->instancia.'"]');
                    $html .= do_shortcode('[evolution_delete_button id="'.$result->instancia.'"]');
                    $html .= "</div>";
                }
            } else {
                $html .= "<p style='text-align:center;font-family:rubik;font-weight:400;color:#999;font-size:12px;text-transform:uppercase;'>".__('Expires in', 'evolution-api')." ".$result->expiracao."</p>";
                $html .= "<p style='text-align:center;font-family:rubik;font-weight:700;color:#007657;font-size:18px;text-transform:uppercase;'>".__('Instance available', 'evolution-api')."</p>";
                $html .= "<p style='text-align:center;font-family:rubik;font-weight:400;color:#333;font-size:13px;text-transform:uppercase;'>".__('To generate the QR Code, click the button below.', 'evolution-api')."</p>";
                $html .= "<div class='buttons'>";
                $html .= do_shortcode('[evolution_create_button id="'.$result->id.'"]');
                $html .= "</div>";    
            }
            $html .= '</div>';
        }
        $html .= '</div>'; 
    } else {
        $html .= __('No records found.', 'evolution-api');
    }  
	$html .= "</div>";
	return $html;
	}
}
add_shortcode('evolution_list_instances', 'evolution_list_instances_shortcode');