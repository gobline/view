<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Title;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\View\Helper\ViewEventDispatcher;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Title extends AbstractViewEventSubscriber implements ViewHelperInterface
{
    private $eventDispatcher;
    private $environment;
    private $title;
    private $suffix = '';

    public function __construct(
        Environment $environment,
        ViewEventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->environment = $environment;
    }

    public static function getName()
    {
        return 'title';
    }

    public function __invoke($title)
    {
        if ($this->environment->isSubRequest()) {
            return;
        }

        if (!$this->title) {
            $this->eventDispatcher->addSubscriber($this);
        }
        $this->title = (string) $title;
    }

    public function suffix($suffix)
    {
        if (!$suffix) {
            $this->suffix = '';

            return;
        }

        $this->suffix = $suffix.$this->suffix;
    }

    public function onHeadTitle()
    {
        echo $this->title.$this->suffix;
    }

    public function getSubscribedEvents()
    {
        return [
            'headTitle' => 'onHeadTitle',
        ];
    }
}
