<?php

include ("db.php");
$db = new Database();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if(isset($_GET["a"])){

    
	
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Buscar conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_user"){
		
		$pesquisa = $_POST['pesq'];
        $where = "";

        if($pesquisa != ""){
            $where .= "WHERE (descricao LIKE '%{$pesquisa}%' OR endereco LIKE '%{$pesquisa}%' OR quantidade LIKE '%{$pesquisa}%')";
        }    
    
		$res = $db->select("SELECT p.descricao, e.endereco, quantidade, iditens_estoque
                FROM ((itens_estoque i 
                inner join end_estoque e on e.idend_estoque = i.idend_estoque)
                inner join produtos p on p.idProdutos = i.idProdutos);");
		
		if(count($res) > 0){
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
						echo '<th style="text-align: left">Produto</th>';
						echo '<th style="text-align: center">Endereço de Estoque</th>';
						echo '<th style="text-align: center">Quantidade</th>';
                        echo '<th style="text-align: center">Editar</th>';
                        echo '<th style="text-align: center">Deletar</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
                foreach($res as $r){
					echo '<tr>';
						echo '<td style="text-align: left">'.$r["descricao"].'</td>';
						echo '<td style="text-align: center">'.$r["endereco"].'</td>';
						echo '<td style="text-align: center">'.$r["quantidade"].'</td>';
                        echo '<td style="text-align: center">';
							echo '<i title="Editar" onclick="get_item(\''.$r["iditens_estoque"].'\')" class="fas fa-edit" style="cursor: pointer"></i>';
						echo '</td>';
                        echo '<td style="text-align: center">';
							echo '<i title="Deletar" onclick="del_item(\''.$r["iditens_estoque"].'\')" class="fas fa-trash" style="cursor: pointer"></i>';
						echo '</td>';
					echo '</tr>';
				}
				echo '</tbody>';
			echo '</table>';
			echo '</div>';
		}else{
			echo '<div class="alert alert-warning" role="alert">';
				echo 'Nenhum registro localizado!';
			echo '</div>';
		}
	}
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Inserir conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "inclui_client"){
      
        $descricao = $_POST["descricao"];
        $endereco = $_POST["endereco"];
        $quantidade = $_POST["quantidade"];
		
		$res = $db->_exec("INSERT INTO itens_estoque (iditens_estoque,idProdutos,idend_estoque,quantidade) VALUES ('','$descricao','$endereco','$quantidade')");

        echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Edita conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "edit_client"){
        

        $id = $_POST["id"];
        $nome = $_POST["nome"];
        $datanasc = $_POST["datanasc"];
        $cpf = $_POST["cpf"];
        

        $res = $db->_exec("UPDATE cliente 
			SET idCliente = '{$id}', Nome = '{$nome}', Data_Nasc = '{$datanasc}', CPF = '{$cpf}'
			WHERE idCliente = '{$id}'");

        echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Deleta conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "del_user"){
      

        $id = $_POST["id"];

        $res = $db->_exec("DELETE FROM cliente WHERE idCliente = '{$id}'");
		
        echo $res;
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "get_client"){
      

        $id = $_POST["id"];

        $res = $db->select("SELECT Nome, Data_Nasc, CPF FROM cliente WHERE idCliente = '{$id}'");
		
        if(count($res) > 0){
            $res[0]['Nome'] = utf8_encode($res[0]['Nome']);
            $res[0]['Data_Nasc'] = utf8_encode($res[0]['Data_Nasc']);
			$res[0]['CPF'] = utf8_encode($res[0]['CPF']);
			
            $a_retorno["res"] = $res;
            $c_retorno = json_encode($a_retorno["res"]);
            print_r($c_retorno);
        }
	}

    die();
}

// Includes para o script:
include("header.php");
include("dashboard.php");

?>


<script type="text/javascript" src="./assets/js/jquery-3.6.1.min.js"></script>
<script type="text/javascript">
	
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Listar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const lista_itens = () => {
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=lista_user',
			type: 'post',
			data: {pesq: $('#input_pesquisa').val() 			},
			beforeSend: function(){
				$('#div_conteudo').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				$('#div_conteudo').html(retorno); 
			}
		});
	}
    
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Incluir itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiClient = () => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=inclui_client',
			type: 'post',
			data: { 
                descricao: $('#descricao').val(),
                endereco: $('#endereco').val(),    
                quantidade: $('#quantidade').val(),
                
            },
			beforeSend: function(){

				$('#modal_formul').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				if(retorno){
                    $('#mod_formul').modal('hide');
                    location.reload();
                    lista_itens();  
                }else{
                    alert("ERRO AO CADASTRAR USUÁRIO! " + retorno);
                }
			}
		});
	}

	// Evento inicial:
	$(document).ready(function() {
		lista_itens();
	});

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Pesquisar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const get_item = (id) => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=get_client',
			type: 'post',
			data: { 
                id: id,
            },
			beforeSend: function(){
                $('#mod_formul_edit').modal("show");
			},
			success: function retorno_ajax(retorno) {
				
				if(retorno){
                    $("#frm_id").val(id);
                    
					var obj_ret = JSON.parse(retorno);

					$("#frm_nome_edit").val(obj_ret[0].Nome);
					$("#frm_datanasc_edit").val(obj_ret[0].Data_Nasc);
					$("#frm_cpf_edit").val(obj_ret[0].CPF);	
				}
			}
		});
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Editar itens:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const editClient = () => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=edit_client',
			type: 'post',
			data: { 
                id: $("#frm_id").val(),
                nome: $("#frm_nome_edit").val(),
                datanasc: $("#frm_datanasc_edit").val(),
                cpf: $("#frm_cpf_edit").val(),
            },
			beforeSend: function(){
                $('#mod_formul_edit').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				if(retorno){
                    $('#mod_formul_edit').modal('hide');
                    location.reload();
                    lista_itens();  
                }else{
                    alert("ERRO AO EDITAR USUÁRIO! " + retorno);
                }
			}
		});
	}

    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Excluir usuário:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	function del_item(id){
        if( confirm( "Deseja excluir o cliente?")){
            if(ajax_div){ ajax_div.abort(); }
		        ajax_div = $.ajax({
		    	cache: false,
		    	async: true,
		    	url: '?a=del_user',
		    	type: 'post',
		    	data: { 
                    id: id,
                },
		    	success: function retorno_ajax(retorno) {
                    if(retorno){
						location.reload();
                    	lista_itens();  
                	}else{
                    	alert("ERRO AO DELETAR USUÁRIO! " + retorno);
                	}
		    	}
		    });
        }else{
            lista_itens();
        }
	}
</script>

<!-- Modal formulário -->
<div class="modal" id="mod_formul">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="tit_frm_formul" class="modal-title">Incluir Itens no Estoque</h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general" name="frm_general">
					
                    <div class="row mb-3">
						<div class="col">
							<label for="descricao" class="form-label">Produto:</label>
							<select id="descricao" class="form-control form-control-lg" name="descricao" type="text" >
                                <option value="" selected></option>
                                <?php
                                    $desc = $db->select('SELECT * FROM produtos');
                                    foreach($desc as $s){
                                        echo  '<option value="'.$s["idProdutos"].'">'.$s["descricao"].'</option>';
                                    }
                                ?>
				            </select>
                        </div>
					</div>

                    <div class="row mb-3">
						<div class="col">
							<label for="endereco" class="form-label">Endereço de Estoque:</label>
							<select id="endereco" class="form-control form-control-lg" name="endereco" type="text" >
                                
                                <option value="" selected></option>
                                <?php
                                    $est = $db->select('SELECT * FROM end_estoque');
                                    foreach($est as $s){
                                        echo  '<option value="'.$s["idend_estoque"].'">'.$s["endereco"].'</option>';
                                    }
                                ?>
                            
				            </select>
                        </div>
					</div>

					

					<div class="input-group">
						<div class="col">
							<label for="quantidade" class="form-label">Quantidade:</label>
							<input type="number" style="text-align: left" aria-describedby="basic-addon2" class="form-control form-control-lg" name="quantidade" id="quantidade" placeholder="">
								
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_formul').modal('hide');">Cancelar</button>
				<button type="button" class="btn btn-primary" id="OK" onclick="incluiClient();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal formulário -->
<div class="modal" id="mod_formul_edit">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="tit_frm_formul_edit" class="modal-title">Editar Usuário</h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul_edit').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general_edit" name="frm_general">
					<div class="row mb-3">
						<div class="col">
                            <input type="text" style="text-align: left" aria-describedby="frm_id" class="form-control form-control-lg" name="frm_id" id="frm_id" hidden>
							<label for="frm_nome_edit" class="form-label">Nome:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_nome_edit" class="form-control form-control-lg" name="frm_nome_edit" id="frm_nome_edit" placeholder="">
						</div>
					</div>

					<div class="row mb-3">
						<div class="col">
							<label for="frm_datanasc_edit" class="form-label">Data de Nascimento:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_datanasc_edit" class="form-control form-control-lg" name="frm_datanasc_edit" id="frm_datanasc_edit" placeholder="">
						</div>
					</div>

					<div class="row mb-3">
						<div class="col">
							<label for="frm_cpf_edit" class="form-label">CPF:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_cpf_edit" class="form-control form-control-lg" name="frm_cpf_edit" id="frm_cpf_edit" placeholder="">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_formul_edit').modal('hide');">Cancelar</button>
				<button type="button" class="btn btn-primary" id="frm_OK" onclick="editClient();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal geral -->
<div class="modal" id="mod_general" tabindex="-1" style="z-index: 1400 !important">
	<div id="mod_general" class="modal-dialog modal-dialog-scrollable modal-xl" tabindex="-1">
		<div class="modal-content">
			<div class="modal-header">
				<h5 id="tit_frm_general" class="modal-title"></h5>
				<button type="button" id="btn_general_close" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
			</div>
			<div class="modal-body modal-dialog-scrollable" id="modmenu_content">
			</div>
		</div>
	</div>
</div>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<div style="display: flex; flex: 1">
			<div style="flex: 1">
				<h1 class="h2">Itens do Estoque</h1>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-10">
			<div class="input-group">
			<input type="text" class="form-control" onkeyup="lista_itens()" id="input_pesquisa" placeholder="Pesquisar">
			</div>
		</div>
		<div class="col-2">
			<div class="input-group">
				<button type="button" onclick="$('#mod_formul').modal('show');" class="btn btn-primary"><i class="fa fa-plus-circle" style="margin-right: 5px"></i>Incluir</button>
			</div>
		</div>
	</div>

	<div id="div_conteudo"></div>
</main>

<?php include("bottom.php"); ?>
