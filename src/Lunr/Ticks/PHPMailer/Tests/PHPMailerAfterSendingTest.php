<?php

/**
 * This file contains the PHPMailerAfterSendingTest class.
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
class PHPMailerAfterSendingTest extends PHPMailerTestCase
{

    /**
     * Test that the afterSending works correctly.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::afterSending
     */
    public function testAfterSendingWorksCorrectlyWithInfoLevel(): void
    {
        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->setReflectionPropertyValue('startTimestamp', 1724932393.008985);
        $this->setReflectionPropertyValue('Mailer', 'smtp');
        $this->setReflectionPropertyValue('MIMEHeader', 'full mime header');
        $this->setReflectionPropertyValue('MIMEBody', 'full mime body');
        $this->setReflectionPropertyValue('analyticsDetailLevel', AnalyticsDetailLevel::Info);
        $this->setReflectionPropertyValue('eventLogger', $this->logger);
        $this->setReflectionPropertyValue('tracingController', $this->controller);

        $this->controller->expects($this->once())
                         ->method('getTraceID')
                         ->willReturn('bc5bfcc7-8d8d-4e59-b4be-7453b97410d');

        $this->controller->expects($this->once())
                         ->method('getSpanID')
                         ->willReturn('ef14c184-5b4a-4e0b-8026-7c5683e611c7');

        $this->controller->expects($this->once())
                         ->method('getParentSpanID')
                         ->willReturn('6cb28307-95b0-491e-a82a-9d679f511e43');

        $this->logger->expects($this->once())
                     ->method('newEvent')
                     ->with('outbound_requests_log')
                     ->willReturn($this->event);

        $this->event->expects($this->once())
                    ->method('addTags')
                    ->with([
                        'type'   => 'smtp',
                        'status' => '200',
                    ]);

        $this->event->expects($this->once())
                    ->method('addFields')
                    ->with([
                        'startTimestamp' => 1724932393.008985,
                        'endTimestamp'   => 1724932394.128985,
                        'executionTime'  => 1.12,
                        'url'            => 'localhost',
                        'ip'             => '127.0.0.1',
                        'traceID'        => 'bc5bfcc7-8d8d-4e59-b4be-7453b97410d',
                        'spanID'         => 'ef14c184-5b4a-4e0b-8026-7c5683e611c7',
                        'parentSpanID'   => '6cb28307-95b0-491e-a82a-9d679f511e43',
                    ]);

        $this->event->expects($this->once())
                    ->method('recordTimestamp');

        $this->event->expects($this->once())
                    ->method('record');

        $extra = [ 'smtp_transaction_id' => FALSE ];

        $method = $this->getReflectionMethod('afterSending');
        $method->invoke($this->class, TRUE, [ 'example@mail.com', 'John Doe' ], [], [], 'subject', 'body', 'from@mail.com', $extra);

        $this->unmockFunction('microtime');

        uopz_unset_return('microtime');
    }

    /**
     * Test that the afterSending works correctly with empty extra.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::afterSending
     */
    public function testAfterSendingWorksCorrectlyWithDetailedLevel(): void
    {
        $string  = 'b7rrrEKWPBBniam2zDQjn2QaYE5dAPLgfyTy2RbTPVykQDrYeq3HKjTKPLeSgaf8dTJNiatfrbGKMUBU4VYY8PphqxBZSe6mKuz2R7FVdcc9VZmAEkNDg7mfT7EPcvg';
        $string .= 'LgTKUihAfxc76CihMFqVpnU7e3iqWJdBPLnP34JQ2zQVBmSv8kvHjAGrv5fCVnPCEvbQx5PUNBukQVNFZukLtEtb2ZYy54JqjbHi4CF9kWV9MHq2Ah5A9vjYLxTBziT';
        $string .= 'MYcTCtXxcFCVYQ6awvkN9TdupdD7ihecSHB79JbqPSAVbRbz4ZFtnbe2aPzVRmVvkLDuFefmutDfGgKCizYMGJnExv6ViCryU4JZAufWxeag22BrDJ34aBRwbnCqwEa';
        $string .= 't2K6p45zvvCVpen5Z6VkQCiLGV5kGzfhb6cgUvnvyKK5tzjE7xx95PLupW8uPaCYyrpgT9RS8GQNf72qwnA5bebjRe3hi66KXLaJU2d5Tkpe4eRutgucvKFFBk8MxkY';

        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->setReflectionPropertyValue('startTimestamp', 1724932393.008985);
        $this->setReflectionPropertyValue('Mailer', 'smtp');
        $this->setReflectionPropertyValue('MIMEHeader', 'full mime header');
        $this->setReflectionPropertyValue('MIMEBody', $string . 'E984TBDFDAKJF');
        $this->setReflectionPropertyValue('eventLogger', $this->logger);
        $this->setReflectionPropertyValue('tracingController', $this->controller);
        $this->setReflectionPropertyValue('analyticsDetailLevel', AnalyticsDetailLevel::Detailed);
        $this->setReflectionPropertyValue('SMTPOptions', [ 'SMTPExtra' => 'extra_value' ]);

        $this->controller->expects($this->once())
                         ->method('getTraceID')
                         ->willReturn('bc5bfcc7-8d8d-4e59-b4be-7453b97410d');

        $this->controller->expects($this->once())
                         ->method('getSpanID')
                         ->willReturn('ef14c184-5b4a-4e0b-8026-7c5683e611c7');

        $this->controller->expects($this->once())
                         ->method('getParentSpanID')
                         ->willReturn('6cb28307-95b0-491e-a82a-9d679f511e43');

        $this->logger->expects($this->once())
                     ->method('newEvent')
                     ->with('outbound_requests_log')
                     ->willReturn($this->event);

        $this->event->expects($this->once())
                    ->method('addTags')
                    ->with([
                        'type'   => 'smtp',
                        'status' => '200',
                    ]);

        $options = [
            'from'          => 'from@mail.com',
            'to'            => [ 'example@mail.com', 'John Doe' ],
            'cc'            => [],
            'bcc'           => [],
            'SMTPHost'      => 'localhost',
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
            'SMTPExtra'     => 'extra_value'
        ];

        $this->event->expects($this->once())
                    ->method('addFields')
                    ->with([
                        'startTimestamp' => 1724932393.008985,
                        'endTimestamp'   => 1724932394.128985,
                        'executionTime'  => 1.12,
                        'url'            => 'localhost',
                        'ip'             => '127.0.0.1',
                        'traceID'        => 'bc5bfcc7-8d8d-4e59-b4be-7453b97410d',
                        'spanID'         => 'ef14c184-5b4a-4e0b-8026-7c5683e611c7',
                        'parentSpanID'   => '6cb28307-95b0-491e-a82a-9d679f511e43',
                        'requestHeaders' => 'full mime header',
                        'data'           => $string . 'E984...',
                        'options'        => json_encode($options),
                    ]);

        $this->event->expects($this->once())
                    ->method('recordTimestamp');

        $this->event->expects($this->once())
                    ->method('record');

        $method = $this->getReflectionMethod('afterSending');
        $method->invoke($this->class, TRUE, [ 'example@mail.com', 'John Doe' ], [], [], 'subject', 'body', 'from@mail.com', []);

        $this->unmockFunction('microtime');

        uopz_unset_return('microtime');
    }

    /**
     * Test that the afterSending works correctly with empty extra.
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::afterSending
     */
    public function testAfterSendingWorksCorrectlyWithFullLevel(): void
    {
        $string  = 'b7rrrEKWPBBniam2zDQjn2QaYE5dAPLgfyTy2RbTPVykQDrYeq3HKjTKPLeSgaf8dTJNiatfrbGKMUBU4VYY8PphqxBZSe6mKuz2R7FVdcc9VZmAEkNDg7mfT7EPcvg';
        $string .= 'LgTKUihAfxc76CihMFqVpnU7e3iqWJdBPLnP34JQ2zQVBmSv8kvHjAGrv5fCVnPCEvbQx5PUNBukQVNFZukLtEtb2ZYy54JqjbHi4CF9kWV9MHq2Ah5A9vjYLxTBziT';
        $string .= 'MYcTCtXxcFCVYQ6awvkN9TdupdD7ihecSHB79JbqPSAVbRbz4ZFtnbe2aPzVRmVvkLDuFefmutDfGgKCizYMGJnExv6ViCryU4JZAufWxeag22BrDJ34aBRwbnCqwEa';
        $string .= 't2K6p45zvvCVpen5Z6VkQCiLGV5kGzfhb6cgUvnvyKK5tzjE7xx95PLupW8uPaCYyrpgT9RS8GQNf72qwnA5bebjRe3hi66KXLaJU2d5Tkpe4eRutgucvKFFBk8MxkY';

        $this->mockFunction('microtime', fn() => 1724932394.128985);

        $this->setReflectionPropertyValue('startTimestamp', 1724932393.008985);
        $this->setReflectionPropertyValue('Mailer', 'smtp');
        $this->setReflectionPropertyValue('MIMEHeader', 'full mime header');
        $this->setReflectionPropertyValue('MIMEBody', $string . 'E984TBDFDAKJF');
        $this->setReflectionPropertyValue('eventLogger', $this->logger);
        $this->setReflectionPropertyValue('tracingController', $this->controller);
        $this->setReflectionPropertyValue('analyticsDetailLevel', AnalyticsDetailLevel::Full);
        $this->setReflectionPropertyValue('SMTPOptions', [ 'SMTPExtra' => 'extra_value' ]);

        $this->controller->expects($this->once())
                         ->method('getTraceID')
                         ->willReturn('bc5bfcc7-8d8d-4e59-b4be-7453b97410d');

        $this->controller->expects($this->once())
                         ->method('getSpanID')
                         ->willReturn('ef14c184-5b4a-4e0b-8026-7c5683e611c7');

        $this->controller->expects($this->once())
                         ->method('getParentSpanID')
                         ->willReturn('6cb28307-95b0-491e-a82a-9d679f511e43');

        $this->logger->expects($this->once())
                     ->method('newEvent')
                     ->with('outbound_requests_log')
                     ->willReturn($this->event);

        $this->event->expects($this->once())
                    ->method('addTags')
                    ->with([
                        'type'   => 'smtp',
                        'status' => '200',
                    ]);

        $options = [
            'from'          => 'from@mail.com',
            'to'            => [ 'example@mail.com', 'John Doe' ],
            'cc'            => [],
            'bcc'           => [],
            'SMTPHost'      => 'localhost',
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
            'SMTPExtra'     => 'extra_value'
        ];

        $this->event->expects($this->once())
                    ->method('addFields')
                    ->with([
                        'startTimestamp' => 1724932393.008985,
                        'endTimestamp'   => 1724932394.128985,
                        'executionTime'  => 1.12,
                        'url'            => 'localhost',
                        'ip'             => '127.0.0.1',
                        'traceID'        => 'bc5bfcc7-8d8d-4e59-b4be-7453b97410d',
                        'spanID'         => 'ef14c184-5b4a-4e0b-8026-7c5683e611c7',
                        'parentSpanID'   => '6cb28307-95b0-491e-a82a-9d679f511e43',
                        'requestHeaders' => 'full mime header',
                        'data'           => $string . 'E984TBDFDAKJF',
                        'options'        => json_encode($options),
                    ]);

        $this->event->expects($this->once())
                    ->method('recordTimestamp');

        $this->event->expects($this->once())
                    ->method('record');

        $method = $this->getReflectionMethod('afterSending');
        $method->invoke($this->class, TRUE, [ 'example@mail.com', 'John Doe' ], [], [], 'subject', 'body', 'from@mail.com', []);

        $this->unmockFunction('microtime');

        uopz_unset_return('microtime');
    }

}

?>
