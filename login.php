<?php
session_start();

// Database connection details
$host = 'localhost';  // Change this to your database host
$dbname = 'hmsDB';  // Change this to your database name
$db_username = 'root';  // Change this to your database username
$db_password = '';  // Change this to your database password

// Connect to the database
try {
    $connection = new PDO("mysql:host=$host;dbname=$dbname", $db_username, $db_password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Logout
if (isset($_GET["logout"])) {

 if(isset($_SESSION["user"])) {
    // Assuming $_SESSION["user"] contains the username information
    $username = $_SESSION["user"];

    // Now you can use $username in your SQL query
    $stmt = $connection->prepare("INSERT INTO user_log (username, logout_time) VALUES (:username, DATE_ADD(NOW(), INTERVAL 7 HOUR))");
    $stmt->bindparam(':username', $username); // Assuming $username is a string
    $stmt->execute();
  }

    session_destroy();
    header("Location: login.php");
    exit;
}

// Login
if (isset($_POST["user"]) && !isset($_SESSION["user"])) {
    $username = $_POST["user"];
    $password = $_POST["password"];

    // Prepare the SQL statement
    $stmt = $connection->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    // Fetch the user data
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verify the user's credentials
    if ($user && $password == $user["password"]) {
        $_SESSION["user"] = $user["username"];
	$_SESSION["root"] = $user["root"];

        $stmt = $connection->prepare("INSERT INTO user_log (username, login_time) VALUES (:username,DATE_ADD(NOW(), INTERVAL 7 HOUR))");
        $stmt->bindParam(':username',$username); // Assuming $username is a string
        $stmt->execute();

    } else {
        // Authentication failed
        $failed = true;
    }
}

// HTML code for login form
if (!isset($_SESSION["user"])) {
   
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Files Login</title>
    <style>
      :root{
          	height: 100% !important;
          	width: 100% !important;
      }
      body{
          	display: flex !important;
          	height: 100% !important;
          	width: 100% !important;
          	justify-content: center !important;
          	align-items: center !important;
          	text-align: center !important;
        	background-color: #1d242b !important;
      }
      .title{
          	display: inline !important;
        	font-size: 26px !important;
      }
      .title img{
        	margin-bottom: 20px !important;
		margin-right: 10px !important;
		width: 40%;
      }
      .logincontainer{
          	position: absolute !important;
      }
      .error{
		  	color: red !important;
      }
      .btn{
		  	background-color: #D6B26A !important;
          	border: none !important;
      }
      .cursor { 
            animation-name: cursor !important;
            animation-iteration-count: infinite !important;
            animation-timing-function: cubic-bezier(1,0,0,1) !important;
            animation-duration: 1s !important;
            display: inline-block !important;
        	position: absolute !important;
            width: 2px !important;
            height: 30px !important;
            margin-left: 5px !important;
        	margin-top: 23px !important;
            border-radius: 2px !important;
            background: #000000 !important;
          }

          @keyframes cursor { 
            from { opacity: 1.0; } to { opacity: 0.0; }
          }
      </style>
  </head>
  <body>
   <div>
   <div class="title"><img src="logo.PNG"></div>
	<form id="login-form" method="post" target="_self">
      <div class="mb-3">
        <div class="mb-3 input-group">
          <span class="input-group-text"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0z"></path><path d="M16.5 12c1.38 0 2.49-1.12 2.49-2.5S17.88 7 16.5 7a2.5 2.5 0 000 5zM9 11c1.66 0 2.99-1.34 2.99-3S10.66 5 9 5C7.34 5 6 6.34 6 8s1.34 3 3 3zm7.5 3c-1.83 0-5.5.92-5.5 2.75V19h11v-2.25c0-1.83-3.67-2.75-5.5-2.75zM9 13c-2.33 0-7 1.17-7 3.5V19h7v-2.25c0-.85.33-2.34 2.37-3.47C10.5 13.1 9.66 13 9 13z"></path></svg></span>
          <input class="form-control" name="user"></div>
      </div>
      <div class="mb-3">
        <div class="mb-3 input-group">
          <span class="input-group-text"><svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 24 24" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path fill="none" d="M0 0h24v24H0V0z"></path><path d="M2 17h20v2H2v-2zm1.15-4.05L4 11.47l.85 1.48 1.3-.75-.85-1.48H7v-1.5H5.3l.85-1.47L4.85 7 4 8.47 3.15 7l-1.3.75.85 1.47H1v1.5h1.7l-.85 1.48 1.3.75zm6.7-.75l1.3.75.85-1.48.85 1.48 1.3-.75-.85-1.48H15v-1.5h-1.7l.85-1.47-1.3-.75L12 8.47 11.15 7l-1.3.75.85 1.47H9v1.5h1.7l-.85 1.48zM23 9.22h-1.7l.85-1.47-1.3-.75L20 8.47 19.15 7l-1.3.75.85 1.47H17v1.5h1.7l-.85 1.48 1.3.75.85-1.48.85 1.48 1.3-.75-.85-1.48H23v-1.5z"></path></svg></span>
          <input type="password" class="form-control" name="password"></div>
      </div>
      <div class="d-grid gap-2"><button type="submit" class="btn">Sign In</button></div>
       <?php if (isset($failed)) { ?>
<br><small class="text-muted">Invalid user or password.</small>
<?php } ?>
     </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  </body>
</html>
<?php
//REDIRECT
}else{
  header("Location: index.php");
  exit();
}
?>

