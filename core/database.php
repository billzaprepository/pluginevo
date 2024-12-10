<?php
global $table_prefix, $wpdb;
$customerTables = array(
    'evolution_clientes',
    'evolution_typebot',
    'evolution_chatwoot',
    'evolution_preferences'
);
$sql = '';
foreach ($customerTables as $table) {
    $currentTable = $table_prefix . $table;
    $tableStructure = '';

    switch ($table) {
        case 'evolution_clientes':
            $tableStructure = "
				id INT(11) NOT NULL AUTO_INCREMENT,
				cliente VARCHAR(255) NOT NULL,
				instancia VARCHAR(255) NOT NULL,
				expiracao DATE NOT NULL,
				ref VARCHAR(255) NOT NULL,
				PRIMARY KEY (id)
            ";
            break;
        case 'evolution_typebot':
            $tableStructure = "
				id INT(11) NOT NULL AUTO_INCREMENT,
				instancia VARCHAR(255) NOT NULL,
				url VARCHAR(255) NOT NULL,
				nome_do_fluxo VARCHAR(255) NOT NULL,
				palavra_de_finalizacao VARCHAR(255) NOT NULL,
				tempo_de_expiracao INT(11) NOT NULL,
				tempo_de_digitacao INT(11) NOT NULL,
				mensagem_desconhecida TEXT NOT NULL,
				status TINYINT(1) NOT NULL,
				PRIMARY KEY (id)
            ";
            break;
        case 'evolution_chatwoot':
            $tableStructure = "
				id INT(11) NOT NULL AUTO_INCREMENT,
				instancia VARCHAR(255) NOT NULL,
				url VARCHAR(255) NOT NULL,
				id_da_conta VARCHAR(255) NOT NULL,
				token_da_conta VARCHAR(255) NOT NULL,
				limite_dias INT(11) NOT NULL,
				assinar_mensagem INT(11) NOT NULL,
				separador_assinatura VARCHAR(255) NOT NULL,
				reabrir_conversa INT(11) NOT NULL,
				iniciar_conversa_pendente INT(11) NOT NULL,
				importar_contatos INT(11) NOT NULL,
				importar_mensagens INT(11) NOT NULL,
				status TINYINT(1) NOT NULL,
				PRIMARY KEY (id)
            ";
            break;
        case 'evolution_preferences':
            $tableStructure = "
				id INT(11) NOT NULL AUTO_INCREMENT,
				instancia VARCHAR(255) NOT NULL,
				rejeitar_chamadas INT(11) NOT NULL,
				mensagem_rejeicao VARCHAR(255) NOT NULL,
				ignorar_grupos INT(11) NOT NULL,
				sempre_online INT(11) NOT NULL,
				marcar_lidas INT(11) NOT NULL,
				marcar_visto INT(11) NOT NULL,
				sincronizar INT(11) NOT NULL,
				PRIMARY KEY (id)
            ";
            break;
        default:
            break;
    }
	if ($wpdb->get_var("SHOW TABLES LIKE '$currentTable'") !== $currentTable) {
		$sql .= "CREATE TABLE $currentTable ($tableStructure) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1;";
	}
}
if (!empty($sql)) {
    require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
    $result = dbDelta($sql);

}