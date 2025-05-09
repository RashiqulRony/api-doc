<?php

namespace RashiqulRony\ApiDoc\Tools;

use Illuminate\Routing\Route;
use RashiqulRony\ApiDoc\Tools\ResponseStrategies\ResponseCallStrategy;
use RashiqulRony\ApiDoc\Tools\ResponseStrategies\ResponseFileStrategy;
use RashiqulRony\ApiDoc\Tools\ResponseStrategies\ResponseTagStrategy;
use RashiqulRony\ApiDoc\Tools\ResponseStrategies\TransformerTagsStrategy;
use SymRashiqulRonyfony\Component\HttpFoundation\Response;

class ResponseResolver
{
    /**
     * @var array
     */
    public static $strategies = [
        ResponseTagStrategy::class,
        TransformerTagsStrategy::class,
        ResponseFileStrategy::class,
        ResponseCallStrategy::class,
    ];

    /**
     * @var Route
     */
    private $route;

    /**
     * @param Route $route
     */
    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    /**
     * @param array $tags
     * @param array $routeProps
     *
     * @return array|null
     */
    private function resolve(array $tags, array $routeProps)
    {
        foreach (static::$strategies as $strategy) {
            $strategy = new $strategy();

            /** @var Response[]|null $response */
            $responses = $strategy($this->route, $tags, $routeProps);

            if (!is_null($responses)) {
                return array_map(function (Response $response) {
                    return ['status' => $response->getStatusCode(), 'content' => $this->getResponseContent($response)];
                }, $responses);
            }
        }
    }

    /**
     * @param $route
     * @param $tags
     * @param $routeProps
     *
     * @return array
     */
    public static function getResponse($route, $tags, $routeProps)
    {
        return (new static($route))->resolve($tags, $routeProps);
    }

    /**
     * @param $response
     *
     * @return mixed
     */
    private function getResponseContent($response)
    {
        return $response ? $response->getContent() : '';
    }
}
