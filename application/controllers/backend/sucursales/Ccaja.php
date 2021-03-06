<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ccaja extends CI_Controller {

	function __construct()
	{
		parent::__construct();		
		$this->load->helper('url');		
		$this->load->database('default');	
		$this->load->model('backend/sucursales/caja_model');	
		$this->load->model('backend/convert/convert_model');
		$this->load->model('backend/login/login_model');		
	}

	public function save_compras()
	{	
		$this->caja_model->save_compras($_POST);	

	}

	public function caja_view($id_sucursal)
	{
		$data['idSucursal'] = $id_sucursal;
		$data['datoSucursal'] = $this->caja_model->getDatosSucursal($id_sucursal);
		$data['pedidos'] = $this->caja_model->getPedidosDespachoBySucursal($id_sucursal);
		$data['detallePedido'] = $this->caja_model->getPedidosByDetalle($id_sucursal);	
		$this->load->view('backend/sucursales/Vcaja.php',$data);
	}

	public function get_lastPedidos()
	{

		if ($_POST['lastPedido'] != 0) 
		{
			$numPedidos = $this->caja_model->get_lastPedidos($_POST);
			if(empty($numPedidos))
			{
				echo 0;	
			}
			else
			{
				echo 1;
			}	
		}
		else
		{
			$numPedidos = $this->caja_model->get_lastPedidosNull($_POST);
			if(empty($numPedidos))
			{
				echo 0;	
			}
			else
			{
				echo 1;
			}	
		}
		
	}

	public function cerrar_cuenta()
	{	
		$this->caja_model->cerrar_cuenta($_POST);	
	}

	public function anular_cuenta()
	{	

		$idPedido = $_POST['idpedidounico'];
		$nota = $_POST['commentAnulacion'];
		$grupo = "CUENTA";
		$accion = "anulacion";
		$valor = "0.0";
		$existPedido = $this->caja_model->getPedidoCuenta($idPedido);
		if($existPedido[0]['numPedidos']== 0)
		{
			$this->caja_model->anular_cuenta_insert($_POST);
			$this->caja_model->addevento_historial($idPedido, $nota, $grupo, $accion, $valor);	
		}
		else
		{
			$this->caja_model->anular_cuenta_update($_POST);
			$this->caja_model->addevento_historial($idPedido, $nota, $grupo, $accion, $valor);
		}
		
	}


	public function addevento_historial()
	{	
		$idPedido = $_POST['idpedidounico']; 
		$nota =  $_POST['comment'];
		$grupo = "DESCUENTO";
		$accion = "descuento ".$_POST['porcent'].".00%";
		$valor = $_POST['porcent'];
		$this->caja_model->addevento_historial($idPedido, $nota, $grupo, $accion, $valor);
		
	}
		
	public function separar_cuenta()
	{	
		$this->caja_model->separar_cuenta($_POST);	
	}

	public function cambiar_mesa()
	{	
		$this->caja_model->cambiar_mesa($_POST);	
	}

	public function combinar_mesa()
	{	
		$this->caja_model->combinar_mesa($_POST);	
	}
	public function mover_mesa()
	{	
		$this->caja_model->udpateMesa($_POST['numMesa'], $_POST['idpedidounico']);	
		$this->caja_model->addevento_historial($_POST['idpedidounico'], "Se cambio de mesa", "Cuenta", "Cambio de mesa", $_POST['numMesa']);
	}
	
	public function descuento_cupon()
	{	
		$validaCupon = $this->caja_model->validaCupon($_POST);	
		
		$idCuponValidar = $validaCupon[0]['id_cupon'];
		$sucursalID = $_POST['sucursalID'];

		if($validaCupon[0]['validanum'] >=1)
		{
			if($validaCupon[0]['estado_cupon'] == 0)
			{
				$this->caja_model->confirmar_cupon($idCuponValidar, $sucursalID);

				$idPedido = $_POST['idpedidounico']; 
				$nota =  "Descuento por cupon";
				
				$valorPorcent = explode(".", $validaCupon[0]['valor_categoria']);

				$tipoGrupo = ($validaCupon[0]['valor_categoria'] < 1) ? "CUPON%" : "CUPON$";

				$tipoValor = ($validaCupon[0]['valor_categoria'] < 1) ? "0.".$valorPorcent[1].".00%" : "$".$validaCupon[0]['valor_categoria'];

				$grupo = $tipoGrupo;
				$accion = "descuento por ".$valorPorcent[1];
				$valor = $validaCupon[0]['valor_categoria'];
				$this->caja_model->addevento_historial($idPedido, $nota, $grupo, $accion, $valor);
				echo "Descuento realizado correctamente";
			}
			else
			{
				echo "El cupon ingresado ya fue canjeado  en la sucursal de ". $validaCupon[0]['nombre_sucursal'];
			}
		}
		else
		{
			echo "El cupon ingresado no fue encontrado :(";
		}
		
	}
	public function eliminar_item()
	{	
		$this->caja_model->eliminar_item($_POST);
		echo "Item cancelado correctamente";	
	}

	public function quitar_propina()
	{

		$idPedido = $_POST['idpedidounico']; 
		$nota =  $_POST['commentPropina'];
		$grupo = "PROPINA";
		$accion = "flag_nopropina";
		$valor = 1;
		$this->caja_model->addevento_historial($idPedido, $nota, $grupo, $accion, $valor);
		echo "Propina eliminada correctamente";	
	}

	public function imprimir_tiquete()
	{
		$data = $this->caja_model->data_tiquete($_POST);
		$idPedido = $_POST['idpedidounico']; 
		$nota =  "Impresion de tiquetes";
		$grupo = "ORDENES";
		$accion = "TIQUETE";
		$valor = 1;
		$dateregistro = date("Y-m-d H:i:s");
		$this->caja_model->addevento_historial($idPedido, $nota, $grupo, $accion, $valor);

		// HTML Header
		$tiqueteHTML = '<div class="orden">
		<p style="font-weight:bold;text-align:center;">'.$data[0]['nombre_legal'].'</p>
		<p style="text-align:center;">'.$data[0]['nombre_empresa'].'</p>
		<p style="text-align:center;">Tel. Oficinas administrativas:<br />'.$data[0]['telefono'].'</p><br />
		<div style="height:1.5em;text-align:center;">
			<span class="grupo" style="height:1.5em;text-align:center;font-size: 16px; font-weight:bold;">Mesa #'.$data[0]['numero_mesa'].'
			</span>
		</div>';

	 	$dataSeparada = explode(",", $data[0]['pedido']);

	 	foreach ($dataSeparada as $value) 
	 	{
	 		$itemSeparado = explode("_", $value);
	 		//   HTML Pedido	
			$tiqueteHTML .= '<div class="pedido" style="padding:0px;margin:0px;">
			<div class="producto" style="padding:0px;margin:0px;">'.$itemSeparado[0].' 
				<div style="z-index:99;float:right;">'.$itemSeparado[1].'</div>
			</div>
			</div>';	
	 	}

	
		// Footer
		$tiqueteHTML .= '<br /><br /><table style="width:100%;" class="totales">
		<tr>
			<td>SubTotal:</td>
			<td>'.$data[0]['moneda'].' '.$data[0]['SubTotal'].'</td>
		</tr>
		<tr>
			<td>Propina (10%):</td>
			<td>'.$data[0]['moneda'].' '.$data[0]['SubTotal'] * 0.10.'</td>
		</tr>
		<tr>
			<td>Total:</td>
			<td>'.$data[0]['moneda'].' '.($data[0]['SubTotal']  + ($data[0]['SubTotal'] * 0.10)).' </td>
		</tr>
		</table>
		<br /><br /><br /><br />
		<p style="text-align:center;">'.$data[0]['nombre_sucursal'].'<br />
		'.$data[0]['direccion'].',<br />
		'.$data[0]['nombre_departamento'].'.<br />
		¡Gracias por su compra!<br />
		<br />'.date("Y-m-d H:i:s").'</p>';

		$this->caja_model->add_comanda($tiqueteHTML, 0, $dateregistro, "tiquetes");
		echo $tiqueteHTML;
			
	}

	public function imprimir_factura()
	{

		$this->caja_model->imprimir_factura($_POST);
		echo "Propina eliminada correctamente";	
	}

	
}
