<?php

namespace WorkOS;

/**
 * Class Client.
 *
 * Communicate with the WorkOS API.
 */
class Client
{
    const METHOD_GET = "get";
    const METHOD_POST = "post";

    private static $_requestClient;

    public static function requestClient()
    {
        if (!self::$_requestClient) {
            self::$_requestClient = RequestClient\CurlRequestClient::class;
        }

        return self::$_requestClient;
    }

    public static function setRequestClient($requestClient)
    {
        self::$_requestClient = $requestClient;
    }

    /**
     * @param string $method Client method
     * @param string $path Path to the WorkOS resource
     * @param null|array $params Associative array that'll be passed as query parameters or form data
     *
     * @throws \WorkOS\Exception\GenericException if a client level exception is encountered
     * @throws \WorkOS\Exception\ServerException if a 5xx status code is returned
     * @throws \WorkOS\Exception\AuthenticationException if a 401 status code is returned
     * @throws \WorkOS\Exception\AuthorizationException if a 403 status code is returned
     * @throws \WorkOS\Exception\BadRequestException if a 400 status code is returned
     *
     * @return \WorkOS\Resource\Response
     */
    public static function request($method, $path, $params = null)
    {
        $headers = ["User-Agent" => "WorkOS PHP/" . WorkOS::VERSION];
        $url = self::generateUrl($path);
        
        list($result, $headers, $statusCode) = self::requestClient()::request($method, $url, $headers, $params);
        $response = new Resource\Response($result, $headers, $statusCode);

        if ($statusCode >= 400) {
            if ($statusCode >= 500) {
                throw new Exception\ServerException($response);
            } elseif ($statusCode === 401) {
                throw new Exception\AuthenticationException($response);
            } elseif ($statusCode === 403) {
                throw new Exception\AuthorizationException($response);
            }

            throw new Exception\BadRequestException($response);
        }

        return $response;
    }

    /** Generates a URL to the WorkOS API.
     *
     * @param string $path Path to the WorkOS resource
     * @param null|array $params Associative arrray to be passed as query parameters
     *
     * @return string
     */
    public static function generateUrl($path, $params = null)
    {
        $url = WorkOS::getApiBaseUrl() . $path;

        if (is_array($params) && !empty($params)) {
            $queryParams = http_build_query($params);
            $url .= "?" . $queryParams;
        }

        return $url;
    }
}
