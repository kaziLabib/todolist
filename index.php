<?php
// Database connection
$host = 'localhost'; // Your database host, often localhost
$user = 'root'; // Your MySQL username
$password = ''; // Your MySQL password
$dbname = 'todo_db'; // The database name

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Add a new task when the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['task']) && !empty($_POST['task'])) {
        $task = $conn->real_escape_string($_POST['task']); // Sanitize input

        // Insert the new task into the database
        $sql = "INSERT INTO tasks (task) VALUES ('$task')";
        if ($conn->query($sql) === TRUE) {
            // Redirect to the same page after adding the task
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Remove a task
if (isset($_GET['delete'])) {
    $taskId = $_GET['delete'];

    // Delete the task from the database
    $sql = "DELETE FROM tasks WHERE id = $taskId";
    if ($conn->query($sql) === TRUE) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all tasks from the database
$sql = "SELECT * FROM tasks ORDER BY created_at DESC";
$result = $conn->query($sql);

// Check if there are any tasks
$tasks = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tasks[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to bottom, #ff7e5f, #feb47b, #ff6a00, #fcb045); /* Gradient background */
            margin: 0;
            padding: 0;
        }
        .container {
            width: 60%;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff; /* White background for container */
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        h1 {
            color: white;
            font-size: 36px;
            margin-bottom: 30px;
            padding: 15px;
            background-color: #333;
            border-radius: 8px;
        }
        input[type="text"] {
            width: 80%;
            padding: 15px;
            font-size: 18px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
        }
        button {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }
        button:hover {
            background-color: #2980b9;
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px;
            background-color: #ecf0f1;
            margin: 10px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        li.completed {
            background-color: #2ecc71;
            color: white;
            text-decoration: line-through;
        }
        li button {
            background-color: #e74c3c;
            font-size: 16px;
            padding: 5px 10px;
            cursor: pointer;
        }
        li button:hover {
            background-color: #c0392b;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            background-color: #2ecc71;
            color: white;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>To-Do List</h1>

    <form method="POST">
        <input type="text" name="task" placeholder="Enter your task..." required>
        <button type="submit">Add Task</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
        <div class="message">Task added successfully!</div>
    <?php endif; ?>

    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <span><?= htmlspecialchars($task['task']) ?></span>
                <a href="?delete=<?= $task['id'] ?>" style="color: white; text-decoration: none;">
                    <button>Delete</button>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

</body>
</html>
