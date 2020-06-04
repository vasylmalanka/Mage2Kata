<?php declare(strict_types=1);

namespace Mage2Kata\CustomerShortName\Api;

interface ShortenFirstNameInterface
{
    public function shorten(string $firstname): string;
}
