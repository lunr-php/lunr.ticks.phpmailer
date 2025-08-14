<?php

/**
 * This file contains the PHPMailerFailureHookTest class.
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
class PHPMailerFailureHookTest extends PHPMailerTestCase
{

    /**
     * Test that the failureHook works correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::failureHook
     */
    public function testFailureHookWithNonSMTPMail(): void
    {
        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->setReflectionPropertyValue('startTimestamp', 1724932393.008985);
        $this->setReflectionPropertyValue('Mailer', 'mail');
        $this->setReflectionPropertyValue('to', [ 'john@doe.com', 'John Doe' ]);
        $this->setReflectionPropertyValue('cc', [ 'cc@doe.com', 'CC Doe' ]);
        $this->setReflectionPropertyValue('bcc', [ 'bcc@doe.com', 'BCC Doe' ]);
        $this->setReflectionPropertyValue('Subject', 'subject');
        $this->setReflectionPropertyValue('MIMEHeader', 'Content-Type: text/plain');
        $this->setReflectionPropertyValue('MIMEBody', 'body');
        $this->setReflectionPropertyValue('From', 'from');
        $this->setReflectionPropertyValue('analyticsDetailLevel', AnalyticsDetailLevel::Detailed);

        $this->controller->shouldReceive('getTraceID')
                         ->once()
                         ->andReturn('bc5bfcc7-8d8d-4e59-b4be-7453b97410d');

        $this->controller->shouldReceive('getSpanID')
                         ->once()
                         ->andReturn('ef14c184-5b4a-4e0b-8026-7c5683e611c7');

        $this->controller->shouldReceive('getParentSpanID')
                         ->once()
                         ->andReturn('6cb28307-95b0-491e-a82a-9d679f511e43');

        $this->controller->shouldReceive('getSpanSpecificTags')
                         ->once()
                         ->andReturn([]);

        $this->logger->expects($this->once())
                     ->method('newEvent')
                     ->with('outbound_requests_log')
                     ->willReturn($this->event);

        $this->event->expects($this->once())
                    ->method('addTags')
                    ->with([
                        'type'   => 'MAIL',
                        'status' => '400',
                        'domain' => 'localhost',
                    ]);

        $this->event->expects($this->once())
                    ->method('addFields')
                    ->with([
                        'startTimestamp' => 1724932393.008985,
                        'endTimestamp'   => 1724932394.128985,
                        'executionTime'  => 1.12,
                        'url'            => 'localhost',
                        'requestHeaders' => '{"Content-Type":"text\/plain"}',
                        'requestBody'    => 'body',
                        'options'        => '[]'
                    ]);

        $this->event->expects($this->once())
                    ->method('recordTimestamp');

        $this->event->expects($this->once())
                    ->method('record');

        $method = $this->getReflectionMethod('failureHook');
        $method->invoke($this->class);

        $this->unmockFunction('microtime');

        uopz_unset_return('microtime');
    }

    /**
     * Test that the failureHook works correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::failureHook
     */
    public function testFailureHookWithSMTPMail(): void
    {
        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->setReflectionPropertyValue('startTimestamp', 1724932393.008985);
        $this->setReflectionPropertyValue('Mailer', 'smtp');
        $this->setReflectionPropertyValue('to', [[ 'john@doe.com', 'John Doe' ]]);
        $this->setReflectionPropertyValue('cc', [[ 'cc@doe.com', 'CC Doe' ]]);
        $this->setReflectionPropertyValue('bcc', [[ 'bcc@doe.com', 'BCC Doe' ]]);
        $this->setReflectionPropertyValue('Subject', 'subject');
        $this->setReflectionPropertyValue('MIMEHeader', 'Content-Type: text/plain');
        $this->setReflectionPropertyValue('MIMEBody', 'body');
        $this->setReflectionPropertyValue('From', 'from');
        $this->setReflectionPropertyValue('analyticsDetailLevel', AnalyticsDetailLevel::Detailed);

        $this->controller->shouldReceive('getTraceID')
                         ->times(3)
                         ->andReturn('bc5bfcc7-8d8d-4e59-b4be-7453b97410d');

        $this->controller->shouldReceive('getSpanID')
                         ->times(3)
                         ->andReturn('ef14c184-5b4a-4e0b-8026-7c5683e611c7');

        $this->controller->shouldReceive('getParentSpanID')
                         ->times(3)
                         ->andReturn('6cb28307-95b0-491e-a82a-9d679f511e43');

        $this->controller->shouldReceive('getSpanSpecificTags')
                         ->times(3)
                         ->andReturn([]);

        $this->logger->expects($this->exactly(3))
                     ->method('newEvent')
                     ->with('outbound_requests_log')
                     ->willReturn($this->event);

        $this->event->expects($this->exactly(3))
                    ->method('addTags')
                    ->with([
                        'type'   => 'SMTP',
                        'status' => '400',
                        'domain' => 'localhost',
                    ]);

        $options = [
            'SMTPPort'      => 25,
            'SMTPHelo'      => '',
            'SMTPSecure'    => '',
            'SMTPAutoTLS'   => TRUE,
            'SMTPAuth'      => FALSE,
            'SMTPUsername'  => '',
            'SMTPPassword'  => '',
            'SMTPKeepAlive' => FALSE,
            'SMTPAuthType'  => '',
            'SMTPTimeout'   => 300,
        ];

        $this->event->expects($this->exactly(3))
                    ->method('addFields')
                    ->with([
                        'startTimestamp' => 1724932393.008985,
                        'endTimestamp'   => 1724932394.128985,
                        'executionTime'  => 1.12,
                        'url'            => 'localhost',
                        'requestHeaders' => '{"Content-Type":"text\/plain"}',
                        'requestBody'    => 'body',
                        'options'        => json_encode($options),
                    ]);

        $this->event->expects($this->exactly(3))
                    ->method('recordTimestamp');

        $this->event->expects($this->exactly(3))
                    ->method('record');

        $method = $this->getReflectionMethod('failureHook');
        $method->invoke($this->class);

        $this->unmockFunction('microtime');

        uopz_unset_return('microtime');
    }

}

?>
