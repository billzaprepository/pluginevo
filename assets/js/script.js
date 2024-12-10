jQuery(document).ready(function($) {
    function updateInstances() {
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_list_instances_ajax',
            },
            success: function(response) {
				$('.evolution_content_instances').html(response);
				closeLoader();
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
    }
    function openLoader() {
		$('.evolution_overlay').addClass('visible');
    }
    function closeLoader() {
		$('.evolution_overlay').removeClass('visible');
    }
    function sendRequest(action, method) {
		openLoader();
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_remote_request',
                action_name: action,
                method: method,
                apikey: customAjax.apikey
            },
            success: function(response) {
                updateInstances();
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
    }
    function preferencesRequest(id) {
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_request_preferences',
                id: id
            },
            success: function(response) {
                if (response != false) {
                    var jsonArray = JSON.parse(response);
                    var rejeitar_chamadas = jsonArray[0].rejeitar_chamadas;
                    var rejectSelect = document.getElementById('rejeitar_chamadas');
                    rejectSelect.value = rejeitar_chamadas;
                    var ignorar_grupos = jsonArray[0].ignorar_grupos;
                    var ignoregroupSelect = document.getElementById('ignorar_grupos');
                    ignoregroupSelect.value = ignorar_grupos;
                    var sempre_online = jsonArray[0].sempre_online;
                    var onlineSelect = document.getElementById('sempre_online');
                    onlineSelect.value = sempre_online;
                    var marcar_lidas = jsonArray[0].marcar_lidas;
                    var readSelect = document.getElementById('marcar_lidas');
                    readSelect.value = marcar_lidas;
                    var marcar_visto = jsonArray[0].marcar_visto;
                    var markSelect = document.getElementById('marcar_visto');
                    markSelect.value = marcar_visto;
                    var sincronizar = jsonArray[0].sincronizar;
                    var sincSelect = document.getElementById('sincronizar');
                    sincSelect.value = sincronizar;
                    jsonArray.forEach(function(objeto) {
                        Object.entries(objeto).forEach(function([chave, valor]) {
                            $('#form_preferences #' + chave).val(valor);
                        });
                    });
                    $('#popup-form-preferences').show();
                } else {
                    $('#mensagem_rejeicao #url').val("");
                    $('#popup-form-preferences').show();
                }
				closeLoader();
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
    }
    function typeBotRequest(id) {
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_request_typebot',
                id: id
            },
            success: function(response) {
                if (response != false) {
                    var jsonArray = JSON.parse(response);
                    var status = jsonArray[0].status;
                    var statusSelect = document.getElementById('statusTypebot');
                    statusSelect.value = status;
                    jsonArray.forEach(function(objeto) {
                        Object.entries(objeto).forEach(function([chave, valor]) {
                            $('#form_typebot #' + chave).val(valor);
                        });
                    });
                    $('#popup-form-typebot').show();
                } else {
                    $('#form_typebot #url').val("");
                    $('#form_typebot #nome_do_fluxo').val("");
                    $('#form_typebot #palavra_de_finalizacao').val("");
                    $('#form_typebot #tempo_de_expiracao').val("");
                    $('#form_typebot #tempo_de_digitacao').val("");
                    $('#form_typebot #mensagem_desconhecida').val("");
                    $('#popup-form-typebot').show();
                }
				closeLoader();
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
		
    }
    function chatWootRequest(id) {
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_request_chatwoot',
                id: id
            },
            success: function(response) {
                if (response != false) {
                    var jsonArray = JSON.parse(response);
                    var assinar_mensagem = jsonArray[0].assinar_mensagem;
                    var assinarSelect = document.getElementById('assinar_mensagem');
                    assinarSelect.value = assinar_mensagem;
                    var reabrir_conversa = jsonArray[0].reabrir_conversa;
                    var reabrirSelect = document.getElementById('reabrir_conversa');
                    reabrirSelect.value = reabrir_conversa;
                    var iniciar_conversa_pendente = jsonArray[0].iniciar_conversa_pendente;
                    var startPendingSelect = document.getElementById('iniciar_conversa_pendente');
                    startPendingSelect.value = iniciar_conversa_pendente;
                    var importar_contatos = jsonArray[0].importar_contatos;
                    var importContactSelect = document.getElementById('importar_contatos');
                    importContactSelect.value = importar_contatos;
                    var importar_mensagens = jsonArray[0].importar_mensagens;
                    var importMessageSelect = document.getElementById('importar_mensagens');
                    importMessageSelect.value = importar_mensagens;
                    var status = jsonArray[0].status;
                    var statusSelect = document.getElementById('statusChatwoot');
                    statusSelect.value = status;
                    jsonArray.forEach(function(objeto) {
                        Object.entries(objeto).forEach(function([chave, valor]) {
                            $('#form_chatwoot #' + chave).val(valor);
                        });
                    });
                    $('#popup-form-chatwoot').show();
                } else {
                    $('#form_chatwoot #url').val("");
                    $('#form_chatwoot #id_da_conta').val("");
                    $('#form_chatwoot #token_da_conta').val("");
                    $('#form_chatwoot #limite_dias').val("");
                    $('#form_chatwoot #separador_assinatura').val("");
                    $('#popup-form-chatwoot').show();
                }
				closeLoader();
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
		
    }
    function createInstance(id) {
		openLoader();
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_ajax_create_instance',
                id: id
            },
            success: function(response) {
                updateInstances();
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
    }
    function deleteInstance(id) {
		openLoader();
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_ajax_delete_instance',
                id: id
            },
            success: function(response) {
                updateInstances();
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
    }
    window.copy_instance = function(button) {
        var id = $(button).data('id');
		navigator.clipboard.writeText(id).then(function() {
			$('.evolution_tag_copy').addClass('visible');
			setTimeout(function() {
				$('.evolution_tag_copy').removeClass('visible');
			}, 3000);
		}, function(err) {
			console.error('Could not copy text: ', err);
		});
    }
    window.custom_create_instance = function(button) {
        var id = $(button).data('id');
        createInstance(id);
    }
    window.custom_restart_instance = function(button) {
        var id = $(button).data('id');
        sendRequest('instance/restart/' + id, 'PUT');
    }
    window.custom_logout_instance = function(button) {
        var id = $(button).data('id');
        sendRequest('instance/logout/' + id, 'DELETE');
    }
    window.custom_delete_instance = function(button) {
        var id = $(button).data('id');
        deleteInstance(id);
    }
    window.open_form_chatwoot = function(button) {
		openLoader();
        var id = $(button).data('id');
        chatWootRequest(id);
        $('#form_chatwoot #instancia').val(id);
    }
    window.open_form_typebot = function(button) {
		openLoader();
        var id = $(button).data('id');
        typeBotRequest(id);
        $('#form_typebot #instancia').val(id);
    }
    window.open_form_preferences = function(button) {
		openLoader();
		var id = $(button).data('id');
        preferencesRequest(id);
        $('#form_preferences #instancia').val(id);
    }
    window.custom_update_page = function(button) {
		openLoader();
        updateInstances();
    }
    $("#submitTypebot").click(function(event) {
		openLoader();
        var formDataArray = $("#form_typebot").serializeArray();
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_set_typebot',
                form: formDataArray
            },
            success: function(response) {
				$('#popup-form-typebot').hide();
                updateInstances();
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
        return false;
    });
    $("#submitChatwoot").click(function(event) {
		openLoader();
        var formDataArray = $("#form_chatwoot").serializeArray();
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_set_chatwoot',
                form: formDataArray
            },
            success: function(response) {
				$('#popup-form-chatwoot').hide();
                updateInstances();
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
        return false;
    });
    $("#submitPreferences").click(function(event) {
		openLoader();
        var formDataArray = $("#form_preferences").serializeArray();
        $.ajax({
            url: customAjax.ajaxurl,
            type: 'POST',
            data: {
                action: 'evolution_set_preferences',
                form: formDataArray
            },
            success: function(response) {
                $('#popup-form-preferences').hide();
				updateInstances();
				
            },
            error: function(error) {
                alert('Error: ' + error);
            }
        });
        return false;
    });
    $(window).click(function(event) {
        if ($(event.target).is('#popup-form-typebot')) {
            $('#popup-form-typebot').hide();
        }
        if ($(event.target).is('#popup-form-chatwoot')) {
            $('#popup-form-chatwoot').hide();
        }
        if ($(event.target).is('#popup-form-preferences')) {
            $('#popup-form-preferences').hide();
        }
    });
});