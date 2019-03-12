<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Description;

use Gobline\View\Helper\ViewHelperInterface;
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\View\Helper\ViewEventDispatcher;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class Description extends AbstractViewEventSubscriber implements ViewHelperInterface
{
    private $eventDispatcher;
    private $description;
    private $environment;

    public function __construct(
        Environment $environment,
        ViewEventDispatcher $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->environment = $environment;
    }

    public static function getName()
    {
        return 'description';
    }

    public function __invoke($description)
    {
        if ($this->environment->isSubRequest()) {
            return;
        }

        if (!$this->description) {
            $this->eventDispatcher->addSubscriber($this);
        }
        $this->description = (string) $description;
    }

    public function onMeta()
    {
        echo '<meta name="description" content="'.$this->description."\">\n";
    }

    public function getSubscribedEvents()
    {
        return [
            'meta' => 'onMeta',
        ];
    }
}
