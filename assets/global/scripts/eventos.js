var verificapercentual = true;

$(document).ready(function() {

	/** Categorias **/
	$("#tbl_categorias tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrocategoriausuario($(this).html());
			}
		});
	});

	$("#tbl_categorias span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrocategoriausuario($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var idcategoria = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Categoria de Usuário',
				message : "Deseja mesmo excluir esta categoria de usuário?",
				callback : function(resultado) {
					if (resultado) {
						acaocategoriausuario = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_categoria_usuario.php",
							data : {
								'idcategoria' : idcategoria,
								'acaocategoriausuario' : acaocategoriausuario
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultacategoriasusuario(pagina, registros, 'sucesso', 'Categoria de usuário excluida com sucesso');
								} else {
									consultacategoriasusuario(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_categoria_usuario").click(function() {
		var idcategoria = $('#txtidcategoria').val();
		var descricaocategoria = $('#txtdescricaocategoria').val();
		var listapermissoesformularios = [];
		var listapermissoesrelatorios = [];
		var listacamposinvalidos = [];

		if (descricaocategoria == "" || descricaocategoria == undefined) {
			listacamposinvalidos.push("- É necessario informar a descrição da categoria de usuário");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		$("#tbl_permissoes_formularios tbody").find('tr').each(function(indice) {
			var permissoesformulario = new Object();

			$(this).find('td').each(function(indice) {
				if (indice == 0) {
					permissoesformulario.idformulario = $(this).html();

				} else if (indice == 2) {
					if ($(this).html().indexOf("glyphicon glyphicon-remove") >= 0) {
						permissoesformulario.leiturapermissao = 0;
					} else {
						permissoesformulario.leiturapermissao = 1;
					}
				} else if (indice == 3) {
					if ($(this).html().indexOf("glyphicon glyphicon-remove") >= 0) {
						permissoesformulario.gravacaopermissao = 0;
					} else {
						permissoesformulario.gravacaopermissao = 1;
					}
				} else if (indice == 4) {
					if ($(this).html().indexOf("glyphicon glyphicon-remove") >= 0) {
						permissoesformulario.edicaopermissao = 0;
					} else {
						permissoesformulario.edicaopermissao = 1;
					}
				} else if (indice == 5) {
					if ($(this).html().indexOf("glyphicon glyphicon-remove") >= 0) {
						permissoesformulario.exclusaopermissao = 0;
					} else {
						permissoesformulario.exclusaopermissao = 1;
					}
				}
			});

			listapermissoesformularios.push(permissoesformulario);
		});

		$("#tbl_permissoes_relatorios tbody").find('tr').each(function(indice) {
			var permissoesrelatorios = new Object();

			$(this).find('td').each(function(indice) {
				if (indice == 0) {
					permissoesrelatorios.idrelatorio = $(this).html();

				} else if (indice == 2) {
					if ($(this).html().indexOf("glyphicon glyphicon-remove") >= 0) {
						permissoesrelatorios.leiturarelatorio = 0;
					} else {
						permissoesrelatorios.leiturarelatorio = 1;
					}
				}
			});

			listapermissoesrelatorios.push(permissoesrelatorios);
		});

		if (idcategoria == "" || idcategoria == undefined) {
			acaocategoriausuario = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_categoria_usuario.php",
				data : {
					'idcategoria' : idcategoria,
					'descricaocategoria' : descricaocategoria,
					'listapermissoesformularios' : listapermissoesformularios,
					'listapermissoesrelatorios' : listapermissoesrelatorios,
					'acaocategoriausuario' : acaocategoriausuario
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultacategoriasusuario('', '', 'sucesso', 'Categoria de usuário incluida com sucesso');
					} else {
						listacamposinvalidos.push(data.msg);
						alert_danger('Erro!', listacamposinvalidos);
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Categoria de Usuário',
				message : "Deseja mesmo alterar esta categoria do usuário?",
				callback : function(resultado) {
					if (resultado) {
						acaocategoriausuario = 'editar';

						$.ajax({
							type : "POST",
							url : "set_categoria_usuario.php",
							data : {
								'idcategoria' : idcategoria,
								'descricaocategoria' : descricaocategoria,
								'listapermissoesformularios' : listapermissoesformularios,
								'listapermissoesrelatorios' : listapermissoesrelatorios,
								'acaocategoriausuario' : acaocategoriausuario
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultacategoriasusuario('', '', 'sucesso', 'Categoria de usuário alterada com sucesso');
								} else {
									listacamposinvalidos.push(data.msg);
									alert_danger('Erro!', listacamposinvalidos);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_categoria_usuario").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrocategoriausuario();
				}
			}
		});
	});

	$("#tbl_permissoes_formularios span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-remove") {
			$(this).removeClass("glyphicon glyphicon-remove");
			$(this).addClass("glyphicon glyphicon-ok");
			$(this).css("color", "green");
		} else {
			$(this).removeClass("glyphicon glyphicon-ok");
			$(this).addClass("glyphicon glyphicon-remove");
			$(this).css("color", "red");
		}
	});

	$("#tbl_permissoes_relatorios span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-remove") {
			$(this).removeClass("glyphicon glyphicon-remove");
			$(this).addClass("glyphicon glyphicon-ok");
			$(this).css("color", "green");
		} else {
			$(this).removeClass("glyphicon glyphicon-ok");
			$(this).addClass("glyphicon glyphicon-remove");
			$(this).css("color", "red");
		}
	});

	/** Contas **/
	$('#btn_filtros_conta').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltrodescricao').val(paramGET.descricao).trigger("change");

		$('#modal_filtros_contas').modal('show');
	});

	$('#btn_pesquisa_conta').click(function() {
		var descricao = $('#cmbfiltrodescricao').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultacontas(pagina, registros, tipo_msg, msg, descricao);
	});

	$('#btn_limpar_pesquisa_conta').click(function() {
		$('#cmbfiltrodescricao').val("nenhum").trigger("change");
	});
	
	/* modal de transferencia */	
	$('#btn_transferencia').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();
		
		$('#cmbconta1').val(paramGET.descricao).trigger("change");
		$('#cmbconta2').val(paramGET.descricao).trigger("change");
		$('#txtvalortransferir').val(paramGET.descricao).trigger("change");
		$('#txtdataransferir').val(paramGET.descricao).trigger("change");
		
		$('#modal_transferencia').modal('show');
	});

	$('#btn_transferir').click(function() {
		
		var idconta1 		= $('#cmbconta1').val();
		var idconta2 		= $('#cmbconta2').val();
		var valortransferir = $('#txtvalortransferir').val();
		var data 			= $('#txtdataransferir').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;
		
		if(idconta1 == "undefined" || idconta1 == "" || idconta1==null )
		{	
			bootbox.alert("Primeira conta não informada.");
			return false;
		}
				
		if(idconta2 == "undefined" || idconta2 == "" || idconta2==null )
		{
			bootbox.alert("Segunda conta não informada.");			
			return false;
		}
		
		if(idconta1==idconta2)
		{	
			bootbox.alert("Informe contas diferentes!");
			return false;			
		}
		
		if(valortransferir<=0 || valortransferir == "undefined" || valortransferir == "" )
		{
			bootbox.alert("Informe um valor maior que zero!");
			return false;
		}
		
		if(data == "undefined" || data == "" )
		{
			bootbox.alert("Informe uma data!");
			return false;
		}
		
		acaotransferencia = 'transferir';
		
		$.ajax({
				type : "POST",
				url : "set_transferencia.php",
				data : {
					'idconta1' : idconta1,
					'idconta2' : idconta2,
					'valor' : valortransferir,
					'data'	: data,
					'acao' : acaotransferencia
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso'){
						consultamovimentacaocontas('', '', 'sucesso', 'Transferência realizada com sucesso!');					
					} 
					else if (data.msg == 'validacaotransferencia1'){
						consultamovimentacaocontas('', '', 'erro', 'Sem saldo para transferência!');
					}
					else {

					}
				}
			});

		
	});

	$('#btn_limpar_transferencia').click(function() {
		$('#cmbconta1').val('nenhum').trigger("change");
		$('#cmbconta2').val('nenhum').trigger("change");
		$('#txtvalortransferir').val('').trigger("change");
		$('#txtdataransferir').val('').trigger("change");
	});

	$("#tbl_contas tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrocontas($(this).html());
			}
		});
	});

	$("#tbl_contas span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrocontas($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Conta',
				message : "Deseja mesmo excluir esta conta?",
				callback : function(resultado) {
					if (resultado) {
						acaocontas = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_contas.php",
							data : {
								'idconta' : id,
								'acaocontas' : acaocontas
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultacontas(pagina, registros, 'sucesso', 'Conta excluida com sucesso');
								} else {
									consultacontas(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});
	
	$("#tpcontapadrao").click(function() {
  		
  		document.getElementById('divconta2').style.display = "none";
	});
	
	$("#tpcontapremio").click(function() {
  		
  		document.getElementById('divconta2').style.display = "block";
	});
	
	$("#tpcontaoutros").click(function() {
  		
  		document.getElementById('divconta2').style.display = "block";
	});
	
	
	$("#grava_contas").click(function() {
		var idconta = $('#txtidconta').val();
		var banco = $('#txtbanco').val();
		var agencia = $('#txtagencia').val();
		var numero = $('#txtnumero').val();
		var descricaoconta = $('#txtdescricaoconta').val();
		var listacamposinvalidos = [];

		var rads = document.getElementsByName('tpconta');

		for (var i = 0; i < rads.length; i++) {
			if (rads[i].checked) {
				var tpconta = rads[i].value;
			}
		}
		
		var rads2 = document.getElementsByName('tpconta2');

		for (var i = 0; i < rads2.length; i++) {
			if (rads2[i].checked) {
				var tpconta2 = rads2[i].value;
			}
		}

		if (tpconta == 1) {
			
			var tipopremio = 0;
			var tipooutros = 0;
			var tipocorrente = 0;
			var tipoaplicacao = 0;
			
		} else if (tpconta == 2) {
			var tipopremio = 1;
			var tipooutros = 0;		
			
			if(tpconta2 == 1)
			{
				var tipocorrente = 1;
				var tipoaplicacao = 0;	
			}
			else if (tpconta2 == 2)
			{
				var tipocorrente = 0;
				var tipoaplicacao = 1;
			}
				
		} else if (tpconta == 3) {
			var tipopremio = 0;
			var tipooutros = 1;

			if(tpconta2 == 1)
			{
				var tipocorrente = 1;
				var tipoaplicacao = 0;	
			}
			else if (tpconta2 == 2)
			{
				var tipocorrente = 0;
				var tipoaplicacao = 1;
			}			
		}
		
		
		if (tpconta == "" || tpconta == undefined) {
			listacamposinvalidos.push("- É necessario informar o tipo da conta! ");
		}

		if (banco == "" || banco == undefined) {
			listacamposinvalidos.push("- É necessario informar o banco! ");
		}

		if (agencia == "" || agencia == undefined) {
			listacamposinvalidos.push("- É necessario informar a agência! ");
		}

		if (numero == "" || numero == undefined) {
			listacamposinvalidos.push("- É necessario informar o numero da conta! ");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idconta == "" || idconta == undefined) {
			acaocontas = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_contas.php",
				data : {
					'idconta' : idconta,
					'banco' : banco,
					'agencia' : agencia,
					'numero' : numero,
					'descricaoconta' : descricaoconta,
					'contapremio' : tipopremio,
					'contaoutros' : tipooutros,
					'contacorrente' : tipocorrente,
					'contaaplicacao' : tipoaplicacao,
					'acaocontas' : acaocontas

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultacontas('', '', 'sucesso', 'Conta incluida com sucesso');
					} else if (data.msg == 'validacaopremio1') {
						listacamposinvalidos.push("- Já existe uma conta prêmio como conta corrente. ");
						alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
						return false;
					} else if (data.msg == 'validacaopremio2') {
						listacamposinvalidos.push("- Já existe uma conta prêmio como conta aplicação. ");
						alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
						return false;
					} else if (data.msg == 'validacaooutros1') {
						alert('oiS');
						listacamposinvalidos.push("- Já existe uma conta outros como conta corrente. ");
						alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
						return false;
					} else if (data.msg == 'validacaooutros2') {
						listacamposinvalidos.push("- Já existe uma conta outros como conta aplicação. ");
						alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
						return false;
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Conta',
				message : "Deseja mesmo alterar esta conta?",
				callback : function(resultado) {
					if (resultado) {
						acaocontas = 'editar';

						$.ajax({
							type : "POST",
							url : "set_contas.php",
							data : {
								'idconta' : idconta,
								'banco' : banco,
								'agencia' : agencia,
								'numero' : numero,
								'descricaoconta' : descricaoconta,
								'contapremio' : tipopremio,
								'contaoutros' : tipooutros,
								'contacorrente' : tipocorrente,
								'contaaplicacao' : tipoaplicacao,
								'acaocontas' : acaocontas
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultacontas('', '', 'sucesso', 'Conta alterada com sucesso');
								} else if (data.msg == 'validacaopremio') {
									listacamposinvalidos.push("- Já existe uma conta prêmio. ");
									alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
									return false;

								} else if (data.msg == 'validacaooutros') {
									listacamposinvalidos.push("- Já existe uma conta outros. ");
									alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
									return false;
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_contas").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrocontas();
				}
			}
		});
	});

	$('#relatorio_contas').click(function() {
		var relatorio = 'contas.php';
		var chama_rel = 'php_exporta_pdf/relatorios/' + relatorio;

		window.open(chama_rel);
	});

	/** Funcionario **/
	$('#btn_relatorio_funcionarios').click(function() {
		
		var relatorio = 'movimentacao_contas.php';
		var chama_rel = 'php_exporta_pdf/relatorios/funcionarios.php';

		window.open(chama_rel);

	});
	$('#btn_filtros_funcionario').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltrofuncionarios').val(paramGET.funcionario).trigger("change");
		$('#cmbfiltrocategorias').val(paramGET.categoria).trigger("change");

		$('#modal_filtros_funcionario').modal('show');
	});

	$('#btn_pesquisa_funcionario').click(function() {
		var funcionario = $('#cmbfiltrofuncionarios').val();
		var categoria = $('#cmbfiltrocategorias').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultafuncionarios(pagina, registros, tipo_msg, msg, funcionario, categoria);
	});

	$('#btn_limpar_pesquisa_funcionario').click(function() {
		$('#cmbfiltrofuncionarios').val("nenhum").trigger("change");
		$('#cmbfiltrocategorias').val("nenhum").trigger("change");
	});

	$("#tbl_funcionarios tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrofuncionario($(this).html());
			}
		});
	});

	$("#tbl_funcionarios span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrofuncionario($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var idfuncionario = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Funcionário',
				message : "Deseja mesmo excluir este funcionário?",
				callback : function(result) {
					if (result) {
						acaofuncionario = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_funcionario.php",
							data : {
								'idfuncionario' : idfuncionario,
								'acaofuncionario' : acaofuncionario
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultafuncionarios(pagina, registros, 'sucesso', 'Funcionário excluido com sucesso');
								} else {
									consultafuncionarios(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$('#txtcepfuncionario').on('blur', function() {
		var cep = $('#txtcepfuncionario').val();

		$.ajax({
			type : "POST",
			url : "http://cep.republicavirtual.com.br/web_cep.php?cep=" + cep + "&formato=json",
			dataType : 'json',
			success : function(data) {
				/* Comentado para quando quizer consultar informações no web service */
				/* console.log(data); */
				$('#txtenderecofuncionario').val(data.logradouro);
				$('#txtbairrofuncionario').val(data.bairro);
				$('#txtcidadefuncionario').val(data.cidade);
				$('#txtuffuncionario').val(data.uf);
			}
		});
	});

	$("#txtusuariofuncionario").change(function() {
		var usuariofuncionario = $('#txtusuariofuncionario').val();

		$.ajax({
			type : "POST",
			url : "get_verificausuario_cadastrado.php",
			data : {
				'usuariofuncionario' : usuariofuncionario
			},
			dataType : 'json',
			success : function(data) {
				if (data.msg != 'sucesso') {
					$('#txtusuariofuncionario').val('');

					bootbox.alert({
						title : 'Validação de Informações',
						message : data.msg
					});
				}
			}
		});
	});

	$("#grava_funcionario").click(function() {
		var idfuncionario = $('#txtidfuncionario').val();
		var nomefuncionario = $('#txtnomefuncionario').val();
		var documentofuncionario = $('#txtdocumentofuncionario').val();
		var dtnascfuncionario = $('#txtdtnascfuncionario').val();
		var emailfuncionario = $('#txtemailfuncionario').val();
		var telefonefuncionario = $('#txttelefonefuncionario').val();
		var usuariofuncionario = $('#txtusuariofuncionario').val();
		var categoriafuncionario = $('#txtcategoriafuncionario').val();
		var senhafuncionario = $('#txtsenhafuncionario').val();
		var senhaantfuncionario = $('#txtsenhaantfuncionario').val();
		var confirmafuncionario = $('#txtconfirmafuncionario').val();
		var enderecofuncionario = $('#txtenderecofuncionario').val();
		var numerofuncionario = $('#txtnumerofuncionario').val();
		var bairrofuncionario = $('#txtbairrofuncionario').val();
		var cidadefuncionario = $('#txtcidadefuncionario').val();
		var uffuncionario = $('#txtuffuncionario').val();
		var cepfuncionario = $('#txtcepfuncionario').val();
		var listacamposinvalidos = [];

		if (nomefuncionario == "" || nomefuncionario == undefined) {
			listacamposinvalidos.push('- É necessario informar o nome do funcionário');
		}

		if (categoriafuncionario == "" || categoriafuncionario == undefined) {
			listacamposinvalidos.push('- É necessario informar a categoria do funcionário');
		}

		if (usuariofuncionario != "" || senhafuncionario != "" || confirmafuncionario != "") {
			if (usuariofuncionario == "") {
				listacamposinvalidos.push('- Se for inserir usuário para o funcionário é necessario informar o nome de usuário');
			}

			if (senhafuncionario == "") {
				listacamposinvalidos.push('- Se for inserir usuário para o funcionário é necessario informar a senha');
			}

			if (confirmafuncionario == "") {
				listacamposinvalidos.push('- Se for inserir usuário para o funcionário é necessario informar a senha de confirmação');
			}

			if (senhafuncionario != confirmafuncionario) {
				listacamposinvalidos.push('- Senha de confirmação e senha não conferem!');
			}
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idfuncionario == "" || idfuncionario == undefined) {
			acaofuncionario = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_funcionario.php",
				data : {
					'idfuncionario' : idfuncionario,
					'nomefuncionario' : nomefuncionario,
					'documentofuncionario' : documentofuncionario,
					'dtnascfuncionario' : dtnascfuncionario,
					'emailfuncionario' : emailfuncionario,
					'telefonefuncionario' : telefonefuncionario,
					'usuariofuncionario' : usuariofuncionario,
					'categoriafuncionario' : categoriafuncionario,
					'senhafuncionario' : senhafuncionario,
					'senhaantfuncionario' : senhaantfuncionario,
					'enderecofuncionario' : enderecofuncionario,
					'numerofuncionario' : numerofuncionario,
					'bairrofuncionario' : bairrofuncionario,
					'cidadefuncionario' : cidadefuncionario,
					'uffuncionario' : uffuncionario,
					'cepfuncionario' : cepfuncionario,
					'acaofuncionario' : acaofuncionario
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultafuncionarios('', '', 'sucesso', 'Funcionário adicionado com sucesso');
					} else {
						listacamposinvalidos.push(data.msg);
						alert_danger('Erro!', listacamposinvalidos);
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Funcionário',
				message : "Deseja mesmo alterar este funcionário?",
				callback : function(result) {
					if (result) {
						acaofuncionario = 'editar';

						$.ajax({
							type : "POST",
							url : "set_funcionario.php",
							data : {
								'idfuncionario' : idfuncionario,
								'nomefuncionario' : nomefuncionario,
								'documentofuncionario' : documentofuncionario,
								'dtnascfuncionario' : dtnascfuncionario,
								'emailfuncionario' : emailfuncionario,
								'telefonefuncionario' : telefonefuncionario,
								'usuariofuncionario' : usuariofuncionario,
								'categoriafuncionario' : categoriafuncionario,
								'senhafuncionario' : senhafuncionario,
								'senhaantfuncionario' : senhaantfuncionario,
								'enderecofuncionario' : enderecofuncionario,
								'numerofuncionario' : numerofuncionario,
								'bairrofuncionario' : bairrofuncionario,
								'cidadefuncionario' : cidadefuncionario,
								'uffuncionario' : uffuncionario,
								'cepfuncionario' : cepfuncionario,
								'acaofuncionario' : acaofuncionario
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultafuncionarios('', '', 'sucesso', 'Funcionário alterado com sucesso');
								} else {
									listacamposinvalidos.push(data.msg);
									alert_danger('Erro!', listacamposinvalidos);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_funcionario").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrofuncionario();
				}
			}
		});
	});

	/** Movimentação Contas **/
	$('#btn_filtro_movimentacao_contas').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#txtfiltrodescricao').val(paramGET.descricao2).trigger("change");
		$('#txtfiltrodata').val(paramGET.datainicial).trigger("change");
		$('#txtfiltrodata2').val(paramGET.datafinal).trigger("change");

		$('#modal_filtros_movimentacao_contas').modal('show');
	});

	$('#btn_pesquisa_movimentacao_contas').click(function() {
		var idconta = $('#txtfiltroidconta').val();
		var descricao = $('#txtfiltrodescricao').val();
		var data = $('#txtfiltrodata').val();
		var data2 = $('#txtfiltrodata2').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		detalhemovimentacaocontas(idconta, pagina, registros, tipo_msg, msg, data, data2, descricao);
	});

	$('#btn_limpar_pesquisa_movimentacao_contas').click(function() {

		$('#txtfiltrodescricao').val('').trigger("change");
		$('#txtfiltrodata').val('').trigger("change");
		$('#txtfiltrodata2').val('').trigger("change");

	});

	/** Modal de Relatórios **/
	$('#btn_relatorio_movimentacao_contas').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#txtfiltroreldescricao').val(paramGET.descricao2).trigger("change");
		$('#txtfiltroreldata').val(paramGET.datainicial).trigger("change");
		$('#txtfiltroreldata2').val(paramGET.datafinal).trigger("change");

		$('#modal_relatorio_movimentacao_contas').modal('show');
	});

	$('#btn_relatorio_filtro_movimentacao_contas').click(function() {
		var idconta = $('#txtfiltroidconta').val();
		var descricao = $('#txtfiltroreldescricao').val();
		var data = $('#txtfiltroreldata').val();
		var data2 = $('#txtfiltroreldata2').val();

		var relatorio = 'movimentacao_contas.php';
		var chama_rel = 'php_exporta_pdf/relatorios/' + relatorio + '?id_conta=' + idconta + "&data=" + data + "&data2=" + data2 + "&descricao=" + descricao;

		window.open(chama_rel);

	});

	$('#btn_limpar_relatorio_movimentacao_contas').click(function() {

		$('#txtfiltroreldescricao').val('').trigger("change");
		$('#txtfiltroreldata').val('').trigger("change");
		$('#txtfiltroreldata2').val('').trigger("change");

	});

	$("#tbl_movimentacao_contas tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				detalhemovimentacaocontas($(this).html());
			}
		});
	});

	$("#tbl_movimentacao_contas span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "fa fa-external-link") {
			detalhemovimentacaocontas($(this).attr('id'));
		}
	});

	$("#tbl_detalhes_contas tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				$idconta = $(this).html();
				//cadastromovimentacaocontas($(this).html());
			}
			if (indice == 1) {
				$idtrans = $(this).html();
			}
			
			if (indice == 2) {
				$idpdcj = $(this).html();
			}

			if (indice == 3) {
				$idmov = $(this).html();
				if($idtrans>0)
				{					
					var pagina = $_GET()["pagina"];
					var registros = $_GET()["registros"];
	
					detalhemovimentacaocontas($idconta, pagina, registros, 'erro', 'Não pode editar uma transferência!');	
				}
				else if($idpdcj != null && $idpdcj != "" && $idpdcj != "undefined" )
				{					
					var pagina = $_GET()["pagina"];
					var registros = $_GET()["registros"];
	
					detalhemovimentacaocontas($idconta, pagina, registros, 'erro', 'Não pode editar um lançamento de gasto do PDCJ!');	
				}
				else
				{
					cadastromovimentacaocontas($idconta, $idmov);	
				}
			}
		});
	});

	$("#tbl_detalhes_contas span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			
			if($(this).attr('id_trans')==1)
			{
				var pagina = $_GET()["pagina"];
				var registros = $_GET()["registros"];

				detalhemovimentacaocontas($(this).attr('id'), pagina, registros, 'erro', 'Não pode editar uma transferência!');
			}
			else if($(this).attr('id_pdcj')!=null && $(this).attr('id_pdcj')!="" && $(this).attr('id_pdcj')!="undefined" )
			{
				var pagina = $_GET()["pagina"];
				var registros = $_GET()["registros"];

				detalhemovimentacaocontas($(this).attr('id'), pagina, registros, 'erro', 'Não pode editar um lançamento de gasto do PDCJ!');
			}
			else
			{
				cadastromovimentacaocontas($(this).attr('id'), $(this).attr('idmov'));	
			}
			
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');
			var idconta = $(this).attr('idconta');
			var idpdcj = $(this).attr('id_pdcj');
			
			if(idpdcj != null && idpdcj != "" && idpdcj != "undefined" )
			{					
					bootbox.alert("Não pode excluir um lançamento de gastos do PDCJ!");
			}
			else
			{
				bootbox.confirm({
					title : 'Exclusão de Conta',
					message : "Deseja mesmo excluir esta movimentação?",
					callback : function(resultado) {
						if (resultado) {
							acao = 'excluir';
	
							$.ajax({
								type : "POST",
								url : "set_movimentacao_contas.php",
								data : {
									'idmov' : id,
									'idconta' : idconta,
									'acaomov' : acao
								},
								dataType : 'json',
								success : function(data) {
									if (data.msg == 'sucesso') 
									{
										var pagina = $_GET()["pagina"];
										var registros = $_GET()["registros"];
	
										detalhemovimentacaocontas(idconta, pagina, registros, 'sucesso', 'Movimentação excluida com sucesso');
									} 
									else if (data.msg == 'transferencia') 
									{
										var pagina = $_GET()["pagina"];
										var registros = $_GET()["registros"];
	
										detalhemovimentacaocontas(idconta, pagina, registros, 'sucesso', 'Transferência excluida com sucesso');
									}
									else 
									{
										detalhemovimentacaocontas(idconta, pagina, registros, 'erro', data.msg);
									}
								}
							});
						}
					}
				});
			}
		}
	});

	$("#grava_mov_contas").click(function() {
		var idmovconta = $('#txtidmovconta').val();
		var idconta = $('#txtidconta').val();
		var descricao = $('#txtdescricao').val();
		var operacao = $('#cmboperacao').val();
		var valor = $('#txtvalor').val();
		var data = $('#txtdata').val();
		var listacamposinvalidos = [];

		valor = valor.replace('R$', '');

		if (descricao == "" || descricao == undefined) {
			listacamposinvalidos.push("- É necessario informar uma descrição! ");
		}

		if (operacao == "" || operacao == undefined) {
			listacamposinvalidos.push("- É necessario informar a operação! ");
		}

		if (valor == "" || valor == undefined) {
			listacamposinvalidos.push("- É necessario informar o valor da movimentação! ");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idmovconta == "" || idmovconta == undefined) {
			acao = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_movimentacao_contas.php",
				data : {
					'idmovconta' : idmovconta,
					'idconta' : idconta,
					'descricao' : descricao,
					'operacao' : operacao,
					'valor' : valor,
					'data' : data,
					'acaomov' : acao
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						detalhemovimentacaocontas(idconta, '', '', 'sucesso', 'Movimentação incluida com sucesso');
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Movimentação',
				message : "Deseja mesmo alterar esta movimentação?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'editar';

						$.ajax({
							type : "POST",
							url : "set_movimentacao_contas.php",
							data : {
								'idmovconta' : idmovconta,
								'idconta' : idconta,
								'descricao' : descricao,
								'operacao' : operacao,
								'valor' : valor,
								'data' : data,
								'acaomov' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									detalhemovimentacaocontas(idconta, '', '', 'sucesso', 'Movimentação alterada com sucesso');
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#btn_novomovimento").click(function() {
		cadastromovimentacaocontas($(this).attr('value'));
	});

	$("#limpa_mov_contas").click(function() {
		var idconta = $(this).attr('value');
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",

			callback : function(result) {
				if (result) {
					cadastromovimentacaocontas(idconta);
				}
			}
		});
	});

	/** Mascaras **/
	$('.cpf').mask('999.999.999-99');
	$('.cnpj').mask('99.999.999/9999-99');
	$('.cep').mask('99999-999');
	$('.tel').mask('(999) 99999-9999');
	$(".maskdinheiro").maskMoney({
		prefix : 'R$ ',
		allowNegative : true,
		thousands : '',
		decimal : ',',
		affixesStay : false
	});
	$(".maskarea").maskMoney({
		prefix : '',
		allowNegative : false,
		thousands : '',
		decimal : ',',
		affixesStay : false
	});
	$(".maskint").maskMoney({
		prefix : '',
		allowNegative : false,
		thousands : '',
		decimal : '',
		affixesStay : false
	});

	/** Movimentação de Café **/
	/** Entradas de Café **/
	$('#btn_filtro_ent_cafe').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();
		$('#cmbfiltroprodutores').val(paramGET.produtor34).trigger("change");
		$('#cmbfiltropropriedade').val(paramGET.produtor34).trigger("change");
		$('#txtfiltrodata').val(paramGET.datainicial34).trigger("change");
		$('#txtfiltrodata2').val(paramGET.datafinal34).trigger("change");
		$('#txtfiltroloteapasentcafe').val(paramGET.datainicial34).trigger("change");
		$('#txtfiltrolotecoperativaentcafe').val(paramGET.datafinal34).trigger("change");
		
		$('#modal_filtros_ent_cafe').modal('show');
	});

	$('#btn_pesquisa_ent_cafe').click(function() {
				
		var produtor 	= $('#cmbfiltroprodutores').val();
		var propriedade = $('#cmbfiltropropriedade').val();
		var data 		= $('#txtfiltrodata').val();
		var data2 		= $('#txtfiltrodata2').val();
		var lote1 		= $('#txtfiltroloteapasentcafe').val();
		var lote2 		= $('#txtfiltrolotecoperativaentcafe').val();

		/* Recupera Parametros da Paginacao */
		var paramGET 	= $_GET();
		var pagina 		= paramGET.pagina;
		var registros 	= paramGET.registros;
		var tipo_msg 	= paramGET.tipo_msg;
		var msg 		= paramGET.msg;

		consultaentradascafe(pagina, registros, tipo_msg, msg, produtor, propriedade, data, data2, lote1, lote2);
	});

	$('#btn_limpar_pesquisa_ent_cafe').click(function() {
		$('#cmbfiltroprodutores').val("nenhum").trigger("change");
		$('#cmbfiltropropriedade').val("nenhum").trigger("change");
		$('#txtfiltrodata').val('').trigger("change");
		$('#txtfiltrodata2').val('').trigger("change");
		$('#txtfiltroloteapasentcafe').val('').trigger("change");
		$('#txtfiltrolotecoperativaentcafe').val('').trigger("change");
	});
	
	$("#cmbprodutor").change(function() {
		var idprodutor 	= $('#cmbprodutor').val();
		var obj 		= $(this);
		var selected 	= obj.data('param');
		var options 	= '<option value="">Selecione uma propriedade...</option>';

		$('#cmbpropriedade').val("").trigger("change");

		if (idprodutor != "" && idprodutor != undefined) 
		{
			$.ajax({
				type : "POST",
				url : "busca_propriedades_produtor.php",
				data : {
					'idprodutor' : idprodutor
				},
				dataType : 'json',
				success : function(data) {					
					if (data.length > 0) 
					{
						for (var i = 0; i < data.length; i++) 
						{
							options += "<option value='" + data[i].id_prop + "'>" + data[i].prop_nome + "</option>";
						}
					}

					$('#cmbpropriedade').html(options);

					if (selected != "" && selected != undefined) 
					{
						$('#cmbpropriedade').val(selected).trigger("change");
					}
				}
			});
		}
		else
		{
			$('#cmbpropriedade').html(options);
		}
	}).trigger('change');

	$(".percentualpeneiraentcafe").change(function() {
		var id 				= $(this).attr('id');
		var percentual 		= parseFloat($(this).val().replace(',', '.'));
		
		if(percentual>100)
		{
			bootbox.alert({
				title : 'Validação de Informações',
				message : 'Percentual não pode ser maior que 100!'
			});
			
			$(".percentualpeneiraentcafe").val("");
			
			return false;
		}
		
		var quantidade 		= parseFloat($('#txtquantidadeentcafe').val().replace(',', '.'));
		var sacaspeneira 	= ((quantidade * percentual) / 100);
		sacaspeneira 		= sacaspeneira.toFixed(2).toString().replace('.', ',');

		$("#" + id.replace('percentualpeneiraentcafe', 'sacaspeneiraentcafe')).val(sacaspeneira);
	});

	$('#txtquantidadeentcafe').change(function() {
		var contadorpeneiras = 0;

		while ($('#txtpercentualpeneiraentcafe' + contadorpeneiras).val() != undefined) 
		{
			var id 			= $('#txtpercentualpeneiraentcafe' + contadorpeneiras).attr('id');
			var percentual 	= parseFloat($('#txtpercentualpeneiraentcafe' + contadorpeneiras).val().replace(',', '.'));
			var quantidade 	= parseFloat($('#txtquantidadeentcafe').val().replace(',', '.'));

			if (percentual == "" || percentual == undefined) 
			{
				percentual = 0;
			}

			if (quantidade == "" || quantidade == undefined) 
			{
				quantidade = 0;
			}

			var sacaspeneira = ((quantidade * percentual) / 100);
			sacaspeneira = sacaspeneira.toFixed(2).toString().replace('.', ',');

			if (sacaspeneira != 'NaN') 
			{
				$("#" + id.replace('percentualpeneiraentcafe', 'sacaspeneiraentcafe')).val(sacaspeneira);
			}

			contadorpeneiras += 1;
		}
	});

	$("#tbl_entradas_cafe tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) 
			{
				cadastroentradacafe($(this).html());
			}
		});
	});

	$("#tbl_entradas_cafe span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") 
		{
			cadastroentradacafe($(this).attr('id'));
		} 
		else if (classe == "glyphicon glyphicon-trash") 
		{
			var identcafe = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Entrada de Café',
				message : "Deseja mesmo excluir esta entrada de café?",
				callback : function(result) {
					if (result) {
						acaoentradacafe = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_entrada_cafe.php",
							data : {
								'identcafe' 		: identcafe,
								'acaoentradacafe' 	: acaoentradacafe
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') 
								{
									var pagina 		= $_GET()["pagina"];
									var registros 	= $_GET()["registros"];

									consultaentradascafe(pagina, registros, 'sucesso', 'Entrada de cadé excluida com sucesso');
								} 
								else 
								{
									consultaentradascafe(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_entrada_cafe").click(function() {
		var identcafe 				= $('#txtidentcafe').val();
		var produtor 				= $('#cmbprodutor').val();
		var propriedade 			= $('#cmbpropriedade').val();
		var nomepropriedade 		= $('#cmbpropriedade option:selected').text();
		var dataentcafe 			= $('#txtdataentcafe').val();
		var loteapasentcafe 		= $('#txtloteapasentcafe').val();
		var lotecoperativaentcafe 	= $('#txtlotecoperativaentcafe').val();
		var quantidadeentcafe 		= $('#txtquantidadeentcafe').val();
		var umidadeentcafe 			= $('#txtumidadeentcafe').val();
		var bebidaentcafe 			= $('#txtbebidaentcafe').val();
		var obsevacaoentcafe 		= $('#txtobsevacaoentcafe').val();
		var tipomovimentacao 		= 'E';
		var produto 				= 1;
		var contadorpeneiras 		= 0;
		var listainformacoespeneira = [];
		var listacamposinvalidos 	= [];

		while($('#txtidpeneiraentcafe' + contadorpeneiras).val() != undefined)
		{
			var informacoespeneira						= new Object();
			informacoespeneira.idpeneiraentcafe 		= $('#txtidpeneiraentcafe' + contadorpeneiras).val();
			informacoespeneira.percentualpeneiraentcafe = $('#txtpercentualpeneiraentcafe' + contadorpeneiras).val();
			informacoespeneira.sacaspeneiraentcafe 		= $('#txtsacaspeneiraentcafe' + contadorpeneiras).val();

			listainformacoespeneira.push(informacoespeneira);

			contadorpeneiras += 1;
		}

		if (produtor == "" || produtor == undefined) 
		{
			listacamposinvalidos.push('- É necessario informar a produtor');
		}

		if (dataentcafe == "" || dataentcafe == undefined) 
		{
			listacamposinvalidos.push('- É necessario informar a data da movimentação');
		}
			
		if (propriedade == "" || propriedade == undefined) 
		{
			listacamposinvalidos.push('- É necessario informar a propriedade do produtor');
		}
		
		if (loteapasentcafe == "" || loteapasentcafe == undefined) 
		{
			listacamposinvalidos.push('- É necessario informar o lote APAS');
		}

		if (lotecoperativaentcafe == "" || lotecoperativaentcafe == undefined) 
		{
			listacamposinvalidos.push('- É necessario informar o lote COOPERVAS');
		}
		
		if (quantidadeentcafe == "" || quantidadeentcafe == undefined) 
		{
			listacamposinvalidos.push('- É necessario informar a quantidade de sacas de café');
		}
		
		if (umidadeentcafe == "" || umidadeentcafe == undefined) 
		{
			umidadeentcafe = 0;
		}
		
		if (bebidaentcafe == "" || bebidaentcafe == undefined) 
		{
			bebidaentcafe = 0;
		}
		
		if (listacamposinvalidos.length != 0) 
		{
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (identcafe == "" || identcafe == undefined) {
			acaoentradacafe = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_entrada_cafe.php",
				data : {
					'identcafe' 				: identcafe,
					'produtor' 					: produtor,
					'propriedade' 				: propriedade,
					'dataentcafe' 				: dataentcafe,
					'loteapasentcafe' 			: loteapasentcafe,
					'lotecoperativaentcafe' 	: lotecoperativaentcafe,
					'quantidadeentcafe' 		: quantidadeentcafe,
					'umidadeentcafe' 			: umidadeentcafe,
					'bebidaentcafe' 			: bebidaentcafe,
					'obsevacaoentcafe' 			: obsevacaoentcafe,
					'produto' 					: produto,
					'tipomovimentacao'			: tipomovimentacao,
					'listainformacoespeneira' 	: listainformacoespeneira,
					'acaoentradacafe' 			: acaoentradacafe
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') 
					{
						consultaentradascafe('', '', 'sucesso', 'Entrada de café adicionada com sucesso');
					}
					else if (data.msg == 'validacaoloteapas') {
						listacamposinvalidos.push("- Já existe um lote apas informado com esse número. ");
						alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
						return false;						
					}
					else if (data.msg == 'validacaolotecoperativa') {
						listacamposinvalidos.push("- Já existe um lote coopervas informado com esse número. ");
						alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
						return false;						
					}
					else 
					{
						listacamposinvalidos.push(data.msg);
						alert_danger('Erro!', listacamposinvalidos);
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Entrada de Café',
				message : "Deseja mesmo alterar esta Entrada de Café?",
				callback : function(result) {
					if (result) 
					{
						acaoentradacafe = 'editar';

						$.ajax({
							type : "POST",
							url : "set_entrada_cafe.php",
							data : {
								'identcafe' 				: identcafe,
								'produtor' 					: produtor,
								'propriedade' 				: propriedade,
								'dataentcafe' 				: dataentcafe,
								'loteapasentcafe' 			: loteapasentcafe,
								'lotecoperativaentcafe' 	: lotecoperativaentcafe,
								'quantidadeentcafe' 		: quantidadeentcafe,
								'umidadeentcafe' 			: umidadeentcafe,
								'bebidaentcafe' 			: bebidaentcafe,
								'obsevacaoentcafe' 			: obsevacaoentcafe,
								'produto' 					: produto,
								'tipomovimentacao' 			: tipomovimentacao,
								'listainformacoespeneira' 	: listainformacoespeneira,
								'acaoentradacafe' 			: acaoentradacafe
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') 
								{
									consultaentradascafe('', '', 'sucesso', 'Entrada de café alterada com sucesso');
								
								}
								else if (data.msg == 'validacaoloteapas') 
								{
									listacamposinvalidos.push("- Já existe um lote apas informado com esse número. ");
									alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
									return false;						
								}
								else if (data.msg == 'validacaolotecoperativa') 
								{
									listacamposinvalidos.push("- Já existe um lote coopervas informado com esse número. ");
									alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
									return false;						
								}
								else 
								{
									listacamposinvalidos.push(data.msg);
									alert_danger('Erro!', listacamposinvalidos);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_entrada_cafe").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) 
				{
					cadastroentradacafe();
				}
			}
		});
	});
	
	/** Venda de Café **/
	$('#btn_filtro_venda_cafe').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();
		$('#cmbfiltroprodutores').val(paramGET.produtor34).trigger("change");
		$('#cmbfiltropropriedade').val(paramGET.produtor34).trigger("change");
		$('#txtfiltrodata').val(paramGET.datainicial34).trigger("change");
		$('#txtfiltrodata2').val(paramGET.datafinal34).trigger("change");
		$('#txtfiltroloteapasentcafe').val(paramGET.datainicial34).trigger("change");
		$('#txtfiltrolotecoperativaentcafe').val(paramGET.datafinal34).trigger("change");
		
		$('#modal_filtros_venda_cafe').modal('show');
	});

	$('#btn_pesquisa_venda_cafe').click(function() {
				
		var produtor 	= $('#cmbfiltroprodutores').val();
		var propriedade = $('#cmbfiltropropriedade').val();
		var data 		= $('#txtfiltrodata').val();
		var data2 		= $('#txtfiltrodata2').val();
		var lote1 		= $('#txtfiltroloteapasentcafe').val();
		var lote2 		= $('#txtfiltrolotecoperativaentcafe').val();

		/* Recupera Parametros da Paginacao */
		var paramGET 	= $_GET();
		var pagina 		= paramGET.pagina;
		var registros 	= paramGET.registros;
		var tipo_msg 	= paramGET.tipo_msg;
		var msg 		= paramGET.msg;

		consultavendascafe(pagina, registros, tipo_msg, msg, produtor, propriedade, data, data2, lote1, lote2);
	});

	$('#btn_limpar_pesquisa_venda_cafe').click(function() {
		$('#cmbfiltroprodutores').val("nenhum").trigger("change");
		$('#cmbfiltropropriedade').val("nenhum").trigger("change");
		$('#txtfiltrodata').val('').trigger("change");
		$('#txtfiltrodata2').val('').trigger("change");
		$('#txtfiltroloteapasentcafe').val('').trigger("change");
		$('#txtfiltrolotecoperativaentcafe').val('').trigger("change");
	});
	$("#cmbprodutorvenda").change(function() {		
		var idprodutor = $('#cmbprodutorvenda').val();
		var obj = $(this);
		var selected = obj.data('param');
		var options = '<option value="">Selecione uma propriedade...</option>';

		$('#cmbpropriedadevenda').val("").trigger("change");

		if (idprodutor != "" && idprodutor != undefined) 
		{
			$.ajax({
				type : "POST",
				url : "busca_propriedades_produtor.php",
				data : {
					'idprodutor' : idprodutor
				},
				dataType : 'json',
				success : function(data) {
					if (data.length > 0) {
						for (var i = 0; i < data.length; i++) {
							options += "<option value='" + data[i].id_prop + "'>" + data[i].prop_nome + "</option>";
						}
					}

					$('#cmbpropriedadevenda').html(options);

					if (selected != "" && selected != undefined) {
						$('#cmbpropriedadevenda').val(selected).trigger("change");
					}
				}
			});
		}
		else
		{
			$('#cmbpropriedadevenda').html(options);
		}
	}).trigger('change');

	$("#cmbpropriedadevenda").change(function() {
		var idprodutor = $('#cmbprodutorvenda').val();
		var idpropriedade = $('#cmbpropriedadevenda').val();
		var obj = $(this);
		var selected = obj.data('param');
		var options = '<option value="">Selecione um lote APAS...</option>';
		var options2 = '<option value="">Selecione um lote COPERVAZ...</option>';
		
		$('#cmbloteapasvendacafe').val("").trigger("change");
		$('#cmblotecoperativavendacafe').val("").trigger("change");
		
		if(idprodutor == "" || idprodutor == undefined || idpropriedade == "" || idpropriedade == undefined )
		{
			$('#cmbloteapasvendacafe').html(options);
			$('#cmblotecoperativavendacafe').html(options2);
			return false;
		}
		
		if (idprodutor != "" && idprodutor != undefined) {
			$.ajax({
				type : "POST",
				url : "busca_lotes_propriedades_produtor.php",
				data : {
					'loteatual' : selected,
					'idprodutor' : idprodutor,
					'idpropriedade' : idpropriedade
				},
				dataType : 'json',
				success : function(data) {										
					if (data.length > 0) {
						for (var i = 0; i < data.length; i++) {
							options += "<option value='" + data[i].lotes_apas + "'>" + data[i].lotes_apas + "</option>";
							options2 += "<option value='" + data[i].lotes_apas + "'>" + data[i].lotes_coperativa + "</option>";
						}
					}
					
					$('#cmbloteapasvendacafe').html(options);
					$('#cmblotecoperativavendacafe').html(options2);
				
					if (selected != "" && selected != undefined) {
						$('#cmbloteapasvendacafe').val(selected).trigger("change");
						$('#cmblotecoperativavendacafe').val(selected).trigger("change");
					}
				}
			});
		}
	}).trigger('change');

	$('#cmbloteapasvendacafe').change(function(){
		var loteapas = $('#cmbloteapasvendacafe').val();
		var obj = $(this);
		var selected = obj.data('param');
		
		if(loteapas != $('#cmblotecoperativavendacafe').val())
		{
			consultainformacoeslote(loteapas,selected);
			$('#cmblotecoperativavendacafe').val(loteapas).trigger("change");
		}
	}).trigger('change');
	
	$('#cmblotecoperativavendacafe').change(function(){
		var lotecoperativa = $('#cmblotecoperativavendacafe').val();
		var obj = $(this);
		var selected = obj.data('param');
		
		if(lotecoperativa != $('#cmbloteapasvendacafe').val())
		{
			consultainformacoeslote(lotecoperativa,selected);			
			$('#cmbloteapasvendacafe').val(lotecoperativa).trigger("change");
		}
	}).trigger('change');
	
	$('#cmbpeneiravenda').change(function(){
		var peneiravenda = $('#cmbpeneiravenda').val();	
		consultainformacoespeneira(peneiravenda);			
	});
	
	$('#txtpercentualpeneiravendacafe').change(function(){
		calculasacasvenda();
	}).trigger('change');

	$('#txtvalorsacaspreparavendacafe').change(function(){
		calculavaloresvenda();
	}).trigger('change');
	
	$('#txtvalorsacasfundovendacafe').change(function(){
		calculavaloresvenda();
	}).trigger('change');

	$("#tbl_vendas_cafe tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrovendacafe($(this).html());
			}
		});
	});

	$("#tbl_vendas_cafe span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrovendacafe($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var idvendacafe = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Venda de Café',
				message : "Deseja mesmo excluir esta venda de café?",
				callback : function(result) {
					if (result) {
						acaovendacafe = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_venda_cafe.php",
							data : {
								'idvendacafe' : idvendacafe,
								'acaovendacafe' : acaovendacafe
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultavendascafe(pagina, registros, 'sucesso', 'Venda de cadé excluida com sucesso');
								} else {
									consultavendascafe(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_venda_cafe").click(function() {
		var idvendacafe = $('#txtidvendacafe').val();
		var produtorvenda = $('#cmbprodutorvenda').val();
		var propriedadevenda = $('#cmbpropriedadevenda').val();
		var descpropriedadevenda = $('#cmbpropriedadevenda option:selected').text();
		var datavendacafe = $('#txtdatavendacafe').val();
		var idloteapasvendacafe = $('#cmbloteapasvendacafe').val();
		var loteapasvendacafe = $("#cmbloteapasvendacafe option:selected").text();
		var idlotecoperativavendacafe = $('#cmblotecoperativavendacafe').val();
		var lotecoperativavendacafe = $("#cmblotecoperativavendacafe option:selected").text();
		var compradorvendacafe = $('#txtcompradorvendacafe').val();
		var bebidavendacafe = $('#txtbebidavendacafe').val();
		var tipovendacafe = 0; //$('#txttipovendacafe').val();
		var quantidadevendacafe = $('#txtquantidadevendacafe').val();
		var peneiravenda = $('#cmbpeneiravenda').val();
		var percentualpeneiravendacafe = $('#txtpercentualpeneiravendacafe').val();
		var sacasprontasvendacafe = $('#txtsacasprontasvendacafe').val();
		var sacasfundovendacafe = $('#txtsacasfundovendacafe').val();
		var valorsacaspreparavendacafe = $('#txtvalorsacaspreparavendacafe').val();
		var valorsacasfundovendacafe = $('#txtvalorsacasfundovendacafe').val();
		var valortotalvendacafe = $('#txtvalortotalvendacafe').val();
		var obsevacaovendacafe = $('#txtobsevacaovendacafe').val();		
		var fairtrade = $("#fairtrade:checked").val(); /** Pra SETAR $('#fairtrade').prop('checked', false); **/
		var tipomovimentacao = 'S';
		var produto = 1;
		var listacamposinvalidos = [];
				
		if (produtorvenda == "" || produtorvenda == undefined) {
			listacamposinvalidos.push('- É necessario informar o produtor');
		}

		if (propriedadevenda == "" || propriedadevenda == undefined) {
			listacamposinvalidos.push('- É necessario informar a propriedade do produtor');
		}
		
		if (datavendacafe == "" || datavendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar a data da venda');
		}

		if (idloteapasvendacafe == "" || idloteapasvendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar o lote APAS');
		}

		if (idlotecoperativavendacafe == "" || idlotecoperativavendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar o lote COOPERVAS');
		}
		
		if (quantidadevendacafe == "" || quantidadevendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar a quantidade de sacas de café');
		}
		
		if (tipovendacafe == "" || tipovendacafe == undefined) {
			umidadeentcafe = 0;
		}
		
		if (bebidavendacafe == "" || bebidavendacafe == undefined) {
			bebidaentcafe = 0;
		}
		
		if (peneiravenda == "" || peneiravenda == undefined) {
			listacamposinvalidos.push('- É necessario informar a peneira');
		}
		
		if (percentualpeneiravendacafe == "" || percentualpeneiravendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar a percentual da peneira');
		}
		
		if (sacasprontasvendacafe == "" || sacasprontasvendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar as sacas prontas');
		}
		
		if (sacasfundovendacafe == "" || sacasfundovendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar as sacas fundo');
		}
		
		if (valorsacaspreparavendacafe == "" || valorsacaspreparavendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar o valor da saca preparada');
		}
		
		if (valorsacasfundovendacafe == "" || valorsacasfundovendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar o valor da saca fundo');
		}
		
		if (valortotalvendacafe == "" || valortotalvendacafe == undefined) {
			listacamposinvalidos.push('- É necessario informar o valor total');
		}
		
		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if(fairtrade == undefined)
		{
			fairtrade = 0;
		}
		else
		{
			fairtrade = 1;
		}

		if (idvendacafe == "" || idvendacafe == undefined) {
			acaovendacafe = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_venda_cafe.php",
				data : {
							'idvendacafe' : idvendacafe,
							'produtorvenda' : produtorvenda,
							'propriedadevenda' : propriedadevenda,
							'descpropriedadevenda' : descpropriedadevenda,
							'datavendacafe' : datavendacafe,
							'loteapasvendacafe' : loteapasvendacafe,
							'lotecoperativavendacafe' : lotecoperativavendacafe,
							'compradorvendacafe' : compradorvendacafe,
							'bebidavendacafe' : bebidavendacafe,
							'tipovendacafe' : tipovendacafe,
							'quantidadevendacafe' : quantidadevendacafe,
							'peneiravenda' : peneiravenda,
							'percentualpeneiravendacafe' : percentualpeneiravendacafe,
							'sacasprontasvendacafe' : sacasprontasvendacafe,
							'sacasfundovendacafe' : sacasfundovendacafe,
							'valorsacaspreparavendacafe' : valorsacaspreparavendacafe,
							'valorsacasfundovendacafe' : valorsacasfundovendacafe,
							'valortotalvendacafe' : valortotalvendacafe,
							'obsevacaovendacafe' : obsevacaovendacafe,
							'tipomovimentacao' : tipomovimentacao,
							'produto' : produto,
							'fairtrade' : fairtrade,						
							'acaovendacafe' : acaovendacafe
						},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') 
					{
						consultavendascafe('', '', 'sucesso', 'Venda de café adicionada com sucesso');
					}					
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Venda de Café',
				message : "Deseja mesmo alterar esta venda de café?",
				callback : function(result) {
					if (result) {
						acaovendacafe = 'editar';

						$.ajax({
							type : "POST",
							url : "set_venda_cafe.php",
							data : {
										'idvendacafe' : idvendacafe,
										'produtorvenda' : produtorvenda,
										'propriedadevenda' : propriedadevenda,
										'descpropriedadevenda' : descpropriedadevenda,
										'datavendacafe' : datavendacafe,
										'loteapasvendacafe' : loteapasvendacafe,
										'lotecoperativavendacafe' : lotecoperativavendacafe,
										'compradorvendacafe' : compradorvendacafe,
										'bebidavendacafe' : bebidavendacafe,
										'tipovendacafe' : tipovendacafe,
										'quantidadevendacafe' : quantidadevendacafe,
										'peneiravenda' : peneiravenda,
										'percentualpeneiravendacafe' : percentualpeneiravendacafe,
										'sacasprontasvendacafe' : sacasprontasvendacafe,
										'sacasfundovendacafe' : sacasfundovendacafe,
										'valorsacaspreparavendacafe' : valorsacaspreparavendacafe,
										'valorsacasfundovendacafe' : valorsacasfundovendacafe,
										'valortotalvendacafe' : valortotalvendacafe,
										'obsevacaovendacafe' : obsevacaovendacafe,
										'tipomovimentacao' : tipomovimentacao,
										'produto' : produto,	
										'fairtrade' : fairtrade,					
										'acaovendacafe' : acaovendacafe
									},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') 
								{
									consultavendascafe('', '', 'sucesso', 'Venda de café alterada com sucesso');
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_venda_cafe").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrovendacafe();
				}
			}
		});
	});    

	/** Produtor **/
	$('#btn_filtros_produtor').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltroprodutores').val(paramGET.produtor).trigger("change");

		$('#modal_filtros_produtores').modal('show');
	});

	$('#btn_pesquisa_produtor').click(function() {
		var produtor = $('#cmbfiltroprodutores').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultaprodutor(pagina, registros, tipo_msg, msg, produtor);
	});

	$('#btn_limpar_pesquisa_produtor').click(function() {
		$('#cmbfiltroprodutores').val("nenhum").trigger("change");
	});

	$("#tbl_produtor tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastroprodutor($(this).html());
			}
		});
	});

	$("#tbl_produtor span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastroprodutor($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var idprodutor = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Produtor',
				message : "Deseja mesmo excluir este produtor?",
				callback : function(result) {
					if (result) {
						acaoprodutor = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_produtor.php",
							data : {
								'idprodutor' : idprodutor,
								'acaoprodutor' : acaoprodutor
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultaprodutor(pagina, registros, 'sucesso', 'Produtor excluido com sucesso');
								} else {
									consultaprodutor(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$('#txtcepprodutor').on('blur', function() {
		var cep = $('#txtcepprodutor').val();

		$.ajax({
			type : "POST",
			url : "http://cep.republicavirtual.com.br/web_cep.php?cep=" + cep + "&formato=json",
			dataType : 'json',
			success : function(data) {
				/* Comentado para quando quizer consultar informações no web service */
				/* console.log(data); */
				$('#txtenderecoprodutor').val(data.logradouro);
				$('#txtbairroprodutor').val(data.bairro);
				$('#txtcidadeprodutor').val(data.cidade);
				$('#txtufprodutor').val(data.uf);
			}
		});
	});

	$("#grava_produtor").click(function() {
		var idprodutor = $('#txtidprodutor').val();
		var nomeprodutor = $('#txtnomeprodutor').val();
		var documentoprodutor = $('#txtdocumentoprodutor').val();
		var emailprodutor = $('#txtemailprodutor').val();
		var telefoneprodutor = $('#txttelefoneprodutor').val();
		var enderecoprodutor = $('#txtenderecoprodutor').val();
		var numeroprodutor = $('#txtnumeroprodutor').val();
		var bairroprodutor = $('#txtbairroprodutor').val();
		var cidadeprodutor = $('#txtcidadeprodutor').val();
		var ufprodutor = $('#txtufprodutor').val();
		var cepprodutor = $('#txtcepprodutor').val();
		var prodbanco = $('#txtprodbanco').val();
		var prodagencia = $('#txtprodagencia').val();
		var prodconta = $('#txtprodconta').val();
		var proddescricaoconta = $('#txtproddescricaoconta').val();
		var listacamposinvalidos = [];

		if (nomeprodutor == "" || nomeprodutor == undefined) {
			listacamposinvalidos.push('- É necessario informar o nome do funcionário');
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idprodutor == "" || idprodutor == undefined) {
			acaoprodutor = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_produtor.php",
				data : {
					'idprodutor' : idprodutor,
					'nomeprodutor' : nomeprodutor,
					'documentoprodutor' : documentoprodutor,
					'emailprodutor' : emailprodutor,
					'telefoneprodutor' : telefoneprodutor,
					'enderecoprodutor' : enderecoprodutor,
					'numeroprodutor' : numeroprodutor,
					'bairroprodutor' : bairroprodutor,
					'cidadeprodutor' : cidadeprodutor,
					'ufprodutor' : ufprodutor,
					'cepprodutor' : cepprodutor,
					'prodbanco' : prodbanco,
					'prodagencia' : prodagencia,
					'prodconta' : prodconta,
					'proddescricaoconta' : proddescricaoconta,
					'acaoprodutor' : acaoprodutor
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultaprodutor('', '', 'sucesso', 'Produtor adicionado com sucesso');
					} else {
						listacamposinvalidos.push(data.msg);
						alert_danger('Erro!', listacamposinvalidos);
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Produtor',
				message : "Deseja mesmo alterar este produtor?",
				callback : function(result) {
					if (result) {
						acaoprodutor = 'editar';

						$.ajax({
							type : "POST",
							url : "set_produtor.php",
							data : {
								'idprodutor' : idprodutor,
								'nomeprodutor' : nomeprodutor,
								'documentoprodutor' : documentoprodutor,
								'emailprodutor' : emailprodutor,
								'telefoneprodutor' : telefoneprodutor,
								'enderecoprodutor' : enderecoprodutor,
								'numeroprodutor' : numeroprodutor,
								'bairroprodutor' : bairroprodutor,
								'cidadeprodutor' : cidadeprodutor,
								'ufprodutor' : ufprodutor,
								'cepprodutor' : cepprodutor,
								'prodbanco' : prodbanco,
								'prodagencia' : prodagencia,
								'prodconta' : prodconta,
								'proddescricaoconta' : proddescricaoconta,
								'acaoprodutor' : acaoprodutor
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultaprodutor('', '', 'sucesso', 'Produtor alterado com sucesso');
								} else {
									listacamposinvalidos.push(data.msg);
									alert_danger('Erro!', listacamposinvalidos);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_produtor").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastroprodutor();
				}
			}
		});
	});
	
	/** Propriedade **/
	$('#btn_filtros_propriedade').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltropropriedade').val(paramGET.propriedade).trigger("change");

		$('#modal_filtros_propriedade').modal('show');
	});

	$('#btn_pesquisa_propriedade').click(function() {
		var propriedade = $('#cmbfiltropropriedade').val();

		/* Recupera Parametros da Paginacao */
		var paramGET 	= $_GET();
		var pagina 		= paramGET.pagina;
		var registros 	= paramGET.registros;
		var tipo_msg 	= paramGET.tipo_msg;
		var msg 		= paramGET.msg;

		consultapropriedade(pagina, registros, tipo_msg, msg, propriedade);
	});

	$('#btn_limpar_pesquisa_propriedade').click(function() {
		$('#cmbfiltropropriedade').val("nenhum").trigger("change");
	});

	$('#relatorio_propriedade').click(function() {
		var relatorio = 'propriedades.php';
		var chama_rel = 'php_exporta_pdf/relatorios/' + relatorio;

		window.open(chama_rel);
	});

	$("#tbl_propriedade tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) 
			{
				cadastropropriedade($(this).html());
			}
		});
	});

	$("#tbl_propriedade span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") 
		{
			cadastropropriedade($(this).attr('id'));
		} 
		else if (classe == "glyphicon glyphicon-trash") 
		{
			var idpropriedade = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Propriedade',
				message : "Deseja mesmo excluir esta propriedade?",
				callback : function(result) {
					if (result) {
						acaopropriedade = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_propriedade.php",
							data : {
								'idpropriedade' 	: idpropriedade,
								'acaopropriedade' 	: acaopropriedade
							},
							dataType : 'json',
							success : function(data) {
								if(data.msg == 'sucesso') 
								{
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultapropriedade(pagina, registros, 'sucesso', 'Propriedade excluida com sucesso');
								} 
								else 
								{
									consultapropriedade(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_propriedade").click(function() {
		var idpropriedade = $('#txtidpropriedade').val();
		var nomepropriedade = $('#txtnomepropriedade').val();
		var iepropriedade = $('#txtiepropriedade').val();
		var areatotal = $('#txtareatotalpropriedade').val();
		var areacafe = $('#txtareacafepropriedade').val();
		var previsaosacas = $('#txtprevisaosacaspropriedade').val();
		var listacamposinvalidos = [];

		if (nomepropriedade == "" || nomepropriedade == undefined) {
			listacamposinvalidos.push('- É necessario informar o nome da propriedade');
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idpropriedade == "" || idpropriedade == undefined) {
			acaopropriedade = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_propriedade.php",
				data : {
					'idpropriedade' : idpropriedade,
					'nomepropriedade' : nomepropriedade,
					'iepropriedade' : iepropriedade,
					'areatotal' : areatotal,
					'areacafe' : areacafe,
					'previsaosacas' : previsaosacas,
					'acaopropriedade' : acaopropriedade
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultapropriedade('', '', 'sucesso', 'Propriedade adicionada com sucesso');
					} else {
						listacamposinvalidos.push(data.msg);
						alert_danger('Erro!', listacamposinvalidos);
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Propriedade',
				message : "Deseja mesmo alterar esta propriedade?",
				callback : function(result) {
					if (result) {
						acaopropriedade = 'editar';

						$.ajax({
							type : "POST",
							url : "set_propriedade.php",
							data : {
								'idpropriedade' : idpropriedade,
								'nomepropriedade' : nomepropriedade,
								'iepropriedade' : iepropriedade,
								'areatotal' : areatotal,
								'areacafe' : areacafe,
								'previsaosacas' : previsaosacas,
								'acaopropriedade' : acaopropriedade
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultapropriedade('', '', 'sucesso', 'Propriedade alterada com sucesso');
								} else {
									listacamposinvalidos.push(data.msg);
									alert_danger('Erro!', listacamposinvalidos);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_propriedade").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastropropriedade();
				}
			}
		});
	});

	/** Propriedades Produtor **/	
	$("#tbl_propriedades_produtor tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				verificapercentual = true;
				cadastropropriedadeprodutor($(this).html());
			}
		});
	});

	$("#tbl_propriedades_produtor span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			verificapercentual = true;
			cadastropropriedadeprodutor($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var idmovpp = $(this).attr('id');
			var produtor = $('#txtidprodutor').val();

			bootbox.confirm({
				title : 'Exclusão de Propriedade',
				message : "Deseja mesmo excluir esta propriedade?",
				callback : function(result) {
					if (result) {
						acaopropriedadeprodutor = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_propriedade_produtor.php",
							data : {
								'idmovpp' : idmovpp,
								'acaopropriedadeprodutor' : acaopropriedadeprodutor
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									cadastroprodutor(produtor, 'sucesso', 'Propriedade excluida com sucesso');
								} else {
									alert_danger_modal('Erro!', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$('#cmbpropriedade').change(function() {
		var idpropriedadeatual = $('#cmbpropriedadeatual').val();
		var idpropriedade = $('#cmbpropriedade').val();
		var idprodutor = $('#txtidprodutor').val();

		if (idpropriedadeatual != idpropriedade || verificapercentual == false) {
			verificapercentual = false;
			$('#txtpercentualpp').val("");
		}

		if (idpropriedade == "" || idpropriedade == undefined) {
			return false;
		}

		$.ajax({
			type : "POST",
			url : "get_verifica_propriedade_produtor.php",
			data : {
				'propriedade' : idpropriedade,
				'produtor' : idprodutor
			},
			dataType : 'json',
			success : function(verificadados) {
				var listacamposinvalidos = [];
				var contador = 0;

				if (verificadados.verifica1.totalregistros > 0 && (idpropriedadeatual != idpropriedade)) {
					listacamposinvalidos[contador] = "Esta propriedade já esta cadastrada para este produtor.";
					contador += 1;
				}

				if (verificadados.verifica2.totalpercentual >= 100) {
					listacamposinvalidos[contador] = "Limite de divisão dessa propriedade já foi atingido.";
				}

				if (listacamposinvalidos.length != 0) {
					alert_danger_modal('Erro!', listacamposinvalidos);
					$('#cmbpropriedade').val("").trigger("change");
					$('#txtpercentualpp').val("").replace(',', '.');
					;
					$('#txtarearealpp').val("").replace(',', '.');
					;
					$('#txtsacasrealpp').val("").replace(',', '.');
					;
					return false;
				} else {
					$.ajax({
						type : "POST",
						url : "get_propriedade.php",
						data : {
							'idpropriedade' : idpropriedade
						},
						dataType : 'json',
						success : function(data) {
							$('#txtareacafep').val(data.dados.prop_areatotalcafe);
							$('#txtprevisaosacasp').val(data.dados.prop_previsaosacas);

							var areacafep = $('#txtareacafep').val().replace(',', '.');
							var previsaosacasp = $('#txtprevisaosacasp').val().replace(',', '.');
							var percentualpp = $('#txtpercentualpp').val().replace(',', '.');

							var arearealpp = parseFloat((parseFloat(areacafep) * parseFloat(percentualpp)) / 100).toFixed(2);
							var sacasrealpp = parseFloat((parseFloat(previsaosacasp) * parseFloat(percentualpp)) / 100).toFixed(2);

							if (arearealpp == 'NaN')
								arearealpp = 0;
							if (sacasrealpp == 'NaN')
								sacasrealpp = 0;

							$('#txtarearealpp').val(arearealpp.toString().replace(".", ","));
							$('#txtsacasrealpp').val(sacasrealpp.toString().replace(".", ","));
						}
					});
				}
			}
		});
	});

	$('#txtpercentualpp').change(function() {
		var idpropriedade = $('#cmbpropriedade').val();
		var idprodutor = $('#txtidprodutor').val();
		var percentualpp = $('#txtpercentualpp').val().replace(',', '.');
		;
		var percentualppatual = $('#txtpercentualppatual').val().replace(',', '.');
		;

		if (percentualppatual == "" || percentualppatual == undefined) {
			percentualppatual = 0;
		}

		if (idpropriedade == "" || idpropriedade == undefined) {
			return false;
		}

		$.ajax({
			type : "POST",
			url : "get_verifica_propriedade_produtor.php",
			data : {
				'propriedade' : idpropriedade,
				'produtor' : idprodutor
			},
			dataType : 'json',
			success : function(verificadados) {
				var listacamposinvalidos = [];
				var contador = 0;

				if (parseFloat(parseFloat(verificadados.verifica2.totalpercentual) + parseFloat(percentualpp) - parseFloat(percentualppatual)) > 100 || parseFloat(percentualpp) > 100) {
					listacamposinvalidos[contador] = "Limite de divisão dessa propriedade será exedido.";
				}

				if (listacamposinvalidos.length != 0) {
					alert_danger_modal('Erro!', listacamposinvalidos);
					$('#txtpercentualpp').val("");
					$('#txtarearealpp').val("");
					$('#txtsacasrealpp').val("");
					return false;
				} else {
					var areacafep = $('#txtareacafep').val().replace(',', '.');
					var previsaosacasp = $('#txtprevisaosacasp').val().replace(',', '.');
					var arearealpp = ((parseFloat(areacafep) * parseFloat(percentualpp)) / 100).toFixed(2);
					var sacasrealpp = ((parseFloat(previsaosacasp) * parseFloat(percentualpp)) / 100).toFixed(2);

					if (arearealpp == 'NaN')
						arearealpp = 0;
					if (sacasrealpp == 'NaN')
						sacasrealpp = 0;

					$('#txtarearealpp').val(arearealpp.toString().replace(".", ","));
					$('#txtsacasrealpp').val(sacasrealpp.toString().replace(".", ","));
				}
			}
		});
	});

	$("#grava_propriedade_produtor").click(function() {
		var idmovpp = $('#txtidmovpp').val();
		var produtor = $('#txtidprodutor').val();
		var propriedade = $('#cmbpropriedade').val();
		var nomepropriedade = $("#cmbpropriedade option:selected").text();
		var percentualpp = $('#txtpercentualpp').val();
		var arearealpp = $('#txtarearealpp').val();
		var sacasrealpp = $('#txtsacasrealpp').val();
		var listacamposinvalidos = [];

		if (propriedade == "" || propriedade == undefined) {
			listacamposinvalidos.push('- É necessario informar a propriedade');
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger_modal('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (percentualpp == "" || percentualpp == undefined) {
			percentualpp = 0;
		}

		if (arearealpp == "" || arearealpp == undefined) {
			arearealpp = 0;
		}

		if (sacasrealpp == "" || sacasrealpp == undefined) {
			sacasrealpp = 0;
		}

		if (idmovpp == "" || idmovpp == undefined) {
			acaopropriedadeprodutor = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_propriedade_produtor.php",
				data : {
					'idmovpp' : idmovpp,
					'produtor' : produtor,
					'propriedade' : propriedade,
					'percentualpp' : percentualpp,
					'arearealpp' : arearealpp,
					'sacasrealpp' : sacasrealpp,
					'acaopropriedadeprodutor' : acaopropriedadeprodutor
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						cadastroprodutor(produtor, 'sucesso', 'Propriedade adicionada com sucesso');
					} else {
						listacamposinvalidos.push(data.msg);
						alert_danger_modal('Erro!', listacamposinvalidos);
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Propriedade',
				message : "Deseja mesmo alterar esta propriedade?",
				callback : function(result) {
					if (result) {
						acaopropriedadeprodutor = 'editar';

						$.ajax({
							type : "POST",
							url : "set_propriedade_produtor.php",
							data : {
								'idmovpp' : idmovpp,
								'produtor' : produtor,
								'propriedade' : propriedade,
								'percentualpp' : percentualpp,
								'arearealpp' : arearealpp,
								'sacasrealpp' : sacasrealpp,
								'acaopropriedadeprodutor' : acaopropriedadeprodutor
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									cadastroprodutor(produtor, 'sucesso', 'Propriedade alterada com sucesso');
								} else {
									listacamposinvalidos.push(data.msg);
									alert_danger_modal('Erro!', listacamposinvalidos);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_propriedade_produtor").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					$('#titulomodalpp').html("<i class='fa fa-fort-awesome'></i>&nbsp;&nbsp;Inclusão de Propriedade do Produtor");
					$('#txtidmovpp').val("");
					$('#cmbpropriedade').val("").trigger("change");
					$('#txtpercentualpp').val("");
					$('#txtarearealpp').val("");
					$('#txtsacasrealpp').val("");
					$('.caixa_mensagens_modal').html('');
					$('#modal-prop-prod').modal('show');
				}
			}
		});
	});

	$('#relatorio_produtor').click(function() {
		
		var paramGET = $_GET();
		$('#cmbrelfiltroprodutores').val(paramGET.produtores).trigger("change");		
		$('#modal_relatorio_produtor').modal('show');
		
		
	});
	
	$('#btn_relatorio_filtro_produtor').click(function() {
		
		var produtor = $('#cmbrelfiltroprodutores').val();
		
		if(produtor!=null)
		{	
			var relatorio = 'produtores.php';
			var chama_rel = 'php_exporta_pdf/relatorios/produtores_filtro.php?id_prod=' + produtor;
			window.open(chama_rel);			
		}
		else
		{					
			var relatorio = 'produtores.php';
			var chama_rel = 'php_exporta_pdf/relatorios/produtores.php';
			window.open(chama_rel);
		}
		
	});
	
	$('#btn_limpar_relatorio_produtor').click(function(){
	
	});

	/** Safras **/
	$('#btn_filtros_safras').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltroprodutores').val(paramGET.produtores).trigger("change");
		$('#txtfiltrosafra').val(paramGET.safra).trigger("change");

		$('#modal_filtros_safras').modal('show');
	});

	$('#btn_pesquisa_safras').click(function() {
		var produtor = $('#cmbfiltroprodutores').val();
		var safra = $('#txtfiltrosafra').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultasafras(pagina, registros, tipo_msg, msg, produtor, safra);
	});

	$('#btn_limpar_pesquisa_safras').click(function() {
		$('#cmbfiltroprodutores').val("nenhum").trigger("change");
		$('#txtfiltrosafra').val('').trigger("change");
	});

	$('#txtesp2').on('blur', function() {

		var esp1 = $('#txtesp1').val();
		var esp2 = $('#txtesp2').val();

		if (esp1 == "" || esp1 == undefined) {
			esp1 = 0;
		} else {
			esp1 = esp1.replace(',', '.');
		}

		if (esp2 == "" || esp2 == undefined) {
			esp2 = 0;
		} else {
			esp2 = esp2.replace(',', '.');
		}

		var total = esp1 * esp2;

		total = total.toFixed(2);

		/** Safras **/
		total = total.split(".");
		total = total.join(",");

		$('#txtarea').val(total);

	});

	$("#tbl_safras tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrosafras($(this).html());
			}
		});
	});

	$("#tbl_safras span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "fa fa-external-link") {
			cadastrosafras($(this).attr('id'));
		}
	});

	$("#tbl_safras tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrosafras($(this).html());
			}
		});
	});

	$("#tbl_safras span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrosafras($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Safra',
				message : "Deseja mesmo excluir esta safra?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_safras.php",
							data : {
								'id' : id,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultasafras(pagina, registros, 'sucesso', 'Safra excluida com sucesso');
								} else {
									consultasafras(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_safras").click(function() {
		var idsafra = $('#txtidsafra').val();
		var idprodutor = $('#cmbprodutor').val();
		var idpropriedade = $('#cmbpropriedade').val();
		var talhao = $('#txttalhao').val();
		var variedade = $('#txtvariedade').val();
		var esp1 = $('#txtesp1').val();
		var esp2 = $('#txtesp2').val();
		var area = $('#txtarea').val();
		var qtdplantas = $('#txtqtdplantas').val();
		var anoplantio = $('#txtanoplantio').val();
		var safra = $('#txtsafra').val();
		var previsao = $('#txtprevisao').val();
		var producao = $('#txtproducao').val();
		var listacamposinvalidos = [];

		if (idprodutor == "" || idprodutor == undefined) {
			listacamposinvalidos.push("- É necessario informar um produtor ");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idsafra == "" || idsafra == undefined) {
			acao = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_safras.php",
				data : {
					'id' : idsafra,
					'idprodutor' : idprodutor,
					'idpropriedade' : idpropriedade,
					'talhao' : talhao,
					'variedade' : variedade,
					'esp1' : esp1,
					'esp2' : esp2,
					'area' : area,
					'qtdplantas' : qtdplantas,
					'anoplantio' : anoplantio,
					'safra' : safra,
					'previsao' : previsao,
					'producao' : producao,
					'acao' : acao
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultasafras('', '', 'sucesso', 'Safra incluida com sucesso');
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Safra',
				message : "Deseja mesmo alterar esta safra?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'editar';

						$.ajax({
							type : "POST",
							url : "set_safras.php",
							data : {
								'id' : idsafra,
								'idprodutor' : idprodutor,
								'idpropriedade' : idpropriedade,
								'talhao' : talhao,
								'variedade' : variedade,
								'esp1' : esp1,
								'esp2' : esp2,
								'area' : area,
								'qtdplantas' : qtdplantas,
								'anoplantio' : anoplantio,
								'safra' : safra,
								'previsao' : previsao,
								'producao' : producao,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultasafras('', '', 'sucesso', 'Safra alterada com sucesso');
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_safras").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",

			callback : function(result) {
				if (result) {
					cadastrosafras();
				}
			}
		});
	});

	$('#relatorio_safras').click(function() {
		var relatorio = 'safras.php';
		var chama_rel = 'php_exporta_pdf/relatorios/' + relatorio;

		window.open(chama_rel);
	});

	/** PDCJ - Grupos Relatório **/
	$("#tbl_pdcj_grupos_rel tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrogruposrel($(this).html());
			}
		});
	});

	$("#tbl_pdcj_grupos_rel span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrogruposrel($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Grupo',
				message : "Deseja mesmo excluir este Grupo?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_pdcj_grupos_rel.php",
							data : {
								'idgrupo' : id,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultagruposrel(pagina, registros, 'sucesso', 'Grupo excluido com sucesso');
								} else {
									consultagruposrel(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_pdcj_grupos_rel").click(function() {
		var idgrupo = $('#txtidgrupo').val();
		var descricao = $('#txtdescricao').val();
		var listacamposinvalidos = [];

		if (descricao == "" || descricao == undefined) {
			listacamposinvalidos.push("- É necessario informar a descricao! ");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idgrupo == "" || idgrupo == undefined) {
			acao = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_pdcj_grupos_rel.php",
				data : {
					'idgrupo' : idgrupo,
					'descricao' : descricao,
					'acao' : acao

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultagruposrel('', '', 'sucesso', 'Grupo incluido com sucesso');
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Grupo',
				message : "Deseja mesmo alterar este Grupo?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'editar';

						$.ajax({
							type : "POST",
							url : "set_pdcj_grupos_rel.php",
							data : {
								'idgrupo' : idgrupo,
								'descricao' : descricao,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultagruposrel('', '', 'sucesso', 'Grupo alterado com sucesso');
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_pdcj_grupos_rel").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrogruposrel();
				}
			}
		});
	});

	/** PDCJ - Sub-Grupos Relatório **/
	$("#tbl_pdcj_subgrupos_rel tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrosubgruposrel($(this).html());
			}
		});
	});

	$("#tbl_pdcj_subgrupos_rel span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrosubgruposrel($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Sub-Grupo',
				message : "Deseja mesmo excluir este Sub-Grupo?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_pdcj_subgrupos_rel.php",
							data : {
								'idsubgrupo' : id,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultasubgruposrel(pagina, registros, 'sucesso', 'Sub-Grupo excluido com sucesso');
								} else {
									consultasubgruposrel(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_pdcj_subgrupos_rel").click(function() {
		var idsubgrupo = $('#txtidsubgrupo').val();
		var descricao = $('#txtdescricao').val();
		var idgrupo = $('#cmbgrupo').val();
		var listacamposinvalidos = [];

		if (descricao == "" || descricao == undefined) {
			listacamposinvalidos.push("- É necessario informar a descricao! ");
		}

		if (idgrupo == "" || idgrupo == undefined) {
			listacamposinvalidos.push("- É necessario informar um grupo! ");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idsubgrupo == "" || idsubgrupo == undefined) {
			acao = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_pdcj_subgrupos_rel.php",
				data : {
					'idsubgrupo' : idsubgrupo,
					'descricao' : descricao,
					'idgrupo' : idgrupo,
					'acao' : acao

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultasubgruposrel('', '', 'sucesso', 'Sub-Grupo incluido com sucesso');
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Sub-Grupo',
				message : "Deseja mesmo alterar este Sub-Grupo?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'editar';

						$.ajax({
							type : "POST",
							url : "set_pdcj_subgrupos_rel.php",
							data : {
								'idsubgrupo' : idsubgrupo,
								'descricao' : descricao,
								'idgrupo' : idgrupo,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultasubgruposrel('', '', 'sucesso', 'Sub-Grupo alterado com sucesso');
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_pdcj_grupos_rel").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrosubgruposrel();
				}
			}
		});
	});

	/** PDCJ - Grupos Relatório **/
	$("#tbl_pdcj_grupos_plan tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrogruposplan($(this).html());
			}
		});
	});

	$("#tbl_pdcj_grupos_plan span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrogruposplan($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Grupo',
				message : "Deseja mesmo excluir este Grupo?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_pdcj_grupos_plan.php",
							data : {
								'idgrupo' : id,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultagruposplan(pagina, registros, 'sucesso', 'Grupo excluido com sucesso');
								} else {
									consultagruposplan(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_pdcj_grupos_plan").click(function() {
		var idgrupo = $('#txtidgrupo').val();
		var descricao = $('#txtdescricao').val();
		var listacamposinvalidos = [];

		if (descricao == "" || descricao == undefined) {
			listacamposinvalidos.push("- É necessario informar a descricao! ");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idgrupo == "" || idgrupo == undefined) {
			acao = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_pdcj_grupos_plan.php",
				data : {
					'idgrupo' : idgrupo,
					'descricao' : descricao,
					'acao' : acao

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultagruposplan('', '', 'sucesso', 'Grupo incluido com sucesso');
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Grupo',
				message : "Deseja mesmo alterar este Grupo?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'editar';

						$.ajax({
							type : "POST",
							url : "set_pdcj_grupos_plan.php",
							data : {
								'idgrupo' : idgrupo,
								'descricao' : descricao,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultagruposplan('', '', 'sucesso', 'Grupo alterado com sucesso');
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_pdcj_grupos_plan").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrogruposplan();
				}
			}
		});
	});

	/** PDCJ - Lancamentos - Relatório - Orçamentos **/
	$('#btn_filtro_orcamentos').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltrogrupos').val(paramGET.grupo1).trigger("change");
		$('#cmbfiltrosubgrupos').val(paramGET.subgrupo2).trigger("change");
		$('#txtano').val(paramGET.ano1).trigger("change");

		$('#modal_filtros_orcamentos').modal('show');
	});

	$('#btn_pesquisa_orcamentos').click(function() {
		var grupo = $('#cmbfiltrogrupos').val();
		var subgrupo = $('#cmbfiltrosubgrupos').val();
		var ano = $('#txtano').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultalancamentosorcamentosrel(pagina, registros, tipo_msg, msg, grupo, subgrupo, ano);
	});

	$('#btn_limpar_pesquisa_orcamentos').click(function() {

		$('#cmbfiltrogrupos').val('nenhum').trigger("change");
		$('#cmbfiltrosubgrupos').val('nenhum').trigger("change");
		$('#txtano').val('').trigger("change");

	});

	$("#tbl_lancamentos_orcamentos_rel tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrolancamentosorcamentosrel($(this).html());
			}
		});
	});

	$("#tbl_lancamentos_orcamentos_rel span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrolancamentosorcamentosrel($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Grupo',
				message : "Deseja mesmo excluir este Orçamento?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_lancamentos_orcamentos_rel.php",
							data : {
								'idsubgrupovalores' : id,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultalancamentosorcamentosrel(pagina, registros, 'sucesso', 'Orçamento excluido com sucesso');
								} else {
									consultalancamentosorcamentosrel(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_lancamentos_orcamentos_rel").click(function() {
		var idsubgrupovalores = $('#txtidsubgvalores').val();
		var idgrupo = $('#cmbgrupolanc').val();
		var idsubgrupo = $('#cmbsubgrupolanc').val();
		var premio = $('#txtpremio').val();
		var outros = $('#txtoutros').val();
		var ano = $('#txtano').val();
		var avaliacao = $('#txtavaliacao').val();
		var listacamposinvalidos = [];

		/* editando campo valores, quando esta zerado...
		 pois quando seto o valor e nao edito o campo ele passa o cifrão junto*/

		premio = premio.replace('R$', '');
		outros = outros.replace('R$', '');

		if (premio == "" || premio == undefined) {
			premio = 0;
		}

		if (outros == "" || outros == undefined) {
			outros = 0;
		}

		if (idgrupo == "" || idgrupo == undefined) {
			listacamposinvalidos.push("- É necessario informar um Grupo! ");
		}

		if (idsubgrupo == "" || idsubgrupo == undefined) {
			listacamposinvalidos.push("- É necessario informar um Subgrupo! ");
		}

		if (ano == "" || ano == undefined) {
			listacamposinvalidos.push("- É necessario informar o ano! ");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idsubgrupovalores == "" || idsubgrupovalores == undefined) {
			acao = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_lancamentos_orcamentos_rel.php",
				data : {

					'idsubgrupovalores' : idsubgrupovalores,
					'idgrupo' : idgrupo,
					'idsubgrupo' : idsubgrupo,
					'premio' : premio,
					'outros' : outros,
					'ano' : ano,
					'avaliacao' : avaliacao,
					'acao' : acao

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultalancamentosorcamentosrel('', '', 'sucesso', 'Orçamento incluido com sucesso');
					} else if (data.msg == 'validacao') {
						consultalancamentosorcamentosrel('', '', 'erro', 'Existe um lançamento para esta data');
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Orçamento',
				message : "Deseja mesmo alterar este Orçamento?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'editar';

						$.ajax({
							type : "POST",
							url : "set_lancamentos_orcamentos_rel.php",
							data : {

								'idsubgrupovalores' : idsubgrupovalores,
								'idgrupo' : idgrupo,
								'idsubgrupo' : idsubgrupo,
								'premio' : premio,
								'outros' : outros,
								'ano' : ano,
								'avaliacao' : avaliacao,
								'acao' : acao

							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultalancamentosorcamentosrel('', '', 'sucesso', 'Orçamento alterado com sucesso');
								} else if (data.msg == 'validacao') {
									consultalancamentosorcamentosrel('', '', 'erro', 'Existe um lançamento para esta data');
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_lancamentos_orcamentos_rel").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrolancamentosorcamentosrel();
				}
			}
		});
	});

	$("#cmbgrupolanc").change(function() {
		var idgrupo = $('#cmbgrupolanc').val();
		var obj = $(this);
		var selected = obj.data('param');

		$('#cmbsubgrupolanc').val("").trigger("change");

		if (idgrupo != "" && idgrupo != undefined) {
			$.ajax({
				type : "POST",
				url : "busca_subgrupos_lancamentos.php",
				data : {
					'idgrupo' : idgrupo
				},
				dataType : 'json',
				success : function(data) {
					var options = '<option value=""> Selecione um Sub-Grupo...</option>';

					if (data.length > 0) {
						for (var i = 0; i < data.length; i++) {
							options += "<option value='" + data[i].id_subgrupo + "'>" + data[i].subg_descricao + "</option>";
						}
					}

					$('#cmbsubgrupolanc').html(options);

					if (selected != "" && selected != undefined) {
						$('#cmbsubgrupolanc').val(selected).trigger("change");
					}
				}
			});
		}
	}).trigger('change');

	/** PDCJ - Lancamentos - Relatório - Gastos **/
	/** Filtros de Detalhes **/
	$('#btn_filtro_movimentacao_gastos').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#txtfiltrodescricao').val(paramGET.descricao32).trigger("change");
		$('#txtfiltrodata').val(paramGET.datainicial32).trigger("change");
		$('#txtfiltrodata2').val(paramGET.datafinal32).trigger("change");

		$('#modal_filtros_movimentacao_gastos').modal('show');
	});

	$('#btn_pesquisa_movimentacao_gastos').click(function() {
		var idsubg = $('#txtfiltroidsubg').val();
		var descricao = $('#txtfiltrodescricao').val();
		var data = $('#txtfiltrodata').val();
		var data2 = $('#txtfiltrodata2').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		detalheslancamentosgastosrel(idsubg, pagina, registros, tipo_msg, msg, data, data2, descricao);
	});

	$('#btn_limpar_pesquisa_movimentacao_gastos').click(function() {

		$('#txtfiltrodescricao').val('').trigger("change");
		$('#txtfiltrodata').val('').trigger("change");
		$('#txtfiltrodata2').val('').trigger("change");

	});

	/** Filtros de Consulta **/
	$('#btn_filtro_gastos_consultas').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltrogrupos').val(paramGET.grupo2).trigger("change");
		$('#cmbfiltrosubgrupos').val(paramGET.subgrupo3).trigger("change");
		$('#txtano').val(paramGET.ano2).trigger("change");

		$('#modal_filtros_gastos_consulta').modal('show');
	});

	$('#btn_pesquisa_gastos_consulta').click(function() {
		var grupo = $('#cmbfiltrogrupos').val();
		var subgrupo = $('#cmbfiltrosubgrupos').val();
		var ano = $('#txtano').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultalancamentosgastosrel(pagina, registros, tipo_msg, msg, grupo, subgrupo, ano);
	});

	$('#btn_limpar_pesquisa_gastos_consulta').click(function() {

		$('#cmbfiltrogrupos').val('nenhum').trigger("change");
		$('#cmbfiltrosubgrupos').val('nenhum').trigger("change");
		$('#txtano').val('').trigger("change");

	});

	$("#btn_novoitemgasto").click(function() {
		cadastrolancamentosgastosrel($(this).attr('value'));
	});

	$("#tbl_lancamentos_gastos_rel tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				detalheslancamentosgastosrel($(this).html());
			}
		});
	});

	$("#tbl_lancamentos_gastos_rel span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "fa fa-external-link") {
			detalheslancamentosgastosrel($(this).attr('id_subgrupo_valores'));
		}

	});

	$("#tbl_detalhes_gastos_rel tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				$idsubg = $(this).html();
			}
			if (indice == 1) {
				$iditem = $(this).html();
				cadastrolancamentosgastosrel($idsubg, $iditem);
			}

		});
	});

	$("#tbl_detalhes_gastos_rel span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrolancamentosgastosrel($(this).attr('idsubgrupo'), $(this).attr('iditem'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('iditem');
			var idsubg = $(this).attr('idsubgrupo');

			bootbox.confirm({
				title : 'Exclusão de Grupo',
				message : "Deseja mesmo excluir este gasto?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_lancamentos_gastos_rel.php",
							data : {
								'iditem' : id,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									detalheslancamentosgastosrel(idsubg, pagina, registros, 'sucesso', 'Gasto excluido com sucesso');
								} else {
									detalheslancamentosgastosrel(idsubg, pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_lancamentos_gastos_rel").click(function() {
		var idsubgrupo = $('#txtidsubg').val();
		var iditem = $('#txtiditem').val();
		var valorpremio = $('#txtvalorpremio').val();
		var valoroutros = $('#txtvaloroutros').val();
		var descricao = $('#txtdescricao').val();
		var responsavel = $('#txtresponsavel').val();
		var data = $('#txtdata').val();
		var listacamposinvalidos = [];

		/* editando campo valores, quando esta zerado...
		 pois quando seto o valor e nao edito o campo ele passa o cifrão junto*/

		valorpremio = valorpremio.replace('R$', '');
		valoroutros = valoroutros.replace('R$', '');

		valorpremio = valorpremio.replace(',', '.');
		valoroutros = valoroutros.replace(',', '.');

		if (valoroutros == "" || valoroutros == undefined) {
			valoroutros = 0;
		}

		if (data == "" || data == undefined) {
			listacamposinvalidos.push("- É necessario informar uma data! ");
		}

		if (valorpremio == "" || valorpremio == undefined) {
			valorpremio = 0;
		}

		if (descricao == "" || descricao == undefined) {
			listacamposinvalidos.push("- É necessario informar uma descrição! ");
		}

		if (valorpremio > 0 && valoroutros > 0) {
			listacamposinvalidos.push("- Não pode informar dois valores, somente um lançamento por vez! ");

		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (iditem == "" || iditem == undefined) {
			acao = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_lancamentos_gastos_rel.php",
				data : {

					'idsubgrupo' : idsubgrupo,
					'iditem' : iditem,
					'valorpremio' : valorpremio,
					'valoroutros' : valoroutros,
					'descricao' : descricao,
					'responsavel' : responsavel,
					'data' : data,
					'acao' : acao

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						detalheslancamentosgastosrel(idsubgrupo, '', '', 'sucesso', 'Gasto incluido com sucesso');
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Orçamento',
				message : "Deseja mesmo alterar este Gasto?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'editar';

						$.ajax({
							type : "POST",
							url : "set_lancamentos_gastos_rel.php",
							data : {

								'idsubgrupo' : idsubgrupo,
								'iditem' : iditem,
								'valorpremio' : valorpremio,
								'valoroutros' : valoroutros,
								'descricao' : descricao,
								'responsavel' : responsavel,
								'data' : data,
								'acao' : acao

							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									detalheslancamentosgastosrel(idsubgrupo, '', '', 'sucesso', 'Gasto Alterado com sucesso');
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_lancamentos_gastos_rel").click(function() {
		var idsubg = $(this).attr('value');
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrolancamentosgastosrel(idsubg);
				}
			}
		});
	});

	/** PDCJ - Lancamentos - Planejamento - Orçamentos **/
	$("#tbl_lancamentos_orcamentos_plan tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrolancamentosorcamentosplan($(this).html());
			}
		});
	});

	$("#tbl_lancamentos_orcamentos_plan span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrolancamentosorcamentosplan($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Grupo',
				message : "Deseja mesmo excluir este Orçamento?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_lancamentos_orcamentos_plan.php",
							data : {
								'idgrupovalores' : id,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultalancamentosorcamentosplan(pagina, registros, 'sucesso', 'Orçamento excluido com sucesso');
								} else {
									consultalancamentosorcamentosplan(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_lancamentos_orcamentos_plan").click(function() {
		var idgrupovalores = $('#txtidgrupvalores').val();
		var idgrupo = $('#cmbgrupolancplan').val();
		var ano = $('#txtano').val();
		var objetivo = $('#txtobjetivo').val();
		var listacamposinvalidos = [];

		/* editando campo valores, quando esta zerado...
		 pois quando seto o valor e nao edito o campo ele passa o cifrão junto*/

		if (idgrupo == "" || idgrupo == undefined) {
			listacamposinvalidos.push("- É necessario informar um Grupo! ");
		}

		if (ano == "" || ano == undefined) {
			listacamposinvalidos.push("- É necessario informar o ano! ");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idgrupovalores == "" || idgrupovalores == undefined) {
			acao = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_lancamentos_orcamentos_plan.php",
				data : {

					'idgrupovalores' : idgrupovalores,
					'idgrupo' : idgrupo,
					'ano' : ano,
					'objetivo' : objetivo,
					'acao' : acao

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultalancamentosorcamentosplan('', '', 'sucesso', 'Orçamento incluido com sucesso');
					} else if (data.msg == 'validacao') {
						consultalancamentosorcamentosplan('', '', 'erro', 'Existe um lançamento para esta data');
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Orçamento',
				message : "Deseja mesmo alterar este Orçamento?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'editar';

						$.ajax({
							type : "POST",
							url : "set_lancamentos_orcamentos_plan.php",
							data : {

								'idgrupovalores' : idgrupovalores,
								'idgrupo' : idgrupo,
								'ano' : ano,
								'objetivo' : objetivo,
								'acao' : acao

							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultalancamentosorcamentosplan('', '', 'sucesso', 'Orçamento alterado com sucesso');
								} else if (data.msg == 'validacao') {
									consultalancamentosorcamentosplan('', '', 'erro', 'Existe um lançamento para esta data');
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_lancamentos_orcamentos_plan").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrolancamentosorcamentosplan();
				}
			}
		});
	});

	/** PDCJ - Recursos Planejamento **/
	$('#btn_filtro_recursos').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#txtfiltrodescricao').val(paramGET.descricao2).trigger("change");
		$('#txtfiltrodata').val(paramGET.datainicial).trigger("change");
		$('#txtfiltrodata2').val(paramGET.datafinal).trigger("change");

		$('#modal_filtros_recursos').modal('show');
	});

	$('#btn_pesquisa_recursos').click(function() {
		var descricao = $('#txtfiltrodescricao').val();
		var datainicial = $('#txtfiltrodatainicial').val();
		var datafinal = $('#txtfiltrodatafinal').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultalancamentosrecursosplan(pagina, registros, tipo_msg, msg, descricao, datainicial, datafinal);
	});

	$('#btn_limpar_pesquisa_recursos').click(function() {

		$('#txtfiltrodescricao').val('').trigger("change");
		$('#txtfiltrodatainicial').val('').trigger("change");
		$('#txtfiltrodatafinal').val('').trigger("change");

	});

	$("#tbl_lancamentos_recursos_plan tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrolancamentosrecursosplan($(this).html());
			}
		});
	});

	$("#tbl_lancamentos_recursos_plan span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrolancamentosrecursosplan($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Recurso',
				message : "Deseja mesmo excluir este Recurso?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_lancamento_recursos_plan.php",
							data : {
								'idrecursos' : id,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultalancamentosrecursosplan(pagina, registros, 'sucesso', 'Recurso excluido com sucesso');
								} else {
									consultalancamentosrecursosplan(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#grava_lancamentos_recursos_plan").click(function() {
		var idrecursos = $('#txtidrecursos').val();
		var descricao = $('#txtdescricao').val();
		var valor = $('#txtvalor').val();
		var qtd = $('#txtqtd').val();
		var data = $('#txtdata').val();

		valor = valor.replace('R$', '');
		
		var listacamposinvalidos = [];

		if (descricao == "" || descricao == undefined) {
			listacamposinvalidos.push("- É necessario informar a descricao! ");
		}

		if (valor == "" || valor == undefined) {
			listacamposinvalidos.push("- É necessario informar o valor! ");
		}

		if (qtd == "" || qtd == undefined) {
			listacamposinvalidos.push("- É necessario informar a quantidade! ");
		}

		if (data == "" || data == undefined) {
			listacamposinvalidos.push("- É necessario informar a data! ");
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idrecursos == "" || idrecursos == undefined) {
			acao = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_lancamento_recursos_plan.php",
				data : {
					'idrecursos' : idrecursos,
					'descricao' : descricao,
					'valor' : valor,
					'qtd' : qtd,
					'data' : data,
					'acao' : acao

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultalancamentosrecursosplan('', '', 'sucesso', 'Recurso incluido com sucesso');
					} else {

					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Recurso',
				message : "Deseja mesmo alterar este Recurso?",
				callback : function(resultado) {
					if (resultado) {
						acao = 'editar';

						$.ajax({
							type : "POST",
							url : "set_lancamento_recursos_plan.php",
							data : {
								'idrecursos' : idrecursos,
								'descricao' : descricao,
								'valor' : valor,
								'qtd' : qtd,
								'data' : data,
								'acao' : acao
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultalancamentosrecursosplan('', '', 'sucesso', 'Recurso alterado com sucesso');
								} else {

								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_lancamentos_recursos_plan").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrolancamentosrecursosplan();
				}
			}
		});
	});
	
	/** Exporta Relatórios PDCJ **/
	$('#btn_exp_relatorio_pdcj').click(function() {
		var relatorio = $('#cmbrelatorio').val();
		var ano = $('#txtanorel').val();
		var modalProgressBar = $('#modal-progress-bar');
		var progressBar = $("#progressBarExportaPDCJ");
		var url_relatorio = "";
		var nomerel = "";
		var listacamposinvalidos = [];

		if (relatorio == "" || relatorio == undefined) {
			listacamposinvalidos.push('- É necessario informar o relatório');
		}

		if (ano == "" || ano == undefined) {
			listacamposinvalidos.push('- É necessario informar o ano');
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		switch(relatorio) {
		case "Planilha PDCJ - Relatório":
			relatorio = 'pdcj_relatorio.php';
			nomerel = 'Planilha PDCJ - Relatório';
			break;

		case "Planilha PDCJ - Planejamento":
			relatorio = 'pdcj_planejamento.php';
			nomerel = 'Planilha PDCJ - Planejamento';
			break;
		}

		url_relatorio = 'php_exporta_excel/Relatorios/' + relatorio + '?';
		url_relatorio = url_relatorio + 'ano=' + ano;

		$('#labelProgressBar').html('EXPORTANDO ' + nomerel + ' - ' + ano);

		modalProgressBar.modal('show');

		$.ajax({
			type : 'POST',
			url : url_relatorio,
			xhrFields : {
				onprogress : function(e) {
					var data = e.currentTarget.response;

					for (var y = data.length; y >= 0; y--) {
						if (data.substr((y - 1), 1) == '|') {
							progressBar.width(data.substr(y) + '%').text(data.substr(y) + '%');
							y = -1;
						}
					}
				}
			},
			success : function(data) {
				modalProgressBar.modal('toggle');

				if (data.match('Nenhum registro a ser exportado')) {
					listacamposinvalidos.push('Nenhum registro a ser exportado');
					alert_danger('Atenção!', listacamposinvalidos);
				} else {

					switch(nomerel) {
					case "Planilha PDCJ - Relatório":
						window.open('php_exporta_excel/Relatorios/planilha-pdcj-relatorio-' + ano + '.xls', '_blank');
						break;

					case "Planilha PDCJ - Planejamento":
						window.open('php_exporta_excel/Relatorios/planilha-pdcj-planejamento-' + ano + '.xls', '_blank');
						break;
					}

				}
			},
			error : function(data) {
				modalProgressBar.modal('toggle');
				alert_danger('Erro! Problemas ao exportar relatório exel', data);
			}
		});
	});

	$('#btn_limpa_exp_relatorio_pdcj').click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					location.href = "tela_exporta_pdcj.php";
				}
			}
		});
	});
	
	$('#btn_exp_relatorio_cafe').click(function() {
		
		var rads = document.getElementsByName('tprel');
		var idprod = $('#cmbfiltroprodutorescafe').val();
		
		for (var i = 0; i < rads.length; i++) {
			if (rads[i].checked) {
				var tprel = rads[i].value;
			}
		}

		if (tprel == 1) 
		{
			var relatorio = 'qualidade_bebida.php';
			var chama_rel = 'php_exporta_pdf/relatorios/' + relatorio;						
		} 
		else if (tprel == 2) 
		{
			var relatorio = 'qualidade_bebida_prod.php';
			var chama_rel = 'php_exporta_pdf/relatorios/' + relatorio + '?produtor=' + idprod;			
		} 
		else if (tprel == 3) 
		{
			var relatorio = 'movimentacao_produtor.php';
			var chama_rel = 'php_exporta_pdf/relatorios/' + relatorio+'?id_prod='+idprod;
		}

		window.open(chama_rel);

	});
	
	$('#divcafe1').click(function() {        
    	document.getElementById('divcomboprod').style.display = "none";   
    });
    
    $('#divcafe2').click(function() {
        document.getElementById('divcomboprod').style.display = "block";
          
    });
    
    $('#divcafe3').click(function() {
        document.getElementById('divcomboprod').style.display = "block";
          
    });
    
    /** Produtos **/
	$('#btn_filtros_produtos').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltroprodutos').val(paramGET.produtor).trigger("change");

		$('#modal_filtros_produtos').modal('show');
	});

	$('#btn_pesquisa_produtos').click(function() {
		var produto = $('#cmbfiltroprodutos').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultaprodutos(pagina, registros, tipo_msg, msg, produtor);
	});

	$('#btn_limpar_pesquisa_produtos').click(function() {
		$('#cmbfiltroprodutors').val("nenhum").trigger("change");
	});

	$("#tbl_produtos tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastroprodutos($(this).html());
			}
		});
	});

	$("#tbl_produtos span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastroprodutos($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var idproduto = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Produto',
				message : "Deseja mesmo excluir este produto?",
				callback : function(result) {
					if (result) {
						acaoprodutos = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_produto.php",
							data : {
								'idproduto' : idproduto,
								'acaoprodutos' : acaoprodutos
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultaprodutos(pagina, registros, 'sucesso', 'Produto excluido com sucesso');
								} else {
									consultaprodutos(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});
	
	$("#grava_produtos").click(function() {
		var idproduto = $('#txtidproduto').val();
		var descricao = $('#txtdescricao').val();		
		var listacamposinvalidos = [];

		if (descricao == "" || descricao == undefined) {
			listacamposinvalidos.push('- É necessario informar a descrição do produto');
		}

		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idproduto == "" || idproduto == undefined) {
			acaoprodutos = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_produto.php",
				data : {
					'idproduto': idproduto,
					'descricao'	: descricao,
					'acaoprodutos' : acaoprodutos
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultaprodutos('', '', 'sucesso', 'Produto adicionado com sucesso');
					} else {
						listacamposinvalidos.push(data.msg);
						alert_danger('Erro!', listacamposinvalidos);
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Produto',
				message : "Deseja mesmo alterar este produto?",
				callback : function(result) {
					if (result) {
						acaoprodutos = 'editar';

						$.ajax({
							type : "POST",
							url : "set_produto.php",
							data : {
								'idproduto' : idproduto,
								'descricao' : descricao,								
								'acaoprodutos' : acaoprodutos
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultaprodutos('', '', 'sucesso', 'Produto alterado com sucesso');
								} else {
									listacamposinvalidos.push(data.msg);
									alert_danger('Erro!', listacamposinvalidos);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_produtos").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastroprodutos();
				}
			}
		});
	});


});

/** Cliente **/
	$('#btn_relatorio_cliente').click(function() {
		
		var relatorio = '';
		var chama_rel = '';

		window.open(chama_rel);

	});
	$('#btn_filtros_cliente').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltrocliente').val(paramGET.cliente).trigger("change");
		
		$('#modal_filtros_cliente').modal('show');
	});

	$('#btn_pesquisa_cliente').click(function() {
		var cliente = $('#cmbfiltrocliente').val();
		
		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultaclientes(pagina, registros, tipo_msg, msg, cliente);
	});

	$('#btn_limpar_pesquisa_cliente').click(function() {
		$('#cmbfiltrocliente').val("nenhum").trigger("change");		
	});

	$("#tbl_cliente tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastroclientes($(this).html());
			}
		});
	});

	
	$('#txtcepcliente').on('blur', function() {
		var cep = $('#txtcepcliente').val();

		$.ajax({
			type : "POST",
			url : "http://cep.republicavirtual.com.br/web_cep.php?cep=" + cep + "&formato=json",
			dataType : 'json',
			success : function(data) {
				/* Comentado para quando quizer consultar informações no web service */
				/* console.log(data); */
				$('#txtenderecocliente').val(data.logradouro);
				$('#txtbairrocliente').val(data.bairro);
				$('#txtcidadecliente').val(data.cidade);
				$('#txtufcliente').val(data.uf);
			}
		});
	});
	
	$("#tbl_cliente span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastroclientes($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var idcliente = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de Cliente',
				message : "Deseja mesmo excluir este cliente?",
				callback : function(result) {
					if (result) {
						acaocliente = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_cliente.php",
							data : {
								'idcliente' : idcliente,
								'acaocliente' : acaocliente
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultaclientes(pagina, registros, 'sucesso', 'Cliente excluido com sucesso');
								} else {
									consultaclientes(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});
	
	$("#grava_cliente").click(function() {
		var idcliente = $('#txtidcliente').val();
		var nomecliente = $('#txtnomecliente').val();
		var documentocliente = $('#txtdocumentocliente').val();
		var dtnasccliente = $('#txtdtnasccliente').val();
		var emailcliente = $('#txtemailcliente').val();
		var telefonecliente = $('#txttelefonecliente').val();		
		var enderecocliente = $('#txtenderecocliente').val();
		var numerocliente = $('#txtnumerocliente').val();
		var bairrocliente = $('#txtbairrocliente').val();
		var cidadecliente = $('#txtcidadecliente').val();
		var ufcliente = $('#txtufcliente').val();
		var cepcliente = $('#txtcepcliente').val();
		var listacamposinvalidos = [];

		if (nomecliente == "" || nomecliente == undefined) {
			listacamposinvalidos.push('- É necessario informar o nome do funcionário');
		}
		
		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idcliente == "" || idcliente == undefined) {
			acaocliente = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_cliente.php",
				data : {
					'idcliente' : idcliente,
					'nomecliente' : nomecliente,
					'documentocliente' : documentocliente,
					'dtnasccliente' : dtnasccliente,
					'emailcliente' : emailcliente,
					'telefonecliente' : telefonecliente,
					'enderecocliente' : enderecocliente,
					'numerocliente' : numerocliente,
					'bairrocliente' : bairrocliente,
					'cidadecliente' : cidadecliente,
					'ufcliente' : ufcliente,
					'cepcliente' : cepcliente,
					'acaocliente' : acaocliente
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultaclientes('', '', 'sucesso', 'Cliente adicionado com sucesso');
					} else {
						listacamposinvalidos.push(data.msg);
						alert_danger('Erro!', listacamposinvalidos);
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de Cliente',
				message : "Deseja mesmo alterar este cliente?",
				callback : function(result) {
					if (result) {
						acaocliente = 'editar';

						$.ajax({
							type : "POST",
							url : "set_cliente.php",
							data : {
								'idcliente' : idcliente,
								'nomecliente' : nomecliente,
								'documentocliente' : documentocliente,
								'dtnasccliente' : dtnasccliente,
								'emailcliente' : emailcliente,
								'telefonecliente' : telefonecliente,								
								'enderecocliente' : enderecocliente,
								'numerocliente' : numerocliente,
								'bairrocliente' : bairrocliente,
								'cidadecliente' : cidadecliente,
								'ufcliente' : ufcliente,
								'cepcliente' : cepcliente,
								'acaocliente' : acaocliente
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultaclientes('', '', 'sucesso', 'Cliente alterado com sucesso');
								} else {
									listacamposinvalidos.push(data.msg);
									alert_danger('Erro!', listacamposinvalidos);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_cliente").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastroclientes();
				}
			}
		});
	});
	
	/** fornecedor **/
	$('#btn_relatorio_fornecedor').click(function() {
		
		var relatorio = '';
		var chama_rel = '';

		window.open(chama_rel);

	});
	$('#btn_filtros_fornecedor').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltrofornecedor').val(paramGET.fornecedor).trigger("change");
		
		$('#modal_filtros_fornecedor').modal('show');
	});

	$('#btn_pesquisa_fornecedor').click(function() {
		var fornecedor = $('#cmbfiltrofornecedor').val();
		
		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultafornecedores(pagina, registros, tipo_msg, msg, fornecedor);
	});

	$('#btn_limpar_pesquisa_fornecedor').click(function() {
		$('#cmbfiltrofornecedor').val("nenhum").trigger("change");		
	});

	$("#tbl_fornecedor tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrofornecedores($(this).html());
			}
		});
	});

	
	$('#txtcepfornecedor').on('blur', function() {
		var cep = $('#txtcepfornecedor').val();

		$.ajax({
			type : "POST",
			url : "http://cep.republicavirtual.com.br/web_cep.php?cep=" + cep + "&formato=json",
			dataType : 'json',
			success : function(data) {
				/* Comentado para quando quizer consultar informações no web service */
				/* console.log(data); */
				$('#txtenderecofornecedor').val(data.logradouro);
				$('#txtbairrofornecedor').val(data.bairro);
				$('#txtcidadefornecedor').val(data.cidade);
				$('#txtuffornecedor').val(data.uf);
			}
		});
	});
	
	$("#tbl_fornecedor span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {
			cadastrofornecedores($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-trash") {
			var idfornecedor = $(this).attr('id');

			bootbox.confirm({
				title : 'Exclusão de fornecedor',
				message : "Deseja mesmo excluir este fornecedor?",
				callback : function(result) {
					if (result) {
						acaofornecedor = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_fornecedor.php",
							data : {
								'idfornecedor' : idfornecedor,
								'acaofornecedor' : acaofornecedor
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];

									consultafornecedores(pagina, registros, 'sucesso', 'fornecedor excluido com sucesso');
								} else {
									consultafornecedores(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});
	
	$("#grava_fornecedores").click(function() {
		var idfornecedor = $('#txtidfornecedor').val();
		var nomefornecedor = $('#txtnomefornecedor').val();
		var documentofornecedor = $('#txtdocumentofornecedor').val();		
		var emailfornecedor = $('#txtemailfornecedor').val();
		var telefonefornecedor = $('#txttelefonefornecedor').val();		
		var enderecofornecedor = $('#txtenderecofornecedor').val();
		var numerofornecedor = $('#txtnumerofornecedor').val();
		var bairrofornecedor = $('#txtbairrofornecedor').val();
		var cidadefornecedor = $('#txtcidadefornecedor').val();
		var uffornecedor = $('#txtuffornecedor').val();
		var cepfornecedor = $('#txtcepfornecedor').val();
		var listacamposinvalidos = [];

		if (nomefornecedor == "" || nomefornecedor == undefined) {
			listacamposinvalidos.push('- É necessario informar o nome do funcionário');
		}
		
		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idfornecedor == "" || idfornecedor == undefined) {
			acaofornecedor = 'adicionar';

			$.ajax({
				type : "POST",
				url : "set_fornecedor.php",
				data : {
					'idfornecedor' : idfornecedor,
					'nomefornecedor' : nomefornecedor,
					'documentofornecedor' : documentofornecedor,					
					'emailfornecedor' : emailfornecedor,
					'telefonefornecedor' : telefonefornecedor,
					'enderecofornecedor' : enderecofornecedor,
					'numerofornecedor' : numerofornecedor,
					'bairrofornecedor' : bairrofornecedor,
					'cidadefornecedor' : cidadefornecedor,
					'uffornecedor' : uffornecedor,
					'cepfornecedor' : cepfornecedor,
					'acaofornecedor' : acaofornecedor
				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultafornecedores('', '', 'sucesso', 'fornecedor adicionado com sucesso');
					} else {
						listacamposinvalidos.push(data.msg);
						alert_danger('Erro!', listacamposinvalidos);
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de fornecedor',
				message : "Deseja mesmo alterar este fornecedor?",
				callback : function(result) {
					if (result) {
						acaofornecedor = 'editar';

						$.ajax({
							type : "POST",
							url : "set_fornecedor.php",
							data : {
								'idfornecedor' : idfornecedor,
								'nomefornecedor' : nomefornecedor,
								'documentofornecedor' : documentofornecedor,								
								'emailfornecedor' : emailfornecedor,
								'telefonefornecedor' : telefonefornecedor,								
								'enderecofornecedor' : enderecofornecedor,
								'numerofornecedor' : numerofornecedor,
								'bairrofornecedor' : bairrofornecedor,
								'cidadefornecedor' : cidadefornecedor,
								'uffornecedor' : uffornecedor,
								'cepfornecedor' : cepfornecedor,
								'acaofornecedor' : acaofornecedor
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultafornecedores('', '', 'sucesso', 'fornecedor alterado com sucesso');
								} else {
									listacamposinvalidos.push(data.msg);
									alert_danger('Erro!', listacamposinvalidos);
								}
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_fornecedores").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrofornecedores();
				}
			}
		});
	});
	
	/** contaspagar a pagar**/
	$('#btn_filtros_contapagar').click(function() {
		/* Recupera Parametros de Filtros */
		var paramGET = $_GET();

		$('#cmbfiltrodescricao').val(paramGET.descricao).trigger("change");

		$('#modal_filtros_contaspagar').modal('show');
	});
	
	$('#btn_limpar_baixa_conta_pagar').click(function() {
		$('#txtdatabaixa').val("nenhum").trigger("change");
		$('#cmbconta_conta_pagar').val("nenhum").trigger("change");		
	});

	$('#btn_pesquisa_contapagar').click(function() {
		var descricao = $('#cmbfiltrodescricao').val();

		/* Recupera Parametros da Paginacao */
		var paramGET = $_GET();
		var pagina = paramGET.pagina;
		var registros = paramGET.registros;
		var tipo_msg = paramGET.tipo_msg;
		var msg = paramGET.msg;

		consultacontaspagar(pagina, registros, tipo_msg, msg, descricao);
	});

	$('#btn_limpar_pesquisa_contapagar').click(function() {
		$('#cmbfiltrodescricao').val("nenhum").trigger("change");
	});
		
	$("#tbl_contaspagar tr").dblclick(function() {
		$(this).find('td').each(function(indice) {
			if (indice == 0) {
				cadastrocontaspagar($(this).html());
			}
		});
	});

	$("#tbl_contaspagar span").click(function() {
		var classe = $(this).attr('class');

		if (classe == "glyphicon glyphicon-pencil") {			
			cadastrocontaspagar($(this).attr('id'));
		} else if (classe == "glyphicon glyphicon-check") {			
			var data = $(this).attr('databaixa');
			var idtmp = $(this).attr('id');				
			var paramGET = $_GET();
			
			
			if (data==null || data == "" || data == undefined)
			{
				document.getElementById('txtidcontapagarbaixa').value = idtmp;
				
				$('#cmbconta_conta_pagar').val(paramGET.conta).trigger("change");				
				$('#txtdatabaixa').val(paramGET.databaixa).trigger("change");
				$('#modal_baixa_contas_pagar').modal('show');	
			}
			else
			{	
				bootbox.alert("Conta a pagar já foi baixada!");	
			}			
			
		} else if (classe == "glyphicon glyphicon-trash") {
			var id = $(this).attr('id');			
			bootbox.confirm({
				title : 'Exclusão de contapagar',
				message : "Deseja mesmo excluir esta conta a pagar?",
				callback : function(resultado) {
					if (resultado) {
						acaocontaspagar = 'excluir';

						$.ajax({
							type : "POST",
							url : "set_conta_pagar.php",
							data : {
								'idconta' : id,
								'acaocontaspagar' : acaocontaspagar
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									var pagina = $_GET()["pagina"];
									var registros = $_GET()["registros"];
									consultacontaspagar(pagina, registros, 'sucesso', 'conta a pagar excluida com sucesso');
									
								} else {
									consultacontaspagar(pagina, registros, 'erro', data.msg);
								}
							}
						});
					}
				}
			});
		}
	});
	
	
	$("#btn_baixa_conta_pagar").click(function() {
		var idcontapagarbaixa 		= $('#txtidcontapagarbaixa').val();		
		var idcontabaixa	  		= $('#cmbconta_conta_pagar').val();
		var databaixa		 		= $('#txtdatabaixa').val();		
		var listacamposinvalidos = [];
						
		if (databaixa == "" || databaixa == undefined) {
			listacamposinvalidos.push("- É necessario informar a data de baixa! ");
		}
		
		if (idcontabaixa == "" || idcontabaixa == undefined) {
			listacamposinvalidos.push("- É necessario informar a conta para baixa! ");
		}
		
		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}
		
		$.ajax({
			type : "POST",
			url : "get_saldo_conta.php",
			data : 
			{
				'idconta' : idcontabaixa
			},
			dataType : 'json',
			success : function(data) 
			{
				var saldo_conta = data.dados.conta_saldo;
			}
		});
		
		
		
		if (idcontapagarbaixa != "" || idcontapagarbaixa != undefined) {
			acaocontaspagar = 'baixar';
			
			$.ajax({
				type : "POST",
				url : "	set_conta_pagar.php",
				data : {

					'idcontapagarbaixa' : idcontapagarbaixa,
					'databaixa'		: databaixa, 		
					'idcontabaixa' 	: idcontabaixa,	
					'saldoconta'	: saldo_conta,					
					'acaocontaspagar' : acaocontaspagar

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultacontaspagar('', '', 'sucesso', 'conta a pagar foi baixada com sucesso');
					}
				}
			});
		}		
	});
	
	$("#grava_contaspagar").click(function() {
		var idcontapagar 		= $('#txtidcontapagar').val();
		var fornecpagar 		= $('#cmbfornecedorpagar').val();
		var documentopagar 		= $('#txtdocumentopagar').val();
		var valorpagar 			= $('#txtvalorpagar').val();
		var dataaberturapagar 	= $('#txtdataaberturapagar').val();
		var datavencimentopagar = $('#txtdatavencimentopagar').val();
		var descricaopagar 		= $('#txtdescricaopagar').val();		
		var listacamposinvalidos = [];
		
		if (fornecpagar == "" || fornecpagar == undefined) {
			listacamposinvalidos.push("- É necessario informar o fornecedor! ");
		}
		
		if (valorpagar == "" || valorpagar == undefined) {
			listacamposinvalidos.push("- É necessario informar o o valor da conta! ");
		}
				
		if (dataaberturapagar == "" || dataaberturapagar == undefined) {
			listacamposinvalidos.push("- É necessario informar a data de abertura! ");
		}
		
		if (datavencimentopagar == "" || datavencimentopagar == undefined) {
			listacamposinvalidos.push("- É necessario informar a data de vencimento! ");
		}
		
		if (listacamposinvalidos.length != 0) {
			alert_danger('Erro! Existem campos invalidos:', listacamposinvalidos);
			return false;
		}

		if (idcontapagar == "" || idcontapagar == undefined) {
			acaocontaspagar = 'adicionar';

			$.ajax({
				type : "POST",
				url : "	set_conta_pagar.php",
				data : {

					'idcontapagar' 		: idcontapagar,
					'fornecpagar'		: fornecpagar, 		
					'documentopagar' 	: documentopagar,	
					'valorpagar' 		: valorpagar,
					'dataaberturapagar' : dataaberturapagar,
					'datavencimentopagar' : datavencimentopagar,
					'descricaopagar' 	: descricaopagar,
					'acaocontaspagar' : acaocontaspagar

				},
				dataType : 'json',
				success : function(data) {
					if (data.msg == 'sucesso') {
						consultacontaspagar('', '', 'sucesso', 'conta a pagar incluida com sucesso');
					}
				}
			});
		} else {
			bootbox.confirm({
				title : 'Alteração de conta a pagar',
				message : "Deseja mesmo alterar esta conta a pagar?",
				callback : function(resultado) {
					if (resultado) {
						acaocontaspagar = 'editar';

						$.ajax({
							type : "POST",
							url : "set_conta_pagar.php",
							data : {
								'idcontapagar' 		: idcontapagar,
								'fornecpagar'		: fornecpagar, 		
								'documentopagar' 	: documentopagar,	
								'valorpagar' 		: valorpagar,
								'dataaberturapagar' : dataaberturapagar,
								'datavencimentopagar' : datavencimentopagar,
								'descricaopagar' 	: descricaopagar,
								'acaocontaspagar' : acaocontaspagar
							},
							dataType : 'json',
							success : function(data) {
								if (data.msg == 'sucesso') {
									consultacontaspagar('', '', 'sucesso', 'conta a pagar alterada com sucesso');
								} 
							}
						});
					}
				}
			});
		}
	});

	$("#limpa_contaspagar").click(function() {
		bootbox.confirm({
			title : 'Confirmação para Limpar Campos',
			message : "Deseja mesmo limpar formulário?",
			callback : function(result) {
				if (result) {
					cadastrocontaspagar();
				}
			}
		});
	});

	$('#relatorio_contaspagar').click(function() {
		var relatorio = 'contaspagar.php';
		var chama_rel = 'php_exporta_pdf/relatorios/' + relatorio;

		window.open(chama_rel);
	});
