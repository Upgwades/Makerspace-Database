<head>
  <title>RAD Makerspace - Login</title>
  <link rel="icon" href="/img/Goomerbot Logo3.png">
  <link href="/css/style.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" />
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  <script src="/js/jquery.tablesorter.js"></script>
  <script src="/js/myfunctions.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  <style>
  body {
background: #76b852; /* fallback for old browsers */
background: -webkit-linear-gradient(right, #76b852, #8DC26F);
background: -moz-linear-gradient(right, #76b852, #8DC26F);
background: -o-linear-gradient(right, #76b852, #8DC26F);
background: linear-gradient(to bottom, #FFFFFF, #492f92);
font-family: "Roboto", sans-serif;
-webkit-font-smoothing: antialiased;
-moz-osx-font-smoothing: grayscale;
}
</style>
</head>
<body>
  <h3><center>RAD Makerspace</h3>
      <div class="login-page">
      <div class="form">
        <h3><center>Login</h3>
        </br>
        <form class="register-form" action="/php/Register.php" method="post">
          <input type="text" name="nameReg" id="nameReg" maxlength="45" placeholder="name"/>
          <input type="password" name="passwordReg" id="passwordReg" maxlength="45" placeholder="password"/>
          <input type="password" name="passwordReg2" id="passwordReg2" maxlength="45" placeholder="confirm password"/>
          <input type="text" name="emailReg" id="emailReg" maxlength="45" placeholder="email address"/>
          <label>
          <input type="radio" name="statusReg" value="Student"/> Student
          </label>
          <label>
          <input type="radio" name="statusReg" value="Faculty"/> Faculty
          </label>
          <button type="submit">create</button>
          <p class="message">Already registered? <a href="#">Sign In</a></p>
        </form>
        <form class="login-form" action="/php/Login.php" method="post">
          <input type="text" name="name" id="name" maxlength="45" placeholder="name or email"/>
          <input type="password" name="password" id="password" maxlength="45" placeholder="password"/>
          <button type="submit" onclick="saveData()">login</button>
          <p class="message">Not registered? <a href="#">Create an account</a></p>
          <p class="message">Forgot your password? <a href="">Send a recovery email</a></p>
        </form>
      </div>
      </div>
<script>
$('.message a').click(function(){
   $('form').animate({height: "toggle", opacity: "toggle"}, "slow");
});
</script>
<script>
function saveData()
  { window.localStorage.setItem("$name","$data");
    //localStorage(document.getElementById("name").value);
    //localStorage(document.getElementById("name").value);
  }
</script>
</body>
</html>
