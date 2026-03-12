<?php

include 'config.php';

$result_data = null;
$error = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['show_result'])) {
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
                $result_data = $result->fetch_assoc();

                if (!is_null($result_data['M1'])) {
                    $result_data['total'] = $result_data['M1'] + $result_data['M2'] + $result_data['M3'];

                    if ($result_data['total'] > 75) {
                        $result_data['grade'] = 'Distinction';
                        $result_data['grade_class'] = 'grade-distinction';
                    } elseif ($result_data['total'] > 60) {
                        $result_data['grade'] = 'First Class';
                        $result_data['grade_class'] = 'grade-first';
                    } elseif ($result_data['total'] > 50) {
                        $result_data['grade'] = 'Second Class';
                        $result_data['grade_class'] = 'grade-second';
                    } else {
                        $result_data['grade'] = 'Fail';
                        $result_data['grade_class'] = 'grade-fail';
                    }
                } else {
                    $result_data['error_marks'] = "No marks found";
                }
            } else {
                $error = "No student found";
            }
            $stmt->close();
        } else {
            $error = "Database error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Result</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h1>Display Result</h1>
        <p class="subtitle">View Student Marks and Grade</p>
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
            <button type="submit" name="show_result" class="btn btn-primary">Show Result</button>
        </form>

        <?php if ($error): ?>
            <div class="alert alert-danger" style="margin-top: 20px;">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($result_data): ?>
            <div class="result-card">
                <h3>Student Information</h3>
                <div class="result-item">
                    <span class="result-label">Roll Number:</span>
                    <span class="result-value"><?php echo htmlspecialchars($result_data['Roll_Number']); ?></span>
                </div>
                <div class="result-item">
                    <span class="result-label">Name:</span>
                    <span class="result-value"><?php echo htmlspecialchars($result_data['Name']); ?></span>
                </div>
                <div class="result-item">
                    <span class="result-label">Class:</span>
                    <span class="result-value"><?php echo htmlspecialchars($result_data['Class']); ?></span>
                </div>
                <div class="result-item">
                    <span class="result-label">Date of Birth:</span>
                    <span class="result-value"><?php echo htmlspecialchars($result_data['DOB']); ?></span>
                </div>
                <div class="result-item">
                    <span class="result-label">Contact:</span>
                    <span class="result-value"><?php echo htmlspecialchars($result_data['Contact_no']); ?></span>
                </div>
            </div>

            <?php if (isset($result_data['error_marks'])): ?>
                <div class="alert alert-info" style="margin-top: 20px;">
                    <?php echo $result_data['error_marks']; ?>
                </div>
            <?php else: ?>
                <div class="result-card">
                    <h3>Marks Details</h3>
                    <div class="result-item">
                        <span class="result-label">Subject 1:</span>
                        <span class="result-value"><?php echo $result_data['M1']; ?>/100</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Subject 2:</span>
                        <span class="result-value"><?php echo $result_data['M2']; ?>/100</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Subject 3:</span>
                        <span class="result-value"><?php echo $result_data['M3']; ?>/100</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Total Marks:</span>
                        <span class="result-value" style="font-weight: bold;"><?php echo $result_data['total']; ?>/300</span>
                    </div>
                    <div style="text-align: center; margin-top: 20px;">
                        <span
                            class="grade <?php echo $result_data['grade_class']; ?>"><?php echo $result_data['grade']; ?></span>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <a href="index.html" class="back-link">Back to Menu</a>
    </div>

    <script>
        document.getElementById('roll_number').focus();
    </script>
</body>

</html>