
<script type="text/javascript" src="./assets/js/jquery-3.6.1.min.js"></script>

<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
	<div class="position-sticky pt-3">
		
		<!-- Gestão: -->
		<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
			<span>Cadastros</span>
		</h6>
		<ul class="nav flex-column mb-2">	
			<li class="nav-item">
				<a class="nav-link" href="pedidos.php">
				    <span data-feather="shopping-bag"></span>
				    Pedidos
				</a>
			</li>	
			<li class="nav-item">
				<a class="nav-link" href="_clientes.php">
					<span data-feather="users"></span>
					Clientes
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="produtos.php">
					<span data-feather="shopping-cart"></span>
					Produtos
				</a>
			</li>
			<li class="nav-item">
                <a class="nav-link" href="_estoque.php">
				    <span data-feather="box"></span>
				    Estoque
				</a>
			</li>
            <li class="nav-item">
                <a class="nav-link" href="_usuarios.php">
				    <span data-feather="user"></span>
				    Usuarios
				</a>
			</li>
		</ul>
		<!-- Gestão: -->
		<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
			<span>Monitoramento</span>
		</h6>
		<ul class="nav flex-column mb-2">	
			<li class="nav-item">
				<a class="nav-link" href="_grafico_pedido.php">
				    <span data-feather="fa fa-dashboard"></span>
				    Monitoramento de Pedidos
				</a>
			</li>	
			<li class="nav-item">
				<a class="nav-link" href="_transacoes.php">
					<span data-feather="box"></span>
					Histórico de Transações
				</a>	
			</li>
			<li class="nav-item">
				<a class="nav-link" href="_log_usuario.php">
					<span data-feather="user"></span>
					Logs do usuário
				</a>
			</li>
		</ul>
		<!-- Gestão: -->
		<h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
			<span>Finalizar Sessão</span>
		</h6>
		<ul class="nav flex-column mb-2">	
			<li class="nav-item">
				<a class="nav-link" href="logout.php">
				    <span data-feather=""></span>
				    Sair
				</a>
			</li>	
		</ul>
	</div>
</nav>