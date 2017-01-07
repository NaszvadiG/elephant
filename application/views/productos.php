<!DOCTYPE html>
<html>
	<head> 
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?=$title?></title>
	<link href="<?php echo base_url('public/css/productos.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/datatables/css/dataTables.bootstrap.css')?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	</head> 
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<ul class="nav navbar-nav">
			<li>
				<a href="/panel">Panel</a>
			</li>
			<li class="active">
				<a href="/panel/productos">Productos</a>
			</li>
			<li>
				<a href="/panel/gastos">Gastos</a>
			</li>
			<li>
				<a href="/panel/ingresos">Ingresos</a>
			</li>
		</ul>
	</nav>
	<div class="container">
		<h1 style="font-size:20pt">Listado de productos</h1>
		<button class="btn btn-success" onclick="add_producto()"><i class="glyphicon glyphicon-plus"></i> Agregar Producto</button>
		<button class="btn btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Recargar Pagina</button>
		<br />
		<br />
		<table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th>Tipo</th>
					<th>Marca</th>
					<th>Talle</th>
					<th>Color</th>
					<th>Producto</th>
					<th>Precio</th>
					<th>Fecha</th>
					<th style="width:125px;">Accion</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
			<tfoot>
			<tr>
				<th>Tipo</th>
				<th>Marca</th>
				<th>Talle</th>
				<th>Color</th>
				<th>Producto</th>
				<th>Precio</th>
				<th>Fecha</th>
				<th>Accion</th>
			</tr>
			</tfoot>
		</table>
	</div>
 
 
<script src="<?php echo base_url('assets/jquery/jquery-2.2.4.min.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap/js/bootstrap.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/jquery.dataTables.min.js')?>"></script>
<script src="<?php echo base_url('assets/datatables/js/dataTables.bootstrap.js')?>"></script>
<script src="<?php echo base_url('assets/bootstrap-datepicker/js/bootstrap-datepicker.min.js')?>"></script>
<script src="<?php echo base_url('public/js/productos.js')?>"></script>
 
 
<script type="text/javascript">

var save_method; //for save method string
var table;

</script>
 
<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h3 class="modal-title">Formulario de Producto</h3>
			</div>
			<div class="modal-body form">
				<form action="#" id="form" class="form-horizontal">
					<input type="hidden" value="" name="id"/> 
					<div class="form-body">
						<div class="form-group">
							<label class="control-label col-md-3">Tipo</label>
							<div class="col-md-9">
								<select name="id_tipo" class="form-control">
									<option value="">-- Selecciona Tipo --</option>
									<?php foreach ($tipos as $id => $tipo): ?>
									<option value="<?=$tipo->id?>"><?=$tipo->tipo?></option>
									<?php endforeach ?>
									<option value="new">-- Agrega Tipo --</option>>
								</select>
								<input name="id_tipo_new" placeholder="Nuevo Tipo" class="form-control hidden" type="text">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Marca</label>
							<div class="col-md-9">
								<select name="id_marca" class="form-control">
									<option value="">-- Selecciona Marca --</option>
									<?php foreach ($marcas as $id => $marca): ?>
									<option value="<?=$marca->id?>"><?=$marca->marca?></option>
									<?php endforeach ?>
									<option value="new">-- Agrega Marca --</option>
								</select>
								<input name="id_marca_new" placeholder="Nueva Marca" class="form-control hidden" type="text">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Talle</label>
							<div class="col-md-9">
								<select name="id_talle" class="form-control">
									<option value="">-- Selecciona Talle --</option>
									<?php foreach ($talles as $id => $talle): ?>
									<option value="<?=$talle->id?>"><?=$talle->talle?></option>
									<?php endforeach ?>
									<option value="new">-- Agrega Talle --</option>
								</select>
								<input name="id_talle_new" placeholder="Nuevo Talle" class="form-control hidden" type="text">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Color</label>
							<div class="col-md-9">
								<select name="id_color" class="form-control">
									<option value="">-- Selecciona Color --</option>
									<?php foreach ($colores as $id => $color): ?>
									<option value="<?=$color->id?>"><?=$color->color?></option>
									<?php endforeach ?>
									<option value="new">-- Agrega Color --</option>
								</select>
								<input name="id_color_new" placeholder="Nuevo Color" class="form-control hidden" type="text">
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Producto</label>
							<div class="col-md-9">
								<textarea name="producto" placeholder="Producto" class="form-control"></textarea>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Precio</label>
							<div class="col-md-9">
								<textarea name="precio" placeholder="Precio" class="form-control"></textarea>
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="control-label col-md-3">Fecha</label>
							<div class="col-md-9">
								<input name="fecha" placeholder="yyyy-mm-dd" class="form-control datepicker" type="text">
								<span class="help-block"></span>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Guardar</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
</body>
</html>