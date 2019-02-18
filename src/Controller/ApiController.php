<?php
/**
 * Created by PhpStorm.
 * User: Daldas
 * Date: 14/02/2019
 * Time: 20:01
 */


namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Unirest;

class ApiController
{

    /**
     * @var integer HTTP status code - 200 (OK) by default
     */
    protected $statusCode = 200;

    /**
     * @var array
     */
    protected $datas = [];

    /**
     * Gets the value of statusCode.
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Sets the value of statusCode.
     *
     * @param integer $statusCode the status code
     *
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     *
     *   Disables SSL cert validation temporary
     */
    protected function SslValidation()
    {
        return Unirest\Request::verifyPeer(false);
    }

    /**
     * Gets the datas from api
     *
     * @return array
     */
    public function getApiData($url, $query = [])
    {
        $this->SslValidation();

        $headers = array('Accept' => 'application/json');

        $response = Unirest\Request::get($url,$headers,$query);

        $responses = get_object_vars($response->body);

        $this->datas = json_decode(json_encode($responses), True);

        return $this->datas;

    }

    /**
     * Returns a JSON response
     *
     * @param array $data
     * @param array $headers
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respond($data, $headers = [])
    {
        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Sets an error message and returns a JSON response
     *
     * @param string $errors
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondWithErrors($errors, $headers = [])
    {
        $data = [
            'errors' => $errors,
        ];

        return new JsonResponse($data, $this->getStatusCode(), $headers);
    }

    /**
     * Returns a 401 Unauthorized http response
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondUnauthorized($message = 'Not authorized!')
    {
        return $this->setStatusCode(401)->respondWithErrors($message);
    }


    /**
     * Returns a 422 Unprocessable Entity
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondValidationError($message = 'Validation errors')
    {
        return $this->setStatusCode(422)->respondWithErrors($message);
    }

    /**
     * Returns a 404 Not Found
     *
     * @param string $message
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondNotFound($message = 'Not found!')
    {
        return $this->setStatusCode(404)->respondWithErrors($message);
    }


    /**
     * Returns a 201 Created
     *
     * @param array $data
     *
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function respondCreated($data = [])
    {
        return $this->setStatusCode(201)->respond($data);
    }

}
