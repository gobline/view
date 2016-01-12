<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Lang;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\View\Helper\ViewEventDispatcher;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Lang extends AbstractViewEventSubscriber implements ViewHelperInterface
{
    private $eventDispatcher;
    private $environment;

    public function __construct(ViewEventDispatcher $eventDispatcher, Environment $environment)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->environment = $environment;
    }

    public static function getName()
    {
        return 'lang';
    }

    public function __invoke($lang = true)
    {
        if ($lang) {
            $this->eventDispatcher->addSubscriber($this);
        } else {
            $this->eventDispatcher->removeSubscriber($this);
        }

        return $this->environment->getLanguage();
    }

    public function onHtmlAttributes()
    {
        if (!$this->environment->getLanguage()) {
            return;
        }
        echo ' lang="'.$this->environment->getLanguage().'"';
    }

    public function getSubscribedEvents()
    {
        return [
            'htmlAttributes' => 'onHtmlAttributes',
        ];
    }

    public function __toString()
    {
        return $this->environment->getLanguage();
    }
}
