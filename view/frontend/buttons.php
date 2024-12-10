<?php
function evolution_create_button_shortcode($atts) {
	if(get_option('evolution_active')==true){ 
    $atts = shortcode_atts(['id' => ''], $atts, 'evolution_create_button');
    return '<button class="custom-button" data-id="' . esc_attr($atts['id']) . '" onclick="custom_create_instance(this)"><i class="fas fa-plus"></i></button>';
	}
}
add_shortcode('evolution_create_button', 'evolution_create_button_shortcode');
function evolution_restart_button_shortcode($atts) {
	if(get_option('evolution_active')==true){ 
    $atts = shortcode_atts(['id' => ''], $atts, 'evolution_restart_button');
    return '<button class="custom-button" data-id="' . esc_attr($atts['id']) . '" onclick="custom_restart_instance(this)"><i class="fas fa-sync-alt"></i></button>';
	}
}
add_shortcode('evolution_restart_button', 'evolution_restart_button_shortcode');
function evolution_logout_button_shortcode($atts) {
	if(get_option('evolution_active')==true){ 
    $atts = shortcode_atts(['id' => ''], $atts, 'evolution_logout_button');
    return '<button class="custom-button" data-id="' . esc_attr($atts['id']) . '" onclick="custom_logout_instance(this)"><i class="fas fa-sign-out-alt"></i> '.__('Logout', 'evolution-api').'</button>';
	}
}
add_shortcode('evolution_logout_button', 'evolution_logout_button_shortcode');
function evolution_delete_button_shortcode($atts) {
	if(get_option('evolution_active')==true){ 
    $atts = shortcode_atts(['id' => ''], $atts, 'evolution_delete_button');
    return '<button class="custom-button" data-id="' . esc_attr($atts['id']) . '" onclick="custom_delete_instance(this)"><i class="fas fa-trash-alt"></i></button>';
	}
}
add_shortcode('evolution_delete_button', 'evolution_delete_button_shortcode');
function evolution_chatwoot_button_shortcode($atts) {
	if(get_option('evolution_active')==true){ 
    $atts = shortcode_atts(['id' => ''], $atts, 'evolution_chatwoot_button');
    return '<button class="custom-button" data-id="' . esc_attr($atts['id']) . '" onclick="open_form_chatwoot(this)"><i class="fas fa-comments"></i> '.__('Chatwoot', 'evolution-api').'</button>';
	}
}
add_shortcode('evolution_chatwoot_button', 'evolution_chatwoot_button_shortcode');
function evolution_typebot_button_shortcode($atts) {
	if(get_option('evolution_active')==true){ 
    $atts = shortcode_atts(['id' => ''], $atts, 'evolution_typebot_button');
    return '<button class="custom-button" data-id="' . esc_attr($atts['id']) . '" onclick="open_form_typebot(this)"><i class="fas fa-robot typebot-icon"></i> '.__('Typebot', 'evolution-api').'</button>';
	}
}
add_shortcode('evolution_typebot_button', 'evolution_typebot_button_shortcode');
function evolution_preferences_button_shortcode($atts) {
	if(get_option('evolution_active')==true){ 
    $atts = shortcode_atts(['id' => ''], $atts, 'evolution_preferences_button');
    return '<button class="custom-button" data-id="' . esc_attr($atts['id']) . '" onclick="open_form_preferences(this)"><i class="fas fa-cog"></i> '.__('Preferences', 'evolution-api').'</button>';
	}
}
add_shortcode('evolution_preferences_button', 'evolution_preferences_button_shortcode');
function evolution_update_page_shortcode() {
	if(get_option('evolution_active')==true){ 
    return '<button class="evolution-update-page-button" onclick="custom_update_page(this)"><i class="fas fa-sync-alt"></i></button>';
	}
}
add_shortcode('evolution_update_page', 'evolution_update_page_shortcode');