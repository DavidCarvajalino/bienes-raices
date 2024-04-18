<?php
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if(!$id){
	header ('Location: /bienesraices');
}
 
//Importamos la base de datos
require'includes/config/databases.php';
$db = conectarDb();

//Consultar con la base de datos
$query = "SELECT * FROM propiedades WHERE id = ${id}";

//Obtener Resultado
$resultado = mysqli_query($db, $query);

if($resultado->num_rows === 0){
    header ('Location: /bienesraices');
}

$propiedad = mysqli_fetch_assoc($resultado);


?>  
    <h1> <?php echo $propiedad['titulo'] ?> </h1>
    
    <img loading="lazy" src="imagenes/<?php echo $propiedad['imagen'];?>" alt="imagen de la propiedad">
    
    <div class="resumen-propiedad">
        <p class="precio"><?php echo "$" . $propiedad['precio'];?></p>
        <ul class="iconos-caracteristicas">
            <li>
                <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                <p><?php echo $propiedad['wc'];?></p>
            </li>
            <li>
                <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg"
                alt="icono estacionamiento">
                <p><?php echo $propiedad['estacionamiento'];?></p>
            </li>
            <li>
                <img class="icono" loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                <p><?php echo $propiedad['habitaciones'];?></p>
            </li>
        </ul>
        
        <p><?php echo $propiedad['descripcion'];?></p>
        
    </div>
    
<?php
//Cerrar la base de datos
mysqli_close($db);
?>