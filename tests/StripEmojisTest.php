<?php

use PHPUnit\Framework\TestCase;
use function Emoji\stripEmojis;

class StripEmojisTest extends TestCase
{
    public function emojiDataProvider(): array
    {
        return [
            [
                'string' => 'The 👨🏻‍🦳 and the 🌊',
                'expected' => 'The  and the ',
            ],
            [
                'string' => '1️⃣2️⃣3️⃣4️⃣5️⃣',
                'expected' => '',
            ],
            [
                'string' => '👨‍👩‍👧‍👦',
                'expected' => '',
            ],
            [
                'string' => 'Україна 🇺🇦',
                'expected' => 'Україна ',
            ],
            [
                'string' => '日本 🇯🇵',
                'expected' => '日本 ',
            ],
            [
                'string' => 'guards woman 💂‍♀️',
                'expected' => 'guards woman ',
            ]
        ];
    }

    /**
     * @dataProvider emojiDataProvider
     * @param string $string
     * @param string $expected
     */
    public function testStripEmoji(string $string, string $expected): void
    {
        $this->assertEquals($expected, stripEmojis($string));
    }
}
