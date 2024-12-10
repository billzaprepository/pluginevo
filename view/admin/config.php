<?php
function evolution_api_menu() {
	global $submenu;
	add_menu_page(
		__('Evolution API', 'evolution-api'),
		__('Evolution API', 'evolution-api'),
		'manage_options',
		'evolution-api',
		'evolution_api_page',
		EVOLUTION_PLUGIN_URL . 'images/evolution.png'
	);
	if(get_option('evolution_active')==true){ 
		add_submenu_page(
			'evolution-api', 
			__('Settings', 'evolution-api'),
			__('Settings', 'evolution-api'),
			'manage_options', 
			'evolution-api-settings', 
			'evolution_api_page' 
		);
		add_submenu_page(
			'evolution-api',
			__('Customization', 'evolution-api'),
			__('Customization', 'evolution-api'),
			'manage_options',
			'evolution-api-customization',
			'evolution_api_customization_page'
		);
	}
    add_submenu_page(
        'evolution-api',
        __('Licenses', 'evolution-api'),
        __('Licenses', 'evolution-api'),
        'manage_options',
        'evolution-api-licenses',
        'evolution_api_licenses_page'
    );
	unset( $submenu['evolution-api'][0] );
}
add_action('admin_menu', 'evolution_api_menu');
function evolution_api_page() {
    ?>
    <div class="wrap">
        <h1><?php echo __('Settings', 'evolution-api'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('evolution_api_settings');
            do_settings_sections('evolution-api');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
function evolution_api_settings() {
    register_setting('evolution_api_settings', 'evolution_api_token');
    register_setting('evolution_api_settings', 'evolution_api_url');

    add_settings_section(
        'evolution_api_section',
        __('Integration Settings with Evolution API.', 'evolution-api'),
        null,
        'evolution-api'
    );

    add_settings_field(
        'evolution_api_token',
        __('API Key', 'evolution-api'),
        'evolution_api_token_callback',
        'evolution-api',
        'evolution_api_section'
    );

    add_settings_field(
        'evolution_api_url',
        __('URL', 'evolution-api'),
        'evolution_api_url_callback',
        'evolution-api',
        'evolution_api_section'
    );
}
add_action('admin_init', 'evolution_api_settings');

function evolution_api_customization_page() {
    ?>
    <div class="wrap">
        <h1><?php echo __('Customization', 'evolution-api'); ?></h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('evolution_api_customization');
            do_settings_sections('evolution-customization');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

function evolution_api_customization() {
	
	register_setting('evolution_api_customization', 'evolution_color_primary');
	register_setting('evolution_api_customization', 'evolution_background_box');
	register_setting('evolution_api_customization', 'evolution_border_box');
	register_setting('evolution_api_customization', 'evolution_text_color');
	
	add_settings_section(
        'evolution_api_customization',
        __('Customize the display options of Evolution API for WooCommerce', 'evolution-api'),
        null,
        'evolution-customization'
    );

    add_settings_field(
        'evolution_color_primary',
        __('Primary Color', 'evolution-api'),
        'evolution_color_primary_callback',
        'evolution-customization',
        'evolution_api_customization'
    );
    add_settings_field(
        'evolution_background_box',
        __('Background Box', 'evolution-api'),
        'evolution_background_box_callback',
        'evolution-customization',
        'evolution_api_customization'
    );
    add_settings_field(
        'evolution_border_box',
        __('Border Color Box', 'evolution-api'),
        'evolution_border_box_callback',
        'evolution-customization',
        'evolution_api_customization'
    );
    add_settings_field(
        'evolution_text_color',
        __('Text Color', 'evolution-api'),
        'evolution_text_color_callback',
        'evolution-customization',
        'evolution_api_customization'
    );
	
}
add_action('admin_init', 'evolution_api_customization');
function evolution_color_primary_callback() {
    $token = get_option('evolution_color_primary','#000');
    echo '<input type="text" name="evolution_color_primary" class="color-picker" value="' . esc_attr($token) . '" />';
}

function evolution_background_box_callback() {
    $token = get_option('evolution_background_box','#EEE');
    echo '<input type="text" name="evolution_background_box" class="color-picker" value="' . esc_attr($token) . '" />';
}

function evolution_border_box_callback() {
    $token = get_option('evolution_border_box','#CCC');
    echo '<input type="text" name="evolution_border_box" class="color-picker" value="' . esc_attr($token) . '" />';
}

function evolution_text_color_callback() {
    $token = get_option('evolution_text_color','#333');
    echo '<input type="text" name="evolution_text_color" class="color-picker" value="' . esc_attr($token) . '" />';
}

function evolution_api_token_callback() {
    $token = get_option('evolution_api_token');
    echo '<input type="text" name="evolution_api_token" value="' . esc_attr($token) . '" />';
}

function evolution_api_url_callback() {
    $url = get_option('evolution_api_url');
    echo '<input type="text" name="evolution_api_url" value="' . esc_attr($url) . '" />';
}

function evolution_api_licenses_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Licenses', 'evolution-api'); ?></h1>
		<?php if(get_option('evolution_active')==true){ ?>
		<p>Licença conectada com sucesso!</p>
		<a href="#" id="evolution-api-license-remove">Desconectar licença</a>
		<?php }else{ ?>
        <form id="evolution-api-license-form">
		<input type="hidden" id="evolution-api-license-url" name="evolution-api-license-url" value="<?php echo get_site_url(); ?>" required />
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><?php _e('Email', 'evolution-api'); ?></th>
                    <td><input type="email" id="evolution-api-license-email" name="evolution-api-license-email" required /></td>
                </tr>
            </table>
            <button type="button" id="evolution-api-license-connect" class="button button-primary"><?php _e('Connect', 'evolution-api'); ?></button>
        </form>
		<?php } ?>
    </div>
    <?php
}