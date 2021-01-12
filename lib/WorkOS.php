<?php

namespace WorkOS;

/**
 * Class WorkOS.
 *
 * This class allows users to get and set configuration for the package.
 */
class WorkOS
{
    /**
     * @var null|string WorkOS API key
     */
    private static $apiKey = null;

    /**
     * @var null|string WorkOS Client ID
     */
    private static $clientId = null;

    /**
     * @var null|string WorkOS Project ID
     */
    private static $projectId = null;

    /**
     * @var string WorkOS base API URL.
     */
    private static $apiBaseUrl = "https://api.workos.com/";

    /**
     * @var string SDK identifier
     */
    private static $identifier = Version::SDK_IDENTIFIER;

    /**
     * @var string SDK version
     */
    private static $version = Version::SDK_VERSION;

    /**
     * @return null|string WorkOS API key
     */
    public static function getApiKey()
    {
        if (isset(self::$apiKey)) {
            return self::$apiKey;
        }

        if (getenv("WORKOS_API_KEY")) {
            self::$apiKey = getenv("WORKOS_API_KEY");
            return self::$apiKey;
        }

        $msg = "\$apiKey is required";
        throw new \WorkOS\Exception\ConfigurationException($msg);
    }

    /**
     * @param null|string $apiKey WorkOS API key
     */
    public static function setApiKey($apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * @return null|string WorkOS Client ID
     */
    public static function getClientId()
    {
        if (isset(self::$clientId)) {
            return self::$clientId;
        }

        if (getenv("WORKOS_CLIENT_ID")) {
            self::$clientId = getenv("WORKOS_CLIENT_ID");
            return self::$clientId;
        }

        $msg = "\$clientId is required";
        throw new \WorkOS\Exception\ConfigurationException($msg);
    }

    /**
     * @param string $clientId WorkOS Client ID
     */
    public static function setClientId($clientId)
    {
        self::$clientId = $clientId;
    }

    /**
     * @deprecated
     * 
     * @return null|string WorkOS Project ID
     */
    public static function getProjectId()
    {
        if (isset(self::$projectId)) {
            $msg = "[DEPRECATED] Project ID is deprecated. Use Client ID instead.";
            \trigger_error($msg, E_USER_WARNING);

            return self::$projectId;
        }

        if (getenv("WORKOS_PROJECT_ID")) {
            $msg = "[DEPRECATED] Project ID is deprecated. Use Client ID instead.";
            \trigger_error($msg, E_USER_WARNING);

            self::$projectId = getenv("WORKOS_PROJECT_ID");

            return self::$projectId;
        }

        $msg = "\$clientId is required";
        throw new \WorkOS\Exception\ConfigurationException($msg);
    }

    /**
     * @param string $projectId WorkOS Project ID
     */
    public static function setProjectId($projectId)
    {
        $msg = "[DEPRECATED] Project ID is deprecated. Use Client ID instead.";
        \trigger_error($msg, E_USER_WARNING);

        self::$projectId = $projectId;
    }

    /**
     * @return string WorkOS base API URL
     */
    public static function getApiBaseURL()
    {
        return self::$apiBaseUrl;
    }

    /**
     * @param string $apiBaseUrl WorkOS base API URL
     */
    public static function setApiBaseUrl($apiBaseUrl)
    {
        self::$apiBaseUrl = $apiBaseUrl;
    }

    /**
     * @param string $identifier SDK identifier
     */
    public static function setIdentifier($identifier)
    {
        self::$identifier = $identifier;
    }

    /**
     * @return string SDK identifier
     */
    public static function getIdentifier()
    {
        return self::$identifier;
    }

    /**
     * @param string $version SDK version
     */
    public static function setVersion($version)
    {
        self::$version = $version;
    }

    /**
     * @return string SDK version
     */
    public static function getVersion()
    {
        return self::$version;
    }
}
