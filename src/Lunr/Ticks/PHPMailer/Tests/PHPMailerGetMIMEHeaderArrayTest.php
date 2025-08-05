<?php

/**
 * This file contains the PHPMailerGetMIMEHeaderArrayTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherlands B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\PHPMailer\Tests;

/**
 * This class contains tests for the getMIMEHeaderArray method.
 *
 * @covers Lunr\Ticks\PHPMailer\PHPMailer::getMIMEHeaderArray
 */
class PHPMailerGetMIMEHeaderArrayTest extends PHPMailerTestCase
{

    /**
     * Test that getMIMEHeaderArray returns an empty array when MIMEHeader is empty.
     */
    public function testGetMIMEHeaderArrayReturnsEmptyArrayWhenHeaderIsEmpty(): void
    {
        $this->setReflectionPropertyValue('MIMEHeader', '');

        $method = $this->getReflectionMethod('getMIMEHeaderArray');
        $result = $method->invoke($this->class);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test that getMIMEHeaderArray returns a single header correctly.
     */
    public function testGetMIMEHeaderArrayReturnsSingleHeader(): void
    {
        $header = 'Content-Type: text/plain';
        $this->setReflectionPropertyValue('MIMEHeader', $header);

        $method = $this->getReflectionMethod('getMIMEHeaderArray');
        $result = $method->invoke($this->class);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('Content-Type', $result);
        $this->assertEquals('text/plain', $result['Content-Type']);
    }

    /**
     * Test that getMIMEHeaderArray returns multiple headers correctly.
     */
    public function testGetMIMEHeaderArrayReturnsMultipleHeaders(): void
    {
        $header = "Content-Type: text/html\r\nX-Custom-Header: value";
        $this->setReflectionPropertyValue('MIMEHeader', $header);

        $method = $this->getReflectionMethod('getMIMEHeaderArray');
        $result = $method->invoke($this->class);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('Content-Type', $result);
        $this->assertArrayHasKey('X-Custom-Header', $result);
        $this->assertEquals('text/html', $result['Content-Type']);
        $this->assertEquals('value', $result['X-Custom-Header']);
    }

    /**
     * Test that getMIMEHeaderArray trims whitespace and ignores empty lines.
     */
    public function testGetMIMEHeaderArrayTrimsWhitespaceAndIgnoresEmptyLines(): void
    {
        $header = "  Content-Type: text/html  \r\n\r\nX-Test: test-value\r\n  ";
        $this->setReflectionPropertyValue('MIMEHeader', $header);

        $method = $this->getReflectionMethod('getMIMEHeaderArray');
        $result = $method->invoke($this->class);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertArrayHasKey('Content-Type', $result);
        $this->assertArrayHasKey('X-Test', $result);
        $this->assertEquals('text/html', $result['Content-Type']);
        $this->assertEquals('test-value', $result['X-Test']);
    }

}

?>
