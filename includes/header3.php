<?php

if ( !isset( $_SESSION[ 'dataSession' ] ) ) {

	header( 'Location: /index.php' );

}

?>

<header class="h-back2">

	<div class="container-fluid">

		<div class="row">

			<div class="col-xs-12 col-sm-8 col-md-9 logo"><a href="#"><img src="../assets/img/logo-p.png"></a><span>|</span> Manejo de recursos BT</div>

			

			<div class="col-sx-12 col-sm-4 col-md-3 icon-h3">

				<a href="javascript:back_course();">

					<p><i class="fa fa-sign-out" aria-hidden="true"></i>SALIR</p>

				</a>

			</div>

		</div>

	</div>

</header>

<script>

function back_course( ) {

	//window.history.back();

	location.href="/Usuario/course-view.php?idCourse="+id_course;

}

</script>