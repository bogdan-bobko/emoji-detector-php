<?php

use PHPUnit\Framework\TestCase;

class SingleEmojiTest extends TestCase
{
    public function testSingleEmoji(): void
    {
        $this->assertTrue(
            Emoji\isSingleEmoji('😻')
        );
    }

    public function testSingleCompositeEmoji(): void
    {
        $this->assertTrue(
            Emoji\isSingleEmoji('👨‍👩‍👦‍👦')
        );
    }

    public function testDoubleCompositeEmoji(): void
    {
        $this->assertFalse(
            Emoji\isSingleEmoji('👨‍👩‍👦‍👦👨‍👩‍👦‍👦')
        );
    }

    public function testMultipleEmoji(): void
    {
        $this->assertFalse(
            Emoji\isSingleEmoji('😻🐈')
        );
    }

    public function testSingleEmojiWithText(): void
    {
        $this->assertFalse(
            $emoji = Emoji\isSingleEmoji('kitty 😻')
        );
    }
}
