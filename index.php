<?php
require_once 'twitter/twitteroauth.php';
require_once 'twitter/tweets.php';

$tweets = new Tweets();
$tweets = $tweets->getTweets();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Twiiter</title>

</head>
<body>
<div class="container" style="width:60%;margin: 100px auto">Tweets form:

    <a href="<?php echo 'https://twitter.com/'.$tweets[0]->user->screen_name; ?>"><?php echo $tweets[0]->user->screen_name; ?></a><br>
    <ul>
 <?php
    foreach ($tweets as $tweet) {
            ?>
    <li><p><?php echo $tweet->text; ?> (<?php echo substr($tweet->created_at, 4, 12); ?>)</p></li>
    <?php
    }

    ?>
    </ul>

</div>

</body>
</html>






