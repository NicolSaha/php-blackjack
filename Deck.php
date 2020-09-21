<?php
declare(strict_types=1);

class Deck
{
    private const CARDS_PER_SUIT = 14;

    private $cards = [];

    public function __construct() {
        $suits = [
            Suit::SPADE(),
            Suit::HEART(),
            Suit::CLUB(),
            Suit::DIAMOND(),
        ];

        foreach ($suits AS $suit) {
            foreach(range(1, self::CARDS_PER_SUIT) AS $i) {
                $this->cards[] = new Card($suit, $i);
            }
        }
    }

    public function shuffle() : void {
        shuffle($this->cards);
    }

    /** @return Card[] */
    public function getCards() : array
    {
        return $this->cards;
    }

    public function drawCard() :? Card
    {
        return array_shift($this->cards);
    }
}