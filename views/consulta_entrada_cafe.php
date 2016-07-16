<?php

date_default_timezone_set('America/Sao_Paulo');

include_once ('verificaLogin.php');
include_once ('verificapermissao.php');

//if(!verificapermissao("Funcionários",$permissoes))
//{
//	header("Location: home.php");
//	exit;
//}

include_once ('connect.php');

global $mysqli;

if (!$mysqli -> set_charset("utf8")) {
	printf("Error loading character set utf8: %s\n", $mysqli -> error);
}

/** INICIO FILTROS **/
$filtros = "AND a.fk_produto = 1 AND a.tipo_movimentacao = 'E'";

if (isset($_GET["produtor"])) {
	if (!empty($_GET["produtor"]) && $_GET["produtor"] != "undefined" && $_GET["produtor"] != "nenhum") {
		$filtros = $filtros . " AND c.id_prod = " . $_GET["produtor"] . " ";
	}
}

if (isset($_GET["propriedade"])) {
	if (!empty($_GET["propriedade"]) && $_GET["propriedade"] != "undefined" && $_GET["propriedade"] != "nenhum") {
		$filtros = $filtros . " AND d.id_prop = " . $_GET["propriedade"] . " ";
	}
}

if (isset($_GET["datainicial"])) {
	if (!empty($_GET["datainicial"]) && $_GET["datainicial"] != "undefined" && $_GET["datainicial"] != "nenhum") {
		$filtros = $filtros . " AND a.data  >= '" . implode("-", array_reverse(explode("/", ($_GET["datainicial"])))) . "' ";
	}
}

if (isset($_GET["datafinal"])) {
	if (!empty($_GET["datafinal"]) && $_GET["datafinal"] != "undefined" && $_GET["datafinal"] != "nenhum") {
		$filtros = $filtros . " AND a.data  <= '" . implode("-", array_reverse(explode("/", ($_GET["datafinal"])))) . "' ";
	}
}

if (isset($_GET["loteapas"])) {
	if (!empty($_GET["loteapas"]) && $_GET["loteapas"] != "undefined" && $_GET["loteapas"] != "nenhum") {
		$filtros = $filtros . " AND a.lote_apas = " . $_GET["loteapas"] . " ";
	}
}

if (isset($_GET["lotecoopervas"])) {
	if (!empty($_GET["lotecoopervas"]) && $_GET["lotecoopervas"] != "undefined" && $_GET["lotecoopervas"] != "nenhum") {
		$filtros = $filtros . " AND a.lote_coperativa = " . $_GET["lotecoopervas"] . " ";
	}
}

/** FIM FILTROS **/

$pagina = 1;

if (isset($_GET['pagina'])) {
	if (!empty($_GET['pagina']) && $_GET['pagina'] != "undefined") {
		$pagina = $_GET['pagina'];
	}
}

$registros = 10;

if (isset($_GET['registros'])) {
	if (!empty($_GET['registros']) && $_GET['registros'] != "undefined") {
		$registros = $_GET['registros'];
	}
}

$inicial = ($pagina - 1) * $registros;
$rs = $mysqli -> query('SELECT count(*)
							  	  FROM mov_razao_produtos a
							      INNER JOIN mov_prop_prod b ON a.fk_mov_pp = b.id_mov_pp
							      INNER JOIN tb_produtor c ON b.fk_id_prod = c.id_prod
							      INNER JOIN tb_propriedade d ON b.fk_id_prop = d.id_prop 
							      WHERE fk_produto = 1 AND tipo_movimentacao = "E" ' . $filtros);

if ($rs) {
	$totalregistros = $rs -> fetch_row();
	$totalregistros = $totalregistros[0];
	$divisaoregistros = $totalregistros / 5;
	$partefracionada = $divisaoregistros - floor($divisaoregistros);
	$parteinteira = floor($divisaoregistros);

	if ($partefracionada > 0.0) {
		$divisaoregistros = $parteinteira * 5 + 5;
	} else {
		$divisaoregistros = $parteinteira * 5;
	}

	$totalpaginas = $divisaoregistros / $registros;
	$partefracionadapag = $totalpaginas - floor($totalpaginas);
	$parteinteirapag = floor($totalpaginas);

	if ($partefracionadapag > 0.0) {
		$totalpaginas = $parteinteirapag + 1;
	} else {
		$totalpaginas = $parteinteirapag;
	}
}

if ($inicial < 0) {
	$inicial = 0;
	$pagina = 1;
}

$sql = "SELECT a.id,  
			   date_format(a.data,'%d/%m/%Y') AS data,
			   c.prod_nome, d.prop_nome, 
			   a.lote_apas,a.lote_coperativa, a.quantidade, a.saldo_produtor_propriedade
			   FROM mov_razao_produtos a
			   INNER JOIN mov_prop_prod b ON a.fk_mov_pp = b.id_mov_pp
			   INNER JOIN tb_produtor c ON b.fk_id_prod = c.id_prod
			   INNER JOIN tb_propriedade d ON b.fk_id_prop = d.id_prop 
			   WHERE fk_produto = 1 AND tipo_movimentacao = 'E' $filtros			   
			   ORDER BY c.prod_nome ASC, d.prop_nome ASC, a.data DESC
			   limit $inicial, $registros";

$rs = $mysqli -> query($sql);

if ($rs) {
	$entradas_cafe = array();

	while ($entrada_cafe = $rs -> fetch_object()) {
		array_push($entradas_cafe, $entrada_cafe);
	}

	$rs -> close();
}
?>

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
	<div class="page-content">

		<!-- BEGIN MENSAGENS-->
		<?php
		if(isset($_GET['msg']))
		{
		if(!empty($_GET['msg']))
		{
		if($_GET['tipo_msg'] == "sucesso")
		{
		?>

		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
			<strong>Sucesso!</strong>
			<?php echo $_GET['msg']; ?>
		</div>

		<?php
		}
		else if($_GET['tipo_msg'] == "erro")
		{
		?>

		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
			<strong>Erro!</strong>
			<?php echo $_GET['msg']; ?>
		</div>

		<?php
		}
		}
		}
		?>
		<!-- END MENSAGENS-->

		<!-- BEGIN PAGE HEADER-->
		<div class="page-bar">
			<ul class="page-breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<a href="inicio.php">Início</a>
					<i class="fa fa-angle-right"></i>
				</li>

				<li>
					Movimentação de Café
					<i class="fa fa-angle-right"></i>
				</li>

				<li>
					<a href="consulta_entrada_cafe.php">Entradas de Café</a>
				</li>
			</ul>

			<div class="page-toolbar">
				<div class="btn-toolbar">
					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height default green-jungle" id="btn_filtro_ent_cafe">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar
						</button>
					</div>

					<div class="btn-group pull-right">
						<button style="width: 110px" type="button" class="btn btn-fit-height green-jungle" onclick="cadastroentradacafe()">
							<i class="fa fa-plus"></i>
							&nbsp;Novo
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE HEADER-->

		<!-- BEGIN PAGE CONTENT-->
		<div class="row">
			<div class="col-md-12">

				<!-- BEGIN SAMPLE TABLE PORTLET-->
				<div class="portlet box green-jungle">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-cart-plus"></i>
							Consulta Entradas de Café
						</div>

						<div class="tools">
							<!-- <a href="javascript:;" class="collapse"></a>-->
						</div>
					</div>

					<div class="portlet-body">
						<div class="table-responsive">
							<table class="table table-striped table-hover" id="tbl_entradas_cafe">
								<thead>
									<tr>
										<th style="display:none;">Movimentação</th>
										<th>Data</th>
										<th>Produtor</th>
										<th>Propriedade</th>
										<th>Lote Apas</th>
										<th>Lote Outros</th>
										<th>Quantidade</th>

										<?php
										//if(verificapermissaoedicao("Funcionários",$permissoes))
										//{
										echo '<th style="text-align: center">Editar</th>';
										//}
										?>

										<?php
										//if(verificapermissaoexclusao("Funcionários",$permissoes))
										//{
										echo '<th style="text-align: center">Excluir</th>';
										//}
										?>
									</tr>
								</thead>

								<tbody>
									<?php

									for ($i = 0; $i < count($entradas_cafe); $i++) {
										echo '<tr>
<td style="display:none;">' . $entradas_cafe[$i] -> id . '</td>
<td>' . $entradas_cafe[$i] -> data . '</td>
<td>' . $entradas_cafe[$i] -> prod_nome . '</td>
<td>' . $entradas_cafe[$i] -> prop_nome . '</td>
<td>' . $entradas_cafe[$i] -> lote_apas . '</td>
<td>' . $entradas_cafe[$i] -> lote_coperativa . '</td>
<td>' . number_format($entradas_cafe[$i] -> quantidade, 2, ',', '.') . '</td>';

										//if(verificapermissaoedicao("Funcionários",$permissoes))
										//{
										echo '	<td align="center">
<a>
<span class="glyphicon glyphicon-pencil" id="' . $entradas_cafe[$i] -> id . '" style="color:green;width:100%;height:100%"></span>
</a>
</td>';
										//}

										//if(verificapermissaoexclusao("Funcionários",$permissoes))
										//{
										echo '	<td align="center">
<a>
<span class="glyphicon glyphicon-trash" id="' . $entradas_cafe[$i] -> id . '" style="color:red;width:100%;height:100%"></span>
</a>
</td>';
										//}

										echo '</tr>';
									}
									?>
								</tbody>
							</table>
						</div>

						<?php

						/** Inclui Função Paginação **/
						include_once ('Funcoes/paginacao.php');

						/** Imprime Paginação **/
						echo paginacao_php($pagina, /** Pagina atual **/
						$registros, /** Quantidade de registros por paginação  **/
						$totalpaginas, /** Total de paginas **/
						$totalregistros, /** Total de registros **/
						'consultaentradascafe', /** Nome da função javascrip arquivo 'funcoes.js' **/
						'entradas de café' /** Descrição que aparecera na paginação **/
						);
						?>

					</div>
				</div>
				<!-- END SAMPLE TABLE PORTLET-->

			</div>
		</div>
		<!-- END PAGE CONTENT-->
		<!-- INICIO FIM MODAL DE CONSULTA -->
		<div id="modal_filtros_ent_cafe" class="modal " tabindex="-1" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
						<h4 class="modal-title">
						<div id="titulomodalpp">
							<i class="fa fa-filter"></i>&nbsp;
							Filtros para Consulta de Entradas de Café
						</div></h4>
					</div>

					<div class="modal-body">
						<div class="scroller" style="height:300px" data-always-visible="1" data-rail-visible="1">
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">Produtores</label>

										<div class="input-group">
											<span class="input-group-addon"> <span class="fa fa-users"></span> </span>

											<select class="form-control select2me" id="cmbfiltroprodutores">
												<option selected value="nenhum">Selecione um produtor...</option>

												<?php

												$sql = "SELECT * FROM `tb_produtor`  order by prod_nome asc";
												$rsfiltro = $mysqli -> query($sql);
												$sql = "";

												if ($rsfiltro) {
													$regs = array();

													while ($reg = $rsfiltro -> fetch_object()) {
														array_push($regs, $reg);

													}

													$rsfiltro -> close();

													for ($i = 0; $i < count($regs); $i++) {
														echo "<option value=" . $regs[$i] -> id_prod . ">" . $regs[$i] -> prod_nome . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div class="form-group">
										<label class="control-label">Propriedades</label>

										<div class="input-group">
											<span class="input-group-addon"> <span class="fa fa-fort-awesome"></span> </span>

											<select class="form-control select2me" id="cmbfiltropropriedade">
												<option selected value="nenhum">Selecione uma propriedade...</option>

												<?php

												$sql = "SELECT * FROM `tb_propriedade`";
												$rsfiltro = $mysqli -> query($sql);
												$sql = "";

												if ($rsfiltro) {
													$regs = array();

													while ($reg = $rsfiltro -> fetch_object()) {
														array_push($regs, $reg);

													}

													$rsfiltro -> close();

													for ($i = 0; $i < count($regs); $i++) {
														echo "<option value=" . $regs[$i] -> id_prop . ">" . $regs[$i] -> prop_nome . "</option>";
													}
												}
												?>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label">Data Inicial</label>

										<div class="input-group date date-pickerbr">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button">
													<i class="fa fa-calendar"></i>
												</button> </span>

											<input type="text" size="16" class="form-control" id="txtfiltrodata" placeholder="Digite a data">

										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label">Data Final</label>

										<div class="input-group date date-pickerbr">
											<span class="input-group-btn">
												<button class="btn default date-set" type="button">
													<i class="fa fa-calendar"></i>
												</button> </span>

											<input type="text" size="16" class="form-control" id="txtfiltrodata2" placeholder="Digite a data">

										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label ">Lote Apas</label>

										<div class="input-group">
											<span class="input-group-addon"> <span class="fa fa-tree"></span> </span>

											<input type="text" class="form-control" id="txtfiltroloteapasentcafe" placeholder="Informe o lote da APAS">
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="form-group">
										<label class="control-label ">Lote Outros</label>

										<div class="input-group">
											<span class="input-group-addon"> <span class="fa fa-tree"></span> </span>

											<input type="text" class="form-control" id="txtfiltrolotecoperativaentcafe" placeholder="informe o lote Outros">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="submit" class="btn blue" id="btn_pesquisa_ent_cafe">
							<i class="fa fa-search"></i>
							&nbsp;Pesquisar
						</button>

						<button type="button" class="btn red-thunderbird" id="btn_limpar_pesquisa_ent_cafe">
							<i class="fa fa-remove"></i>
							&nbsp;Limpar
						</button>
					</div>
				</div>
			</div>
		</div>
		<!-- FIM MODAL DE CONSULTA-->
	</div>
</div>
<!-- END CONTENT -->

<?php

$mysqli -> close();
?>
