<?php
function evolution_popup_form_shortcode() {
	if(get_option('evolution_active')==true){ 
    ob_start();
    ?>
    <div class="widget-content">
        <div id="popup-form-preferences" class="popup">
            <div class="popup-content">
                <form id="form_preferences" class="form">
                    <input type="hidden" id="instancia" name="instancia" placeholder="" required>
                    <label class="half-width">
                        <span><?php echo __('Reject calls', 'evolution-api'); ?></span>
                        <select id="rejeitar_chamadas" name="rejeitar_chamadas" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Rejection message', 'evolution-api'); ?></span>
                        <input type="text" id="mensagem_rejeicao" name="mensagem_rejeicao" placeholder="" style="width:100%;" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Ignore Groups', 'evolution-api'); ?></span>
                        <select id="ignorar_grupos" name="ignorar_grupos" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Always online', 'evolution-api'); ?></span>
                        <select id="sempre_online" name="sempre_online" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Mark messages as read', 'evolution-api'); ?></span>
                        <select id="marcar_lidas" name="marcar_lidas" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Mark status as seen', 'evolution-api'); ?></span>
                        <select id="marcar_visto" name="marcar_visto" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="">
                        <span><?php echo __('Sync full history', 'evolution-api'); ?></span>
                        <select id="sincronizar" name="sincronizar" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <input type="submit" id="submitPreferences" value="<?php echo __('Update', 'evolution-api'); ?>">
                </form>
            </div>
        </div>
        <div id="popup-form-typebot" class="popup">
            <div class="popup-content">
                <form id="form_typebot" class="form">
                    <input type="hidden" id="instancia" name="instancia" placeholder="" required>
                    <label>
                        <span><?php echo __('URL', 'evolution-api'); ?></span>
                        <input type="url" id="url" name="url" placeholder="URL" style="width:100%;" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Typebot flow name', 'evolution-api'); ?></span>
                        <input type="text" id="nome_do_fluxo" name="nome_do_fluxo" placeholder="" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('End keyword', 'evolution-api'); ?></span>
                        <input type="text" id="palavra_de_finalizacao" name="palavra_de_finalizacao" placeholder="" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Expiration time (in minutes)', 'evolution-api'); ?></span>
                        <input type="number" id="tempo_de_expiracao" name="tempo_de_expiracao" placeholder="" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Typing time (in milliseconds)', 'evolution-api'); ?></span>
                        <input type="number" id="tempo_de_digitacao" name="tempo_de_digitacao" placeholder="" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Unknown format message', 'evolution-api'); ?></span>
                        <input type="text" id="mensagem_desconhecida" name="mensagem_desconhecida" placeholder="" style="width:100%;" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Status', 'evolution-api'); ?></span>
                        <select id="statusTypebot" name="statusTypebot" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <input type="submit" id="submitTypebot" value="<?php echo __('Update', 'evolution-api'); ?>">
                </form>
            </div>
        </div>
        <div id="popup-form-chatwoot" class="popup">
            <div class="popup-content">
                <form id="form_chatwoot" class="form">
                    <input type="hidden" id="instancia" name="instancia" placeholder="" required>
                    <label>
                        <span><?php echo __('URL', 'evolution-api'); ?></span>
                        <input type="url" id="url" name="url" placeholder="" style="width:100%;" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Account ID', 'evolution-api'); ?></span>
                        <input type="text" id="id_da_conta" name="id_da_conta" placeholder="" style="width:100%;" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Token', 'evolution-api'); ?></span>
                        <input type="text" id="token_da_conta" name="token_da_conta" placeholder="" style="width:100%;" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Days limit to import messages', 'evolution-api'); ?></span>
                        <input type="number" id="limite_dias" name="limite_dias" placeholder="" style="width:100%;" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Sign message', 'evolution-api'); ?></span>
                        <select id="assinar_mensagem" name="assinar_mensagem" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Message signature separator', 'evolution-api'); ?></span>
                        <input type="text" id="separador_assinatura" name="separador_assinatura" placeholder="" style="width:100%;" required>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Reopen conversation', 'evolution-api'); ?></span>
                        <select id="reabrir_conversa" name="reabrir_conversa" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Start conversation as pending', 'evolution-api'); ?></span>
                        <select id="iniciar_conversa_pendente" name="iniciar_conversa_pendente" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Import contacts', 'evolution-api'); ?></span>
                        <select id="importar_contatos" name="importar_contatos" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Import message', 'evolution-api'); ?></span>
                        <select id="importar_mensagens" name="importar_mensagens" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <label class="half-width">
                        <span><?php echo __('Status', 'evolution-api'); ?></span>
                        <select id="statusChatwoot" name="statusChatwoot" style="width:100%;" required>
                            <option value="1"><?php echo __('Enabled', 'evolution-api'); ?></option>
                            <option value="0"><?php echo __('Disabled', 'evolution-api'); ?></option>
                        </select>
                    </label>
                    <input type="submit" id="submitChatwoot" value="<?php echo __('Update', 'evolution-api'); ?>">
                </form>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
}
add_shortcode('evolution_popup_form', 'evolution_popup_form_shortcode');
