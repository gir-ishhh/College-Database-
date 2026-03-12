<?php

include 'config.php';

$student_data = null;
$error = null;
$success = null;
$show_confirm = false;

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
                $show_confirm = true;
            } else {
                $error = "No student found";
            }
            $stmt->close();
        } else {
            $error = "Database error";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student'])) {
    $roll_number = isset($_POST['roll_number']) ? trim($_POST['roll_number']) : '';

    if (!empty($roll_number)) {
        $delete_query = "DELETE FROM Student WHERE Roll_Number = ?";
        $stmt = $conn->prepare($delete_query);

        if ($stmt) {
            $stmt->bind_param("s", $roll_number);
            if ($stmt->execute()) {
                $success = "Student record deleted successfully";
                $show_confirm = false;
            } else {
                $error = "Error deleting student";
            }
            $stmt->close();
        } else {
            $error = "Database error";
        }
    } else {
        $error = "Invalid roll number";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Delete Student</h1>
        <p class="subtitle">Remove Student Records</p>
        <div style="text-align: center; padding: 8px; background: #ecf0f1; border-radius: 4px; margin-bottom: 15px;">
            <strong>Project By: Girish Sapkale</strong>
        </div>

        <div class="alert alert-danger">
            <strong>Warning:</strong> Deleting a student record will also delete associated marks.
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="roll_number">Roll Number:</label>
                <input type="text" id="roll_number" name="roll_number" placeholder="Enter roll number"
                    value="<?php echo isset($_POST['roll_number']) && !isset($_POST['delete_student']) ? htmlspecialchars($_POST['roll_number']) : ''; ?>"
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

        <?php if ($show_confirm && $student_data): ?>
            <div
                style="background: #fff3cd; border: 2px solid #ffc107; padding: 25px; border-radius: 10px; margin-top: 25px;">
                <h3 style="color: #856404;">Confirm Deletion</h3>
                <p style="color: #856404;">Are you sure you want to delete this student record?</p>

                <div class="result-card">
                    <h4>Student Information</h4>
                    <div class="result-item">
                        <span class="result-label">Roll Number:</span>
                        <span class="result-value"><?php echo htmlspecialchars($student_data['Roll_Number']); ?></span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Name:</span>
                        <span class="result-value"><?php echo htmlspecialchars($student_data['Name']); ?></span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Class:</span>
                        <span class="result-value"><?php echo htmlspecialchars($student_data['Class']); ?></span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Date of Birth:</span>
                        <span class="result-value"><?php echo htmlspecialchars($student_data['DOB']); ?></span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Contact:</span>
                        <span class="result-value"><?php echo htmlspecialchars($student_data['Contact_no']); ?></span>
                    </div>

                    <?php if (!is_null($student_data['M1'])): ?>
                        <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                        <h4>Marks Information</h4>
                        <div class="result-item">
                            <span class="result-label">Subject 1:</span>
                            <span class="result-value"><?php echo $student_data['M1']; ?>/100</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Subject 2:</span>
                            <span class="result-value"><?php echo $student_data['M2']; ?>/100</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Subject 3:</span>
                            <span class="result-value"><?php echo $student_data['M3']; ?>/100</span>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="POST" action="" style="margin-top: 20px;">
                    <input type="hidden" name="roll_number"
                        value="<?php echo htmlspecialchars($student_data['Roll_Number']); ?>">
                    <div class="button-group">
                        <button type="submit" name="delete_student" class="btn btn-danger"
                            onclick="return confirm('Are you absolutely sure?');">Confirm Delete</button>
                        <a href="delete_student.php" class="btn btn-secondary">Cancel</a>
                    </div>
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Student - College Database</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Delete Student</h1>
        <p class="subtitle">Remove Student Records</p>
        <div style="text-align: center; padding: 8px; background: #ecf0f1; border-radius: 4px; margin-bottom: 15px;">
            <strong>Project By: Girish Sapkale</strong>
        </div>

        <div class="alert alert-danger">
            <strong>Warning:</strong> Deleting a student record will also delete associated marks.
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label for="roll_number">Roll Number:</label>
                <input type="text" id="roll_number" name="roll_number" placeholder="Enter roll number">
                value="<?php echo isset($_POST['roll_number']) && !isset($_POST['delete_student']) ? htmlspecialchars($_POST['roll_number']) : ''; ?>"
                required>
            </div>
            <button type="submit" name="fetch_student" class="btn btn-primary">Fetch Student Data</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-top: 20px;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success" style="margin-top: 20px;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <?php if ($show_confirm && $student_data): ?>
            <div
                style="background: #fff3cd; border: 2px solid #ffc107; padding: 25px; border-radius: 10px; margin-top: 25px;">
                <h3 style="color: #856404; margin-bottom: 20px;">⚠️ Confirm Deletion</h3>
                <p style="color: #856404; margin-bottom: 20px;">
                    <strong>Are you sure you want to delete the following student record?</strong>
                </p>

                <div class="result-card">
                    <h4 style="color: #333; margin-bottom: 15px;">Student Information</h4>
                    <div class="result-item">
                        <span class="result-label">Roll Number:</span>
                        <span class="result-value">
                            <?php echo htmlspecialchars($student_data['Roll_Number']); ?>
                        </span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Name:</span>
                        <span class="result-value">
                            <?php echo htmlspecialchars($student_data['Name']); ?>
                        </span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Class:</span>
                        <span class="result-value">
                            <?php echo htmlspecialchars($student_data['Class']); ?>
                        </span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Date of Birth:</span>
                        <span class="result-value">
                            <?php echo date('d-m-Y', strtotime($student_data['DOB'])); ?>
                        </span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Contact Number:</span>
                        <span class="result-value">
                            <?php echo htmlspecialchars($student_data['Contact_no']); ?>
                        </span>
                    </div>

                    <?php if ($student_data['M1'] !== null): ?>
                        <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                        <h4 style="color: #333; margin: 15px 0;">Marks Information</h4>
                        <div class="result-item">
                            <span class="result-label">Subject 1 (M1):</span>
                            <span class="result-value">
                                <?php echo $student_data['M1']; ?>/100
                            </span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Subject 2 (M2):</span>
                            <span class="result-value">
                                <?php echo $student_data['M2']; ?>/100
                            </span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Subject 3 (M3):</span>
                            <span class="result-value">
                                <?php echo $student_data['M3']; ?>/100
                            </span>
                        </div>
                    <?php endif; ?>
                </div>

                <form method="POST" action="" style="margin-top: 20px;">
                    <input type="hidden" name="roll_number"
                        value="<?php echo htmlspecialchars($student_data['Roll_Number']); ?>">

                    <div class="button-group">
                        <button type="submit" name="delete_student" class="btn btn-danger"
                            onclick="return confirm('Are you absolutely sure? This action cannot be undone!');">
                            🗑️ Confirm Delete
                        </button>
                        <a href="delete_student.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        <?php endif; ?>

        <a href="index.html" class="back-link">← Back to Menu</a>
    </div>

    <script>
        document.getElementById('roll_number').focus();
    </script>
</body>

</html>