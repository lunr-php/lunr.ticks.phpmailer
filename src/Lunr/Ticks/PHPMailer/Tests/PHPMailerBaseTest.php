<?php

/**
 * This file contains the PHPMailerBaseTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherlands B.V., Zwolle, The Netherlands
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
     * Test that the event logger is set correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__construct
     */
    public function testEventLoggerIsSetCorrectly(): void
    {
        $this->assertPropertySame('eventLogger', $this->logger);
    }

    /**
     * Test that the controller is set correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__construct
     */
    public function testControllerIsSetCorrectly(): void
    {
        $this->assertPropertySame('tracingController', $this->controller);
    }

    /**
     * Test that the level is set correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::__construct
     */
    public function testLevelIsSetCorrectly(): void
    {
        $this->assertPropertySame('analyticsDetailLevel', AnalyticsDetailLevel::Info);
    }

    /**
     * Test that the setAnalyticsDetailLevel() sets properties correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::setAnalyticsDetailLevel
     */
    public function testSetAnalyticsDetailLevelProperty(): void
    {
        $this->class->setAnalyticsDetailLevel(AnalyticsDetailLevel::None);

        $this->assertPropertySame('analyticsDetailLevel', AnalyticsDetailLevel::None);
    }

}

?>
