<?php

namespace WorkOS;

trait TestHelper
{
    protected $defaultRequestClient;
    protected $requestClientMock;

    protected function setUp()
    {
        $this->defaultRequestClient = Client::requestClient();
        $this->requestClientMock = $this->createMock("\WorkOS\RequestClient\RequestClientInterface");
    }

    protected function tearDown()
    {
        WorkOS::setApiKey(null);
        WorkOS::setClientId(null);
        @WorkOS::setProjectId(null);

        Client::setRequestClient($this->defaultRequestClient);
    }

    // Configuration

    protected function withApiKey($apiKey = "pk_secretsauce")
    {
        WorkOS::setApiKey($apiKey);
    }

    protected function withApiKeyAndClientId($apiKey = "pk_secretsauce", $projectId = "client_pizza")
    {
        WorkOS::setApiKey($apiKey);
        WorkOS::setClientId($projectId);
    }

    protected function withApiKeyAndProjectId($apiKey = "pk_secretsauce", $projectId = "project_pizza")
    {
        WorkOS::setApiKey($apiKey);
        @WorkOS::setProjectId($projectId);
    }

    // Requests

    protected function mockRequest(
        $method,
        $path,
        $headers = null,
        $params = null,
        $withAuth = false,
        $result = null,
        $responseHeaders = null,
        $responseCode = 200
    ) {
        Client::setRequestClient($this->requestClientMock);

        $url = Client::generateUrl($path);
        if (!$headers) {
            $requestHeaders = Client::generateBaseHeaders($withAuth);
        } else {
            $requestHeaders = \array_merge(Client::generateBaseHeaders(), $headers);
        }

        if (!$result) {
            $result = "{}";
        }
        if (!$responseHeaders) {
            $responseHeaders = [];
        }

        $this->prepareRequestMock($method, $url, $requestHeaders, $params)
            ->willReturn([$result, $responseHeaders, $responseCode]);
    }

    private function prepareRequestMock($method, $url, $headers, $params)
    {
        return $this->requestClientMock
            ->expects(static::once())->method('request')
            ->with(
                static::identicalTo($method),
                static::identicalTo($url),
                static::identicalTo($headers),
                static::identicalTo($params)
            );
    }
}
