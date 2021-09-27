<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <p>
        <?php

        function sanitize_input($text)
        {
            $text = trim($text);
            $text = stripslashes($text);
            $text = htmlspecialchars($text);
            return $text;
        }

        $subjects = [
            'subject1' => "Sujet 1",
            'subject2' => "Sujet 2",
            'subject3' => "Sujet 3",
            'subject4' => "Sujet 4",
            'subject5' => "Sujet 5",
        ];

        $formData = [
            'lastName' => '',
            'firstName' => '',
            'email' => '',
            'phone' => '',
            'subject' => '',
            'message' => '',
        ];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            foreach ($formData as $key => $value) {
                if (array_key_exists($key, $_POST)) {
                    $formData[$key] = sanitize_input($_POST[$key]);
                    if ($key === 'email') {
                        $formData[$key] = filter_var($formData[$key], FILTER_VALIDATE_EMAIL);
                    }
                    if ($key === 'subject' && !array_key_exists($formData[$key], $subjects)) {
                        $formData[$key] = false;
                    }
                }
            }
        }

        $error = false;
        foreach ($formData as $key => $value) {
            if (!$value) {
                echo "Invalid form field: $key";
                if (array_key_exists($key, $_POST)) {
                    echo " - value: " . $_POST[$key];
                }
                echo '<br>';
                $error = true;
            }
        }
        if ($error) {
            exit(1);
        }
        echo "Merci " . $formData['firstName'] . " " . $formData['lastName'] . " de nous avoir contacté à propos de " . $subjects[$formData['subject']] . ".";
        echo "<br>";
        echo "Un de nos conseiller vous contactera soit à l’adresse " . $formData['email'] . " ou par téléphone au " . $formData['phone'] . " dans les plus brefs délais pour traiter votre demande : ";
        echo "<br>";
        echo $formData['message'];

        ?>
    </p>
</body>

</html>
