<?php


if(isset($_GET["a"])){

	include("db.php");
	$db = new Database();
	
	if($_GET["a"] == "lista_user"){

		$res = $db->select("SELECT * FROM cliente");
        echo '<div class="table-responsive">';
			echo '<table id="tb_lista" class="table table-striped table-hover table-sm" style="font-size: 10pt">';
				echo '<thead>';
					echo '<tr>';
						echo '<th style="text-align: left">Nome</th>';
						//echo '<th style="text-align: center">E-mail</th>';
                        echo '<th style="text-align: center">Data de Nascimento</th>';
                        echo '<th style="text-align: center">CPF</th>';
					echo '</tr>';
				echo '</thead>';
				echo '<tbody style="cursor: row-resize">';
					
					foreach($res as $r){
						echo '<tr>';
						echo '<td style="text-align: left">'.$r["Nome"].'</td>';
						echo '<td style="text-align: center">'.$r["Data_Nasc"].'</td>';
						echo '<td style="text-align: center">'.$r["CPF"].'</td>';	
						echo '</tr>';
					}			

						/*$array = array(
							
							'<td style="text-align: left"> jonas </td>',
							'<td style="text-align: center"> pedrolindo@gmail.com </td>',
							'<td style="text-align: center"> <i title="Editar" onclick="get_item()" class="fas fa-edit" style="cursor: pointer"></i> </td>',
							'<td style="text-align: center"> <i title="Deletar" onclick="del_item()" class="fas fa-trash" style="cursor: pointer"></i> </td>',

							);
							
						foreach ($array as $value) {
							echo $value;
						}*/					
					
				echo '</tbody>';
			echo '</table>';
			echo '</div>';

    }
    die();
}

include("header.php");
include("dashboard.php");

?>

<script type="text/javascript" src="./assets/bootstrap/js/jquery-3.6.1.min.js"></script>
<script type="text/javascript"> 

    var ajax_div = $.ajax(null);
	const lista_itens = () => {
		if(ajax_div){ ajax_div.abort(); }
		ajax_div = $.ajax({
			cache: false,
			async: true,
			url: '?a=lista_user',
			type: 'post',
			data: { },
			beforeSend: function(){
				$('#div_conteudo').html('<div class="spinner-grow m-3 text-primary" role="status"><span class="visually-hidden">Aguarde...</span></div>');
			},
			success: function retorno_ajax(retorno) {
				$('#div_conteudo').html(retorno); 
			}
		});
	}

    $(document).ready(function(){

        lista_itens();

    });


</script>


<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
	<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
		<div style="display: flex; flex: 1">
			<div style="flex: 1">
				<h1 class="h2">Usuários</h1>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col">
			<div class="input-group mb-3">
				<button type="button" onclick="$('#mod_formul').modal('show');" class="btn btn-primary"><i class="fa fa-plus-circle" style="margin-right: 5px"></i>Incluir</button>
			</div>
		</div>
	</div>

	<div id="div_conteudo"></div>
</main>


<?php include("bottom.php"); ?>