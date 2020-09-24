<?php
declare(strict_types=1);

class Player
{

    protected array $cards;
    protected bool $lost = false;

    public function hit(Deck $deck)
    {
            if ($this->getScore() <= 21) {
                $hit_Card = $deck->drawCard();
                array_push($this->cards, $hit_Card);
            } elseif ($this->getScore() > 21) {
                return $this->lost = true;
            }
    }

    public function resetGame()
    {
        session_unset();
    }

    public function surrender()
    {
        return $this->lost = true;
    }

    public function getCards(): array
    {
        return $this->cards;
    }

    public function getScore()
    {
        $score = 0;
        foreach ($this->cards as $card) {
            $score += $card -> getValue();
        }
        return $score;
    }

    public function hasLost()
    {
        return $this->lost;
    }

    public function showCards() : void {
        $Show_Cards = $this->cards;
        foreach($Show_Cards AS $card) {
            echo $card->getUnicodeCharacter(true);
        }
    }

    public function __construct(Deck $deck)
    {
        $Card_One = $deck->drawCard();
        $Card_Two = $deck->drawCard();
        $this->cards = [$Card_One, $Card_Two];
    }

}

class Dealer extends Player {

    public function hit(Deck $deck)
    {
        if ($this->getScore() <= 15) {
            parent::hit($deck);
        }
    }

    public function playerSurrendered()
    {
       return $this->lost = false;
    }

    public function hasLost()
    {
        return parent::hasLost();
    }

    public function __construct(Deck $deck)
    {
        parent::__construct($deck);
    }

}
