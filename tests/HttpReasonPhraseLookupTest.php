<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2018 - Code Inc. SAS - All Rights Reserved.           |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     12/06/2018
// Time:     12:49
// Project:  HttpReasonPhraseLookup
//
declare(strict_types=1);
namespace CodeInc\HttpReasonPhraseLookup\Tests;
use CodeInc\HttpReasonPhraseLookup\HttpReasonPhraseLookup;
use PHPUnit\Framework\TestCase;


/**
 * Class HttpReasonPhraseLookupTest
 *
 * @package CodeInc\HttpReasonPhraseLookup\Tests
 * @author  Joan Fabrégat <joan@codeinc.fr>
 * @uses HttpReasonPhraseLookup
 */
class HttpReasonPhraseLookupTest extends TestCase
{
    public function testList():void
    {
        foreach (HttpReasonPhraseLookup::getReasonPhrases() as $statusCode => $reasonPhrase) {
            self::assertInternalType('int', $statusCode);
            self::assertInternalType('string', $reasonPhrase);
            self::assertNotEmpty($reasonPhrase);
        }
    }

    public function testAllCodesLookup():void
    {
        $reasonPhrases = HttpReasonPhraseLookup::getReasonPhrases();
        self::assertInstanceOf(\Generator::class, $reasonPhrases);
        self::assertInternalType('iterable', $reasonPhrases);

        $reasonPhrases = iterator_to_array($reasonPhrases, true);
        self::assertInternalType('array', $reasonPhrases);
        self::assertNotEmpty($reasonPhrases);

        for ($statusCode = 0; $statusCode <= 599; $statusCode++) {
            if (!array_key_exists($statusCode, $reasonPhrases)) {
                self::assertNull(HttpReasonPhraseLookup::getReasonPhrase($statusCode));
            }
            else {
                self::assertNotNull($reasonPhrase = HttpReasonPhraseLookup::getReasonPhrase($statusCode));
                self::assertEquals($reasonPhrase, $reasonPhrases[$statusCode]);
            }
        }
    }
}