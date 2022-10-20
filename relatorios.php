<?php

include ("db.php");
$db = new Database();

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
* Verificação de ações requisitadas via AJAX:
* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
if(isset($_GET["a"])){

    function remove_acento($string){
		$caracteres_sem_acento = array(
			'Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Â'=>'Z', 'Â'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A',
			'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I',
			'Ï'=>'I', 'Ñ'=>'N', 'Å'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U',
			'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a',
			'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i',
			'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'Å'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u',
			'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f',
			'Ä'=>'a', 'î'=>'i', 'â'=>'a', 'È'=>'s', 'È'=>'t', 'Ä'=>'A', 'Î'=>'I', 'Â'=>'A', 'È'=>'S', 'È'=>'T',
		);
		$nova_string = strtr($string, $caracteres_sem_acento);
		return ($nova_string);
	}
	
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Buscar conteúdo de relatorio de vendas na div conteudo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_user"){
		
		$pesquisa = $_POST['pesq'];
        $where = "";

        if($pesquisa != ""){
            $where .= "WHERE idPedido LIKE '%{$pesquisa}%' OR c.Nome LIKE '%{$pesquisa}%' OR quantidade LIKE '%{$pesquisa}%' OR preco LIKE '%{$pesquisa}%' OR nf LIKE '%{$pesquisa}%' OR p.statusped LIKE '%{$pesquisa}%'";
        }    
    
		$res = $db->select("SELECT c.idCliente as idCliente, c.Nome as nomec, COUNT(p.idPedido) as quan_ped, sum(p.quantidade) as quan_total, sum(p.preco) as preco_total		
                            FROM cliente c
                            INNER JOIN pedidos p ON c.idCliente = p.idCliente
							{$where}
                            GROUP BY Nome;");
		
		if(count($res) > 0){
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
					
						echo '<th style="text-align: left">Nome do Cliente</th>';
						echo '<th style="text-align: center">Quantidade de Pedidos</th>';
                        echo '<th style="text-align: center">Quantidade de Itens</th>';
                        echo '<th style="text-align: center">Preço</th>';
                    
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
                foreach($res as $r){
					echo '<tr onclick="get_item_rel('.$r["idCliente"].')" >';
						
						echo '<td style="text-align: left">'.$r["nomec"].'</td>';
						echo '<td style="text-align: center">'.$r["quan_ped"].'</td>';
                        echo '<td style="text-align: center">'.$r["quan_total"].'</td>';
                        echo '<td style="text-align: center">'.$r["preco_total"].'</td>';
                        
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
	* Buscar conteúdo de relatorio de estoque na div conteudo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_user_est"){
		
		$pesquisa = $_POST['pesq'];
        $where = "";

        if($pesquisa != ""){
            $where .= "WHERE p.descricao LIKE '%{$pesquisa}%' OR e.endereco LIKE '%{$pesquisa}%' OR quantidade LIKE '%{$pesquisa}%'";
        }    
    
		$res = $db->select("SELECT p.descricao, e.endereco, i.quantidade, i.iditens_estoque, sum(d.quantidade) as quant_ped 
                FROM itens_estoque i 
                left join end_estoque e on e.idend_estoque = i.idend_estoque
                left join produtos p on p.idProdutos = i.idProdutos
				inner join itens_pedido s on s.idProdutos = i.idProdutos
				inner join pedidos d on d.idPedido = s.idPedido
				{$where} 
				GROUP BY p.descricao;");
		
		if(count($res) > 0){
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
						echo '<th style="text-align: left">Produto</th>';
						echo '<th style="text-align: center">Endereço de Estoque</th>';
						echo '<th style="text-align: center">Quantidade em Estoque</th>';
                        echo '<th style="text-align: center">Quantidade em Pedidos</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
                foreach($res as $r){
					echo '<tr>';
						echo '<td style="text-align: left">'.$r["descricao"].'</td>';
						echo '<td style="text-align: center">'.$r["endereco"].'</td>';
						echo '<td style="text-align: center">'.$r["quantidade"].'</td>';
						echo '<td style="text-align: center">'.$r["quant_ped"].'</td>';
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
	* Exibição dos pedidos de determinado cliente
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "lista_mod_insert"){    

		$s = $db->select("SELECT idPedido FROM pedidos WHERE idCliente = $cliente AND idVendedor = '$vendedor' ORDER BY idPedido DESC LIMIT 1");

		foreach($s as $s1){
			$numped = $s1["idPedido"];
		}

		$res = $db->select("SELECT idProdutos, descricao, Preço FROM produtos ORDER BY descricao");
		
		if(count($res) > 0){
			echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
						echo '<th style="text-align: left">Descrição</th>';
						echo '<th style="text-align: center">Preço</th>';
                        echo '<th style="text-align: center">Quantidade</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody>';
                foreach($res as $r){
					echo '<tr >';
						echo '<td  style="text-align: left">'.$r["descricao"].'</td>';
						echo '<td style="text-align: center">'.$r["Preço"].'</td>';
						echo '<td style="text-align: center">';
							echo '<input type="number" onchange="incluiPed(this.value,\''.$r["idProdutos"].'\',\''.$numped.'\')" min="0" max="100"></input>';
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
	* Busca conteúdo:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "get_client"){
      

        $id = $_POST["id"];

        $res = $db->select("SELECT iditens_estoque, p.descricao, e.endereco, quantidade
			FROM itens_estoque i 
			inner join end_estoque e on e.idend_estoque = i.idend_estoque
			inner join produtos p on p.idProdutos = i.idProdutos
			WHERE iditens_estoque = {$id}");
		
        if(count($res) > 0){
            $res[0]['descricao'] = utf8_encode($res[0]['descricao']);
            $res[0]['endereco'] = utf8_encode($res[0]['endereco']);
			$res[0]['quantidade'] = utf8_encode($res[0]['quantidade']);
			
            $a_retorno["res"] = $res;
            $c_retorno = json_encode($a_retorno["res"]);
            print_r($c_retorno);
        }
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo para a exibição dos detalhes do pedido:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "get_det_rel"){
      

        $id = $_POST["id"];

		$res = $db->select("SELECT p.idPedido, p.idCliente, c.Nome as nomec, sum(p.quantidade) as quantidade, sum(p.preco) as preco
							FROM pedidos p
							INNER JOIN cliente c ON c.idCliente = p.idCliente
							WHERE p.idCliente = {$id}");
		
        if(count($res) > 0){

			$res[0]['quantidade'] = remove_acento($res[0]['quantidade']);
			$res[0]['preco'] = remove_acento($res[0]['preco']);
			
			$c_retorno = array();
			$body = ""; 
			
			$lista = $db->select("SELECT p.idCliente, p.idPedido, i.idProdutos, sum(i.quantidade) as quantidade, sum(i.valor_final) as valor_final
								
								FROM itens_pedido i
								INNER JOIN pedidos p ON p.idPedido = i.idPedido 

								WHERE p.idCliente = {$id}
								GROUP BY p.idPedido");
				foreach($lista as $s){
					$body .= '<tr onclick="get_item_ped('.$s["idPedido"].')">';
						$body .= '<td style="text-align: left">'.$s["idPedido"].'</td>';
						$body .= '<td style="text-align: center">'.$s["quantidade"].'</td>';
						
						$body .= '<td style="text-align: center">'.$s["valor_final"].'</td>';
				}						
			
			$title = '<h5 id="div_exibe_title" class="modal-title">Pedidos do Cliente '.$id.'</h5>';
			
			$c_retorno["title"] = $title;	
			$c_retorno["header"] = $res;	
            //$a_retorno["res"] = $res;
            //$c_retorno["header"] = json_encode($a_retorno["res"]);
			$c_retorno["body"] = $body;
			echo json_encode($c_retorno);
            //print_r(json_encode($c_retorno));
			//print_r($a_retorno["res"]);

        }
	}

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Busca conteúdo para a exibição dos detalhes do pedido:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	if($_GET["a"] == "get_det_ped"){
      

        $id = $_POST["id"];
		$c_retorno = array();
		$a_retorno = array();
		$body = " ";
			$lista = $db->select("SELECT p.descricao, i.idProdutos, p.idProdutos, p.Preço as preco, i.quantidade, i.valor_final FROM itens_pedido i
								INNER JOIN produtos p ON p.idProdutos = i.idProdutos 
								WHERE i.idPedido = {$id}");
				foreach($lista as $s){
					$body .= '<tr>';
						$body .= '<td style="text-align: left">'.$s["descricao"].'</td>';
						$body .= '<td style="text-align: center">'.$s["quantidade"].'</td>';
						$body .= '<td style="text-align: center">'.$s["preco"].'</td>';
						$body .= '<td style="text-align: center">'.$s["valor_final"].'</td>';
				}						
			
			$title = '<h5 id="div_exibe_title_ped" class="modal-title">Informações do Pedido '.$id.'</h5>';
			
			$c_retorno["title"] = remove_acento($title);		
			$c_retorno["body"] = remove_acento($body);
			//$a_retorno = remove_acento($body);
			echo json_encode($c_retorno);
            

        
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
	* Listar itens de vendas:
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
	* Listar itens de estoque:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const lista_itens_est = () => {
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=lista_user_est',
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
	* inclui no modal os itens para inclusão:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const incluiPed = (quantidade,produto,pedido) => {
		$('#numpedido').val(pedido);
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=inclui_pedido',
			type: 'post',
			data: {quantidade: quantidade,
				produto: produto,
                pedido: pedido},
			
			success: function retorno_ajax(retorno) {
				//$('#numpedido').val(pedido);
				if(!retorno){
					alert("ERRO AO INLUIR ITEM NO PEDIDO!");
				} 
			}
		});
	}

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Exibir no modal os itens para inclusão:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const listaModinsert = () => {
		if(ajax_div){ ajax_div.abort(); }
			ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=lista_mod_insert',
			type: 'post',
			data: {pesq: $('#input_pesquisa').val()},
			beforeSend: function(){
				$('#mod_insert').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				$('#mod_insert').html(retorno); 
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

					$("#frm_val1_edit").val(obj_ret[0].descricao);
					$("#frm_val2_edit").val(obj_ret[0].endereco);
					$("#frm_val3_edit").val(obj_ret[0].quantidade);	
				}
			}
		});
	}

	  /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Pesquisar itens dos detalhes do pedido:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const get_item_rel = (id) => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=get_det_rel',
			type: 'post',
			data: { 
                id: id,
            },
			beforeSend: function(){
                $('#mod_formul_exibe').modal("show");
			},
			success: function retorno_ajax(retorno) {
				
				var obj = JSON.parse(retorno);

				
				//if(retorno){
                    $("#frm_id_exibe").val(id);
                    
					var obj_ret = obj.header;
					var obj_title = obj.title;
					var obj_body = obj.body;

					$("#frm_val5_exibe").val(obj_ret[0].quantidade);	
					$("#frm_val6_exibe").val(obj_ret[0].preco);	

					$('#div_exibe_title').html(obj_title); 

					$('#div_exibe_ped').html(obj_body); 
				//}
			}
		});
	}

  /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Pesquisar itens dos detalhes do pedido:
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	var ajax_div = $.ajax(null);
	const get_item_ped = (id) => {
        if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=get_det_ped',
			type: 'post',
			data: { 
                id: id,
            },
			beforeSend: function(){
				$('#mod_formul_exibe').modal("hide");
                $('#mod_exibe_ped').modal("show");
			},
			success: function retorno_ajax(retorno) {
				
				var obj = JSON.parse(retorno);
				var obj_title = obj.title;
				var obj_body = obj.body;
				
				//alert(teste.header);
				//if(retorno){
                    $("#frm_id_exibe_ped").val(id);

					$('#div_exibe_title_ped').html(obj_title); 

					$('#div_exibe_det_ped').html(obj_body); 
				//}
			}
		});
	}

</script>

<!-- Modal formulário Inclusao -->
<div class="modal" id="mod_formul">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="tit_frm_formul" class="modal-title">Incluir Peidos</h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general" name="frm_general">

				<div class="row mb-3">
						<div class="col">
							<label for="frm_val1_insert" class="form-label">Vendedor:</label>
								<div class="scrollable">
								<select id="frm_val1_insert"  class="select form-control form-control-lg" name="frm_val1_insert" type="text" >
									<option value="" selected></option>
									<?php
										$desc = $db->select('SELECT idVendedor, Nome FROM vendedor');
										foreach($desc as $s){
											echo  '<option value="'.$s["idVendedor"].'">'.$s["Nome"].'</option>';
										}
									?>
								</select>
							</div>
                        </div>
					</div>
                    <div class="row mb-3">
						<div class="col">
							<label for="frm_val2_insert" class="form-label">Cliente:</label>
								<div class="scrollable">
								<select id="frm_val2_insert"  onchange="listaModinsert()" class="select form-control form-control-lg" name="frm_val2_insert" type="text" >
									<option value="" selected></option>
									<?php
										$desc = $db->select('SELECT idCliente, Nome FROM cliente');
										foreach($desc as $s){
											echo  '<option value="'.$s["idCliente"].'">'.$s["Nome"].'</option>';
										}
									?>
								</select>
								<input id="numpedido" hidden></input>
							</div>
                        </div>
					</div>
					
					<div id="mod_insert"></div>	
   
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_formul').modal('hide');">Cancelar</button>
				<button type="button" class="btn btn-primary" id="OK" onclick="incluiClient();"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
			</div>
		</div>
	</div>
</div>


<!-- Modal formulário Edição-->
<div class="modal" id="mod_formul_edit">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="tit_frm_formul_edit" class="modal-title">Editar Itens do Estoque</h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul_edit').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general_edit" name="frm_general">
					<div class="row mb-3">
						<div class="col">
                            <input type="text" style="text-align: left" aria-describedby="frm_id" class="form-control form-control-lg" name="frm_id" id="frm_id" hidden>
							<label for="frm_val1_edit" class="form-label">Descrição:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val1_edit" class="form-control form-control-lg" name="frm_val1_edit" id="frm_val1_edit" placeholder="" disabled>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col">
							<label for="frm_val2_edit" class="form-label">Endereço:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val2_edit" class="form-control form-control-lg" name="frm_val2_edit" id="frm_val2_edit" placeholder="" disabled>
						</div>
					</div>

					<div class="row mb-3">
						<div class="col">
							<label for="frm_val3_edit" class="form-label">Quantidade:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val3_edit" class="form-control form-control-lg" name="frm_val3_edit" id="frm_val3_edit" placeholder="">
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

<!-- Modal formulário Exibição-->
<div class="modal" id="mod_formul_exibe">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"  style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="div_exibe_title"></h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_formul_exibe').modal('hide');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general_exib" name="frm_general">
				<input type="text" style="text-align: left" aria-describedby="frm_id_exibe" class="form-control form-control-lg" name="frm_id_exibe" id="frm_id_exibe" hidden>
					
					<div class="row mb-3">
						<div class="col">			
							<label for="frm_vallista_exibe" class="form-label"><b>Pedidos:</b></label>
								<div class="table-responsive">
									<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">
										<thead>
											<tr>
												<th style="text-align: left">idPedido</th>
												<th style="text-align: center">Quantidade de Itens</th>
												<th style="text-align: center">Valor Total</th>
												
										</thead>
										<tbody id="div_exibe_ped"></tbody>
									</table>
								</div>				
						</div>			
					</div>

					<div class="row mb-3">					
						<div class="col">
							<label for="frm_val5_exibe" class="form-label">Quantidade Total:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val5_exibe" class="form-control form-control-lg" name="frm_val5_exibe" id="frm_val5_exibe" placeholder="" disabled>
						</div>

						<div class="col">
							<label for="frm_val6_exibe" class="form-label">Valor Final:</label>
							<input type="text" style="text-align: left" aria-describedby="frm_val6_exibe" class="form-control form-control-lg" name="frm_val6_exibe" id="frm_val6_exibe" placeholder="" disabled>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_formul_exibe').modal('hide');">Cancelar</button>
				<button type="button" class="btn btn-primary" id="frm_OK" onclick="$('#mod_formul_exibe').modal('hide');"><img id="img_btn_ok" style="width: 15px; display: none; margin-right: 10px">OK</button>
			</div>
		</div>
	</div>
</div>

<!-- Modal formulário Exibição de pedidos individuais-->
<div class="modal" id="mod_exibe_ped">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl"  style="max-width: 70%;">
		<div class="modal-content">
			<div class="modal-header" style="align-items: center">
				<div style="display: flex; align-items: center">
					<div style="margin-right: 5px">
						<h2 style="margin: 0"><span class="badge bg-info text-white" style="padding: 8px" id="span_endereco_nome"></span></h2>
					</div>
					<div>
						<h5 id="div_exibe_title_ped"></h5>
					</div>
				</div>
				<button type="button" style="cursor: pointer; border: 1px solid #ccc; border-radius: 10px" aria-label="Fechar" onclick="$('#mod_exibe_ped').modal('hide'); $('#mod_formul_exibe').modal('show');">X</button>
			</div>
			<div class="modal-body modal-dialog-scrollable">
				<form id="frm_general_exib" name="frm_general">
				<input type="text" style="text-align: left" aria-describedby="frm_id_exibe_ped" class="form-control form-control-lg" name="frm_id_exibe_ped" id="frm_id_exibe_ped" hidden>
					<div class="row mb-3">
						<div class="col">			
							<label for="frm_vallista_exibe" class="form-label"><b>Produtos:</b></label>
							<div class="table-responsive">
								<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">
									<thead>
										<tr>
											<th style="text-align: left">Descrição do Produto</th>
											<th style="text-align: center">Quantidade</th>
											<th style="text-align: center">Valor Unitário</th>
											<th style="text-align: center">Valor</th>
									</thead>
									<tbody id="div_exibe_det_ped"></tbody>
								</table>
							</div>				
						</div>			
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" onclick="$('#mod_exibe_ped').modal('hide'); $('#mod_formul_exibe').modal('show');">Cancelar</button>
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
				<h1 class="h2">Relatórios</h1>
			</div>
		</div>
	</div>

	<div class="form-group row">
		<div class="col-8">
			<div class="input-group">
			<input type="text" class="form-control" onkeyup="lista_itens()" id="input_pesquisa" placeholder="Pesquisar">
			</div>
		</div>
		<div class="col-2">
			<div class="input-group">
				<button type="button" onclick="lista_itens()" class="btn btn-primary"><i style="margin-right: 5px"></i>Vendas</button>
			</div>
		</div>
		<div class="col-2">
			<div class="input-group">
				<button type="button" onclick="lista_itens_est()" class="btn btn-primary"><i style="margin-right: 5px"></i>Estoque</button>
			</div>
		</div>
	</div>

	<div id="div_conteudo"></div>
</main>

<?php include("bottom.php"); ?>
