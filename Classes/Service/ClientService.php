<?php
/*
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Filoucrackeur\Hubic\Service;

/**
 * Client for hubiC (http://api.hubic.com).
 *
 * @package Filoucrackeur\Hubic
 */
class Client
{
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;
    /**
     * @var string
     */
    protected $userAgent;
    /**
     * @var string
     */
    protected $locale;
    /**
     * @var string
     */
    protected $cookiePath;
    /**
     * @var string
     */
    protected $token;
    /**
     * @var PollRepository
     */
    protected $pollRepository;

    /**
     * Sends a HTTP request to Doodle.
     *
     * @param string $method
     * @param string $relativeUrl
     * @param array $data
     * @return string
     */
    protected function doRequest($method, $relativeUrl, array $data)
    {
        $scheme = strlen($relativeUrl) > 4 && substr($relativeUrl, 0, 4) === '/np/' ? 'https' : 'http';
        $url = $scheme . '://doodle.com' . $relativeUrl;
        $cookieFileName = $this->getCookieFileName();
        $dataQuery = http_build_query($data);
        $dataQuery = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '%5B%5D=', $dataQuery);
        $ch = curl_init();
        switch ($method) {
            case 'GET':
                if (!empty($dataQuery)) {
                    $url .= '?' . $dataQuery;
                }
                break;
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $dataQuery);
                break;
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->userAgent);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieFileName);
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieFileName);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}
