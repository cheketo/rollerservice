<?php
  include("../../includes/inc.main.php");
  $Head->setTitle("Login");
  $Head->setHead();

  if($_COOKIE['rememberuser']){
      $Checked = 'checked="checked"';
  }
?>
  <body class="login">
    <div>
      <div class="login_wrapper">
        <div class="animate form login_form">
          <div class="text-center"><h1><i class="fa fa-cog"></i> Roller Service</h1></div>
          <section class="login_content">
            <form>
              <h1>Iniciar Sesión</h1>
              <div class="">
                <input type="text" name="user" id="user" class="form-control" placeholder="Usuario o Email" validateEmpty="Ingrese su usuario o email." value="<?php echo $_COOKIE['rememberuser'];?>" />
              </div>
              <div>
                <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" validateEmpty="Ingrese su contraseña." value="<?php echo $_COOKIE['rememberpassword'];?>" />
              </div>
              <div>
                <p><input type="checkbox" <?php echo $Checked ?> class="iCheckbox" name="rememberuser" id="rememberuser" value="1" > Recordar mi usuario</p>
              </div>
              <div>
                <a class="btn btn-primary ButtonLogin">Ingresar</a>
                <a class="reset_pass">Olvidé mi contraseña</a>
              </div>
              
              <div class="clearfix"></div>
            </form>
          </section>
        </div>

        
      </div>
    </div>
  </body>

<?php $Foot->setFoot(); ?>
