<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper\Asset;

use Gobline\View\Helper\AbstractViewEventSubscriber;
use Gobline\Environment\Environment;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
abstract class AbstractAssetHelper extends AbstractViewEventSubscriber
{
    protected $asset;
    private $basePath;
    private $minify = false;
    private $localize = false;
    private $noCache = false;
    private $minifier;

    public function __construct(
        Environment $environment,
        MinifierInterface $minifier
    ) {
        $this->minifier = $minifier;
        $this->environment = $environment;
        $this->basePath = $environment->getBasePath();
    }

    public function minify($path)
    {
        $this->minify = $path;

        return $this;
    }

    public function localize($path)
    {
        $this->localize = $path;

        return $this;
    }

    public function noCache()
    {
        $this->noCache = true;

        return $this;
    }

    abstract protected function printReference($path);

    abstract protected function printInternalContent($data);

    protected function printAsset()
    {
        if ($this->asset->getIeConditionalComment()) {
            echo '<!--[if '.$this->asset->getIeConditionalComment()."]>\n";
        }

        if ($this->asset->isExternal()) {
            if ($this->localize) {
                $path = getcwd().'/public/'.$this->localize;
                if (!file_exists($path)) {
                    $assetPath = (strpos($this->asset->getPath(), '//') === 0) ? 'http:'.$this->asset->getPath() : $this->asset->getPath();
                    $curl = curl_init();
                    curl_setopt($curl, CURLOPT_URL, $assetPath);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($curl, CURLOPT_HEADER, false);
                    $content = curl_exec($curl);
                    curl_close($curl);
                    if ($content !== false) {
                        if (!file_exists(dirname($path))) {
                            mkdir(dirname($path), 0777, true);
                        }
                        file_put_contents($path, $content);
                        $this->asset->setPath($this->localize);
                    } else {
                        $this->printReference($this->asset->getPath());
                    }
                } else {
                    $this->asset->setPath($this->localize);
                }
            } else {
                $this->printReference($this->asset->getPath());
            }
        }

        if (!$this->asset->isExternal()) {
            if (
                strpos($this->asset->getPath(), '{') ||
                strpos($this->asset->getPath(), ';')
            ) {
                $this->printInternalContent($this->asset->getPath());
            } else {
                if (!$this->minify) {
                    $path = $this->basePath.'/'.$this->asset->getPath();
                    if ($this->noCache) {
                        $path .= '?v='.strtotime('now');
                    }
                    $this->printReference($path);
                } else {
                    $minifyPath = $this->minify;
                    if (!is_file(getcwd().'/public/'.$minifyPath)) {
                        $content = file_get_contents(getcwd().'/public/'.$this->asset->getPath());
                        $content = $this->minifier->minify($content);
                        $path = getcwd().'/public/'.$minifyPath;
                        if (!file_exists(dirname($path))) {
                            mkdir(dirname($path), 0777, true);
                        }
                        file_put_contents($path, $content);
                    }
                    if ($this->noCache) {
                        $minifyPath .= '?v='.strtotime('now');
                    }
                    $this->printReference($this->basePath.'/'.$minifyPath);
                }
            }
        }

        if ($this->asset->getIeConditionalComment()) {
            echo "<![endif]-->\n";
        }
    }
}
