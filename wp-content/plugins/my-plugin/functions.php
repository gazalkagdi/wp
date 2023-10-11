<?php
// Database connection
$dsn = 'mysql:host=localhost;dbname=wp';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if (isset($_POST['submit'])) {
    $title = $_POST['title'];
    $answer = $_POST['answer'];

    // Insert the new FAQ into the database
    $insertQuery = "INSERT INTO test (question, answer) VALUES (:title, :answer)";
    $stmt = $db->prepare($insertQuery);
    $stmt->bindParam(':title', $title, PDO::PARAM_STR);
    $stmt->bindParam(':answer', $answer, PDO::PARAM_STR);
    if ($stmt->execute()) {
        // echo "FAQ added successfully!";
    } else {
        echo "Error adding FAQ.";
    }
}

if (isset($_POST['delete'])) {
    $delete_id = $_POST['delete_id'];

    // Delete the FAQ entry from the database
    $deleteQuery = "DELETE FROM test WHERE id = :delete_id";
    $stmt = $db->prepare($deleteQuery);
    $stmt->bindParam(':delete_id', $delete_id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        // echo "FAQ deleted successfully!";
    } else {
        echo "Error deleting FAQ.";
    }
}



// Retrieve FAQ data from the database
$query = "SELECT * FROM test";
$faqs = $db->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ Page</title>
    <style>
        .faq-accordion {
            margin: 20px;
        }

        .faq-item {
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .faq-question {
            font-weight: bold;
            cursor: pointer;
        }

        .faq-answer {
            margin-top: 10px;
            display: none; /* Initially hide answers */
        }

        .faq-item.active .faq-answer {
            display: block; /* Show answers when the item is active */
        }

        .delete-button {
            background-color: #ff5555;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .delete-button:hover {
            background-color: #cc0000;
        }

        .form-container {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 400px;
        }

        .form-container label {
            display: block;
            font-weight: bold;
            margin-top: 10px;
        }

        .form-container input[type="text"],
        .form-container textarea {
            width: 100%;
            padding: 5px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-container input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }

        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="faq-accordion">
    <?php foreach ($faqs as $faq) : ?>
        <div class="faq-item">
            <h3 class="faq-question"><?php echo esc_html($faq['question']); ?></h3>
            <div class="faq-answer"><?php echo wpautop(esc_html($faq['answer'])); ?></div>
            <form method="post">
                <input type="hidden" name="delete_id" value="<?php echo $faq['id']; ?>">
                <input type="submit" name="delete" value="Delete" class="delete-button">
            </form>
        </div>
    <?php endforeach; ?>
</div>

<form method="post" class="form-container">
    <label for="title">Title:</label>
    <input type="text" name="title" id="title" required><br>

    <label for="answer">Post:</label>
    <textarea name="answer" id="answer" required></textarea><br>

    <input type="submit" name="submit" value="Add FAQ">
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    // Toggle the answer visibility on click
    $('.faq-question').on('click', function(){
        var item = $(this).parent('.faq-item');
        item.toggleClass('active');
    });
    
    // Delay and show the answer on hover
    var hoverTimeout;
    $('.faq-question').hover(function() {
        var item = $(this).parent('.faq-item');
        hoverTimeout = setTimeout(function() {
            item.addClass('active');
        }, 300); // Delay for 1 second
    }, function() {
        var item = $(this).parent('.faq-item');
        clearTimeout(hoverTimeout); // Clear the timeout
        item.removeClass('active');
    });
});
</script>
</body>
</html>



