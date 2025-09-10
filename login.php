<?php
// login.php
// Author: Your Name (s1234567)

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT password FROM customer WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            header("Location: booking.php?email=" . urlencode($email));
            exit;
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "No such user!";
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login - CabsOnline</title></head>
<body>
<h2>Login</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Email: <input type="email" name="email"><br>
    Password: <input type="password" name="password"><br>
    <input type="submit" value="Login">
</form>
</body>
</html>
