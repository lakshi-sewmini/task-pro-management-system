<?php
include 'db_config.php';

$msg = "";

if(isset($_POST['register']))
{
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check = $conn->query("SELECT * FROM users WHERE email='$email'");

    if($check->num_rows > 0)
    {
        $msg = "Email already exists";
    }
    else
    {
        $conn->query("INSERT INTO users(username,email,password,role)
        VALUES('$username','$email','$password','user')");

        $msg = "Registration Successful. Please login.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="login.css">
</head>

<body>

<div class="auth-wrapper">
<div class="auth-card">

<h2>Register</h2>

<?php if($msg!="") echo "<p style='color:green'>$msg</p>"; ?>

<form method="POST">

<input type="text" name="username" placeholder="Username" required><br><br>

<input type="email" name="email" placeholder="Email" required><br><br>

<input type="password" name="password" placeholder="Password" required><br><br>

<button type="submit" name="register">Register</button>

</form>

<p>
Already have account? <a href="login.php">Login</a>
</p>

</div>
</div>

</body>
</html>
