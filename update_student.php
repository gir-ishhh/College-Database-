<?php

include 'config.php';

$student_data = null;
$error = null;
$success = null;
$show_update_form = false;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['fetch_student'])) {
    $roll_number = isset($_POST['roll_number']) ? trim($_POST['roll_number']) : '';

    if (empty($roll_number)) {
        $error = "Please enter a roll number";
    } else {
        $query = "SELECT s.Roll_Number, s.Name, s.Class, s.DOB, s.Contact_no, m.M1, m.M2, m.M3 
                  FROM Student s 
                  LEFT JOIN Marks m ON s.Roll_Number = m.Roll_Number 
                  WHERE s.Roll_Number = ?";

        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("s", $roll_number);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $student_data = $result->fetch_assoc();
                $show_update_form = true;
            } else {
                $error = "No student found";
            }
            $stmt->close();
        } else {
            $error = "Database error";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_personal'])) {
    $roll_number = isset($_POST['roll_number']) ? trim($_POST['roll_number']) : '';
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $class = isset($_POST['class']) ? trim($_POST['class']) : '';
    $dob = isset($_POST['dob']) ? $_POST['dob'] : '';
    $contact_no = isset($_POST['contact_no']) ? trim($_POST['contact_no']) : '';

    if (!empty($roll_number) && !empty($name) && !empty($class) && !empty($dob) && !empty($contact_no)) {
        $update_query = "UPDATE Student SET Name = ?, Class = ?, DOB = ?, Contact_no = ? WHERE Roll_Number = ?";
        $stmt = $conn->prepare($update_query);

        if ($stmt) {
            $stmt->bind_param("sssss", $name, $class, $dob, $contact_no, $roll_number);
            if ($stmt->execute()) {
                $success = "Personal information updated successfully";
                $fetch_query = "SELECT s.Roll_Number, s.Name, s.Class, s.DOB, s.Contact_no, m.M1, m.M2, m.M3 
                               FROM Student s 
                               LEFT JOIN Marks m ON s.Roll_Number = m.Roll_Number 
                               WHERE s.Roll_Number = ?";
                $fetch_stmt = $conn->prepare($fetch_query);
                if ($fetch_stmt) {
                    $fetch_stmt->bind_param("s", $roll_number);
                    $fetch_stmt->execute();
                    $fetch_result = $fetch_stmt->get_result();
                    if ($fetch_result) {
                        $student_data = $fetch_result->fetch_assoc();
                    }
                    $fetch_stmt->close();
                }
                $show_update_form = true;
            } else {
                $error = "Error updating personal information";
            }
            $stmt->close();
        } else {
            $error = "Database error";
        }
    } else {
        $error = "Please fill all fields";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_marks'])) {
    $roll_number = isset($_POST['roll_number']) ? trim($_POST['roll_number']) : '';
    $m1 = isset($_POST['m1']) ? intval($_POST['m1']) : 0;
    $m2 = isset($_POST['m2']) ? intval($_POST['m2']) : 0;
    $m3 = isset($_POST['m3']) ? intval($_POST['m3']) : 0;

    if ($m1 < 0 || $m1 > 100 || $m2 < 0 || $m2 > 100 || $m3 < 0 || $m3 > 100) {
        $error = "Marks must be between 0 and 100";
        $fetch_query = "SELECT s.Roll_Number, s.Name, s.Class, s.DOB, s.Contact_no, m.M1, m.M2, m.M3 
                       FROM Student s 
                       LEFT JOIN Marks m ON s.Roll_Number = m.Roll_Number 
                       WHERE s.Roll_Number = ?";
        $fetch_stmt = $conn->prepare($fetch_query);
        if ($fetch_stmt) {
            $fetch_stmt->bind_param("s", $roll_number);
            $fetch_stmt->execute();
            $fetch_result = $fetch_stmt->get_result();
            if ($fetch_result) {
                $student_data = $fetch_result->fetch_assoc();
            }
            $fetch_stmt->close();
        }
        $show_update_form = true;
    } else {
        $check_query = "SELECT * FROM Marks WHERE Roll_Number = ?";
        $check_stmt = $conn->prepare($check_query);
        if ($check_stmt) {
            $check_stmt->bind_param("s", $roll_number);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();

            if ($check_result && $check_result->num_rows > 0) {
                $update_query = "UPDATE Marks SET M1 = ?, M2 = ?, M3 = ? WHERE Roll_Number = ?";
                $stmt = $conn->prepare($update_query);
                if ($stmt) {
                    $stmt->bind_param("iis", $m1, $m2, $m3, $roll_number);
                    if ($stmt->execute()) {
                        $success = "Marks updated successfully";
                    } else {
                        $error = "Error updating marks";
                    }
                    $stmt->close();
                }
            } else {
                $insert_query = "INSERT INTO Marks (Roll_Number, M1, M2, M3) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($insert_query);
                if ($stmt) {
                    $stmt->bind_param("siii", $roll_number, $m1, $m2, $m3);
                    if ($stmt->execute()) {
                        $success = "Marks added successfully";
                    } else {
                        $error = "Error adding marks";
                    }
                    $stmt->close();
                }
            }
            $check_stmt->close();
        }

        $fetch_query = "SELECT s.Roll_Number, s.Name, s.Class, s.DOB, s.Contact_no, m.M1, m.M2, m.M3 
                       FROM Student s 
                       LEFT JOIN Marks m ON s.Roll_Number = m.Roll_Number 
                       WHERE s.Roll_Number = ?";
        $fetch_stmt = $conn->prepare($fetch_query);
        if ($fetch_stmt) {
            $fetch_stmt->bind_param("s", $roll_number);
            $fetch_stmt->execute();
            $fetch_result = $fetch_stmt->get_result();
            if ($fetch_result) {
                $student_data = $fetch_result->fetch_assoc();
            }
            $fetch_stmt->close();
        }
        $show_update_form = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Student</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Update Student</h1>
        <p class="subtitle">Update Personal Information and Marks</p>
        <div style="text-align: center; padding: 8px; background: #ecf0f1; border-radius: 4px; margin-bottom: 15px;">
            <strong>Project By: Girish Sapkale</strong>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="roll_number">Roll Number:</label>
                <input type="text" id="roll_number" name="roll_number" placeholder="Enter roll number"
                    value="<?php echo isset($_POST['roll_number']) ? htmlspecialchars($_POST['roll_number']) : ''; ?>"
                    required>
            </div>
            <button type="submit" name="fetch_student" class="btn btn-primary">Fetch Student Data</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-top: 20px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success" style="margin-top: 20px;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <?php if ($show_update_form && $student_data): ?>
            <hr style="margin: 30px 0; border: none; border-top: 2px solid #eee;">

            <div style="background: #f8f9fa; padding: 25px; border-radius: 10px; margin-bottom: 25px;">
                <h3>Personal Information</h3>
                <form method="POST" action="">
                    <input type="hidden" name="roll_number"
                        value="<?php echo htmlspecialchars($student_data['Roll_Number']); ?>">

                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name"
                            value="<?php echo htmlspecialchars($student_data['Name']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="class">Class:</label>
                        <input type="text" id="class" name="class"
                            value="<?php echo htmlspecialchars($student_data['Class']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="dob">Date of Birth:</label>
                        <input type="date" id="dob" name="dob" value="<?php echo $student_data['DOB']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_no">Contact Number:</label>
                        <input type="text" id="contact_no" name="contact_no"
                            value="<?php echo htmlspecialchars($student_data['Contact_no']); ?>" pattern="[0-9]{10,15}"
                            required>
                    </div>

                    <button type="submit" name="update_personal" class="btn btn-success">Update Personal Info</button>
                </form>
            </div>

            <div style="background: #f8f9fa; padding: 25px; border-radius: 10px;">
                <h3>Marks Information</h3>
                <form method="POST" action="">
                    <input type="hidden" name="roll_number"
                        value="<?php echo htmlspecialchars($student_data['Roll_Number']); ?>">

                    <div class="form-group">
                        <label for="m1">Subject 1 (M1):</label>
                        <input type="number" id="m1" name="m1" min="0" max="100"
                            value="<?php echo !is_null($student_data['M1']) ? $student_data['M1'] : '0'; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="m2">Subject 2 (M2):</label>
                        <input type="number" id="m2" name="m2" min="0" max="100"
                            value="<?php echo !is_null($student_data['M2']) ? $student_data['M2'] : '0'; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="m3">Subject 3 (M3):</label>
                        <input type="number" id="m3" name="m3" min="0" max="100"
                            value="<?php echo !is_null($student_data['M3']) ? $student_data['M3'] : '0'; ?>" required>
                    </div>

                    <button type="submit" name="update_marks" class="btn btn-success">Update Marks</button>
                </form>
            </div>
        <?php endif; ?>

        <a href="index.html" class="back-link">Back to Menu</a>
    </div>

    <script>
        document.getElementById('roll_number').focus();
    </script>
</body>

</html>