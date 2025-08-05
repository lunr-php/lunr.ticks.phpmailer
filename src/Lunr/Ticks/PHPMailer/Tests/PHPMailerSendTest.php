<?php

/**
 * This file contains the PHPMailerSendTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherland B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\PHPMailer\Tests;

use Lunr\Ticks\AnalyticsDetailLevel;

/**
 * This class contains tests for the PHPMailer class.
 *
 * @covers Lunr\Ticks\PHPMailer\PHPMailer
 */
class PHPMailerSendTest extends PHPMailerTestCase
{

    /**
     * Test that the send() does nothing.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::send
     */
    public function testSendDoesNothing(): void
    {
        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->setReflectionPropertyValue('analyticsDetailLevel', AnalyticsDetailLevel::None);

        $this->controller->shouldNotReceive('startChildSpan');

        $this->class->send();

        $this->assertPropertyUnset('startTimestamp');

        $this->unmockFunction('microtime');
    }

    /**
     * Test that the send() calls the failure hook when PHPMailer Exception is thrown
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::send
     */
    public function testSendSucceedsWithAnalytics(): void
    {
        $this->mockFunction('microtime', fn() => 1724932394.128985);
        $this->mockMethod([ get_parent_class($this->class), 'send' ], fn() => TRUE);

        $this->setReflectionPropertyValue('analyticsDetailLevel', AnalyticsDetailLevel::Info);

        $this->controller->shouldReceive('startChildSpan')
                         ->once();

        $this->assertTrue($this->class->send());

        $this->assertPropertySame('startTimestamp', 1724932394.128985);

        $this->unmockMethod([ get_parent_class($this->class), 'send' ]);
        $this->unmockFunction('microtime');
    }

}

?>
