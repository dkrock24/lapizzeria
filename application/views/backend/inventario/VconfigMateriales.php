<link href="../../../assets/plugins/input-text/style.min.css" rel="stylesheet">
<script>
  
  //-------- Asociar proveedo a material-------------------
  $(".asociarProveedor").click(function()
  {
      var materialSucursalId = $(this).find(".materiaSucursalID").val();
      var proveedorId = $(this).find(".proveedorID").val();
      //alert(materialSucursalId+proveedorId); 
       $.ajax
        ({
            url: "../inventario/Cinventario/asociar_proveedor_meterial",
            type: "post",
            data: {materialSucursalId:materialSucursalId,proveedorId,proveedorId},                           
          
            success: function(data)
            {                                                  
              //alert("vengo");
              $(".pages").load("../inventario/Cinventario/config_meteriales/"+materialSucursalId);
          
            },
            error:function()
            {

            }
        });
  });
  //-------------------------Fin -----------------------------------

  //-------- Asociar precion de proveedo a material-------------------
  $(".asociaPrecio").click(function()
  {
      $(this).find(".load-precio").show();
      $(this).find(".asociaPrecio").hide();
      /*var materialSucursalId = $(this).find(".materiaSucursalID").val();
      var proveedorId = $(this).find(".proveedorID").val();
       //alert(materialSucursalId+proveedorId); 
       $.ajax
        ({
            url: "../inventario/Cinventario/asociar_proveedor_meterial",
            type: "post",
            data: {materialSucursalId:materialSucursalId,proveedorId,proveedorId},                           
          
            success: function(data)
            {                                                  
            

          
            },
            error:function()
            {

            }
        });*/
  });
  //-------------------------Fin -----------------------------------



//-------- Desasociar proveedor a material-------------------
  $(".desasociarProvee").click(function()
  {
     var materialSucursalId = $(this).find(".materiaSucursalID").val();
      var proveedorId = $(this).find(".proveedorID").val();
       //alert(materialSucursalId+proveedorId); 
       $.ajax
        ({
            url: "../inventario/Cinventario/desasociar_proveedor_meterial",
            type: "post",
            data: {materialSucursalId:materialSucursalId,proveedorId,proveedorId},                           
          
            success: function(data)
            {                                                  
            
              $(".pages").load("../inventario/Cinventario/config_meteriales/"+materialSucursalId);
          
            },
            error:function()
            {

            }
        });
  });
  //-------------------------Fin -----------------------------------


//-------- Desasociar precion de proveedo a material-------------------
  $(".desasociarPrecio").click(function()
  {
     var materialSucursalId = $(this).find(".materiaSucursalID").val();
      var proveedorId = $(this).find(".proveedorID").val();
       alert(materialSucursalId+proveedorId); 
       $.ajax
        ({
            url: "../inventario/Cinventario/asociar_proveedor_meterial",
            type: "post",
            data: {materialSucursalId:materialSucursalId,proveedorId,proveedorId},                           
          
            success: function(data)
            {                                                  
            

          
            },
            error:function()
            {

            }
        });
  });
  //-------------------------Fin -----------------------------------

   //-----------------save proveedor--------------
      $("#saveConfigMaterial").click(function()
      {
        var minimoExistencia = $("#miniExistencia").val();
        var maximoExistencia = $("#maximoExistencia").val();
        var sucursalID = $(".sucursalID").val();
        
        if (minimoExistencia !="0" && maximoExistencia !="0") 
        {
          $.ajax
             ({
              url: "../inventario/Cinventario/save_config_material",
              type:"post",
              data: $("#configMaterial").serialize(),
              success: function()
              {
                //alert("Material agregado correctamente");
                $(".load-inventario").load("../inventario/Cinventario/inventarioBySucursal/"+sucursalID);
              },
              error:function(){
                  alert("failure");
              }
            });
        }
        else
        {
           alert("El mínimo y el máximo de existencias son campos necesarios!"); 
        }

      });
      //-------------------------Fin -----------------------------------
      


</script>

<style>
  .table-dynamic{width: 100%;}
  .form-inline .form-control {
    width:85%;
    font-weight: 10px;
    padding: 4px;
  }

  .input__label-content{
    margin-top: -20px;
  }
  .line{
    
  }
  .titleMaterial
  {
    font-weight: bold;
  }

</style>

<div class="addIngredientes" style="background: #88b32f;height: 2px;">
<div class="col-md-12">
  <h1 class='titleMaterial'></h1>
</div>  
<hr>
  <form id="configMaterial" action="post">
  <div class="col-md-12">
    <h1>
    <?php
     //var_dump($materialSucursal);
     ?> 
      <?php echo $materialSucursal[0]['nombre_matarial']."<br>";?>  
       <?php echo "Unidad medida: ".$materialSucursal[0]['nombre_unidad_medida']."<br>";?>  
      <?php echo $materialSucursal[0]['codigo_meterial']."<br>";?>
    </h1>
  </div>
  <div class="col-md-12">
               <div class="col-md-6">
                  <span class="input input--hoshi">
                      <input class="input__field input__field--hoshi" type="text" id="miniExistencia" required="true"  name="miniExistencia" value="<?php echo $materialSucursal[0]['minimo_existencia'];?>" />
                      <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                      <span class="input__label-content">Minimo en existencia</span>
                      </label>
                  </span>
                </div>  
                
                 <div class="col-md-6">  
                   <span class="input input--hoshi">
                      <input class="input__field input__field--hoshi" type="text" id="maximoExistencia" name="maximoExistencia" value="<?php echo $materialSucursal[0]['maximo_existencia'];?>" />
                      <label class="input__label input__label--hoshi input__label--hoshi-color-1" for="input-4">
                      <span class="input__label-content">Maximo en existencia</span>
                      </label>
                  </span>
                </div>  
  </div>              
  

 <div class="col-md-12">   
          <H1>Lista de proveedores </H1><br>
            <span class="well center-block list-proveedores"> 
              <table class="table table-hover">
                <thead class='titulos'>
                    <tr>
                        <th width="40%">Nombre proveedor</th>
                        <th width="40%">Asociar</th>
                      <!--  <th width="30%">Asignar precio</th>-->
                    </tr>
                </thead>
                <tbody>
                <?php
                    if (!empty($materialSucursal)) 
                    {
                      //var_dump($materialSucursal);
                      foreach ($materialSucursal as $value) 
                      {
                      ?>
                    <tr>
                        <td><?php echo $value['nombre_proveedor'];  ?></td>
                        <td>
                        <?php
                            if ($value['listInventarioProvee'] == 'Desasociar') 
                            {
                        ?>

                        <button type="button" class="btn btn-primary btn-sm desasociarProvee fa fa-check-circle">
                          <input type="hidden" name="proveedorID" class="proveedorID" value="<?php echo $value['id_proveedor'] ?>">
                          <input type="hidden" name="materiaSucursalID" class="materiaSucursalID" value="<?php echo $materialSucursal[0]['id_inventario_sucursal'] ?>">
                            Desasociar
                        </button>

                        <?php 
                            }
                            else
                            {
                        ?>
                          
                          <button type="button" class="btn btn-primary btn-sm asociarProveedor">
                          <input type="hidden" name="proveedorID" class="proveedorID" value="<?php echo $value['id_proveedor'] ?>">
                          <input type="hidden" name="materiaSucursalID" class="materiaSucursalID" value="<?php echo $materialSucursal[0]['id_inventario_sucursal'] ?>">
                            Asociar
                        </button>
                        <?php      
                            }
                        ?> 
                        </td>
                         
                    </tr>        
                 <!--  Vista dinamica de prodcutos -->
          <?php
            }
        }
           
      ?> 
                   
    </tbody>   
  </table>
  </span>            
  </div>  
  
 <div class="col-md-12">
          <div class="col-md-6"> 
            <span class="input input--hoshi">
            <input type="hidden" name="catalogoSucursalID" class="catalogoSucursalID" value="<?php echo $materialSucursal[0]['id_inventario_sucursal'] ?>">
            <input type="hidden" name="sucursalID" class="sucursalID" value="<?php echo $materialSucursal[0]['id_sucursal'] ?>">
              <button type="button" id="saveConfigMaterial" class="btn btn-primary">Guardar</button>
            </span>
          </div>


  </form>
  </div>



