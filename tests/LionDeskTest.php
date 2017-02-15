<?php

declare(strict_types=1);

namespace Skosm\Test;

use Skosm\LionDesk\Exception;
use Skosm\LionDesk\LionDesk;
use Skosm\LionDesk\Request;

use PHPUnit\Framework\TestCase;

class LionDeskTest extends TestCase
{
    private function getRequestMock($response)
    {
        $mock = $this->getMockBuilder('Skosm\LionDesk\Request\Request')
                     ->setMethods(['exec'])
                     ->getMock();
        $mock->expects($this->any())
             ->method('exec')
             ->will($this->returnValue($response));

        return $mock;
    }

    public function testLionDeskConstructor()
    {
        $lionDesk = new LionDesk('123', '456', '789');
        $this->assertEquals('123', $lionDesk->getApiKey());
        $this->assertEquals('456', $lionDesk->getUserKey());
        $this->assertEquals('789', $lionDesk->getEndPoint());
        $this->assertInstanceOf(Request\Request::class, $lionDesk->getRequest());
    }

    public function testLionDeskEndPointSetterAndGetter()
    {
        $lionDesk = new LionDesk();
        $this->assertInstanceOf(LionDesk::class, $lionDesk->setEndPoint('https://api.liondesk.com'));
        $this->assertEquals('https://api.liondesk.com', $lionDesk->getEndPoint());
    }

    public function testLionDeskApiKeySetterAndGetter()
    {
        $lionDesk = new LionDesk();
        $this->assertInstanceOf(LionDesk::class, $lionDesk->setApiKey('123ABC'));
        $this->assertEquals('123ABC', $lionDesk->getApiKey());
    }

    public function testLionDeskUserKeySetterAndGetter()
    {
        $lionDesk = new LionDesk();
        $this->assertInstanceOf(LionDesk::class, $lionDesk->setUserKey('123abc'));
        $this->assertEquals('123abc', $lionDesk->getUserKey());
    }

    public function testLionDeskDataSetterAndGetter()
    {
        $lionDesk = new LionDesk();
        $this->assertInstanceOf(LionDesk::class, $lionDesk->setData(['test']));
        $this->assertEquals(['test'], $lionDesk->getData());
    }

    public function testLionDeskRequestSetterAndGetter()
    {
        $lionDesk = new LionDesk();
        $this->assertInstanceOf(LionDesk::class, $lionDesk->setRequest(new Request\Request()));
        $this->assertInstanceOf(Request\Request::class, $lionDesk->getRequest());
    }

    public function testLionDeskTiemOutSetterAndGetter()
    {
        $lionDesk = new LionDesk();
        $this->assertInstanceOf(LionDesk::class, $lionDesk->setTimeOut(30));
        $this->assertEquals(30, $lionDesk->getTimeOut());
    }

    public function testLionDeskGetValueSuccess()
    {
        $lionDesk = new LionDesk();
        $lionDesk->setResponse((object)[
            'variable' => 'value'
        ]);
        $method = new \ReflectionMethod('Skosm\LionDesk\LionDesk', 'getValue');
        $method->setAccessible(true);

        $this->assertEquals('value', $method->invoke($lionDesk, 'variable'));
    }

    public function testLionDeskGetValueException()
    {
        $lionDesk = new LionDesk();
        $lionDesk->setResponse((object)[]);
        $method = new \ReflectionMethod('Skosm\LionDesk\LionDesk', 'getValue');
        $method->setAccessible(true);

        $this->expectException(Exception::class);
        $method->invoke($lionDesk, 'variable');
    }

    public function testLionDeskRunException()
    {
        $lionDesk = new LionDesk();
        $lionDesk->setRequest($this->getRequestMock((object) [
            'error' => 10,
            'errorText' => 'User Validation Error',
        ]));
        $method = new \ReflectionMethod('Skosm\LionDesk\LionDesk', 'run');
        $method->setAccessible(true);

        $this->expectException(Exception::class);
        $method->invoke($lionDesk);
    }

    public function testLionDeskEcho()
    {
        $lionDesk = new LionDesk('123456');
        $lionDesk->setRequest($this->getRequestMock((object) [
            'error' => 0,
            'errorText' => '',
            'action' => 'Echo',
            'msg' => 'test'
        ]));

        $this->assertEquals('test', $lionDesk->echo('test'));
    }

    public function testLionDeskGetUser()
    {
        $lionDesk = new LionDesk('123456');
        $lionDesk->setRequest($this->getRequestMock((object) [
            'error' => 0,
            'errorText' => '',
            'action' => 'getUsers',
            'users' => [
                (object) [
                    'userKey' => '123',
                    'email' => 'test@email'
                ]
            ]
        ]));

        $this->assertEquals([(object)['userKey' => '123', 'email' => 'test@email']], $lionDesk->getUsers());
    }

    public function testLionDeskNewSubmission()
    {
        $lionDesk = new LionDesk('123456');
        $lionDesk->setRequest($this->getRequestMock((object) [
            'error' => 0,
            'errorText' => '',
            'action' => 'newSubmission',
            'id' => 123
        ]));

        $this->assertEquals(123, $lionDesk->newSubmission([]));
    }


    public function testLionDeskNewActivity()
    {
        $lionDesk = new LionDesk('123456');
        $lionDesk->setRequest($this->getRequestMock((object) [
            'error' => 0,
            'errorText' => '',
            'action' => 'newActivity',
            'id' => 123
        ]));

        $this->assertEquals(123, $lionDesk->newActivity([]));
    }
}
