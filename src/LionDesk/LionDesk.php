<?php

declare(strict_types = 1);

namespace Skosm\LionDesk;

use Skosm\LionDesk\Request;

/**
 * Class LionDesk
 * @package LionDesk
 */
class LionDesk implements LionDeskInterface
{
    /**
     * @var string API endpoint URL.
     */
    protected $endPoint = '';

    /**
     * @var string API Key.
     */
    protected $apiKey = '';

    /**
     * @var string User Key.
     */
    protected $userKey = '';

    /**
     * @var int Timeout for API requests.
     */
    protected $timeOut = 30;

    /**
     * @var Request\Request
     */
    protected $request;

    /**
     * @var \stdClass Response JSON object.
     */
    protected $response;

    /**
     * @var array Data for request.
     */
    protected $data = [];

    /**
     * LionDesk constructor.
     *
     * @param string $apiKey
     * @param string $endPoint
     * @param string $userKey
     */
    public function __construct(
        string $apiKey = '',
        string $userKey = '',
        string $endPoint = 'https://api.liondesk.com'
    ) {
        $this
            ->setApiKey($apiKey)
            ->setUserKey($userKey)
            ->setEndPoint($endPoint)
            ->setRequest(new Request\Request());
    }

    /**
     * @return string
     */
    public function getEndPoint(): string
    {
        return $this->endPoint;
    }

    /**
     * @param string $endPoint
     *
     * @return LionDesk
     */
    public function setEndPoint(string $endPoint) : LionDesk
    {
        $this->endPoint = $endPoint;

        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     *
     * @return LionDesk
     */
    public function setApiKey(string $apiKey) : LionDesk
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserKey(): string
    {
        return $this->userKey;
    }

    /**
     * @param string $userKey
     *
     * @return LionDesk
     */
    public function setUserKey(string $userKey) : LionDesk
    {
        $this->userKey = $userKey;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return LionDesk
     */
    public function setData(array $data) : LionDesk
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return \stdClass
     */
    public function getResponse(): \stdClass
    {
        return $this->response;
    }

    /**
     * @param \stdClass $response
     *
     * @return LionDesk
     */
    public function setResponse(\stdClass $response) : LionDesk
    {
        $this->response = $response;

        return $this;
    }

    /**
     * @return Request\Request
     */
    public function getRequest() : Request\Request
    {
        return $this->request;
    }

    /**
     * @param Request\Request $request
     *
     * @return LionDesk
     */
    public function setRequest(Request\Request $request) : LionDesk
    {
        $this->request = $request;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeOut(): int
    {
        return $this->timeOut;
    }

    /**
     * @param int $timeOut
     *
     * @return LionDesk
     */
    public function setTimeOut(int $timeOut) : LionDesk
    {
        $this->timeOut = $timeOut;

        return $this;
    }

    /**
     * Set action and empty data array.
     *
     * @param string $action
     *
     * @return LionDesk
     */
    protected function action(string $action) : LionDesk
    {
        $this->setData([
            'action' => $action
        ]);

        return $this;
    }

    /**
     * Add data for request.
     *
     * @param array $data
     *
     * @return LionDesk
     */
    protected function payload(array $data = []) : LionDesk
    {
        $this->setData(array_merge(
            $this->getData(),
            $data
        ));

        return $this;
    }

    /**
     * Run API request using Request.
     *
     * @return LionDesk
     * @throws Exception
     * @throws Request\Exception
     */
    protected function run() : LionDesk
    {
        $this->setResponse((object)[]);
        try {
            $response = $this->getRequest()->exec(
                $this->getEndPoint(),
                $this->getApiKey(),
                $this->getUserKey(),
                $this->getTimeOut(),
                $this->getData()
            );
        } catch (Request\Exception $e) {
            throw $e;
        }
        $this->setResponse($response);
        if ($response->errorText) {
            throw new Exception(
                $response->errorText,
                $response->error
            );
        }

        return $this;
    }

    /**
     * Retrieve response value by key.
     *
     * @param string $key
     *
     * @return mixed
     * @throws Exception
     */
    protected function getValue(string $key)
    {
        if (isset($this->getResponse()->$key)) {
            return $this->getResponse()->$key;
        } else {
            throw new Exception(
                sprintf('Variable "%s" not found in API response', $key)
            );
        }
    }

    /**
     * Used to test communication with the API.
     *
     * @param string $msg
     * @see https://api.liondesk.com/docs.html
     *
     * @return string Message you sent
     * @throws Exception
     * @throws Request\Exception
     */
    public function echo(string $msg = '') : string
    {
        return (string)$this
            ->action('Echo')
            ->payload(['msg' => $msg])
            ->run()
            ->getValue('msg');
    }

    /**
     * Returns an array of users who have authorized the partner for API submissions.
     *
     * @see https://api.liondesk.com/docs.html
     *
     * @return array
     */
    public function getUsers() : array
    {
        return (array)$this
            ->action('GetUsers')
            ->run()
            ->getValue('users');
    }

    /**
     * Add new submission (client).
     *
     * @param array $data
     * @see https://api.liondesk.com/docs.html
     *
     * @return int ID of the new submission (client).
     */
    public function newSubmission(array $data) : int
    {
        return (int)$this
            ->action('NewSubmission')
            ->payload($data)
            ->run()
            ->getValue('id');
    }

    /**
     * Add new comment.
     *
     * @param array $data
     * @see https://api.liondesk.com/docs.html
     *
     * @return int ID of the new comment.
     */
    public function newComment(array $data) : int
    {
        return (int)$this
            ->action('NewComment')
            ->payload($data)
            ->run()
            ->getValue('id');
    }

    /**
     * Add new activity.
     *
     * @param array $data
     * @see https://api.liondesk.com/docs.html
     *
     * @return int ID of the new activity.
     */
    public function newActivity(array $data) : int
    {
        return (int)$this
            ->action('NewActivity')
            ->payload($data)
            ->run()
            ->getValue('id');
    }
}
