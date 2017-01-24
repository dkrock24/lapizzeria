<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cdashboard extends CI_Controller {

	function __construct()
	{
		parent::__construct();		
		$this->load->helper('url');		
		$this->load->database('default');	
		$this->load->model('backend/admin/dashboard_model');			
	}

	public function index()
	{		
		$data['querys'] =  $this->dashboard_model->getQuerys();
		$data['graficos'] = $this->dashboard_model->getTiposGraficas();
		$data['reportes'] = $this->dashboard_model->getReportes();
		$data['consultas1'] = $this->dashboard_model->getConsultas();
		
		$this->load->view('backend/admin/Vdashboard.php',$data);
	}

	public function guardar_consulta(){
		$this->dashboard_model->guardar_consulta($_POST);	
		$this->index();	
	}

	public function eliminar_consulta($id){
		$this->dashboard_model->eliminar_consulta($id);	
		$this->index();	
	}	

	public function cargar_grafica($id){
		$data = $this->dashboard_model->getParametros($id);	
		$html = "";
		$html .=  "		
			<table width='100%' border=0 class='table'>
			";
			if($data!=null)
			{
				foreach ($data as $value)
				{
					$html .="
					<div class='row'>
					<tr class='espacios'>
						<td width='50%'align='center'><span class='titulo'>".$value->title."</span>
						<td width='50%'>							
							<input class='". $value->input_name. "' type='".$value->input_type."' name='". $value->input_name. "'>
						</td>												
					</tr>
					</div>
					";			
				}
			}				
			$html .="
			<input type='hidden' name='id_consulta' value='".$id."'>
			</table>		
		<div id='cc'></div>
		";
		echo $html;
	}

	//Construye la Query de con los parametros y la envia ha procesar
	public function get_query(){
		//* Inicio Proceso para obtener Data
		$id_consulta=  $_POST['id_consulta'];		
		$aSettings 	= $this->dashboard_model->getDataQuery($id_consulta);	
		$data 		= array();

		foreach ($aSettings as $value)
		{
			$data['id_global_report'] 	= $value->id_global_report;
			$data['descripcion'] 		= $value->description;
			$data['query'] 				= $value->query;
			$data['title'] 				= $value->title;
			$data['chrarType'] 			= $value->chart_type;
			$data['chartFunction'] 		= $value->chart_function;
		}

		$type_chart 	= $data['chrarType'];
		$function 		= $data['chartFunction'];
		$description 	= $data['descripcion'];
		$aInput 		= array();
		$aInputs 		= $this->dashboard_model->getParametros($id_consulta);
		$oParameter 	= array();

		foreach($aInputs as $values)
		{
		   	$oParameter[] = $_POST[$values->input_name];
		}

		$aSettings 	= $this->dashboard_model->getDataQuery($id_consulta);
		$query 		= $aSettings[0]->query;
		$builQuery 	= $this->dashboard_model->getBuilQuery($oParameter,$query);
		$this->ActionReport($builQuery,$type_chart,$function,$description);
	}

	public function ActionReport($dataChart,$type_chart,$function,$description)
	{		
		$a = $dataChart;
		if($type_chart=='Data Table')
		{
			$this->build_table($dataChart);
		}
		else
		{
			
			$this->myChart($dataChart,$type_chart,$function,$description);  
			//$this->build_table($a);  
        }	
	}

	public function myChart($dataChart,$type_chart,$function,$description)
	{
			$url = 'http://45.33.3.227/lapizzeria/assets/globalreport/js/generatorCharts.js';
	        echo '<script type="text/javascript" src="'.$url.'">    
	        </script>';
	        $data 	= array();
	        $cont 	=0;
	        $alias 	= array();


	        $c = json_encode( $dataChart, JSON_NUMERIC_CHECK );


	        echo '	<div id="chart" style="width: 100% !important;"></div>';
	        echo "	<script type='text/javascript'>
	        			var obj = JSON.parse('".$c."');
	        			typeChart(obj,'".$type_chart."','".$function."','".$description."');
	        		</script>";

	}

	function build_table($array)
	{
	    // start table
	    $cont=1;
	    $html = '<table class="build_table">';
	    $html .= '<tr><th>#</th>';
	    foreach($array[0] as $key=>$value)
	    {
	        $html .= '<th>' . $key . '</th>';
	    }
	    $html .= '</tr>';
	    foreach( $array as $key=>$value)
	    {
	        $html .= '<tr><td>'.$cont.'</td>';

	        foreach($value as $key2=>$value2)
	        {
	            $html .= '<td>' . $value2 . '</td>';
	        }
	        $html .= '</tr>';
	        $cont++;
	    }
	    $html .= '</table>';
	    echo  $html;
	}
}
