<?php

/**
 * This file contains the PHPMailerBaseTest class.
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
class PHPMailerBaseTest extends PHPMailerTestCase
{

    /**
     * Test that the eventlogger is unset.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__construct
     */
    public function testEventLoggerIsUnset(): void
    {
        $this->assertPropertyUnset('eventLogger', $this->logger);
    }

    /**
     * Test that the controller is unset.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__construct
     */
    public function testControllerIsUnset(): void
    {
        $this->assertPropertyUnset('tracingController', $this->controller);
    }

    /**
     * Test that the level is set correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__construct
     */
    public function testLevelIsSetCorrectly(): void
    {
        $this->assertPropertySame('analyticsDetailLevel', AnalyticsDetailLevel::None);
    }

    /**
     * Test that the action_function is set correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__construct
     */
    public function testRequestIsSetCorrectly(): void
    {
        $this->assertPropertySame('action_function', [ $this->class, 'afterSending' ]);
    }

    /**
     * Test that the startTimestamp is unset correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__destruct
     */
    public function testStartTimeIsUnsetCorrectly(): void
    {
        $this->setReflectionPropertyValue('startTimestamp', microtime(TRUE));

        $this->class->__destruct();

        $this->assertPropertyUnset('startTimestamp');
    }

    /**
     * Test that the enableAnalytics() sets properties correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::enableAnalytics
     */
    public function testEnableAnalyticsSetProperties(): void
    {
        $this->class->enableAnalytics($this->logger, $this->controller);

        $this->assertPropertySame('analyticsDetailLevel', AnalyticsDetailLevel::Info);
        $this->assertPropertySame('tracingController', $this->controller);
        $this->assertPropertySame('eventLogger', $this->logger);
    }

}

?>
