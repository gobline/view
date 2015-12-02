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

use Gobline\View\Helper\Asset\AssetVersions;
use Gobline\View\Helper\Asset\MinifierInterface;
use Gobline\View\Helper\Asset\ModuleAssetCopier;
use Gobline\View\Helper\Asset\Css\Style;
use Gobline\Mediator\EventDispatcherInterface;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class CssCollection extends AbstractCollection
{
    public function __construct(
        Collection $collection,
        EventDispatcherInterface $eventDispatcher,
        AssetVersions $assetVersions,
        MinifierInterface $minifier,
        ModuleAssetCopier $moduleAssetCopier,
        $baseUrl
    ) {
        parent::__construct($collection, $assetVersions, $minifier, $moduleAssetCopier, $baseUrl);
        $eventDispatcher->addSubscriber($this);
    }

    public static function getName()
    {
        return 'cssCollection';
    }

    public function add($path, $isModuleAsset = false)
    {
        $this->collection->add(
            new Style(
                $path,
                $isModuleAsset));

        return $this;
    }

    protected function printHtml($path)
    {
        echo '<link rel="stylesheet" href="'.$path."\">\n";
    }

    public function onHeadStylesheets()
    {
        $this->printCollection();
    }

    public function getSubscribedEvents()
    {
        return [
            'headStylesheets' => 'onHeadStylesheets',
        ];
    }
}
