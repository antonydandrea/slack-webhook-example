<!DOCTYPE HTML>
<html>
    <head>
        <title>Feedback Form To Slack</title>
    </head>
    <body>
        <h1>Please leave some feedback</h1>
        <form method="POST">
            <p>Forename:&nbsp;<input type="text" name="forename" /></p>
            <p>Surname:&nbsp;<input type="text" name="surname" /></p>
            <p>E-mail Address:&nbsp;<input type="text" name="email" /></p>
            <p>Please rate your experience from 1 to 10:</p>
            1 <input type="radio" name="rating" value="1" />
            2 <input type="radio" name="rating" value="2" />
            3 <input type="radio" name="rating" value="3" />
            4 <input type="radio" name="rating" value="4" />
            5 <input type="radio" name="rating" value="5" />
            6 <input type="radio" name="rating" value="6" />
            7 <input type="radio" name="rating" value="7" />
            8 <input type="radio" name="rating" value="8" />
            9 <input type="radio" name="rating" value="9" />
            10 <input type="radio" name="rating" value="10" /></p>
            <p><button value="1" type="submit" name="send_feedback">Send Feedback</button></p>           
        </form>
    </body>
</html>
<?php
if (!empty($_POST) && isset($_POST['send_feedback'])) {
    if (empty($_POST['forename']) || empty($_POST['surname']) || empty($_POST['email']) || empty($_POST['send_feedback'])) {
        echo 'One of the fields left blank.';
        return;
    }
    $forename = $_POST['forename'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $rating = $_POST['rating'];
    
    $payload = array();
	$title = "Feedback received from $forename $surname:";
    $text = "They gave a rating of $rating out of 10.\nContact them via $email.";
    
    $webHookURL = '[PUT YOUR WEBHOOK URL HERE]';
  
   
    //$payload['text'] = $text;
    if ($rating <= 4) {
        $payload['attachments'][0]['fallback'] = 'BAD FEEDBACK';
		$payload['attachments'][0]['title'] = $title;
        $payload['attachments'][0]['text'] = $text;
        $payload['attachments'][0]['color'] = 'danger';
    } elseif ($rating > 4 && $rating <= 7) {
        $payload['attachments'][0]['fallback'] = 'MEH FEEDBACK';
        $payload['attachments'][0]['title'] = $title;
		$payload['attachments'][0]['text'] = $text;
        $payload['attachments'][0]['color'] = 'warning';
    } elseif ($rating > 7 && $rating <= 10) {
        $payload['attachments'][0]['fallback'] = 'GOOD FEEDBACK';
		$payload['attachments'][0]['title'] = $title;
        $payload['attachments'][0]['text'] = $text;
        $payload['attachments'][0]['color'] = 'good';
    }
    
    $payloadJson = json_encode($payload);
    
    $post = array('payload' => $payloadJson);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $webHookURL);
    curl_setopt($ch, CURLOPT_POST, count($post));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    $result = curl_exec($ch);
    curl_close($ch);
    if ($result === true) {
        echo 'Feedback sent successfully';
    }
}
