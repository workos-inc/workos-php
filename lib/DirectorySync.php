<?php

namespace WorkOS;

/**
 * Class DirectorySync.
 *
 * This class facilitates the user of WorkOS Directory Sync.
 */
class DirectorySync
{
    const DEFAULT_PAGE_SIZE = 10;
    
    /**
     * List Directories.
     *
     * @param null|string $domain Domain of a Directory
     * @param null|string $search Searchable text for a Directory
     * @param int $limit Maximum number of records to return
     * @param null|string $before Pagination cursor to receive records before a provided ID
     * @param null|string $after Pagination cursor to receive records after a provided ID
     *
     * @return array An array containing the following:
     *      null|string Before pagination cursor
     *      null|string After pagination cursor
     *      array \WorkOS\Resource\Directory instances
     */
    public function listDirectories(
        $domain = null,
        $search = null,
        $limit = self::DEFAULT_PAGE_SIZE,
        $before = null,
        $after = null
    ) {
        $directoriesPath = "directories";
        $params = [
            "limit" => $limit,
            "before" => $before,
            "after" => $after,
            "domain" => $domain,
            "search" => $search
        ];

        $response = Client::request(
            Client::METHOD_GET,
            $directoriesPath,
            null,
            $params,
            true
        );

        $directories = [];
        list($before, $after) = Util\Request::parsePaginationArgs($response);
        foreach ($response["data"] as $response) {
            \array_push($directories, Resource\Directory::constructFromResponse($response));
        }

        return [$before, $after, $directories];
    }

    /**
     * List Directory Groups.
     *
     * @param null|string $directory Directory ID
     * @param null|string $user Directory User ID
     * @param int $limit Maximum number of records to return
     * @param null|string $before Pagination cursor to receive records before a provided ID
     * @param null|string $after Pagination cursor to receive records after a provided ID
     *
     * @return array An array containing the following:
     *      null|string Before pagination cursor
     *      null|string After pagination cursor
     *      array \WorkOS\Resource\DirectoryGroup instances
     */
    public function listGroups(
        $directory = null,
        $user = null,
        $limit = self::DEFAULT_PAGE_SIZE,
        $before = null,
        $after = null
    ) {
        $groupsPath = "directory_groups";

        $params = [
            "limit" => $limit,
            "before" => $before,
            "after" => $after
        ];
        if ($directory) {
            $params["directory"] = $directory;
        }
        if ($user) {
            $params["user"] = $group;
        }

        $response = Client::request(
            Client::METHOD_GET,
            $groupsPath,
            null,
            $params,
            true
        );

        $groups = [];
        list($before, $after) = Util\Request::parsePaginationArgs($response);
        foreach ($response["data"] as $response) {
            \array_push($groups, Resource\DirectoryGroup::constructFromResponse($response));
        }

        return [$before, $after, $groups];
    }

    /**
     * Get a Directory Group.
     *
     * @param string $directoryGroup Directory Group ID
     *
     * @return \WorkOS\Resource\DirectoryGroup
     */
    public function getGroup($directoryGroup)
    {
        $groupPath = "directory_groups/${directoryGroup}";

        $response = Client::request(
            Client::METHOD_GET,
            $groupPath,
            null,
            null,
            true
        );

        return Resource\DirectoryGroup::constructFromResponse($response);
    }

    /**
     * List Directory Users.
     *
     * @param null|string $directory Directory ID
     * @param null|string $group Directory Group ID
     * @param int $limit Maximum number of records to return
     * @param null|string $before Pagination cursor to receive records before a provided ID
     * @param null|string $after Pagination cursor to receive records after a provided ID
     *
     * @return array An array containing the following:
     *      null|string Before pagination cursor
     *      null|string After pagination cursor
     *      array \WorkOS\Resource\DirectoryUser instances
     */
    public function listUsers(
        $directory = null,
        $group = null,
        $limit = self::DEFAULT_PAGE_SIZE,
        $before = null,
        $after = null
    ) {
        $usersPath = "directory_users";

        $params = [
            "limit" => $limit,
            "before" => $before,
            "after" => $after
        ];
        if ($directory) {
            $params["directory"] = $directory;
        }
        if ($group) {
            $params["group"] = $group;
        }

        $response = Client::request(
            Client::METHOD_GET,
            $usersPath,
            null,
            $params,
            true
        );

        $users = [];
        list($before, $after) = Util\Request::parsePaginationArgs($response);
        foreach ($response["data"] as $response) {
            \array_push($users, Resource\DirectoryUser::constructFromResponse($response));
        }

        return [$before, $after, $users];
    }

    /**
     * Get a Directory User.
     *
     * @param string $directoryUser Directory User ID
     *
     * @return \WorkOS\Resource\DirectoryUser
     */
    public function getUser($directoryUser)
    {
        $userPath = "directory_users/${directoryUser}";

        $response = Client::request(
            Client::METHOD_GET,
            $userPath,
            null,
            null,
            true
        );

        return Resource\DirectoryUser::constructFromResponse($response);
    }
}
