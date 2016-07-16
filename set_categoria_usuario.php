<?php 
 
    header ('Content-type: text/html; charset=UTF-8');
 
    include 'verificaLogin.php';
    include 'connect.php';
         
    global $mysqli;
     
    if (!$mysqli->set_charset("utf8")) 
    {
        printf("Error loading character set utf8: %s\n", $mysqli->error);
    }
     
    if(isset($_POST['acaocategoriausuario']))
    {
        $acaocategoriausuario = $_POST['acaocategoriausuario'];
    }
    else
    {
        echo json_encode(array('msg'=>'Erro Inesperado! Ação não identificada'));
        return false;
    }
     
    if(!strcmp($acaocategoriausuario,'adicionar') || !strcmp($acaocategoriausuario,'editar'))
    {
        $descricaocategoria = $mysqli->real_escape_string($_POST['descricaocategoria']);
        
        if(isset($_POST['listapermissoesformularios']))
        {
            $objlistapermissoes = $_POST['listapermissoesformularios'];
        }
        else
        {
            echo json_encode(array('msg'=>'Erro Inesperado! Permissções de formularios não identificada'));
            return false;
        }
         
        if(isset($_POST['listapermissoesrelatorios']))
        {
            $objlistapermissoesrelatorios = $_POST['listapermissoesrelatorios'];
        }
        else
        {
            echo json_encode(array('msg'=>'Erro Inesperado! Permissções de relatórios não identificada'));
            return false;
        }
         
        if(!strcmp($acaocategoriausuario,'adicionar'))
        {
            if(!$rs = $mysqli->query("INSERT INTO tb_categoria(
                                                            	cat_descricao
                                                           	  ) 
                                                     	values(
                                                               '$descricaocategoria'
                                                           	  )"))
            {
                echo json_encode(array('msg'=>'Error ao gravar categoria do usuário'));
                return false;
            }
             
            $idcategoria = $mysqli->insert_id;
             
            if(!is_null($objlistapermissoes))
            {
                $tamanho  = count($objlistapermissoes) - 1;
                 
                for($i = 0 ; $i <= $tamanho; $i++)
                {
                    $idformulario           = $objlistapermissoes[$i]['idformulario'];
                    $leiturapermissao       = $objlistapermissoes[$i]['leiturapermissao'];
                    $gravacaopermissao      = $objlistapermissoes[$i]['gravacaopermissao'];
                    $edicaopermissao        = $objlistapermissoes[$i]['edicaopermissao'];
                    $exclusaopermissao      = $objlistapermissoes[$i]['exclusaopermissao'];
                                           
                    if(!$rs = $mysqli->query("INSERT INTO tb_permissao_formulario(
	                                                                              fk_id_cat,
	                                                                              fk_id_form,
	                                                                              perm_visualizar,
	                                                                              perm_incluir,
	                                                                              perm_alterar,
	                                                                              perm_excluir
	                                                                             ) 
	                                                                       values(
	                                                                              $idcategoria,
	                                                                              $idformulario,
	                                                                              $leiturapermissao,
	                                                                              $gravacaopermissao,
	                                                                              $edicaopermissao,
	                                                                              $exclusaopermissao
	                                                                             )"))
                    {
                        echo json_encode(array('msg'=>'Error ao gravar permissões do formulário'));
                        return false;
                    }
                     
                    $idpermissao = $mysqli->insert_id;
                }
            }
             
            if(!is_null($objlistapermissoesrelatorios))
            {
                $tamanho  = count($objlistapermissoesrelatorios) - 1;
                 
                for($i = 0 ; $i <= $tamanho; $i++)
                {
                    $idrelatorio            = $objlistapermissoesrelatorios[$i]['idrelatorio'];
                    $leiturarelatorio       = $objlistapermissoesrelatorios[$i]['leiturarelatorio'];
                         
                    if(!$rs = $mysqli->query("INSERT INTO tb_permissao_relatorio(
	                                                                             fk_id_cat,
	                                                                             fk_id_rel,
	                                                                             perm_visualizar
	                                                                            ) 
	                                                                      values(
	                                                                             $idcategoria,
	                                                                             $idrelatorio,
	                                                                             $leiturarelatorio
	                                                                            )"))
                    {
                        echo json_encode(array('msg'=>'Error ao gravar permissões do formulário'));
                        return false;
                    }
                     
                    $idpermissao = $mysqli->insert_id;
                }
            }
                        
            echo json_encode(array('msg' => 'sucesso'));
        }
        elseif(!strcmp($acaocategoriausuario,'editar'))
        {
            if(isset($_POST['idcategoria']))
            {
                $idcategoria = $_POST['idcategoria'];
            }
            else
            {
                echo json_encode(array('msg'=>'Categoria não identificada para alteração'));
                return false;
            }
 
            if(!$rs = $mysqli->query("UPDATE tb_categoria 
                                      SET cat_descricao = '$descricaocategoria'
                                      WHERE id_cat 		=  $idcategoria"))
            {
                echo json_encode(array('msg'=>'Error na alteração da categoria do usuário'));
                return false;
            }
             
            if(!is_null($objlistapermissoes))
            {
                $tamanho = count($objlistapermissoes) - 1;
                 
                for($i = 0 ; $i <= $tamanho; $i++)
                {
                    $idformulario           = $objlistapermissoes[$i]['idformulario'];
                    $leiturapermissao       = $objlistapermissoes[$i]['leiturapermissao'];
                    $gravacaopermissao      = $objlistapermissoes[$i]['gravacaopermissao'];
                    $edicaopermissao        = $objlistapermissoes[$i]['edicaopermissao'];
                    $exclusaopermissao      = $objlistapermissoes[$i]['exclusaopermissao'];
                     
                    if(!$rs = $mysqli->query("UPDATE tb_permissao_formulario SET perm_visualizar = $leiturapermissao,
				                                                                 perm_incluir 	= $gravacaopermissao,
				                                                                 perm_alterar   	= $edicaopermissao,
				                                                                 perm_excluir 	= $exclusaopermissao
                                              WHERE fk_id_cat  = $idcategoria
                                              AND   fk_id_form = $idformulario"))
                    {
                        echo json_encode(array('msg'=>'Error ao alterar permissões do formulário'));
                        return false;
                    }
                }
            }
 
            if(!is_null($objlistapermissoesrelatorios))
            {
                $tamanho = count($objlistapermissoesrelatorios) - 1;
                 
                for($i = 0 ; $i <= $tamanho; $i++)
                {
                    $idrelatorio            = $objlistapermissoesrelatorios[$i]['idrelatorio'];
                    $leiturarelatorio       = $objlistapermissoesrelatorios[$i]['leiturarelatorio'];
                     
                    if(!$rs = $mysqli->query("UPDATE tb_permissao_relatorio 
                                              SET perm_visualizar = $leiturarelatorio
                                              WHERE fk_id_cat = $idcategoria
                                              AND   fk_id_rel = $idrelatorio"))
                    {
                        echo json_encode(array('msg'=>'Error ao alterar permissões do formulário'));
                        return false;
                    }
                }
            }
                 
            echo json_encode(array ('msg' => 'sucesso'));
        }
    }
    elseif(!strcmp($acaocategoriausuario,'excluir'))
    {
        if(isset($_POST['idcategoria']))
        {
            $idcategoria = $_POST['idcategoria'];
        }
        else
        {
            echo json_encode(array('msg'=>'Categoria não identificada para exclusão'));
            return false;
        }
         
        if(!$res = $mysqli->query("DELETE FROM tb_permissao_formulario WHERE fk_id_cat = " . $idcategoria))
        {
            echo json_encode(array('msg'=>'Erro na exclusão das permissões dos formulários'));
            return false;
        }
         
        if(!$res = $mysqli->query("DELETE FROM tb_permissao_relatorio WHERE fk_id_cat = " . $idcategoria))
        {
            echo json_encode(array('msg'=>'Erro na exclusão das permissões dos relatórios'));
            return false;
        }
         
        if(!$res = $mysqli->query("DELETE FROM tb_categoria WHERE id_cat = " . $idcategoria))
        {
            echo json_encode(array('msg'=>'Erro na exclusão da categoria do usuário'));
            return false;
        }
         
        echo json_encode( array ( "msg" => 'sucesso'));
    }
     
?>