<?php

declare(strict_types=1);

namespace Skosm\Test;

use Skosm\LionDesk\LionDesk;
use Skosm\LionDesk\Request\Request;
use Skosm\LionDesk\Request\Exception;
use PHPUnit\Framework\TestCase;

class LionDeskRequestTest extends TestCase
{

    private function getCurlMock()
    {
        $mock = $this->getMockBuilder('\Curl\Curl')
                     ->setMethods(['post'])
                     ->getMock();
        $mock->expects($this->any())
             ->method('post');

        return $mock;
    }

    public function testLionRequestDoRequestSuccess()
    {
        $response = (object)[
            'error' => 0,
            'errorText' => '',
        ];
        $curlMock = $this->getCurlMock();
        $curlMock->response = $response;
        $lionDeskRequest = new Request();
        $this->assertEquals($response, $lionDeskRequest->exec('', '', '', 0, [], $curlMock));
    }

    public function testLionRequestDoRequestError()
    {
        $curlMock = $this->getCurlMock();
        $curlMock->error = true;
        $curlMock->errorMessage = 'Error message';
        $curlMock->errorCode = '0';
        $lionDeskRequest = new Request();
        $this->expectException(Exception::class);
        $lionDeskRequest->exec('', '', '', 30, [], $curlMock);
    }
}
