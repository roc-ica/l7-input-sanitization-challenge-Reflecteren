<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Sanitization Challenge</title>
</head>

<body>

    <h1>Welkom op onze website</h1>

    <?php
    // Ontvang het ingevoerde bericht van de gebruiker
    $userMessage = $_GET['message'] ?? '';

    // Toon het ingevoerde bericht zonder sanitatie (onveilig)
    echo "<p>Ongesaneerd bericht: $userMessage</p>";

    // Toon het ingevoerde bericht met HTML-sanitatie (deel van XSS-preventie)
    echo "<p>Veilig bericht (HTML-gecodeerd): " . htmlspecialchars($userMessage, ENT_QUOTES, 'UTF-8') . "</p>";

    // Toon het ingevoerde bericht met JavaScript-sanitatie (deel van XSS-preventie)
    echo "<script>let userMessage = '" . addslashes($userMessage) . "';</script>";

    // Voeg het bericht toe aan een database met een enkele tabel
    // Zorg voor eigen credentials voor deze database
    // Maak gebruik van prepared statements om SQL-injectie te voorkomen
    $servername = "localhost";
    $username = "";
    $password = "";
    $dbname = "";

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("INSERT INTO messages (message) VALUES (:message)");
        $stmt->bindParam(':message', $userMessage);
        $stmt->execute();

        echo "Message inserted successfully";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    $conn = null;
    ?>

    <hr>

    <!-- Formulier om bericht in te voeren -->
    <form action="opdracht.php" method="get">
        <label for="message">Voer hier uw bericht in:</label>
        <input type="text" id="message" name="message">
        <input type="submit" value="Verzenden">
    </form>

</body>

</html>