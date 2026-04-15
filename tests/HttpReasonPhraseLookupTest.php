<?php

declare(strict_types=1);

namespace CodeInc\HttpReasonPhraseLookup\Tests;

use CodeInc\HttpReasonPhraseLookup\HttpReasonPhraseLookup;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class HttpReasonPhraseLookupTest extends TestCase
{
    public function testGetAllReasonPhrasesReturnsNonEmptyArray(): void
    {
        $phrases = HttpReasonPhraseLookup::getAllReasonPhrases();

        self::assertNotEmpty($phrases);
        self::assertIsArray($phrases);
    }

    public function testAllKeysAreIntsAndValuesAreNonEmptyStrings(): void
    {
        foreach (HttpReasonPhraseLookup::getAllReasonPhrases() as $statusCode => $reasonPhrase) {
            self::assertIsInt($statusCode);
            self::assertIsString($reasonPhrase);
            self::assertNotEmpty($reasonPhrase, "Reason phrase for status code $statusCode is empty");
        }
    }

    public function testAllStatusCodesAreInValidRange(): void
    {
        foreach (HttpReasonPhraseLookup::getAllReasonPhrases() as $statusCode => $reasonPhrase) {
            self::assertGreaterThanOrEqual(100, $statusCode, "Status code $statusCode is below 100");
            self::assertLessThan(600, $statusCode, "Status code $statusCode is 600 or above");
        }
    }

    public function testGetReasonPhraseMatchesGetAllReasonPhrases(): void
    {
        $allPhrases = HttpReasonPhraseLookup::getAllReasonPhrases();

        foreach ($allPhrases as $statusCode => $expectedPhrase) {
            self::assertSame(
                $expectedPhrase,
                HttpReasonPhraseLookup::getReasonPhrase($statusCode),
                "getReasonPhrase($statusCode) does not match getAllReasonPhrases()"
            );
        }
    }

    public function testUnknownStatusCodesReturnNull(): void
    {
        // Codes outside the valid range
        self::assertNull(HttpReasonPhraseLookup::getReasonPhrase(0));
        self::assertNull(HttpReasonPhraseLookup::getReasonPhrase(99));
        self::assertNull(HttpReasonPhraseLookup::getReasonPhrase(600));
        self::assertNull(HttpReasonPhraseLookup::getReasonPhrase(999));
        self::assertNull(HttpReasonPhraseLookup::getReasonPhrase(-1));

        // Unassigned codes within valid range
        self::assertNull(HttpReasonPhraseLookup::getReasonPhrase(109));
        self::assertNull(HttpReasonPhraseLookup::getReasonPhrase(299));
        self::assertNull(HttpReasonPhraseLookup::getReasonPhrase(420));
    }

    public function testHasReasonPhraseForKnownCodes(): void
    {
        self::assertTrue(HttpReasonPhraseLookup::hasReasonPhrase(200));
        self::assertTrue(HttpReasonPhraseLookup::hasReasonPhrase(404));
        self::assertTrue(HttpReasonPhraseLookup::hasReasonPhrase(500));
    }

    public function testHasReasonPhraseForUnknownCodes(): void
    {
        self::assertFalse(HttpReasonPhraseLookup::hasReasonPhrase(0));
        self::assertFalse(HttpReasonPhraseLookup::hasReasonPhrase(999));
        self::assertFalse(HttpReasonPhraseLookup::hasReasonPhrase(299));
    }

    #[DataProvider('wellKnownCodesProvider')]
    public function testWellKnownStatusCodes(int $statusCode, string $expectedPhrase): void
    {
        self::assertSame($expectedPhrase, HttpReasonPhraseLookup::getReasonPhrase($statusCode));
    }

    /**
     * @return iterable<string, array{int, string}>
     */
    public static function wellKnownCodesProvider(): iterable
    {
        yield '200 OK' => [200, 'OK'];
        yield '201 Created' => [201, 'Created'];
        yield '204 No Content' => [204, 'No Content'];
        yield '301 Moved Permanently' => [301, 'Moved Permanently'];
        yield '302 Found' => [302, 'Found'];
        yield '304 Not Modified' => [304, 'Not Modified'];
        yield '308 Permanent Redirect' => [308, 'Permanent Redirect'];
        yield '400 Bad Request' => [400, 'Bad Request'];
        yield '401 Unauthorized' => [401, 'Unauthorized'];
        yield '403 Forbidden' => [403, 'Forbidden'];
        yield '404 Not Found' => [404, 'Not Found'];
        yield '405 Method Not Allowed' => [405, 'Method Not Allowed'];
        yield '409 Conflict' => [409, 'Conflict'];
        yield '418 Teapot' => [418, "I'm a Teapot"];
        yield '422 Unprocessable Content' => [422, 'Unprocessable Content'];
        yield '429 Too Many Requests' => [429, 'Too Many Requests'];
        yield '500 Internal Server Error' => [500, 'Internal Server Error'];
        yield '502 Bad Gateway' => [502, 'Bad Gateway'];
        yield '503 Service Unavailable' => [503, 'Service Unavailable'];
        yield '504 Gateway Timeout' => [504, 'Gateway Timeout'];
    }

    public function testDeprecatedGetReasonPhrasesReturnsGenerator(): void
    {
        $generator = HttpReasonPhraseLookup::getReasonPhrases();

        self::assertInstanceOf(\Generator::class, $generator);

        $fromGenerator = iterator_to_array($generator, true);
        self::assertSame(HttpReasonPhraseLookup::getAllReasonPhrases(), $fromGenerator);
    }

    public function testGapCodesReturnNull(): void
    {
        $allPhrases = HttpReasonPhraseLookup::getAllReasonPhrases();

        for ($code = 100; $code < 600; $code++) {
            if (!array_key_exists($code, $allPhrases)) {
                self::assertNull(
                    HttpReasonPhraseLookup::getReasonPhrase($code),
                    "Unregistered code $code should return null"
                );
                self::assertFalse(
                    HttpReasonPhraseLookup::hasReasonPhrase($code),
                    "hasReasonPhrase($code) should be false for unregistered code"
                );
            }
        }
    }
}
