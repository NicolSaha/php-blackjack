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
//whatIsHappening();

// Game Status & Variables Defined
$_GAMESTATUS = '';
$_OUTCOME_PLAYER = '';
$_OUTCOME_DEALER = '';
$errorMessage = "";

//Outcome Game
$winner = "<p style='text-align: center; display: inline;' class='col-3 alert alert-success' role='success'> WINNER  </p>";
$loser = "<p style='text-align: center; display: inline;' class='col-3 alert alert-danger' role='danger'> LOSER  </p>";


// PLAY AGAIN
if (isset($_POST['reset-button'])) {
    session_unset();
    unset($blackjack);
}

// Blackjack Session Initialised
if (!isset($_SESSION['Created_Blackjack_Game'])) {
    $blackjack = new Blackjack();
    $_SESSION['Created_Blackjack_Game'] = serialize($blackjack);
}
else {
    $blackjack = unserialize($_SESSION['Created_Blackjack_Game'], ['allowed_classes' => true]);
}

// Player Makes Choice

if(!isset($_POST['hit']) && !isset($_POST['stand']) && !isset($_POST['surrender']))
{
    $errorMessage = "<div style='text-align: center;' class='alert alert-warning' role='warning'>Please make a choice! </div>";
}

// HIT Player
if (isset($_POST['hit'])) {
    $blackjack->getPlayer()->hit($blackjack->getDeck());
    $blackjack->getDealer()->hit($blackjack->getDeck());

    $blackjack->compareScores();

   if ($blackjack->getPlayer()->hasLost() == false && $blackjack->getDealer()->hasLost() == true ) {
        $_OUTCOME_PLAYER = $winner;
        $_OUTCOME_DEALER = $loser;
    }

   if ($blackjack->getPlayer()->hasLost() == true && $blackjack->getDealer()->hasLost() == false ) {
        $_OUTCOME_DEALER = $winner;
        $_OUTCOME_PLAYER = $loser;
    }

   if ($blackjack->getPlayer()->hasLost() == true && $blackjack->getDealer()->hasLost() == true) {
        $_OUTCOME_DEALER = $loser;
        $_OUTCOME_PLAYER = $loser;
   }

    $_GAMESTATUS = "<div style='text-align: center;' class='alert alert-dark' role='success'> PLAYER CHOSE TO HIT </div>";
    $_SESSION['Created_Blackjack_Game'] = serialize($blackjack);
}

// Stand Player
if (isset($_POST['stand'])) {
    
   // $blackjack->compareScores();

    // Compare Score
    $score_player = $blackjack->getPlayer()->getScore();
    $score_dealer = $blackjack->getDealer()->getScore();

    if ($score_dealer === $score_player) {
        $_OUTCOME_DEALER = $winner;
        $_OUTCOME_PLAYER = $loser;
    } elseif ($score_dealer == 21) {
        $_OUTCOME_DEALER = $winner;
        $_OUTCOME_PLAYER = $loser;
    } elseif ($score_player == 21) {
        $_OUTCOME_PLAYER = $winner;
        $_OUTCOME_DEALER = $loser;
    }

    if ($score_player > $score_dealer  && $score_player <= 21) {
        $_OUTCOME_PLAYER = $winner;
        $_OUTCOME_DEALER = $loser;
    }

    if ($score_player > $score_dealer  && $score_dealer <= 21) {
        $_OUTCOME_PLAYER = $loser;
        $_OUTCOME_DEALER = $winner;
    }

    if ($score_player > 21) {
        $_OUTCOME_DEALER = $winner;
        $_OUTCOME_PLAYER = $loser;
    }
    if ($score_dealer > 21) {
        $_OUTCOME_PLAYER = $winner;
        $_OUTCOME_DEALER = $loser;
    }

    $_GAMESTATUS = "<div style='text-align: center;' class='alert alert-dark' role='warning'> PLAYER CHOSE TO STAND </div>";
    $_SESSION['Created_Blackjack_Game'] = serialize($blackjack);
}

// SURRENDER Player
if (isset($_POST['surrender'])) {

    if ($blackjack->getPlayer()->surrender() == 1 || $blackjack->getDealer()->playerSurrendered() == 0  ) {
        $_OUTCOME_DEALER = $winner;
        $_OUTCOME_PLAYER = $loser;
    }

    $_GAMESTATUS = "<div style='text-align: center;' class='alert alert-dark' role='warning'> PLAYER CHOSE TO SURRENDERED, DEALER WINS </div>";
    $_SESSION['Created_Blackjack_Game'] = serialize($blackjack);
}

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

    <form method="post" action="" style='text-align: center; margin: 15px;'>

        <input style='display: inline; width: 100px; margin-right: 15px;' type="submit" name="hit" value="Hit" class="btn btn-md btn-success" data-toggle="button" aria-pressed="false" autocomplete="off"/>
        <input style='display: inline; width: 100px; margin-right: 15px;' type="submit" name="stand" value="Stand" class="btn btn-md btn-primary" data-toggle="button" aria-pressed="false" autocomplete="off"/>
        <input style='display: inline; width: 100px; margin-right: 15px;' type="submit" name="surrender" value="Surrender" class="btn btn-md btn-secondary" data-toggle="button" aria-pressed="false" autocomplete="off"/>

        <input style='display: inline; width: 150px; margin-right: 15px;' type="submit" name="reset-button" value="Play Again" class="btn btn-lg btn-outline-dark" data-toggle="button" aria-pressed="false" autocomplete="off"/>
    </form>

    <p> <?php echo $errorMessage ?> </p>
    <p> <?php echo $_GAMESTATUS ?> </p>

    </br>

    <H3 style='display: inline;'> Player <?php echo $_OUTCOME_PLAYER; ?> </H3>
    <p style=' margin-top: -75px;'> <?php $blackjack->getPlayer()->showCards(); ?> </p>
    <p style=' margin-top: -75px; margin-left: 15px;'> Score: <?php echo $blackjack->getPlayer()->getScore(); ?> </p>

    </br>

    <H3 style='display: inline;'> Dealer <?php echo $_OUTCOME_DEALER; ?> </H3>
    <p style=' margin-top: -75px;'> <?php $blackjack->getDealer()->showCards(); ?> </p>
    <p style=' margin-top: -75px; margin-left: 15px;'> Score: <?php echo $blackjack->getDealer()->getScore(); ?> </p>

</body>
</html>


