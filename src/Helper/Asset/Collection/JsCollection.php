<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Asset\Collection;

use Gobline\View\Helper\Asset\MinifierInterface;
use Gobline\View\Helper\Asset\Js\Script;
use Gobline\Mediator\EventDispatcherInterface;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class JsCollection extends AbstractCollection
{
    public function __construct(
        Collection $collection,
        EventDispatcherInterface $eventDispatcher,
        Environment $environment,
        MinifierInterface $minifier
    ) {
        parent::__construct($collection, $environment, $minifier);
        $eventDispatcher->addSubscriber($this);
    }

    public static function getName()
    {
        return 'jsCollection';
    }

    public function add($path, $isModuleAsset = false)
    {
        $this->collection->add(new Script($path, $this->collection->getLocation()));

        return $this;
    }

    protected function printHtml($path)
    {
        echo '<script src="'.$path."\"></script>\n";
    }

    public function onHeadScripts()
    {
        $this->printCollection();
    }

    public function onBodyScripts()
    {
        $this->printCollection();
    }

    public function getSubscribedEvents()
    {
        switch ($this->collection->getLocation()) {
            case 'head':
                return ['headScripts' => 'onHeadScripts'];
            case 'body';

                return ['bodyScripts' => 'onBodyScripts'];
        }
    }
}
