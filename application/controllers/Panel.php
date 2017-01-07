<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Panel extends CI_Controller {
 
	function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->model('Ingresos_model', 'ingresos');
		$this->load->model('Gastos_model', 'gastos');
	}

	function index()
	{
		$data = [
			'title' => 'Panel del control'
		];

		$this->load->view("panel", $data);
	}

	public function balance()
	{
		$ingresos = $this->ingresos->get_balance();
		$gastos   = $this->gastos->get_balance();

		$meses = [
			1 => 'Enero',
			2 => 'Febrero',
			3 => 'Marzo',
			4 => 'Abril',
			5 => 'Mayo',
			6 => 'Junio',
			7 => 'Julio',
			8 => 'Agosto',
			9 => 'Septiembre',
			10 => 'Octubre',
			11 => 'Noviembre',
			12 => 'Diciembre'
		];

		$array[] = ['Mes', 'Ingreso', 'Gasto'];
		$ingresos = 100;
		$gastos = 50;

		foreach ($meses as $numero => $mes) {
			$array[] = [
				$mes,
				$ingresos++,
				$gastos++
			];
		}

		echo json_encode($array);
	}
}