<?php

require "../../includes/app.php";

use App\Propiedad;

estaAutenticado();

//Base de datos
 $db = conectarDb();

 //Consultar para obtener vendedores
$consulta = "SELECT * FROM vendedores";
$resultado = mysqli_query($db, $consulta);

 //Arreglos con mensajes de errores

 $errores = [];
 
 $titulo = "";
 $precio = "";
 $descripcion = "";
 $habitaciones = "";
 $wc = "";
 $estacionamiento = "";
 $id_vendedor = "";
 $imagen = "";
 
 //echo "<pre>";
 //var_dump($_FILES);
 //echo "</pre>";

 if($_SERVER["REQUEST_METHOD"] === "POST"){	 

  $propiedad = new Propiedad($_POST);
  
  $propiedad->guardar();
  debuguear($propiedad);
	 
	$titulo = mysqli_real_escape_string( $db, $_POST['titulo']);
	$precio = mysqli_real_escape_string( $db, $_POST['precio']);
	$descripcion = mysqli_real_escape_string( $db, $_POST['descripcion']);
	$habitaciones = mysqli_real_escape_string( $db, $_POST['habitaciones']);
	$wc = mysqli_real_escape_string( $db, $_POST['wc']);
	$estacionamiento = mysqli_real_escape_string( $db, $_POST['estacionamiento']);
	$id_vendedor = mysqli_real_escape_string( $db, $_POST['id_vendedor']);
	$creado = date('Y/m/d');

	$imagen = $_FILES['imagen'];


	if(!$titulo){
    $errores[] = "Debes añadir un título";
  }
  if(!$precio){
        $errores[] = "Debes añadir un precio";
  }
  if(strlen( $descripcion ) < 50 ){
        $errores[] = "La descripción es obligatoria y debe tener mas de 50 caracteres";
  }
  if(!$habitaciones){
    $errores[] = "El numero de habitaciones es obligatorio";
  }
  if(!$wc){
        $errores[] = "El numero de baños es obligatorio";
  }
  if(!$estacionamiento){
    $errores[] = "El numero de estacionamientos es obligatorio";
  }
  if(!$id_vendedor){
    $errores[] = "Elige un vendedor";
  }
  if(!$imagen['name'] || $imagen['error']){
    $errores[] = "La imagen es obligatoria";
  }
	
	//Validar por tamaño(100kB maximo)
	$medida = 1000 * 1000;
	
	if($medida < $imagen['size']){
		$errores[] = "La imagen es demasiado grande";

	}


    //echo "<pre>";
      //var_dump($errores);
    //echo "</pre>";
    //exit;
//VALIDAR SI NO HAY NINGUN ERROR

    if(empty($errores)){

			/*Subida de archivos*/

			$carpetaImagenes = '../../imagenes/';

			if( !is_dir($carpetaImagenes) ){	
				mkdir($carpetaImagenes);
			}

			//Nombrar las imagenes
			$nombreImagenes = uniqid( rand(), true ) . $imagen['name'];

			move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagenes);


        //INSERTAR DATOS EN LA BASE 
        $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, id_vendedor) VALUES ('$titulo', '$precio', '$nombreImagenes', '$descripcion', '$habitaciones', '$wc', '$estacionamiento', '$creado', '$id_vendedor')";
    
        $resultado = mysqli_query($db, $query);
    
        if($resultado){
          //Redireccionar al usuario
					header ("location: /bienesraices/admin?resultado=1");
        }
    }
    }


incluirTemplate('header');
?>
  <main class="contenedor seccion">
     <h1>Crear</h1>
     <a href="/bienesraices/admin" class="boton-verde">Volver</a>

     <?php foreach($errores as $error):?>

      <div class="alerta error">
       <?php echo $error?>
       </div>
    	 <?php endforeach; ?>

          <form class="formulario" action="/bienesraices/admin/propiedades/crear.php" method="post" enctype="multipart/form-data" >
              <fieldset>
                  <legend>Información General</legend>
                    
                    <label for="titulo">TITULO:</label>
                    <input type="text" id="titulo" name="titulo" placeholder="Título Propiedad" value="<?php echo $titulo?>">
                    
                    <label for="precio">PRECIO:</label>
                    <input type="number" name="precio" id="precio" placeholder="Precio Propiedad" value="<?php echo $precio?>">
                    
                    <label for="imagen">IMAGEN:</label>
                    <input type="file" id="imagen" accept="image/jpeg, image/png" value="<?php echo $imagen?>" name="imagen">
                    
                    <label for="descripcion">DESCRIPCION:</label>
                    <textarea id="descripcion" name="descripcion"><?php echo $descripcion?></textarea>
                    
                    
                </fieldset>
                
                <fieldset>
                    <legend>Información Propiedad</legend>
                    
                    <label for="habitaciones">HABITACIONES:</label>
                    <input type="number" id="habitaciones" name="habitaciones" placeholder="Ej: 2" min="1" max="9" value="<?php echo $habitaciones?>"?>
                    
                    <label for="wc">BAÑOS:</label>
                    <input type="number" id="wc" name="wc" placeholder="Ej: 2" min="1" max="9" value="<?php echo $wc?>">
                    
                    <label for="estacionamiento">ESTACIONAMIENTOS:</label>
                    <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej: 2" min="1" max="9" value="<?php echo $estacionamiento?>">
                    
                </fieldset>
                
                <fieldset>
                    <legend>Información General</legend>
                    
                    <label for="vendedor">VENDEDOR:</label>
                    <select id="vendedor" name="id_vendedor">

                        <option value="">--seleccione--</option>
                        <?php while($vendedor = mysqli_fetch_assoc($resultado)	) :?>

													<option <?php echo $id_vendedor === $vendedor['id'] ? 'selected' : ""?> value="<?php echo $vendedor['id']; ?>"> <?php echo $vendedor ['nombre'] . " " . $vendedor['apellido']?> </option>

													<?php endwhile; ?>
                    </select>

                </fieldset>

                <input type="submit" value="Crear Propiedad" class="boton boton-verde">
        </form>

    </main>

<?php
incluirTemplate ('footer');
?>
