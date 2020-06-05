<?php

use PHPUnit\Framework\TestCase;

class EmojiSingleTest extends TestCase
{
    public function testSingleEmoji(): void
    {
        $string = '😻';
        $emoji = Emoji\isSingleEmoji($string);
        $this->assertSame($string, $emoji['emoji']);
    }

    public function testSingleCompositeEmoji(): void
    {
        $string = '👨‍👩‍👦‍👦';
        $emoji = Emoji\isSingleEmoji($string);
        $this->assertSame($string, $emoji['emoji']);
    }

    public function testMultipleEmoji(): void
    {
        $string = '😻🐈';
        $emoji = Emoji\isSingleEmoji($string);
        $this->assertFalse($emoji);
    }

    public function testSingleEmojiWithText(): void
    {
        $string = 'kitty 😻';
        $emoji = Emoji\isSingleEmoji($string);
        $this->assertFalse($emoji);
    }
}
