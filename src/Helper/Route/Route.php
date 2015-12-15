<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Route;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\Application\Middleware\MiddlewareDispatcher;
use Gobline\Router\UriBuilder;
use Gobline\Router\RouteData;
use Gobline\Environment\Environment;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Route implements ViewHelperInterface
{
    private $environment;
    private $uriBuilder;
    private $routeData;
    private $dispatcher;
    private $activeClass = 'active';

    public function __construct(
        Environment $environment,
        UriBuilder $uriBuilder,
        MiddlewareDispatcher $dispatcher
    ) {
        $this->environment = $environment;
        $this->uriBuilder = $uriBuilder;
        $this->dispatcher = $dispatcher;
    }

    public static function getName()
    {
        return 'route';
    }

    public function __invoke($routeName)
    {
        $this->data = new Data($routeName, $this);

        return $this->data;
    }

    public function uri($language = null, $absolute = false)
    {
        $routeData = new RouteData($this->data->getName(), $this->data->getParams());

        if (!$language) {
            $language = $this->environment->getLanguage();
        }

        $uri = $this->uriBuilder->buildUri($routeData, $language);
        $uri = $this->environment->buildUri($uri, $language, $absolute);

        return $uri.$this->data->getQueryString();
    }

    public function url($language = null)
    {
        return $this->uri($language, true);
    }

    public function redirect($status = 302)
    {
        $response = new RedirectResponse($this->url(), 301);
        (new SapiEmitter())->emit($response);
        exit;
    }

    public function request($suppressErrors = false)
    {
        $uri = $this->uri();

        $originalRequest = $this->environment->getRequest();
        $originalMatchedRouteName = $this->environment->getMatchedRouteName();
        $originalMatchedRouteParams = $this->environment->getMatchedRouteParams();

        $post = $_POST;
        $_POST = [];

        $get = $_GET;
        $_GET = [];

        $request = $originalRequest
                    ->withAttribute('_isSubRequest', true)
                    ->withParsedBody([])
                    ->withQueryParams($this->data->getParams())
                    ->withUri(
                        $originalRequest->getUri()
                            ->withPath($uri)
                            ->withQuery($this->data->getQueryString())
                    );

        $this->environment->setRequest($request);

        $response = $this->dispatcher->dispatch($request, new Response(), $suppressErrors);

        $this->environment->setRequest($originalRequest);
        $this->environment->setMatchedRouteName($originalMatchedRouteName);
        $this->environment->setMatchedRouteParams($originalMatchedRouteParams);

        $_POST = $post;
        $_GET = $get;

        return $response->getBody();
    }

    public function silentRequest()
    {
        return $this->request(true);
    }

    public function active()
    {
        $matchedRouteName = $this->environment->getMatchedRouteName();
        $matchedRouteParams = $this->environment->getMatchedRouteParams();
        $request = $this->environment->getRequest();

        if ($this->data->getName() !== $matchedRouteName) {
            return;
        }

        if ($this->data->getQueryArray() && array_diff_assoc($this->data->getQueryArray(), $request->getQueryParams())) {
            return;
        }

        if (!$this->data->getParams()) {
            return $this->activeClass;
        }

        if (array_diff_assoc($this->data->getParams(), $request->getAttributes())) {
            return;
        }

        return $this->activeClass;
    }

    public function __toString()
    {
        return $this->uri();
    }
}
