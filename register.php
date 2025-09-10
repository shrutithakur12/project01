<?php
// register.php
// Author: Your Name (s1234567)
// Allows a new customer to register

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $repassword = $_POST['repassword'];
    $phone = trim($_POST['phone']);

    if (empty($name) || empty($email) || empty($password) || empty($repassword) || empty($phone)) {
        $error = "All fields are required!";
    } elseif ($password !== $repassword) {
        $error = "Passwords do not match!";
    } else {
        // check unique email
        $sql = "SELECT email FROM customer WHERE email=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already exists!";
        } else {
            $hashed_pw = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO customer (email, name, password, phone) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $email, $name, $hashed_pw, $phone);
            if ($stmt->execute()) {
                header("Location: booking.php?email=" . urlencode($email));
                exit;
            } else {
                $error = "Error creating account!";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Register - CabsOnline</title></head>
<body>
<h2>Register</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Name: <input type="text" name="name"><br>
    Email: <input type="email" name="email"><br>
    Password: <input type="password" name="password"><br>
    Re-enter Password: <input type="password" name="repassword"><br>
    Phone: <input type="text" name="phone"><br>
    <input type="submit" value="Register">
</form>
</body>
</html>
