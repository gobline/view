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
use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
abstract class AbstractCollection extends AbstractViewEventSubscriber
{
    protected $collection;
    private $minify = false;
    private $noCache = false;
    private $minifier;
    private $basePath;

    public function __construct(
        Collection $collection,
        Environment $environment,
        MinifierInterface $minifier
    ) {
        $this->collection = $collection;
        $this->minifier = $minifier;
        $this->basePath = $environment->getBasePath();
    }

    public function minify($path)
    {
        $this->minify = $path;

        return $this;
    }

    public function noCache()
    {
        $this->noCache = true;

        return $this;
    }

    private function merge(Collection $collection)
    {
        $merged = '';
        foreach ($collection as $asset) {
            if ($asset->isExternal()) {
                $assetPath = (strpos($asset->getPath(), '//') === 0) ? 'http:'.$asset->getPath() : $asset->getPath();
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_URL, $assetPath);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_HEADER, false);
                $content = curl_exec($curl);
                curl_close($curl);
                if ($content !== false) {
                    $merged .= $content;
                }
            } else {
                $merged .= file_get_contents(getcwd().'/public/'.$asset->getPath());
            }
        }

        return $merged;
    }

    private function save($path, $content)
    {
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }
        file_put_contents($path, $content);
    }

    abstract protected function printHtml($path);

    protected function printCollection()
    {
        $path = $this->minify ?: $this->collection->getPath();
        $absolutePath = getcwd().'/public/'.$path;
        if (!is_file($absolutePath)) {
            $content = $this->merge($this->collection);
            if ($this->minify) {
                $content = $this->minifier->minify($content);
            }
            $this->save($absolutePath, $content);
        }

        if ($this->noCache) {
            $path .= '?v='.strtotime('now');
        }

        $this->printHtml($this->basePath.'/'.$path);
    }
}
