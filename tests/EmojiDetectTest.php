<?php

use PHPUnit\Framework\TestCase;

class EmojiDetectTest extends TestCase
{
    public function testDetectSimpleEmoji(): void
    {
        $string = '😻';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('😻', $emoji[0]['emoji']);
        $this->assertSame('heart_eyes_cat', $emoji[0]['short_name']);
        $this->assertSame('1F63B', $emoji[0]['hex_str']);
    }

    public function testDetectEvenSimplerEmoji(): void
    {
        $string = '❤️';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('❤️', $emoji[0]['emoji']);
        $this->assertSame('heart', $emoji[0]['short_name']);
        $this->assertSame('2764-FE0F', $emoji[0]['hex_str']);
    }

    public function testDetectEmojiWithZJW(): void
    {
        $string = '👨‍👩‍👦‍👦';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('man-woman-boy-boy', $emoji[0]['short_name']);
        $this->assertSame('1F468-200D-1F469-200D-1F466-200D-1F466', $emoji[0]['hex_str']);
    }

    public function testDetectEmojiWithZJW2(): void
    {
        $string = '👩‍❤️‍👩';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('woman-heart-woman', $emoji[0]['short_name']);
        $this->assertSame('1F469-200D-2764-FE0F-200D-1F469', $emoji[0]['hex_str']);
    }

    public function testDetectEmojiWithSkinTone(): void
    {
        $string = '👍🏼';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('👍🏼', $emoji[0]['emoji']);
        $this->assertSame('+1', $emoji[0]['short_name']);
        $this->assertSame('1F44D-1F3FC', $emoji[0]['hex_str']);
        $this->assertSame('skin-tone-3', $emoji[0]['skin_tone']);
    }

    public function testDetectMultipleEmoji(): void
    {
        $string = '👩❤️';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(2, $emoji);
        $this->assertSame('woman', $emoji[0]['short_name']);
        $this->assertSame('heart', $emoji[1]['short_name']);
    }

    public function testDetectFlagEmoji(): void
    {
        $string = '🇩🇪';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('flag-de', $emoji[0]['short_name']);
    }

    public function testDetectSymbolWithModifier(): void
    {
        $string = '♻️';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('recycle', $emoji[0]['short_name']);
    }

    public function testDetectCharacterSymbol(): void
    {
        $string = '™️';
        $emoji = Emoji\detect_emoji($string);
        $this->assertEquals(1, count($emoji));
        $this->assertEquals('tm', $emoji[0]['short_name']);
    }

    public function testDetectEmojiWithZJW3(): void
    {
        $string = '🏳️‍🌈';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('rainbow-flag', $emoji[0]['short_name']);
        $this->assertSame('1F3F3-FE0F-200D-1F308', $emoji[0]['hex_str']);
    }

    public function testDetectText(): void
    {
        $string = 'This has no emoji.';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(0, $emoji);
    }

    public function testDetectInText(): void
    {
        $string = 'This has an 🎉 emoji.';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('tada', $emoji[0]['short_name']);
    }

    public function testDetectGenderModifier(): void
    {
        // Added in June 2017 http://www.unicode.org/Public/emoji/5.0/emoji-test.txt
        $string = 'guardswoman 💂‍♀️';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('female-guard', $emoji[0]['short_name']);
    }

    public function testDetectGenderAndSkinToneModifier(): void
    {
        // Added in June 2017 http://www.unicode.org/Public/emoji/5.0/emoji-test.txt
        $string = 'guardswoman 💂🏼‍♀️';
        $emoji = Emoji\detect_emoji($string);
        $this->assertCount(1, $emoji);
        $this->assertSame('female-guard', $emoji[0]['short_name']);
    }
}
