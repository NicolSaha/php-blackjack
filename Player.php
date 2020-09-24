<?php
declare(strict_types=1);

class Player
{
    private array $cards;
    private bool $lost = false;

    public function hit($deck) : void
    {
            if ($this->getScore() > 21) {
                $this->lost = true;
            } else {
                $hit_Card = $deck->drawCard();
                array_push($this->cards, $hit_Card);
            }
    }

    public function surrender() : void
    {
        if (isset($_POST['formChoice']) && $_POST['formChoice'] === 'surrender') {
            $this->lost = true;
        }
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
        echo $score;
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

    public function __construct($deck)
    {
        $playerCard_One = $deck->drawCard();
        $playerCard_Two = $deck->drawCard();
        $this->cards = [$playerCard_One, $playerCard_Two];
    }

}


