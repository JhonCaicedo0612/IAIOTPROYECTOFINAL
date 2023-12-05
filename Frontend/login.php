<?php
  session_start();
  session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="./css/style2.css" />
    <title>Animated Login Form</title>
  </head>
  <body>
    <div class="box">
    <div class="form">
        <form action="php/login_usuario_be.php" class="formulario__login" method="POST">
        <h2>Sign in</h2>
        <div class="input-box">
        <input type="text" required name="user"/>
        <i></i>
        <span>Username</span>
        
        </div>
        <div class="input-box">
        <input type="password" required name= "password"/>
        <i></i>
        <span>Password</span>
        </div>
        <input type="submit" value="Login" />
      </form>
    </div>
    
    </div>
  </body>
</html>