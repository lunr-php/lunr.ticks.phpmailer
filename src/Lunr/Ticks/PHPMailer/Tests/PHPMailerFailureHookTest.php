<?php

/**
 * This file contains the PHPMailerFailureHookTest class.
 *
 * SPDX-FileCopyrightText: Copyright 2025 Framna Netherland B.V., Zwolle, The Netherlands
 * SPDX-License-Identifier: MIT
 */

namespace Lunr\Ticks\PHPMailer\Tests;

use Lunr\Ticks\PHPMailer\PHPMailer;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

/**
 * This class contains tests for the PHPMailer class.
 *
 * @covers Lunr\Ticks\PHPMailer\PHPMailer
 */
class PHPMailerFailureHookTest extends PHPMailerTestCase
{

    use MockeryPHPUnitIntegration;

    /**
     * Test that the failureHook() when Mailer is not set to SMTP
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::failureHook
     */
    public function testFailureHookWithNonSMTPMail(): void
    {
        $class = Mockery::mock(PHPMailer::class . '[afterSending]')
                          ->shouldAllowMockingProtectedMethods();

        $this->baseSetUp($class);

        $this->setReflectionPropertyValue('Mailer', 'mail');
        $this->setReflectionPropertyValue('to', [ 'john@doe.com', 'John Doe' ]);
        $this->setReflectionPropertyValue('cc', [ 'cc@doe.com', 'CC Doe' ]);
        $this->setReflectionPropertyValue('bcc', [ 'bcc@doe.com', 'BCC Doe' ]);
        $this->setReflectionPropertyValue('Subject', 'subject');
        $this->setReflectionPropertyValue('MIMEBody', 'body');
        $this->setReflectionPropertyValue('From', 'from');

        $class->expects('afterSending')
              ->once()
              ->with(FALSE, [ 'john@doe.com', 'John Doe' ], [ 'cc@doe.com', 'CC Doe' ], [ 'bcc@doe.com', 'BCC Doe' ], 'subject', 'body', 'from', []);

        $method = $this->getReflectionMethod('failureHook');
        $method->invoke($class);
    }

    /**
     * Test that the failureHook() when Mailer is set to SMTP
     *
     * @covers Lunr\Ticks\PHPMailer\PHPMailer::failureHook
     */
    public function testFailureHookWithSMTPMail(): void
    {
        $class = Mockery::mock(PHPMailer::class . '[afterSending]')
                          ->shouldAllowMockingProtectedMethods();

        $this->baseSetUp($class);

        $this->setReflectionPropertyValue('Mailer', 'smtp');
        $this->setReflectionPropertyValue('to', [[ 'john@doe.com', 'John Doe' ]]);
        $this->setReflectionPropertyValue('cc', [[ 'cc@doe.com', 'CC Doe' ]]);
        $this->setReflectionPropertyValue('bcc', [[ 'bcc@doe.com', 'BCC Doe' ]]);
        $this->setReflectionPropertyValue('Subject', 'subject');
        $this->setReflectionPropertyValue('MIMEBody', 'body');
        $this->setReflectionPropertyValue('From', 'from');

        $class->expects('afterSending')
              ->once()
              ->with(FALSE, [ 'john@doe.com', 'John Doe' ], [], [], 'subject', 'body', 'from', []);

        $class->expects('afterSending')
              ->once()
              ->with(FALSE, [ 'cc@doe.com', 'CC Doe' ], [], [], 'subject', 'body', 'from', []);

        $class->expects('afterSending')
              ->once()
              ->with(FALSE, [ 'bcc@doe.com', 'BCC Doe' ], [], [], 'subject', 'body', 'from', []);

        $method = $this->getReflectionMethod('failureHook');
        $method->invoke($class);
    }

}

?>
