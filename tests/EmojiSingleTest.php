<?php

use PHPUnit\Framework\TestCase;

class EmojiSingleTest extends TestCase {
  public function testSingleEmoji() {
    $string = '😻';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertSame($string, $emoji['emoji']);
  }

  public function testSingleCompositeEmoji() {
    $string = '👨‍👩‍👦‍👦';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertSame($string, $emoji['emoji']);
  }

  public function testMultipleEmoji() {
    $string = '😻🐈';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertFalse($emoji);
  }

  public function testSingleEmojiWithText() {
    $string = 'kitty 😻';
    $emoji = Emoji\is_single_emoji($string);
    $this->assertFalse($emoji);
  }
}
