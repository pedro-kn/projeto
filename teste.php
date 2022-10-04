

<div class="row mb-3">
						<div class="col">
							<label for="descricao" class="form-label">Produto:</label>
							<select id="descricao" class="form-control form-control-lg" onselect="confereItem();" name="descricao" type="text" >
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


        