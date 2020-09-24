<?php
declare(strict_types=1);

require 'Suit.php';
require 'Card.php';
require 'Deck.php';
require 'Player.php';
require 'Blackjack.php';

session_start();

function whatIsHappening() {
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}
whatIsHappening();

// Game Status
$_GAMESTATUS = '';
$_OUTCOME_PLAYER = '';
$_OUTCOME_DEALER = '';

// Blackjack Session Initialised
if (!isset($_SESSION['Created_Blackjack_Game'])) {
    $blackjack = new Blackjack();
    $_SESSION['Created_Blackjack_Game'] = serialize($blackjack);
}
else {
    $blackjack = unserialize($_SESSION['Created_Blackjack_Game'], ['allowed_classes' => true]);
}

// Player Makes Choice
if(isset($_POST['formChoice']) )
{
    //$varChoiceNextStep = $_POST['formChoice'];
    $errorMessage = "";
}

if(!isset($_POST['formChoice']) || $_POST['formChoice'] == "null" )
{
    $errorMessage = "<div class='alert alert-warning' role='warning'>Please make a choice! </div>";

}

//
if ($_POST['formChoice'] === 'hit' || $_POST['formChoice'] === 'stand') {
    if ($blackjack->getPlayer()->hasLost() == 1 || $blackjack->getDealer()->hasLost() == 0  ) {
        $_OUTCOME_PLAYER = "LOSER";
        $_OUTCOME_DEALER = "WINNER";
    } else {
        $_OUTCOME_PLAYER = "WINNER";
        $_OUTCOME_DEALER = "LOSER";
    }
}

// HIT Player
if (isset($_POST['formChoice']) && $_POST['formChoice'] === 'hit') {
    $blackjack->getPlayer()->hit($blackjack->getDeck());
    $_SESSION['Created_Blackjack_Game'] = serialize($blackjack);
    $_GAMESTATUS = "<div class='alert alert-success' role='success'> PLAYER HITS </div>";
}

// Stand Player
if (isset($_POST['formChoice']) && $_POST['formChoice'] === 'stand') {
    $blackjack->getDealer()->hit($blackjack->getDeck());
    $_SESSION['Created_Blackjack_Game'] = serialize($blackjack);
    $_GAMESTATUS = "<div class='alert alert-success' role='success'> PLAYER STANDS </div>";
}

// SURRENDER Player
if (isset($_POST['formChoice']) && $_POST['formChoice'] === 'surrender') {
    if ($blackjack->getPlayer()->surrender() == 1 || $blackjack->getDealer()->playerSurrendered() == 0  ) {
        $_OUTCOME_PLAYER = "LOSER";
        $_OUTCOME_DEALER = "WINNER";
    } else {
        $_OUTCOME_PLAYER = "WINNER";
        $_OUTCOME_DEALER = "LOSER";
    }
    $blackjack->getPlayer()->resetGame();
    $_SESSION['Created_Blackjack_Game'] = serialize($blackjack);
    $_GAMESTATUS = "<div class='alert alert-danger' role='danger'> PLAYER SURRENDERED, DEALER WINS </div>";

}

// PLAY AGAIN
if (isset($_POST['reset-button'])) {
    $blackjack->getPlayer()->resetGame();
}

//session_destroy();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" type="text/css"
          rel="stylesheet"/>
    <title>Blackjack</title>
</head>
<body>
<div class="container">
    <h1 style='text-align: center;';>Blackjack</h1>

    <form method="post" action="">

        <fieldset style='display: inline;' >
            <legend>Select Choice</legend>
            <p>
                <label>I want to ..</label>
                <select name = "formChoice" >
                    <option value = "null"></option>
                    <option value = "hit">Hit</option>
                    <option value = "stand">Stand</option>
                    <option value = "surrender">Surrender</option>
                </select>
            </p>
        </fieldset>
        <input style='display: inline; margin-left: 15px; margin-bottom: 5px;' type="submit" name="submit-button" value="Submit" class="btn btn-sm btn-outline-primary" data-toggle="button" aria-pressed="false" autocomplete="off"/>
        <input style='display: inline; margin-left: 15px; margin-bottom: 5px;' type="submit" name="reset-button" value="Play Again" class="btn btn-md btn-outline-dark" data-toggle="button" aria-pressed="false" autocomplete="off"/>
    </form>

    <p> <?php echo $errorMessage ?> </p>
    <p> <?php echo $_GAMESTATUS ?> </p>

    <H3> Player </H3>
    <p style=' margin-top: -100px;'> <?php $blackjack->getPlayer()->showCards(); ?> </p>
    <p style=' margin-top: -75px; margin-left: 15px;'> Score: <?php echo $blackjack->getPlayer()->getScore(); ?> </p>
    <p> <?php var_dump($_OUTCOME_PLAYER); ?> </p>
    <H3> Dealer </H3>
    <p style=' margin-top: -100px;'> <?php $blackjack->getDealer()->showCards(); ?> </p>
    <p style=' margin-top: -75px; margin-left: 15px;'> Score: <?php echo $blackjack->getDealer()->getScore(); ?> </p>
    <p> <?php var_dump($_OUTCOME_DEALER); ?> </p>

</body>
</html>