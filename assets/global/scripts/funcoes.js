/** Categorias **/
function consultacategoriasusuario(pagina, registros, tipo_msg, msg)
{
 	location.href = "consulta_categoria_usuario.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

function cadastrocategoriausuario(idcategoriausuario, tipo_msg, msg)
{
	location.href = "cadastro_categoria_usuario.php?id_categoria="+idcategoriausuario+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Contas **/
function consultacontas(pagina, registros, tipo_msg, msg, descricao)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(descricao == "" || descricao == undefined)
	{
		descricao = paramGET.descricao;
	}
	
 	location.href = "consulta_contas.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&descricao="+descricao;
}

function cadastrocontas(idconta, tipo_msg, msg)
{
	location.href = "cadastro_contas.php?id_conta="+idconta+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Financeiro - Movimentação **/
function consultamovimentacaocontas(pagina, registros, tipo_msg, msg)
{
 	location.href = "consulta_movimentacao_contas.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

function detalhemovimentacaocontas(idconta, pagina, registros, tipo_msg, msg, data, data2, descricao)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(data == "" || data == undefined)
	{
		data = paramGET.data;
	}
	
	if(data2 == "" || data2 == undefined)
	{
		data2 = paramGET.data2;
	}
	
	if(descricao == "" || descricao == undefined)
	{
		descricao = paramGET.descricao;
	}
	
 	location.href = "detalhe_movimentacao_contas.php?id_conta="+idconta+"&pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&data="+data+"&data2="+data2+"&descricao="+descricao;
}

function cadastromovimentacaocontas(idconta, idmov, tipo_msg, msg)
{
	location.href = "cadastro_movimentacao_contas.php?id_conta="+idconta+"&idmov="+idmov+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Funcionários **/
function consultafuncionarios(pagina, registros, tipo_msg, msg, funcionario, categoria)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(funcionario == "" || funcionario == undefined)
	{
		funcionario = paramGET.funcionario;
	}
	
	if(categoria == "" || categoria == undefined)
	{
		categoria = paramGET.categoria;
	}
	
 	location.href = "consulta_funcionario.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&funcionario="+funcionario+"&categoria="+categoria;
}

function cadastrofuncionario(idfuncionario, tipo_msg, msg)
{
	location.href = "cadastro_funcionario.php?id_funcionario="+idfuncionario+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Grupos Planejamento - PDCJ **/
function consultagruposplan(pagina, registros, tipo_msg, msg)
{
 	location.href = "consulta_pdcj_grupos_plan.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

function cadastrogruposplan(idgrupo, tipo_msg, msg)
{
	location.href = "cadastro_pdcj_grupos_plan.php?id_grupo="+idgrupo+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Grupos Relatório - PDCJ **/
function consultagruposrel(pagina, registros, tipo_msg, msg)
{
 	location.href = "consulta_pdcj_grupos_rel.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

function cadastrogruposrel(idgrupo, tipo_msg, msg)
{
	location.href = "cadastro_pdcj_grupos_rel.php?id_grupo="+idgrupo+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Sub-Grupos Relatório - PDCJ **/
function consultasubgruposrel(pagina, registros, tipo_msg, msg)
{
 	location.href = "consulta_pdcj_subgrupos_rel.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

function cadastrosubgruposrel(idsubgrupo, tipo_msg, msg)
{
	location.href = "cadastro_pdcj_subgrupos_rel.php?id_subgrupo="+idsubgrupo+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Lançamentos Orçamentos - PDCJ **/
function consultalancamentosorcamentosrel(pagina, registros, tipo_msg, msg, grupo, subgrupo, ano)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(grupo == "" || grupo == undefined)
	{
		grupo = paramGET.grupo;
	}
	if(subgrupo == "" || subgrupo == undefined)
	{
		subgrupo = paramGET.subgrupo;
	}
	if(ano == "" || ano == undefined)
	{
		ano = paramGET.ano;
	}
	
 	location.href = "consulta_lancamentos_orcamentos_rel.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&grupo="+grupo+"&subgrupo="+subgrupo+"&ano="+ano;
}

function cadastrolancamentosorcamentosrel(idsubgrupovalores, tipo_msg, msg)
{
	location.href = "cadastro_lancamentos_orcamentos_rel.php?id_subgrupo_valores="+idsubgrupovalores+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Lançamentos Recursos - PDCJ **/
function consultalancamentosrecursosplan(pagina, registros, tipo_msg, msg, descricao, datainicial, datafinal)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(descricao == "" || descricao == undefined)
	{
		descricao = paramGET.descricao;
	}
	if(datainicial == "" || datainicial == undefined)
	{
		datainicial = paramGET.datainicial;
	}
	if(datafinal == "" || datafinal == undefined)
	{
		datafinal = paramGET.datafinal;
	}
	
 	location.href = "consulta_lancamentos_recursos_plan.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&descricao="+descricao+"&datainicial="+datainicial+"&datafinal="+datafinal;
}

function cadastrolancamentosrecursosplan(idrecursos, tipo_msg, msg)
{
	location.href = "cadastro_lancamentos_recursos_plan.php?id_recursos="+idrecursos+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Lançamentos Gastos - PDCJ **/
function consultalancamentosgastosrel(pagina, registros, tipo_msg, msg, grupo, subgrupo, ano)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(grupo == "" || grupo == undefined)
	{
		grupo = paramGET.grupo;
	}
	if(subgrupo == "" || subgrupo == undefined)
	{
		subgrupo = paramGET.subgrupo;
	}
	if(ano == "" || ano == undefined)
	{
		ano = paramGET.ano;
	}
	
 	location.href = "consulta_lancamentos_gastos_rel.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&grupo="+grupo+"&subgrupo="+subgrupo+"&ano="+ano;
}

function detalheslancamentosgastosrel(idsubg, pagina, registros, tipo_msg, msg, data, data2, descricao)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(data == "" || data == undefined)
	{
		data = paramGET.data;
	}
	
	if(data2 == "" || data2 == undefined)
	{
		data2 = paramGET.data2;
	}
	
	if(descricao == "" || descricao == undefined)
	{
		descricao = paramGET.descricao;
	}
	
 	location.href = "detalhe_lancamentos_gastos_rel.php?id_subgrupo="+idsubg+"&pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&data="+data+"&data2="+data2+"&descricao="+descricao;
}

function cadastrolancamentosgastosrel(idsubg, iditem, tipo_msg, msg)
{
	location.href = "cadastro_lancamentos_gastos_rel.php?id_subgrupo="+idsubg+"&id_item="+iditem+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Lançamentos Orçamentos - PDCJ **/
function consultalancamentosorcamentosplan(pagina, registros, tipo_msg, msg)
{
 	location.href = "consulta_lancamentos_orcamentos_plan.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

function cadastrolancamentosorcamentosplan(idgrupovalores, tipo_msg, msg)
{
	location.href = "cadastro_lancamentos_orcamentos_plan.php?id_grupo_valores="+idgrupovalores+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Mensagens **/
function alert_danger(titulo_erros,erros)
{
	html = 	'<div class="alert alert-danger">' +
				'<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' +
				'<strong>' + titulo_erros + '</strong>';
						
	for(var y = 0; y < erros.length; y++)			
	{
		html += "</br>" + erros[y];
	}		
				
	html +=	'</div>';
	
	$('.caixa_mensagens').html(html);
	$('html,body').stop().animate({scrollTop: 0}, 400);
}

function alert_danger_modal(titulo_erros,erros)
{
	html = 	'<div class="alert alert-danger">' +
				'<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>' +
				'<strong>' + titulo_erros + '</strong>';
						
	for(var y = 0; y < erros.length; y++)			
	{
		html += "</br>" + erros[y];
	}		
				
	html +=	'</div>';
	
	$('.caixa_mensagens_modal').html(html);
	/* $('html,body').stop().animate({scrollTop: 0}, 400); */
}

/** Movimentação Entradas de Café **/
function consultaentradascafe(pagina, registros, tipo_msg, msg, produtor, propriedade, datainicial, datafinal, loteapas, lotecoopervas)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(produtor == "" || produtor == undefined)
	{
		produtor = paramGET.produtor;
	}
	
	if(propriedade == "" || propriedade == undefined)
	{
		propriedade = paramGET.propriedade;
	}
	
	if(datainicial == "" || datainicial == undefined)
	{
		datainicial = paramGET.datainicial;
	}
	
	if(datafinal == "" || datafinal == undefined)
	{
		datafinal = paramGET.datafinal;
	}
	
	if(loteapas == "" || loteapas == undefined)
	{
		loteapas = paramGET.loteapas;
	}
	
	if(lotecoopervas == "" || lotecoopervas == undefined)
	{
		lotecoopervas = paramGET.lotecoopervas;
	}
	
 	location.href = "consulta_entrada_cafe.php?pagina="+pagina+"&registros="+registros
 	+"&tipo_msg="+tipo_msg+"&msg="+msg+"&produtor="+produtor+"&propriedade="+propriedade
 	+"&datainicial="+datainicial+"&datafinal="+datafinal+"&loteapas="+loteapas
 	+"&lotecoopervas="+lotecoopervas;
}

function cadastroentradacafe(identradacafe, tipo_msg, msg)
{
	location.href = "cadastro_entrada_cafe.php?id_entrada_cafe="+identradacafe+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Movimentação Vendas de Café **/
function consultavendascafe(pagina, registros, tipo_msg, msg, produtor, propriedade, datainicial, datafinal, loteapas, lotecoopervas)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(produtor == "" || produtor == undefined)
	{
		produtor = paramGET.produtor;
	}
	
	if(propriedade == "" || propriedade == undefined)
	{
		propriedade = paramGET.propriedade;
	}
	
	if(datainicial == "" || datainicial == undefined)
	{
		datainicial = paramGET.datainicial;
	}
	
	if(datafinal == "" || datafinal == undefined)
	{
		datafinal = paramGET.datafinal;
	}
	
	if(loteapas == "" || loteapas == undefined)
	{
		loteapas = paramGET.loteapas;
	}
	
	if(lotecoopervas == "" || lotecoopervas == undefined)
	{
		lotecoopervas = paramGET.lotecoopervas;
	}
	
 	location.href = "consulta_venda_cafe.php?pagina="+pagina+"&registros="+registros
 	+"&tipo_msg="+tipo_msg+"&msg="+msg+"&produtor="+produtor+"&propriedade="+propriedade
 	+"&datainicial="+datainicial+"&datafinal="+datafinal+"&loteapas="+loteapas
 	+"&lotecoopervas="+lotecoopervas;
}

function cadastrovendacafe(identradacafe, tipo_msg, msg)
{
	location.href = "cadastro_venda_cafe.php?id_venda_cafe="+identradacafe+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

function calculasacasvenda()
{
	var quantidadevendacafe = $('#txtquantidadevendacafe').val().replace(',','.');
	var percentualpeneiravendacafe = $('#txtpercentualpeneiravendacafe').val().replace(',','.');	
	
	if(quantidadevendacafe == "" || quantidadevendacafe == undefined || percentualpeneiravendacafe == "" || percentualpeneiravendacafe == undefined)
	{
		$('#txtsacasprontasvendacafe').val("");
		$('#txtsacasfundovendacafe').val("");
		calculavaloresvenda();
		return false;
	}			
	
	var sacasprontasvendacafe = ((parseFloat(quantidadevendacafe) * parseFloat(percentualpeneiravendacafe))/100).toFixed(2);
	var sacasfundovendacafe = (quantidadevendacafe - sacasprontasvendacafe).toFixed(2);
	
	$('#txtsacasprontasvendacafe').val(sacasprontasvendacafe.toString().replace('.',','));
	$('#txtsacasfundovendacafe').val(sacasfundovendacafe.toString().replace('.',','));
	calculavaloresvenda();
}

function calculavaloresvenda()
{
	var sacasprontasvendacafe = $('#txtsacasprontasvendacafe').val().replace('.',',');
	var valorsacaspreparavendacafe = $('#txtvalorsacaspreparavendacafe').val().replace('.',',');
	var sacasfundovendacafe = $('#txtsacasfundovendacafe').val().replace('.',',');
	var valorsacasfundovendacafe = $('#txtvalorsacasfundovendacafe').val().replace('.',',');
	
	if(sacasprontasvendacafe == "" || sacasprontasvendacafe == undefined)
	{
		sacasprontasvendacafe = 0;
	}
	
	if(valorsacaspreparavendacafe == "" || valorsacaspreparavendacafe == undefined)
	{
		valorsacaspreparavendacafe = 0;
	}
	
	if(sacasfundovendacafe == "" || sacasfundovendacafe == undefined)
	{
		sacasfundovendacafe = 0;
	}
	
	if(valorsacasfundovendacafe == "" || valorsacasfundovendacafe == undefined)
	{
		valorsacasfundovendacafe = 0;
	}
			
	var valortotalvendacafe = ((parseFloat(sacasprontasvendacafe) * parseFloat(valorsacaspreparavendacafe)) + (parseFloat(sacasfundovendacafe) * parseFloat(valorsacasfundovendacafe))).toFixed(2);
	
	$('#txtvalortotalvendacafe').val(valortotalvendacafe.toString().replace('.',','));
}

function consultainformacoeslote(lote_atual,selected)
{
	var options = '<option value="">Selecione uma peneira...</option>';
	
	$('#cmbpeneiravenda').val("").trigger("change");
	
	if(lote_atual != "" && lote_atual != undefined)
	{
		$.ajax({
			type : "POST",
			url : "busca_informacoes_lotes.php",
			data : {
				'lote_atual' : lote_atual
			},
			dataType : 'json',
			success : function(data) {
				$('#txtquantidadevendacafe').val(data[0].quantidade.replace('.',','));		
				$('#txtbebidavendacafe').val(data[0].bebida.replace('.',','));		
				
				calculasacasvenda();	
				
				if (data.length > 0) 
				{
					for (var i = 0; i < data.length; i++) 
					{
						options += "<option value='" + data[i].id_mov_peneira + "'>" + data[i].descricao + "</option>";
					}
				}
				
				$('#cmbpeneiravenda').html(options);
			
				if (selected != "" && selected != undefined) 
				{
					$('#cmbpeneiravenda').val(selected).trigger("change");					
				}				
			}
		});
	}	
	else
	{
		$('#txtquantidadevendacafe').val("");
		$('#txtbebidavendacafe').val("");	
		$('#cmbpeneiravenda').html(options);	
		calculasacasvenda();	
	}		
}

function consultainformacoespeneira(peneira_atual)
{	
	if(peneira_atual != "" && peneira_atual != undefined)
	{
		$.ajax({
			type : "POST",
			url : "busca_informacoes_peneiras.php",
			data : {
				'peneira_atual' : peneira_atual
			},
			dataType : 'json',
			success : function(data) {
				$('#txtpercentualpeneiravendacafe').val(data[0].percentual_sacas.replace('.',','));		
				calculasacasvenda();	
			}
		});
	}	
	else
	{
		$('#txtpercentualpeneiravendacafe').val("");		
		calculasacasvenda();	
	}		
}

/** Pega Valores da URL Metodo GET **/
function $_GET()
{
    var vars 	= [], hash;
    var hashes	 = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
   
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    
    return vars;
}

/** Produtores **/
function consultaprodutor(pagina, registros, tipo_msg, msg, produtor)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(produtor == "" || produtor == undefined)
	{
		produtor = paramGET.produtor;
	}
	
 	location.href = "consulta_produtor.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&produtor="+produtor;
}

function cadastroprodutor(idprodutor, tipo_msg, msg)
{
	location.href = "cadastro_produtor.php?id_produtor="+idprodutor+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

function cadastropropriedadeprodutor(idpropriedadeprodutor)
{	
	if(idpropriedadeprodutor != "" && idpropriedadeprodutor != undefined)
	{
		$.ajax({
			type: "POST",
			url: "get_propriedade_produtor.php",
			data: { 
					'idpropriedadeprodutor'	: idpropriedadeprodutor 
				  },
			dataType: 'json',
			success: function(data){
				if (data.msg == 'sucesso')
                {      
                	$('#titulomodalpp').html("<i class='fa fa-fort-awesome'></i>&nbsp;&nbsp;Alteração de Propriedade do Produtor");         
                	$('#txtidmovpp').val(data.dados.id_mov_pp);
                	$('#cmbpropriedadeatual').val(data.dados.fk_id_prop);
                	$('#cmbpropriedade').val(data.dados.fk_id_prop).trigger("change");
                	$('#txtpercentualppatual').val(data.dados.mov_pp_percentual);
                	$('#txtpercentualpp').val(data.dados.mov_pp_percentual);                	
                	$('#txtarearealpp').val(data.dados.mov_pp_sacasreal);
                	$('#txtsacasrealpp').val(data.dados.mov_pp_areareal);
                	$('.caixa_mensagens_modal').html('');
                	$('#modal-prop-prod').modal('show');    
                }
			}
		});
	}
	else
    {
    	$('#titulomodalpp').html("<i class='fa fa-fort-awesome'></i>&nbsp;&nbsp;Inclusão de Propriedade do Produtor");
    	$('#txtidmovpp').val("");
    	$('#cmbpropriedadeatual').val("");
    	$('#cmbpropriedade').val("").trigger("change");
    	$('#txtpercentualatualpp').val("");
    	$('#txtpercentualpp').val("");
    	$('#txtarearealpp').val("");
    	$('#txtsacasrealpp').val("");
    	$('.caixa_mensagens_modal').html('');
    	$('#modal-prop-prod').modal('show');    
	}
}

/** Propriedades **/
function consultapropriedade(pagina, registros, tipo_msg, msg, propriedade)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(propriedade == "" || propriedade == undefined)
	{
		propriedade = paramGET.propriedade;
	}
	
 	location.href = "consulta_propriedade.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&propriedade="+propriedade;
}

function cadastropropriedade(id_propriedade, tipo_msg, msg)
{
	location.href = "cadastro_propriedade.php?id_propriedade="+id_propriedade+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Safras **/
function consultasafras(pagina, registros, tipo_msg, msg, produtor,safra)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(produtor == "" || produtor == undefined)
	{
		produtor = paramGET.produtor;
	}
	
	if(safra == "" || safra == undefined)
	{
		safra = paramGET.safra;
	}
	
 	location.href = "consulta_safras.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&produtor="+produtor+"&safra="+safra;
}

function cadastrosafras(id_safra, tipo_msg, msg)
{
	location.href = "cadastro_safras.php?id_safra="+id_safra+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Produtos **/
function consultaprodutos(pagina, registros, tipo_msg, msg, produto)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(produto == "" || produto == undefined)
	{
		produto = paramGET.produto;
	}
	
	
 	location.href = "consulta_produtos.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&produto="+produto;
}

function cadastroprodutos(idproduto, tipo_msg, msg)
{
	location.href = "cadastro_produtos.php?id_produto="+idproduto+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Clientes **/
function consultaclientes(pagina, registros, tipo_msg, msg, cliente)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(cliente == "" || cliente == undefined)
	{
		cliente = paramGET.cliente;
	}

	
 	location.href = "consulta_clientes.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&cliente="+cliente;
}

function cadastroclientes(idcliente, tipo_msg, msg)
{
	location.href = "cadastro_clientes.php?id_cliente="+idcliente+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Fornecedores **/
function consultafornecedores(pagina, registros, tipo_msg, msg, fornecedor)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(fornecedor == "" || fornecedor == undefined)
	{
		fornecedor = paramGET.fornecedor;
	}

	
 	location.href = "consulta_fornecedores.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&fornecedor="+fornecedor;
}

function cadastrofornecedores(idfornecedor, tipo_msg, msg)
{
	location.href = "cadastro_fornecedores.php?id_fornecedor="+idfornecedor+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Contas a Pagar **/
function consultacontaspagar(pagina, registros, tipo_msg, msg, descricao)
{
	/* Recupera Parametros de Filtros */
	/* OBS: So se estiverem vazios */
	var paramGET = $_GET();
	
	if(descricao == "" || descricao == undefined)
	{
		descricao = paramGET.descricao;
	}
	
 	location.href = "consulta_conta_pagar.php?pagina="+pagina+"&registros="+registros+"&tipo_msg="+tipo_msg+"&msg="+msg+"&descricao="+descricao;
}

function cadastrocontaspagar(idcontapagar, tipo_msg, msg)
{
	location.href = "cadastro_conta_pagar.php?id_conta_pagar="+idcontapagar+"&tipo_msg="+tipo_msg+"&msg="+msg;
}

/** Sair do Sistema **/
function sairdosistema()
{
	
	window.close();
}