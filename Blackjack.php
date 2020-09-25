<?php
declare(strict_types=1);

class Blackjack {
    private $player;
    private $dealer;
    private $deck;


    public function getDeck(): Deck
    {
        return $this->deck;
    }

    public function getPlayer(){
        return $this->player;
    }

    public function getDealer(){
        return $this->dealer;
    }

    public function compareScores()
    {
        $dealer_score = $this->dealer->getScore();
        $player_score = $this->player->getScore();

        if ($player_score > 21) {
            $this->player->setToLost();
        }

        if ($dealer_score > 21) {
            $this->dealer->setToLost();
        }

        if ($player_score === $dealer_score && $player_score <= 21) {
            $this->player->setToLost();
        } elseif ($player_score == 21) {
            $this->dealer->setToLost();
        } elseif ($dealer_score == 21) {
            $this->player->setToLost();
        }

        if ($player_score <= 21 && $player_score > $dealer_score) {
            $this->dealer->setToLost();
        }

        if ($dealer_score <= 21 && $dealer_score > $player_score) {
            $this->player->setToLost();
        }
    }

    public function __construct()
    {
        $this->deck = new Deck();
        $this->deck->shuffle();
        $this->player = new Player($this->deck);
        $this->dealer = new Dealer($this->deck);
    }
}