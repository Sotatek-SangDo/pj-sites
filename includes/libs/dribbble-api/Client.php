<?php
/**
 * PHP wrapper for the Dribbble API.
 *
 * @author   Ali Aghdam <ali@betterstudio.com>
 * @license  MIT License
 * @version  1.0
 */


/**
 * The core Dribbble API PHP wrapper class.
 */
class Better_Dribbble_Client{


    /**
     * Default options for cURL.
     *
     * @var array
     */
    public static $CURL_OPTS = array(
        CURLOPT_CONNECTTIMEOUT => 10,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 600,
        CURLOPT_USERAGENT      => 'better-dribbble-api-wrapper'
    );


    /**
     * The API endpoint.
     *
     * @var string
     */
    protected $endpoint = 'https://api.dribbble.com/v1';


    /**
     * The application access token.
     *
     * @var string
     */
    protected $access_token = '';


    /**
     * Client initializer.
     *
     * @param string $access_token
     */
    public function __construct( $access_token = '' ) {
        $this->setAccessToken( $access_token );
    }


    /**
     * Returns current access token
     *
     * @return string
     */
    public function getAccessToken() {
        return $this->access_token;
    }


    /**
     * Set or change access token.
     *
     * @param string $access_token
     */
    public function setAccessToken( $access_token ) {
        $this->access_token = $access_token;
    }


    /**
     * Returns details for a shot specified by ID.
     *
     * @param  integer $id
     * @return object
     */
    public function getShot( $id ){

        return $this->makeRequest( sprintf( '/shots/%d', $id ), 'GET' );

    }


    /**
     * Returns the set of rebounds (shots in response to a shot) for the shot specified by ID.
     *
     * @param  integer $id
     * @return object
     */
    public function getShotRebounds( $id ){

        return $this->makeRequest( sprintf( '/shots/%d/rebounds', $id ), 'GET' );

    }


    /**
     * Returns the set of comments for the shot specified by ID.
     *
     * @param  integer $id
     * @param  integer $page
     * @param  integer $per_page
     * @return object
     */
    public function getShotComments( $id, $page = 1, $per_page = 15 ){

        $options = array(
            'page' => intval($page),
            'per_page' => intval($per_page)
        );

        return $this->makeRequest( sprintf( '/shots/%d/comments', $id ), 'GET', $options );

    }


    /**
     * Returns the specified list of shots.
     *
     * @param  string  $list
     * @param  integer $page
     * @param  integer $per_page
     * @return object
     */
    public function getShotsList( $list = 'everyone', $page = 1, $per_page = 15 ){

        $options = array(
            'page' => intval($page),
            'per_page' => intval($per_page)
        );

        return $this->makeRequest(sprintf('/shots/%s', $list), 'GET', $options);

    }


    /**
     * Returns the most recent shots for specified user.
     *
     * http://developer.dribbble.com/v1/users/
     *
     * @param   mixed       $id
     * @return  object
     */
    public function getUser( $id ){

        return $this->makeRequest( '/users/' . $id );

    }


    /**
     * Returns the most recent shots for specified user.
     *
     * @param   mixed       $id
     * @param   integer     $page
     * @param   integer     $per_page
     * @return  object
     */
    public function getUserShots( $id, $page = 1, $per_page = 15 ){

        $options = array(
            'page'      => intval( $page ),
            'per_page'  => intval( $per_page )
        );

        return $this->makeRequest( sprintf( '/users/%s/shots', $id ), 'GET', $options );

    }


    /**
     * Returns the most recent shots published by those the player specified is following.
     *
     * @param  mixed   $id
     * @param  integer $page
     * @param  integer $per_page
     * @return object
     */
    public function getPlayerFollowingShots( $id, $page = 1, $per_page = 15 ){

        $options = array(
            'page' => intval($page),
            'per_page' => intval($per_page)
        );

        return $this->makeRequest( sprintf( '/players/%s/shots/following', $id ), 'GET', $options );

    }


    /**
     * Returns shots liked by the player specified.
     *
     * @param  mixed   $id
     * @param  integer $page
     * @param  integer $per_page
     * @return object
     */
    public function getPlayerLikes( $id, $page = 1, $per_page = 15 ){

        $options = array(
            'page' => intval($page),
            'per_page' => intval($per_page)
        );

        return $this->makeRequest( sprintf( '/players/%s/shots/likes', $id ), 'GET', $options );

    }


    /**
     * Returns profile details for a player specified.
     *
     * @param  mixed   $id
     * @return object
     */
    public function getPlayer( $id ){

        return $this->makeRequest(sprintf('/players/%s', $id), 'GET');

    }


    /**
     * Returns the list of followers for a player specified.
     *
     * @param  mixed  $id
     * @return object
     */
    public function getPlayerFollowers( $id ){

        return $this->makeRequest( sprintf( '/players/%s/followers', $id ), 'GET' );

    }

    /**
     * Returns the list of players followed by the player specified.
     *
     * @param  mixed  $id
     * @return object
     */
    public function getPlayerFollowing( $id ){

        return $this->makeRequest( sprintf( '/players/%s/following', $id ), 'GET' );

    }


    /**
     * Returns the list of players drafted by the player specified.
     *
     * @param  mixed  $id
     * @return object
     */
    public function getPlayerDraftees( $id, $page = 1, $per_page = 15 ){

        $options = array(
            'page' => intval($page),
            'per_page' => intval($per_page)
        );

        return $this->makeRequest(sprintf('/players/%s/draftees', $id), 'GET', $options);

    }


    /**
     * Makes a HTTP request.
     * This method can be overriden by extending classes if required.
     *
     * @param  string $url
     * @param  string $method
     * @param  array  $params
     * @return object
     * @throws Exception
     */
    protected function makeRequest( $url, $method = 'GET', $params = array() ){
        $ch = curl_init();

        $options = self::$CURL_OPTS;
        $options[CURLOPT_URL] = $this->endpoint . $url;

        $token = $this->getAccessToken();
        if( empty( $params['access_token'] ) && ! empty( $token ) ){
            $params['access_token'] = $token;
        }

        if( ! empty( $params ) ) {
            $options[CURLOPT_URL] .= '?' . http_build_query( $params, null, '&' );
        }

        curl_setopt_array( $ch, $options );

        $result = curl_exec( $ch );
        $status = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

        if( $result === false ){
            throw new Exception( curl_error( $ch ), curl_errno( $ch ) );
        }

        curl_close( $ch );

        $result = json_decode( $result );

        if( isset( $result->message ) ){
            throw new Exception( $result->message, $status );
        }

        return $result;
    }
}
