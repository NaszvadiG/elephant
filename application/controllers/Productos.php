<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Productos extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('Productos_model', 'producto');
		$this->load->model('Tipos_model', 'tipo');
		$this->load->model('Marcas_model', 'marca');
		$this->load->model('Colores_model', 'color');
		$this->load->model('Talles_model', 'talle');
	}

	public function index()
	{
		$data = [
			'title' => 'Panel de Control: Productos',
			'tipos' => $this->tipo->get_all(),
			'marcas' => $this->marca->get_all(),
			'colores' => $this->color->get_all(),
			'talles' => $this->talle->get_all()
		];

		$this->load->view('productos', $data);
	}

	public function ajax_list()
	{
		$list = $this->producto->get_datatables();
		$data = array();
		$no = $_POST['start'];

		foreach ($list as $producto) {
			$tipo = $this->tipo->get_by_id($producto->id_tipo);
			$marca = $this->marca->get_by_id($producto->id_marca);
			$talle = $this->talle->get_by_id($producto->id_talle);
			$color = $this->color->get_by_id($producto->id_color);
			$no++;
			$row = array();
			$row[] = $tipo->tipo;
			$row[] = $marca->marca;
			$row[] = $talle->talle;
			$row[] = $color->color;
			$row[] = $producto->producto;
			$row[] = $producto->precio;
			$row[] = $producto->fecha;

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Editar" onclick="edit_producto('."'".$producto->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Editar</a>
			<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Eliminar" onclick="delete_producto('."'".$producto->id."'".')"><i class="glyphicon glyphicon-trash"></i> Eliminar</a>';

			$data[] = $row;
		}

		$output = array(
			"draw" => $_POST['draw'],
			"recordsTotal" => $this->producto->count_all(),
			"recordsFiltered" => $this->producto->count_filtered(),
			"data" => $data,
		);

		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->producto->get_by_id($id);
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
			$idTipo = $this->tipo->save($dataTipo);
		};
		if ($this->input->post('id_marca') == 'new' && $this->input->post('id_marca_new') != '') 
		{
			$dataMarca = ['marca' => $this->input->post('id_marca_new')];
			$idMarca = $this->marca->save($dataMarca);
		};
		if ($this->input->post('id_talle') == 'new' && $this->input->post('id_talle_new') != '') 
		{
			$dataTalle = ['talle' => $this->input->post('id_talle_new')];
			$idTalle = $this->talle->save($dataTalle);
		};
		if ($this->input->post('id_color') == 'new' && $this->input->post('id_color_new') != '') 
		{
			$dataColor = ['color' => $this->input->post('id_color_new')];
			$idColor = $this->color->save($dataColor);
		};

		$data = array(
			'id_tipo' => ($this->input->post('id_tipo') == 'new') ? $idTipo : $this->input->post('id_tipo'),
			'id_marca' => ($this->input->post('id_marca') == 'new') ? $idMarca : $this->input->post('id_marca'),
			'id_talle' => ($this->input->post('id_talle') == 'new') ? $idTalle : $this->input->post('id_talle'),
			'id_color' => ($this->input->post('id_color') == 'new') ? $idColor : $this->input->post('id_color'),
			'producto' => $this->input->post('producto'),
			'precio' => $this->input->post('precio'),
			'fecha' => $this->input->post('fecha'),
		);
		$insert = $this->producto->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$this->_validate();
		$data = array(
			'id_tipo' => $this->input->post('id_tipo'),
			'id_marca' => $this->input->post('id_marca'),
			'id_talle' => $this->input->post('id_talle'),
			'id_color' => $this->input->post('id_color'),
			'producto' => $this->input->post('producto'),
			'precio' => $this->input->post('precio'),
			'fecha' => $this->input->post('fecha')
		);
		$this->producto->update(array('id' => $this->input->post('id')), $data);

		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->producto->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	private function _validate()
	{
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		/*if($this->input->post('id_tipo') == '')
		{
			$data['inputerror'][] = 'id_tipo';
			$data['error_string'][] = 'First name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('id_marca') == '')
		{
			$data['inputerror'][] = 'id_marca';
			$data['error_string'][] = 'Last name is required';
			$data['status'] = FALSE;
		}

		if($this->input->post('id_talle') == '')
		{
			$data['inputerror'][] = 'id_talle';
			$data['error_string'][] = 'Please select id_talle';
			$data['status'] = FALSE;
		}

		if($this->input->post('id_color') == '')
		{
			$data['inputerror'][] = 'id_color';
			$data['error_string'][] = 'Addess is required';
			$data['status'] = FALSE;
		}*/

		if($this->input->post('producto') == '')
		{
			$data['inputerror'][] = 'producto';
			$data['error_string'][] = 'producto is required';
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
}