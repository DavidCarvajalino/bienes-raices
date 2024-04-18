<?php

require "../includes/funciones.php";

use App\Propiedad;

estaAutenticado();
/*$auth = estaAutenticado();

if(!$auth){
    header ('Location: /bienesraices');
}*/

//Importar la conexión
require "../includes/config/databases.php";
 $db = conectarDb();

 //Escribir el query
 $query = "SELECT * FROM propiedades";

 //Consultar la base de datos
 $resultadoConsulta = mysqli_query($db, $query);

//Muestra mensaje condicional
$resultado = $_GET['resultado'] ?? null;



if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id){
        //Eliminar el archivo
        $query = "SELECT imagen FROM propiedades WHERE id = ${id}";

        $resultado = mysqli_query($db, $query);
        $propiedad = mysqli_fetch_assoc($resultado);

        unlink('../imagenes/' . $propiedad['imagen']);

        //Eliminar propiedad
        $query = "DELETE FROM propiedades WHERE id = ${id}"; 

        $resultado = mysqli_query($db, $query);

        if($resultado){
            header('location: /bienesraices/admin?resultado=3');
        }
    }
}

//Incluye un template
incluirTemplate('header');

?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>

        <?php if($resultado == 1): ?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>
            
            <?php elseif($resultado == 2): ?>
                <p class="alerta exito">Anuncio Actualizado Correctamente</p>
            
                <?php elseif($resultado == 3): ?>
                <p class="alerta exito">Anuncio Eliminado Correctamente</p>

            <?php endif; ?>
            
            <a href="/bienesraices/admin/propiedades/crear.php" class="boton-verde">Nueva Propiedad</a>

            <table class="propiedades">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Imagen</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while( $propiedad = mysqli_fetch_assoc($resultadoConsulta)): ?>
                    <tr>
                        <th><?php echo $propiedad['id']?></th>
                        <th><?php echo $propiedad['titulo']?></th>
                        <th><img src="/bienesraices/imagenes/<?php echo $propiedad['imagen']?>" class="imagen-tabla"> </th>
                        <th><?php echo $propiedad['precio']?></th>
                        <th> 
                            <form method="POST" clas="w-100">
                                <input type="hidden" name="id" value="<?php echo $propiedad['id']?>">
                                <input value="Eliminar" class="boton-rojo-block" type="submit">
                            </form> 
                            <a href="/bienesraices/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id']?>"class="boton-amarillo-block">Actualizar</a>
                        </th>
                        <br>
                    </tr>
                    <?php endwhile ?>
                </tbody>

            </table>

        </main>
        
        <?php

        //Cerrar la conexion
        mysqli_close($db);

incluirTemplate ('footer');
?>
