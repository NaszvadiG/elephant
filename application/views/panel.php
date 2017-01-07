<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?=$title?></title>
	<link rel="stylesheet" href="<?=base_url("assets/bootstrap/css/bootstrap.css");?>" />
	<link rel="stylesheet" href="<?=base_url("assets/bootstrap/css/panel.css");?>" />
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
	<ul class="nav navbar-nav">
		<li class="active">
			<a href="/panel">Panel</a>
		</li>
		<li>
			<a href="/panel/productos">Productos</a>
		</li>
		<li>
			<a href="/panel/ingresos">Gastos</a>
		</li>
		<li>
			<a href="/panel/ingresos">Ingresos</a>
		</li>
	</ul>
</nav>
<div class="container">
	<div class="row">
		<div class=".col-md-6 .col-md-offset-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Balance</h3>
				</div>
				<div class="panel-body">
					<div id="chart_div">
					</div>
					<button type="button" class="btn btn-primary" id="reload-charts">Recargar</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?=base_url("assets/jquery/jquery-2.2.4.min.js");?>"></script>
<script type="text/javascript" src="<?=base_url("assets/bootstrap/js/bootstrap.js");?>"></script>
<script type="text/javascript" src="<?=base_url("assets/bootstrap/js/bootstrap.js");?>"></script>
<script src="<?php echo base_url('public/js/panel.js')?>"></script>
</body>
</html>