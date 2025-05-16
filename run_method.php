<?php
// run_method.php

// Security note: In a real app, ensure you sanitize/validate these GET params
$table  = $_GET['table']  ?? '';
$column = $_GET['column'] ?? '';
$id     = $_GET['id']     ?? '';

$path = __DIR__ . "/metodos/$table/$column.php";
if (!file_exists($path)) {
    die("Method file not found for $table / $column");
}

// You might want to fetch the row from the DB to pass into the method:
require 'config.php'; // so we know $config['db_name']
$db = new SQLite3($config['db_name']);
$sql    = "SELECT * FROM \"$table\" WHERE id = :id LIMIT 1";
$stmt   = $db->prepare($sql);
$stmt->bindValue(':id', $id, SQLITE3_TEXT);
$result = $stmt->execute();
$row    = $result->fetchArray(SQLITE3_ASSOC);

// Potentially pass $row to the method file. For demonstration, let's do:
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Run Method</title>
    <style>
        /* Global Styles */
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap');

        :root {
            --primary-color: #3498db;
            --secondary-color: #2980b9;
            --accent-color: #e74c3c;
            --text-color: #333;
            --light-gray: #f5f5f5;
            --dark-gray: #333;
            --white: #ffffff;
            --shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
        }

        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background-color: var(--light-gray);
            color: var(--text-color);
            line-height: 1.6;
        }

        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        /* Header */
        .header {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 15px 20px;
            text-align: center;
            font-size: 1.5em;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .header h1 {
            font-size: 24px;
            margin-left: 10px;
        }

        #corporativo {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            justify-content: center;
            align-items: center;
            align-content: stretch;
        }

        #corporativo img {
            width: 60px;
            margin-right: 20px;
        }

        /* Main Content Area */
        .main {
            flex: 1;
            padding: 20px;
            background-color: var(--white);
            box-shadow: var(--shadow);
            margin: 20px;
            border-radius: var(--border-radius);
        }

        /* Method Output */
        .method-output {
            background-color: var(--white);
            border-radius: var(--border-radius);
            padding: 20px;
            margin: 20px;
            box-shadow: var(--shadow);
        }

        .method-output h3 {
            color: var(--primary-color);
            margin-bottom: 15px;
        }

        .method-output p {
            margin-bottom: 10px;
        }

        .method-output pre {
            background-color: var(--light-gray);
            padding: 15px;
            border-radius: var(--border-radius);
            overflow-x: auto;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main {
                margin: 10px;
            }

            .method-output {
                margin: 10px;
            }
        }
    </style>
</head>
<body>

<div class="header">
    <div id="corporativo">
        <img src="metagrow.png" alt="Logo">
        <h1>Run Method</h1>
    </div>
</div>

<div class="main">
    <div class="method-output">
        <h3>Running method for Table: <?php echo htmlspecialchars($table); ?>, Column: <?php echo htmlspecialchars($column); ?>, ID: <?php echo htmlspecialchars($id); ?></h3>
        <p>Row data:</p>
        <pre><?php print_r($row); ?></pre>

        <!-- Now let's include the method file. That file might expect a $row variable, or do something else. -->
        <h4>Method Output:</h4>
        <?php include $path; ?>
    </div>
</div>

</body>
</html>
<