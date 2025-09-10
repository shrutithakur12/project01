<?php
// booking.php
// Author: Your Name (s1234567)

include "db.php";

$email = $_GET['email'] ?? "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pname = $_POST['pname'];
    $phone = $_POST['phone'];
    $unit = $_POST['unit'];
    $street_no = $_POST['street_no'];
    $street_name = $_POST['street_name'];
    $suburb = $_POST['suburb'];
    $dest_suburb = $_POST['dest_suburb'];
    $pickup_time = $_POST['pickup_time'];

    $pickup_timestamp = strtotime($pickup_time);
    if ($pickup_timestamp - time() < 2400) { // 40 mins
        $error = "Pick-up time must be at least 40 minutes later!";
    } else {
        $ref = uniqid("B");
        $booking_time = date("Y-m-d H:i:s");
        $status = "unassigned";

        $sql = "INSERT INTO booking 
        (ref_no, email, passenger_name, passenger_phone, unit_no, street_no, street_name, suburb, dest_suburb, pickup_time, booking_time, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssss", $ref, $email, $pname, $phone, $unit, $street_no, $street_name, $suburb, $dest_suburb, $pickup_time, $booking_time, $status);

        if ($stmt->execute()) {
            echo "<p>Thank you! Your booking reference number is $ref. We will pick up the passengers in front of your provided address at $pickup_time.</p>";

            // send email
            $to = $email;
            $subject = "Your booking request with CabsOnline!";
            $message = "Dear $pname, Thanks for booking with CabsOnline! Your booking reference number is $ref. We will pick you up at $pickup_time.";
            $headers = "From: booking@cabsonline.com.au";
            mail($to, $subject, $message, $headers, "-r yourstudentID@student.swin.edu.au");
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Booking - CabsOnline</title></head>
<body>
<h2>Booking Page</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Passenger Name: <input type="text" name="pname"><br>
    Contact Phone: <input type="text" name="phone"><br>
    Unit No: <input type="text" name="unit"><br>
    Street No: <input type="text" name="street_no"><br>
    Street Name: <input type="text" name="street_name"><br>
    Suburb: <input type="text" name="suburb"><br>
    Destination Suburb: <input type="text" name="dest_suburb"><br>
    Pick-up Date/Time: <input type="datetime-local" name="pickup_time"><br>
    <input type="submit" value="Book Now">
</form>
</body>
</html>
