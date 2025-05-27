<?php

/**
 * This file contains the PHPMailerTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherlands B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\PHPMailer\Tests;

use Lunr\Halo\LunrBaseTestCase;
use Lunr\Ticks\EventLogging\EventInterface;
use Lunr\Ticks\EventLogging\EventLoggerInterface;
use Lunr\Ticks\PHPMailer\PHPMailer;
use Lunr\Ticks\TracingControllerInterface;
use Lunr\Ticks\TracingInfoInterface;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\MockObject\MockObject;

/**
 * This class contains common setup routines, providers
 * and shared attributes for testing the PHPMailerTest class.
 *
 * @covers Lunr\Ticks\PHPMailer\PHPMailer
 */
abstract class PHPMailerTestCase extends LunrBaseTestCase
{

    /**
     * Instance of the EventLoggerInterface.
     * @var EventLoggerInterface&MockObject
     */
    protected EventLoggerInterface&MockObject $logger;

    /**
     * Mock instance of a Tracing Controller
     * @var TracingControllerInterface&TracingInfoInterface&MockInterface
     */
    protected TracingControllerInterface&TracingInfoInterface&MockInterface $controller;

    /**
     * Instance of the EventInterface.
     * @var EventInterface&MockObject
     */
    protected EventInterface&MockObject $event;

    /**
     * Instance of the tested class.
     * @var PHPMailer
     */
    protected PHPMailer $class;

    /**
     * TestCase Constructor.
     */
    public function setUp(): void
    {
        $this->event = $this->getMockBuilder(EventInterface::class)->getMock();

        $this->logger = $this->getMockBuilder(EventLoggerInterface::class)->getMock();

        $this->controller = Mockery::mock(
                         TracingControllerInterface::class,
                                TracingInfoInterface::class,
                            );

        $this->class = new PHPMailer($this->logger, $this->controller);

        $this->baseSetUp($this->class);
    }

    /**
     * TestCase Destructor.
     */
    public function tearDown(): void
    {
        unset($this->class);
        unset($this->logger);
        unset($this->event);
        unset($this->controller);

        parent::tearDown();
    }

}

?>
