<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingresos extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('Ingresos_model', 'ingresos');
		$this->load->model('Productos_model', 'productos');
		$this->load->model('Tipos_Ingresos_model', 'tipos');
	}

	public function index()
	{
		$data = [
			'title' => 'Panel de Control: Ingresos',
			'tipos' => $this->tipos->get_all(),
			'productos' => $this->productos->get_all()
		];

		$this->load->view('ingresos', $data);
	}

	public function ajax_list()
	{
		$list = $this->ingresos->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $ingreso) {

			$no++;
			$row = $this->_formatList($ingreso);

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_ingreso('."'".$ingreso->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_ingreso('."'".$ingreso->id."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->ingresos->count_all(),
			"recordsFiltered" => $this->ingresos->count_filtered(),
			"data" => $data,
		);

		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->ingresos->get_by_id($id);
		$data->fecha = ($data->fecha == '0000-00-00') ? '' : $data->fecha; // if 0000-00-00 set tu empty for datepicker compatibility
		
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$this->_validate();

		// Extra adds (tipo, marca, talle, color)
		if ($this->input->post('id_producto') == 'new' && $this->input->post('id_producto_new') != '') 
		{
			$dataProducto = ['producto' => $this->input->post('id_producto_new')];
			$idProducto = $this->producto->save($dataProducto);
		};
		if ($this->input->post('id_tipo') == 'new' && $this->input->post('id_tipo_new') != '') 
		{
			$dataTipo = ['tipo' => $this->input->post('id_tipo_new')];
			$idTipo = $this->tipos->save($dataTipo);
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

		$insert = $this->ingresos->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'id_producto' => $this->input->post('id_producto'),
			'id_tipo' => $this->input->post('id_tipo'),
			'monto' => $this->input->post('monto'),
			'descripcion' => $this->input->post('descripcion'),
			'fecha' => $this->input->post('fecha'),
		);
		$this->ingresos->update(array('id' => $this->input->post('id')), $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->ingresos->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

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

	private function _formatList($ingreso)
	{
		$tipo = $this->tipos->get_by_id($ingreso->id_tipo);
		$producto = $this->productos->get_by_id($ingreso->id_producto);
		
		$row = array();

		if ($tipo->tipo == 'Producto')
			$row[] = '<a href="productos">'.$tipo->tipo.'</a>';
		else
			$row[] = $tipo->tipo;

		$row[] = (isset($producto->producto)) ? $producto->producto : '';
		$row[] = $ingreso->monto;
		$row[] = $ingreso->descripcion;
		$row[] = $ingreso->fecha;
		return $row;
	}
}