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
        return 'hrefLang';
    }

    public function __invoke($hreflang = true)
    {
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
            $this->environment->getRouteName(),
            $this->environment->getRouteParams());

        foreach ($this->environment->getSupportedLanguages() as $language) {
            echo '<link rel="alternate" hreflang="'.$language.'" href="'.
                $this->uriBuilder->buildUri($routeData, $language)."\">\n";
        }
    }

    public function getSubscribedEvents()
    {
        return [
            'headLinks' => 'onHeadLinks',
        ];
    }
}
