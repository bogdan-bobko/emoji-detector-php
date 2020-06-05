<?php

namespace Emoji;

define('LONGEST_EMOJI', 8);

function map(): array
{
    static $map;

    if (!isset($map)) {
        $map = loadMap();
    }

    return $map;
}

function loadMap(): array
{
    return json_decode(file_get_contents(dirname(__FILE__) . '/map.json'), true);
}

function regexp(): string
{
    static $regexp;

    if (!isset($regexp)) {
        $regexp = loadRegexp();
    }

    return $regexp;
}

function loadRegexp(): string
{
    return '/(?:' . json_decode(file_get_contents(dirname(__FILE__) . '/regexp.json')) . ')/u';
}

function stripEmojis(string $string): string
{
    $prevEncoding = mb_internal_encoding();
    mb_internal_encoding('UTF-8');

    $result = preg_replace(regexp(), '', $string);

    if ($prevEncoding) {
        mb_internal_encoding($prevEncoding);
    }

    return $result;
}

/**
 * Find all the emoji in the input string
 * @param $string
 * @return array
 */
function detectEmojis(string $string): array
{
    $prevEncoding = mb_internal_encoding();
    mb_internal_encoding('UTF-8');

    $skinTones = array(
        '1F3FB' => 'skin-tone-2',
        '1F3FC' => 'skin-tone-3',
        '1F3FD' => 'skin-tone-4',
        '1F3FE' => 'skin-tone-5',
        '1F3FF' => 'skin-tone-6',
    );

    $data = array();
    if (preg_match_all(regexp(), $string, $matches)) {
        foreach ($matches[0] as $match) {
            $points = array();
            for ($i = 0; $i < mb_strlen($match); $i++) {
                $points[] = strtoupper(dechex(uniOrd(mb_substr($match, $i, 1))));
            }

            $hexString = implode('-', $points);

            $shortName = null;
            if (array_key_exists($hexString, map())) {
                $shortName = map()[$hexString];
            }

            $skinTone = null;

            foreach ($points as $point) {
                if (array_key_exists($point, $skinTones)) {
                    $skinTone = $skinTones[$point];
                }
            }

            $data[] = array(
                'emoji' => $match,
                'short_name' => $shortName,
                'num_points' => mb_strlen($match),
                'points_hex' => $points,
                'hex_str' => $hexString,
                'skin_tone' => $skinTone,
            );
        }
    }

    if ($prevEncoding) {
        mb_internal_encoding($prevEncoding);
    }

    return $data;
}

function isSingleEmoji(string $string): bool
{
    $prevEncoding = mb_internal_encoding();
    mb_internal_encoding('UTF-8');

    // If the string is longer than the longest emoji, it's not a single emoji
    if (mb_strlen($string) >= LONGEST_EMOJI) {
        return false;
    }

    $allEmojis = detectEmojis($string);
    $singleEmoji = count($allEmojis) === 1;

    // If there are more than one or none, return false immediately
    if ($singleEmoji) {
        $emoji = $allEmojis[0];

        // Check if there are any other characters in the string

        // Remove the emoji found
        $string = str_replace($emoji['emoji'], '', $string);

        // If there are any characters left, then the string is not a single emoji
        if (strlen($string) > 0) {
            $singleEmoji = false;
        }
    }

    if ($prevEncoding) {
        mb_internal_encoding($prevEncoding);
    }

    return $singleEmoji;
}

function uniOrd(string $c): int
{
    $ord0 = ord($c[0]);
    if ($ord0 >= 0 && $ord0 <= 127) {
        return $ord0;
    }

    $ord1 = ord($c[1]);
    if ($ord0 >= 192 && $ord0 <= 223) {
        return ($ord0 - 192) * 64 + ($ord1 - 128);
    }

    $ord2 = ord($c[2]);
    if ($ord0 >= 224 && $ord0 <= 239) {
        return ($ord0 - 224) * 4096 + ($ord1 - 128) * 64 + ($ord2 - 128);
    }

    $ord3 = ord($c[3]);
    if ($ord0 >= 240 && $ord0 <= 247) {
        return ($ord0 - 240) * 262144 + ($ord1 - 128) * 4096 + ($ord2 - 128) * 64 + ($ord3 - 128);
    }

    return false;
}
