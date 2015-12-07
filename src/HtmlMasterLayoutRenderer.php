<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View;

use Gobline\View\Helper\ViewHelperRegistry;
use Gobline\Mediator\EventDispatcher;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class HtmlMasterLayoutRenderer implements ViewRendererInterface
{
    private $htmlRenderer;
    private $viewEventDispatcher;

    public function __construct(ViewRendererInterface $htmlRenderer, ViewHelperRegistry $viewHelperRegistry)
    {
        $this->htmlRenderer = $htmlRenderer;

        $this->viewEventDispatcher = $viewHelperRegistry->getViewEventDispatcher();
    }

    public function render($template, $model)
    {
        $content = $this->htmlRenderer->render($template, $model);

        ob_start();

        echo "<!DOCTYPE html>\n";
        echo '<html';
        $this->viewEventDispatcher->dispatch('htmlAttributes');
        echo ">\n";

        echo "<head>\n";
        $this->viewEventDispatcher->dispatch('headOpened');
        echo "<meta charset=\"utf-8\">\n";
        echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=Edge\">\n";

        $this->viewEventDispatcher->dispatch('meta');

        echo '<title>';
        $this->viewEventDispatcher->dispatch('headTitle');
        echo "</title>\n";

        // print <link> elements (other than stylesheets)
        $this->viewEventDispatcher->dispatch('headLinks');

        // print stylesheets
        $this->viewEventDispatcher->dispatch('headStylesheets');

        // print scripts
        $this->viewEventDispatcher->dispatch('headScripts');

        // print close head, open body
        echo "</head>\n<body";
        $this->viewEventDispatcher->dispatch('bodyAttributes');
        echo ">\n";
        $this->viewEventDispatcher->dispatch('bodyOpened');

        // print body content
        echo $content;

        // print scripts
        $this->viewEventDispatcher->dispatch('bodyScripts');

        // print close body, close html
        echo "\n</body>\n</html>";

        $content = ob_get_clean();

        return $content;
    }

    public function isRenderable($template)
    {
        return $this->htmlRenderer->isRenderable($template);
    }
}
