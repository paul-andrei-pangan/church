<?php
session_start();
include('db.php');

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Default values
$theme = 'light';
$mobile_view = 'no';

$user_id = $_SESSION['user_id'];

// Fetch user settings from database
$sql = "SELECT theme, mobile_view FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user_settings = $result->fetch_assoc();
    $theme = $user_settings['theme'];
    $mobile_view = $user_settings['mobile_view'];
}

// Store settings in session
$_SESSION['theme'] = $theme;
$_SESSION['mobile_view'] = $mobile_view;

// Handle settings update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_theme = $_POST['theme'] ?? 'light';
    $new_mobile_view = $_POST['mobile_view'] ?? 'no';

    // Update database
    $update_sql = "UPDATE users SET theme = ?, mobile_view = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sss", $new_theme, $new_mobile_view, $user_id);

    if ($stmt->execute()) {
        // Update session values
        $_SESSION['theme'] = $new_theme;
        $_SESSION['mobile_view'] = $new_mobile_view;
        header("Location: settings.php?success=1"); // Reload with success message
        exit();
    } else {
        echo "Error updating settings!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="css/fstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            transition: background-color 0.3s ease;
        }

        /* Dark Mode */
        body.dark-mode {
            background-color: #2c3e50;
            color: white;
        }

        /* Light Mode */
        body.light-mode {
            background-color: white;
            color: black;
        }

        /* Mobile View */
        body.mobile-view {
            font-size: 12px; /* Smaller font size for mobile */
            max-width: 480px;
            margin: 0 auto;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 250px;
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            font-size: 18px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .sidebar ul li {
            margin: 8px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 14px;
            display: flex;
            align-items: center;
            padding: 8px 12px;
            border-radius: 5px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover,
        .sidebar ul li a.active {
            background: #34495e;
        }

        /* Main content container */
        .container {
            margin-left: 280px;
            padding: 20px;
            background-color: #f8f9fa;
        }

        h2 {
            color: white;
            font-size: 22px;
            text-align: center;
            margin-bottom: 20px;
        }

        /* Form Styling */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }

        form label {
            font-size: 14px;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        form input, form select {
            width: 100%;
            padding: 8px;
            margin: 8px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        form button {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Back button styling */
        .btn-back {
            display: inline-block;
            background-color: #e74c3c;
            color: white;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            text-align: center;
            text-decoration: none;
            margin-top: 20px;
        }

        .btn-back:hover {
            background-color: #c0392b;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .container {
                margin-left: 0;
                padding: 10px;
            }

            .sidebar {
                width: 200px;
                padding: 10px;
            }

            .sidebar h2 {
                font-size: 16px;
            }

            form {
                padding: 15px;
                width: 90%;
            }

            form input, form button {
                font-size: 14px;
            }

            .btn-back {
                width: 100%;
            }
        }

        /* Ensure mobile-view class adjusts layout */
        body.mobile-view .sidebar {
            width: 100%;
            position: relative;
        }

        body.mobile-view .container {
            margin-left: 0;
        }

        /* Mobile View Adjustments */
        body.mobile-view form {
            font-size: 12px;  /* Smaller font size for mobile view */
        }

        body.mobile-view form input,
        body.mobile-view form select {
            font-size: 12px; /* Smaller form input size */
            padding: 6px;    /* Smaller padding */
        }

        body.mobile-view form button {
            font-size: 14px; /* Smaller button size */
            padding: 8px;    /* Smaller padding */
        }

        /* Mobile View-specific */
        body.mobile-view h2 {
            font-size: 16px; /* Smaller heading size */
        }
        button {
            background-color: <?php echo ($theme == 'dark') ? '#3498db' : '#2c3e50'; ?>;
            color: white;
            cursor: pointer;
        }
        button:hover {
            background-color: <?php echo ($theme == 'dark') ? '#2980b9' : '#34495e'; ?>;
        }
        h3{
            color: black;
        font-size: 22px;
        text-align: center; /* Center the title */
        margin-bottom: 30px;
        }
    </style>
</head>
<body class="<?php echo ($_SESSION['theme'] == 'dark') ? 'dark-mode' : 'light-mode'; ?> <?php echo ($_SESSION['mobile_view'] == 'yes') ? 'mobile-view' : ''; ?>">

<div class="sidebar">
    <h2>💸 Dashboard</h2>
    <ul>
    <li><a href="dashboard.php">🏠 Home</a></li>
        <li><a href="profile.php">👤 Profile</a></li>
        <li><a href="crops.php">🌱 Crops</a></li>
        <li><a href="livestock.php">🐄 Livestock</a></li>
        <li><a href="inventory.php">📦 Inventory</a></li>
        <li><a href="expenses.php">💸 Expenses</a></li>
        <li><a href="income.php">💰 Income</a></li>
        <li><a href="settings.php">⚙ Settings</a></li>
        <li><a href="logout.php" class="logout">🚪 Logout</a></li>
    </ul>
</div>

<div class="container">
    <h3>Settings</h3>
    <form method="POST">
        <label for="theme">Select Theme:</label>
        <select name="theme" id="theme">
            <option value="light" <?php echo ($theme == 'light') ? 'selected' : ''; ?>>Light Mode</option>
            <option value="dark" <?php echo ($theme == 'dark') ? 'selected' : ''; ?>>Dark Mode</option>
        </select>

        <button type="submit">Save Changes</button>
    </form>

    <a href="dashboard.php" class="btn-back">Back to Dashboard</a>
</div>

<script>
    // JS for dynamic theme and mobile view changes
    function changeTheme() {
        let theme = document.getElementById('theme').value;
        document.body.classList.remove('light-mode', 'dark-mode');
        document.body.classList.add(theme + '-mode');
    }

    function changeMobileView() {
        let mobileView = document.getElementById('mobile_view').value;
        if (mobileView === 'yes') {
            document.body.classList.add('mobile-view');
        } else {
            document.body.classList.remove('mobile-view');
        }
    }
</script>

</body>
</html>
