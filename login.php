<?php
//Insertar la base de datos
require "includes/app.php";
$db = conectarDb();
//Autenticar el usuario
$errores = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  
    
    $email = mysqli_real_escape_string($db, filter_var( $_POST['email'], FILTER_VALIDATE_EMAIL));
    
    $password = mysqli_real_escape_string($db,$_POST['password']);
    
    if(!$email){
        $errores [] = 'El correo es obligatorio o esta incorrecto';
    }
    
    if(!$password){
        $errores [] = 'La contraseña es obligatoria o esta incorrecta';
    }

    if(empty($errores)){
        //Revisar si el usuario existe
        $query = "SELECT * FROM usuarios WHERE email = '${email}'";
        $resultado = mysqli_query($db, $query);

        //var_dump($resultado);
        
        if($resultado->num_rows){
            //Revisar si el password es válido
            $usuario = mysqli_fetch_assoc($resultado);


            $auth = password_verify($password, $usuario['password']);

            if($auth){
                //Usuario correcto
                session_start();

                //Llenar el arreglo
                $_SESSION['usuario'] = $usuario['email'];
                $_SESSION['login'] = true;

                header ('Location: /bienesraices/admin');

            }else{
                $errores[] = 'Password incorrecta';
            }

        }else {
            $errores[] = 'El usuario no existe';
        }
    }

}

incluirTemplate('header');
?>
    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesión</h1>

        <?php
        foreach($errores as $error):?>
            <div class="alerta error">
                <?php echo $error ?>
            </div>
        <?php endforeach; ?>

        <form method="POST" class="formulario" novalidate>
             <fieldset>
                <legend>E-mail y Password</legend>

                <label for="email">E-mail</label>
                <input type="email" name="email" placeholder="Tu Email" id="email" required>

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Tu Password" id="password" requird>

            </fieldset>
            
            <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        
        </form>



    </main>

<?php
incluirTemplate ('footer');
?>
