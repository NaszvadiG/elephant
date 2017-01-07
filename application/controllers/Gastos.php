<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gastos extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('Gastos_model', 'gasto');
		$this->load->model('Productos_model', 'productos');
		$this->load->model('Tipos_Gastos_model', 'tipos');
	}

	public function index()
	{
		$data = [
			'title' => 'Panel de Control: Gastos',
			'tipos' => $this->tipos->get_all(),
			'productos' => $this->productos->get_all()
		];

		$this->load->view('gastos', $data);
	}

	public function ajax_list()
	{
		$list = $this->gasto->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $gasto) {

			$no++;
			$row = $this->_formatList($gasto);

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_gasto('."'".$gasto->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_gasto('."'".$gasto->id."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->gasto->count_all(),
			"recordsFiltered" => $this->gasto->count_filtered(),
			"data" => $data,
		);

		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->gasto->get_by_id($id);
		$data->fecha = ($data->fecha == '0000-00-00') ? '' : $data->fecha; // if 0000-00-00 set tu empty for datepicker compatibility
		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();

		// Extra adds (tipo, marca, talle, color)
		if ($this->input->post('id_tipo') == 'new' && $this->input->post('id_tipo_new') != '') 
		{
			$dataTipo = ['tipo' => $this->input->post('id_tipo_new')];
			$idTipo = $this->tipos->save($dataTipo);
		};
		if ($this->input->post('id_producto') == 'new' && $this->input->post('id_producto_new') != '') 
		{
			$dataProducto = ['producto' => $this->input->post('id_producto_new')];
			$idProducto = $this->producto->save($dataProducto);
		};

		$data = array(
			'id_tipo' => ($this->input->post('id_tipo') == 'new') ? $idTipo : $this->input->post('id_tipo'),
			'monto' => $this->input->post('monto'),
			'descripcion' => $this->input->post('descripcion'),
			'fecha' => $this->input->post('fecha'),
		);

		if ($this->input->post('id_producto') != '') {
			$data['id_producto'] = ($this->input->post('id_producto') == 'new') ? $idProducto : $this->input->post('id_producto');
		}

		$insert = $this->gasto->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'id_tipo' => $this->input->post('id_tipo') ? $this->input->post('id_tipo') : NULL,
			'id_producto' => $this->input->post('id_producto') ? $this->input->post('id_producto') : NULL,
			'monto' => $this->input->post('monto'),
			'descripcion' => $this->input->post('descripcion'),
			'fecha' => $this->input->post('fecha'),
		);
		$this->gasto->update(array('id' => $this->input->post('id')), $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->gasto->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('id_tipo') == '')
		{
			$data['inputerror'][] = 'id_tipo';
			$data['error_string'][] = 'tipo is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('monto') == '')
		{
			$data['inputerror'][] = 'monto';
			$data['error_string'][] = 'monto is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('descripcion') == '')
		{
			$data['inputerror'][] = 'descripcion';
			$data['error_string'][] = 'descripcion is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('fecha') == '')
		{
			$data['inputerror'][] = 'fecha';
			$data['error_string'][] = 'fecha is required';
			$data['status'] = FALSE;
		}

		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}

	private function _formatList($gasto)
	{
		$tipo = $this->tipos->get_by_id($gasto->id_tipo);
		$producto = $this->productos->get_by_id($gasto->id_producto);
		
		$row = array();

		if ($tipo->tipo == 'Producto')
			$row[] = '<a href="productos">'.$tipo->tipo.'</a>';
		else
			$row[] = $tipo->tipo;

		$row[] = (isset($producto->producto)) ? $producto->producto : '';
		$row[] = $gasto->monto;
		$row[] = $gasto->descripcion;
		$row[] = $gasto->fecha;
		return $row;
	}
}