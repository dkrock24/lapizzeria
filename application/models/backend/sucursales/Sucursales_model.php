<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class sucursales_model extends CI_Model
{
    const cargos = 'sr_cargos';
    const roles = 'sr_roles';
    const avatar = 'sr_avatar';
    const usuarios = 'sr_usuarios';
    const sys_sucursal = 'sys_sucursal';
    const sys_pais_departamento  = 'sys_pais_departamento';
    const sys_pais          = 'sys_pais';
    const sys_sucursal_int_usuarios = 'sys_sucursal_int_usuarios';
    const sys_nodo = 'sys_nodo';
    const sys_sucursal_nodo = 'sys_sucursal_nodo';
    const sys_productos = 'sys_productos';
    const sys_productos_sucursal = 'sys_productos_sucursal';
    const categorias = 'sys_categoria_producto';
    const sucursal_suarios = 'sys_sucursal_int_usuarios';
    const producto_detalle = 'sys_detalle_producto';
    const unidad_medida = 'sys_unidad_medida';
    
    

    
    public function __construct()
    {
        parent::__construct();
        
    }
    
    public function getSucursalesByUser($id_user)
    {
        $this->db->select('*');
        $this->db->from(self::sys_sucursal);
        $this->db->join(self::sys_sucursal_int_usuarios,' on '. 
                        self::sys_sucursal_int_usuarios.'.id_sucursal = '.
                        self::sys_sucursal.'.id_sucursal');

        $this->db->join(self::usuarios,' on '. 
                        self::usuarios.'.id_usuario = '.
                        self::sys_sucursal_int_usuarios.'.id_usuario');
        $this->db->where(self::usuarios.'.id_usuario',$id_user);
        $query = $this->db->get();
        //echo $this->db->queries[0];
        
        if($query->num_rows() > 0 )
        {
            return $query->result();
        }        
    } 
    public function getSucursalesById($id_sucursal)
    {
        $this->db->select('*');
        $this->db->from(self::sys_sucursal);
        $this->db->join(self::sys_sucursal_nodo,' on '. 
                        self::sys_sucursal_nodo.'.id_sucursal = '.
                        self::sys_sucursal.'.id_sucursal');

        $this->db->join(self::sys_nodo,' on '. 
                        self::sys_nodo.'.id_nodo = '.
                        self::sys_sucursal_nodo.'.id_nodo');
        $this->db->where(self::sys_sucursal.'.id_sucursal',$id_sucursal);
        $query = $this->db->get();
        //echo $this->db->queries[0];
        
        if($query->num_rows() > 0 )
        {
            return $query->result();
        }        
    }  
    public function getSucursalesByNodo($id_sucursal)
    {
        $this->db->select('*');
        $this->db->from(self::sys_sucursal);
        $this->db->join(self::sys_sucursal_nodo,' on '. 
                        self::sys_sucursal_nodo.'.id_sucursal = '.
                        self::sys_sucursal.'.id_sucursal');

        $this->db->join(self::sys_nodo,' on '. 
                        self::sys_nodo.'.id_nodo = '.
                        self::sys_sucursal_nodo.'.id_nodo');
        $this->db->where(self::sys_sucursal.'.id_sucursal',$id_sucursal);
        $this->db->where(self::sys_sucursal_nodo.'.sucursal_estado_nodo',1);
        $query = $this->db->get();
        //echo $this->db->queries[0];
        
        if($query->num_rows() > 0 )
        {
            return $query->result();
        }        
    }  
    public function getSucursalByNodoId($id_nodo,$id_sucursal)
    {
        $this->db->select('*');
        $this->db->from(self::sys_sucursal);
        $this->db->join(self::sys_sucursal_nodo,' on '. 
                        self::sys_sucursal_nodo.'.id_sucursal = '.
                        self::sys_sucursal.'.id_sucursal');

        $this->db->join(self::sys_nodo,' on '. 
                        self::sys_nodo.'.id_nodo = '.
                        self::sys_sucursal_nodo.'.id_nodo');
        $this->db->where(self::sys_sucursal.'.id_sucursal',$id_sucursal);
        //$this->db->where(self::sys_sucursal_nodo.'.sucursal_estado_nodo',1);
        $this->db->where(self::sys_sucursal_nodo.'.id_nodo',$id_nodo);
        $query = $this->db->get();
        //echo $this->db->queries[0];
        
        if($query->num_rows() > 0 )
        {
            return $query->result();
        }        
    }

    public function login($usuario){
        $this->db->select('*');
        $this->db->from(self::usuarios);        
        $this->db->where(self::usuarios.'.usuario',$id_sucursal);        
        $this->db->where(self::usuarios.'.password',$id_nodo);
        $query = $this->db->get();
        //echo $this->db->queries[0];
        
        if($query->num_rows() > 0 )
        {
            return $query->result();
        } 
    }

    public function getCategorias(){
        $this->db->select('*');
        $this->db->from(self::categorias);        
        $query = $this->db->get();
        
        if($query->num_rows() > 0 )
        {
            return $query->result();
        } 
    }

    public function getProductosBySucursales($id_sucursal){
        $this->db->select('*');
        $this->db->from(self::sys_productos.' AS P');        
        $this->db->join(self::sys_productos_sucursal.' AS PS ',' on PS.id_producto = P.id_producto');
        $this->db->join(self::sys_sucursal.' AS S',' on S.id_sucursal = '.'PS.id_sucursal');
        $this->db->join(self::sys_pais_departamento.' AS D',' on D.id_departamento = S.id_departamento');
        $this->db->join(self::sys_pais.' AS PA',' on PA.id_pais = D.id_pais');

        $this->db->where('S.id_sucursal',$id_sucursal);
        $query = $this->db->get();
        
        if($query->num_rows() > 0 )
        {
            return $query->result();
        } 
    }

    public function getUsuariosSucursal($id_sucursal){
        $this->db->select('*');
        $this->db->from(self::sys_sucursal.' AS S');        
        $this->db->join(self::sucursal_suarios.' AS SU ',' on SU.id_sucursal = S.id_sucursal');
        $this->db->join(self::usuarios.' AS U',' on U.id_usuario = '.'SU.id_usuario');
        $this->db->where('S.id_sucursal',$id_sucursal);
        $query = $this->db->get();
        
        if($query->num_rows() > 0 )
        {
            return $query->result();
        } 
    }

    public function getValidarUsuariosSucursal($id_sucursal,$id_mesero){
        $this->db->select('*');
        $this->db->from(self::sys_sucursal.' AS S');        
        $this->db->join(self::sucursal_suarios.' AS SU ',' on SU.id_sucursal = S.id_sucursal');
        $this->db->join(self::usuarios.' AS U',' on U.id_usuario = '.'SU.id_usuario');
        $this->db->where('S.id_sucursal',$id_sucursal);
         $this->db->where('U.id_usuario',$id_mesero);
        $query = $this->db->get();
        
        if($query->num_rows() > 0 )
        {
            return 1;
        } 
    }

    // Validacion de Materias en exsitencia por producto
    public function getProductoItems($sucursal,$id_producto){
        $this->db->select('*');
        $this->db->from(self::sys_productos.' AS P');
        $this->db->join(self::sys_productos_sucursal.' AS PS',' on P.id_producto = '.'PS.id_producto');
        $this->db->join(self::sys_sucursal.' AS S',' on S.id_sucursal = '.'PS.id_sucursal');
        $this->db->join(self::producto_detalle.' AS PD',' on PD.id_producto = '.'P.id_producto');
        $this->db->join(self::unidad_medida.' AS UD',' on UD.id_unidad_medida = '.'PD.unidad_medida_id');
        $this->db->where('S.id_sucursal',$sucursal);
        $this->db->where('P.id_producto',$id_producto);
        $query = $this->db->get();
        
        if($query->num_rows() > 0 )
        {
            return $query->result();
        } 
    }

    
}
/*
 * end of application/models/consultas_model.php
 */
