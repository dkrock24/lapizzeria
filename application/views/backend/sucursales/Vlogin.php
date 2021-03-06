<?php

?>
<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
		

.login-page {
  width: 360px;
  padding: 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #D3D3D3;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.logo{
	background: black;
	padding: 5px;
	margin-top: 0px;
	top: 0px;
}
.sucursal{
	font-family: Arial;
}
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}


.form button {
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline: 0;
  background: #4CAF50;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form button:hover,.form button:active,.form button:focus {
  background: #43A047;
}

.container {
  position: relative;
  z-index: 1;
  max-width: 300px;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.container .info {
  margin: 50px auto;
  text-align: center;
}
.container .info h1 {
  margin: 0 0 15px;
  padding: 0;
  font-size: 36px;
  font-weight: 300;
  color: #1a1a1a;
}
.container .info span {
  color: #4d4d4d;
  font-size: 12px;
}
.container .info span a {
  color: #000000;
  text-decoration: none;
}
.container .info span .fa {
  color: #EF3B3A;
}
body {
  background: #76b852; /* fallback for old browsers */
  background: -webkit-linear-gradient(right, #76b852, #8DC26F);
  background: -moz-linear-gradient(right, #76b852, #8DC26F);
  background: -o-linear-gradient(right, #76b852, #8DC26F);
  background: linear-gradient(to left, #76b852, #8DC26F);
  font-family: "Roboto", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;      
}
	</style>
<script src="../../../../../assets/plugins/jquery/jquery-1.11.1.min.js"></script>
<script type="text/javascript">


	$(document).ready(function(){
  		$(".entrar").click(function(e){
  			var id = $(this).attr("id");
	        $.ajax({
	            url: "../ordenes/"+id,
	            type:"post",
	            data: $('#login_orden').serialize(),   

	            success: function(){     
	              $("body").load("../ordenes/"+id);      
	            },
	            error:function(){
	                
	                alert("Error");
	            }
	        });  
   		});
   		//End
	});
</script>
</head>
<body>
	<div class="login-page">
  		<div class="form">
  			<p class="logo"><img src="../../../../../assets/images/logo_cupon.png"></p>
  			<p class="sucursal"><strong>SUCURSAL </strong>:
  				<?php
  					echo $sucursales[0]->nombre_sucursal;
  				?>
  			</p>
		    <form role="form"  method="post" action="../ordenes/<?php echo $sucursales[0]->id_sucursal ?>" id="login_orden">
		      	<input type="text" name="usuario" placeholder="Usuario"/>
		      	<input type="password" name="password" placeholder="Password"/>
		      	<input type="submit" name="Entrar">		      	  
		    </form>
  		</div>
	</div>
</body>
</html>
