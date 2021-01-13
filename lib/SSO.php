<?php

namespace WorkOS;

/**
 * Class SSO.
 *
 * This class facilitates the use of WorkOS SSO.
 */
class SSO
{
    /**
     * Generates an OAuth 2.0 authorization URL used to initiate the SSO flow with WorkOS.
     *
     * @param null|string $domain Domain of the user that will be going through SSO
     * @param null|string $redirectUri URI to direct the user to upon successful completion of SSO
     * @param null|array $state Associative array containing state that will be returned from WorkOS as a json encoded string
     * @param null|\WorkOS\Resource\ConnectionType $provider Service provider that handles the identity of the user
     *
     * @return string
     */
    public function getAuthorizationUrl($domain, $redirectUri, $state, $provider)
    {
        $authorizationPath = "sso/authorize";

        if (!isset($domain) && !isset($provider)) {
            $msg = "Either \$domain or \$provider is required";

            throw new Exception\UnexpectedValueException($msg);
        }

        $params = [
            "client_id" => WorkOS::getProjectId(),
            "response_type" => "code"
        ];

        if ($domain) {
            $params["domain"] = $domain;
        }

        if ($redirectUri) {
            $params["redirect_uri"] = $redirectUri;
        }

        if (null !== $state && !empty($state)) {
            $params["state"] = \json_encode($state);
        }

        if ($provider) {
            $params["provider"] = $provider;
        }

        return Client::generateUrl($authorizationPath, $params);
    }

    /**
     * Verify that SSO has been completed successfully and retrieve the identity of the user.
     *
     * @param string $code Code returned by WorkOS on completion of OAuth 2.0 flow
     *
     * @return \WorkOS\Resource\Profile
     */
    public function getProfile($code)
    {
        $profilePath = "sso/token";

        $params = [
            "client_id" => WorkOS::getProjectId(),
            "client_secret" => WorkOS::getApikey(),
            "code" => $code,
            "grant_type" => "authorization_code"
        ];
        $response = Client::request(Client::METHOD_POST, $profilePath, null, $params);

        return Resource\Profile::constructFromResponse($response[Resource\Profile::RESOURCE_TYPE]);
    }

    /**
     * Create a Connection.
     *
     * @param string $source Token returned by WorkOS as a result of the WorkOS.js embed workflow.
     *
     * @throws \WorkOS\Exception\GenericException if an error internal to the SDK is encountered
     * @throws \WorkOS\Exception\ServerException if an error internal to WorkOS is encountered
     * @throws \WorkOS\Exception\NotFoundException if a Draft Connection could not be found
     *
     * @return \WorkOS\Resource\Connection
     */
    public function createConnection($source)
    {
        $connectionPath = "connections";
        $params = [
            "source" => $source
        ];

        $response = Client::request(Client::METHOD_POST, $connectionPath, null, $params, true);

        return Resource\Connection::constructFromResponse($response);
    }
}
