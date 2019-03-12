<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Hreflang;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\View\Helper\ViewEventDispatcher;
use Gobline\Router\UriBuilder;
use Gobline\Router\RouteData;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Hreflang extends AbstractViewEventSubscriber implements ViewHelperInterface
{
    private $eventDispatcher;
    private $uriBuilder;
    private $environment;

    public function __construct(
        ViewEventDispatcher $eventDispatcher,
        Environment $environment,
        UriBuilder $uriBuilder
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->uriBuilder = $uriBuilder;
        $this->environment = $environment;
    }

    public static function getName()
    {
        return 'hreflang';
    }

    public function __invoke($hreflang = true)
    {
        if ($this->environment->isSubRequest()) {
            return;
        }

        if ($hreflang) {
            if (count($this->environment->getSupportedLanguages()) < 2) {
                return;
            }
            $this->eventDispatcher->addSubscriber($this);
        } else {
            $this->eventDispatcher->removeSubscriber($this);
        }
    }

    public function onHeadLinks()
    {
        $routeData = new RouteData(
            $this->environment->getMatchedRouteName(),
            $this->environment->getMatchedRouteParams());

        $queryString = '';
        $queryParams = $this->environment->getRequest()->getQueryParams();
        if ($queryParams) {
            $queryString = '?'.http_build_query($queryParams);
        }

        foreach ($this->environment->getSupportedLanguages() as $language) {
            $uri = $this->uriBuilder->buildUri($routeData, $language);
            $uri = $this->environment->buildUri($uri, $language, true);
            $uri .= $queryString;

            echo '<link rel="alternate" hreflang="'.$language.'" href="'.$uri."\">\n";
        }
    }

    public function getSubscribedEvents()
    {
        return [
            'headLinks' => 'onHeadLinks',
        ];
    }
}
