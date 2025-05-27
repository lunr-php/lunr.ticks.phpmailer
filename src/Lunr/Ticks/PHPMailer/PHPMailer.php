<?php

/**
 * This file contains the PHPMailer class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherlands B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\PHPMailer;

use Lunr\Ticks\AnalyticsDetailLevel;
use Lunr\Ticks\EventLogging\EventLoggerInterface;
use Lunr\Ticks\TracingControllerInterface;
use Lunr\Ticks\TracingInfoInterface;
use PHPMailer\PHPMailer\PHPMailer as BaseMailer;

/**
 * PHPMailer class
 */
class PHPMailer extends BaseMailer
{

    /**
     * Shared instance of the event logger
     * @var EventLoggerInterface
     */
    protected readonly EventLoggerInterface $eventLogger;

    /**
     * Shared instance of the info tracing controller
     * @var TracingControllerInterface&TracingInfoInterface
     */
    protected readonly TracingControllerInterface&TracingInfoInterface $tracingController;

    /**
     * Profiling level
     * @var AnalyticsDetailLevel
     */
    protected AnalyticsDetailLevel $analyticsDetailLevel;

    /**
     * Constructor.
     *
     * @param EventLoggerInterface                            $eventLogger Instance of an event logger
     * @param TracingControllerInterface&TracingInfoInterface $controller  Instance of a tracing controller
     * @param AnalyticsDetailLevel                            $level       Analytics detail level (defaults to Info)
     * @param bool|null                                       $exceptions  Should we throw external exceptions?
     */
    public function __construct(
        EventLoggerInterface $eventLogger,
        TracingControllerInterface&TracingInfoInterface $controller,
        AnalyticsDetailLevel $level = AnalyticsDetailLevel::Info,
        ?bool $exceptions = NULL
    )
    {
        $this->eventLogger          = $eventLogger;
        $this->tracingController    = $controller;
        $this->analyticsDetailLevel = $level;

        parent::__construct($exceptions);
    }

    /**
     * Destructor.
     */
    public function __destruct()
    {
        unset($this->analyticsDetailLevel);

        parent::__destruct();
    }

    /**
     * Sets the analytics details level
     *
     * @param AnalyticsDetailLevel $level Analytics detail level.
     *
     * @return void
     */
    public function setAnalyticsDetailLevel(AnalyticsDetailLevel $level): void
    {
        $this->analyticsDetailLevel = $level;
    }

}

?>
