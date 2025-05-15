<?php

/**
 * This file contains the PHPMailerSendTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherland B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\PHPMailer\Tests;

use Lunr\Ticks\AnalyticsDetailLevel;
use PHPMailer\PHPMailer\Exception;

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
        $this->setReflectionPropertyValue('tracingController', $this->controller);

        $this->controller->expects($this->never())
                         ->method('startChildSpan');

        $this->class->send();

        $this->assertPropertyUnset('startTimestamp');

        $this->unmockFunction('microtime');
    }

    /**
     * Test that the send() calls the failure hook when returning false
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::send
     */
    public function testSendCallsFailureHookWhenReturningFalse(): void
    {
        $this->mockFunction('microtime', fn() => 1724932394.128985);
        $this->mockMethod([ get_parent_class($this->class), 'send' ], fn() => FALSE);
        $this->mockMethod([ $this->class, 'failureHook' ], function () { echo 'failureHook is called'; });

        $this->setReflectionPropertyValue('analyticsDetailLevel', AnalyticsDetailLevel::Info);
        $this->setReflectionPropertyValue('tracingController', $this->controller);

        $this->controller->expects($this->once())
                         ->method('startChildSpan');

        $this->expectOutputString('failureHook is called');

        $this->assertFalse($this->class->send());

        $this->assertPropertySame('startTimestamp', 1724932394.128985);

        $this->unmockMethod([ $this->class, 'failureHook' ]);
        $this->unmockMethod([ get_parent_class($this->class), 'send' ]);
        $this->unmockFunction('microtime');
    }

    /**
     * Test that the send() calls the failure hook when PHPMailer Exception is thrown
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::send
     */
    public function testSendCallsFailureHookWhenPHPMailerExceptionIsThrown(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('PHPMailer Exception');

        $this->mockFunction('microtime', fn() => 1724932394.128985);
        $this->mockMethod([ get_parent_class($this->class), 'send' ], fn() => throw new Exception('PHPMailer Exception'));
        $this->mockMethod([ $this->class, 'failureHook' ], function () { echo 'failureHook is called'; });

        $this->setReflectionPropertyValue('analyticsDetailLevel', AnalyticsDetailLevel::Info);
        $this->setReflectionPropertyValue('tracingController', $this->controller);

        $this->controller->expects($this->once())
                         ->method('startChildSpan');

        $this->expectOutputString('failureHook is called');

        $this->class->send();

        $this->assertPropertySame('startTimestamp', 1724932394.128985);

        $this->unmockMethod([ $this->class, 'failureHook' ]);
        $this->unmockMethod([ get_parent_class($this->class), 'send' ]);
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
        $this->setReflectionPropertyValue('tracingController', $this->controller);

        $this->controller->expects($this->once())
                         ->method('startChildSpan');

        $this->assertTrue($this->class->send());

        $this->assertPropertySame('startTimestamp', 1724932394.128985);

        $this->unmockMethod([ get_parent_class($this->class), 'send' ]);
        $this->unmockFunction('microtime');
    }

}

?>
