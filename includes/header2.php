<?php
if ( !isset( $_SESSION[ 'dataSession' ] ) ) {
	header( 'Location: /index.php' );
}
?>
<header>
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12 col-sm-8 col-md-7 logo"><a href="#"><img src="../assets/img/logo-p.png" alt="CEEl Capacitación Empresarial en Línea"></a><span>|</span> Formación Empresarial en Línea</div>
			<div class="col-sx-12 col-sm-4 col-md-5 contact-p">
				<a href="profile.php">
				<div class="text-avatar-header">
					<?php echo($_SESSION['dataSession']['nombres'].' '.$_SESSION['dataSession']['apellidos']); ?> <br>
					<span>
						<?php echo($_SESSION['dataSession']['cargo']); ?>
					</span>
				</div>
				<div class="avatar-header">
					<img src="<?php if (isset($_SESSION['dataSession'][ 'url_foto'])){echo $_SESSION['dataSession'][ 'url_foto'];}else{echo '../assets/avatar/avatar-user-lg.jpg';} ?>" alt="..." class="img-circle">
				</div>
				</a>
				<!-- Inicio Menu header -->
				<div class="menu">
					<div class="dropdown">
						<button class="btn btn-default dropdown-toggle" type="button" id="dropdownmenu1" data-toggle="dropdown" aria-extended="true">
							<span class="lnr lnr-menu"></span><span class="caret"></span>
						</button>
						<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownmenu1">
							<li><a href="./"><i class="fa fa-home" aria-hidden="true"></i></span>Inicio <i class="fa fa-chevron-right icon-right" aria-hidden="true"></i></a>
							</li>
							<?php if($_SESSION['dataSession'][ 'perfil'] != 'Usuario'){ ?>
							<li><a href="./courses-list.php"><i class="fa fa-book" aria-hidden="true"></i>Cursos <i class="fa fa-chevron-right icon-right"  aria-hidden="true"></i></a>
							</li>
							<?php } ?>
							<li><a href="./profile.php"><i class="fa fa-user" aria-hidden="true"></i> Perfil <i class="fa fa-chevron-right icon-right" aria-hidden="true"></i></a>
							</li>
							<?php if($_SESSION['dataSession'][ 'perfil'] != 'Usuario'){ ?>
							<li><a href="./users-list.php"><i class="fa fa-users" aria-hidden="true"></i> Usuarios <i class="fa fa-chevron-right icon-right" aria-hidden="true"></i></a>
							</li>
							<?php } ?>
							<li><a href="../salir.php"><i class="fa fa-times" aria-hidden="true"></i>Cerrar sesión</a>
							</li>
						</ul>
					</div>
				</div>
				<!-- Fin Menu header -->
			</div>
		</div>
		<p class="title-p"></p>
	</div>
</header>