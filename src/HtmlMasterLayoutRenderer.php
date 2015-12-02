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

use Gobline\View\Helper\ViewHelperContainer;
use Gobline\Mediator\EventDispatcher;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class HtmlMasterLayoutRenderer implements ViewRendererInterface
{
    private $htmlRenderer;
    private $eventDispatcher;

    public function __construct(ViewRendererInterface $htmlRenderer, ViewHelperContainer $helperContainer = null)
    {
        $this->htmlRenderer = $htmlRenderer;

        if ($helperContainer) {
            $this->eventDispatcher = $helperContainer->getEventDispatcher();
        } else {
            $this->eventDispatcher = new EventDispatcher();
        }
    }

    public function render($template, $model)
    {
        $content = $this->htmlRenderer->render($template, $model);

        ob_start();
        try {
            echo "<!DOCTYPE html>\n";
            echo '<html';
            $this->eventDispatcher->dispatch('htmlAttributes');
            echo ">\n";

            echo "<head>\n";
            $this->eventDispatcher->dispatch('headOpened');
            echo "<meta charset=\"utf-8\">\n";
            echo "<meta http-equiv=\"X-UA-Compatible\" content=\"IE=Edge\">\n";

            $this->eventDispatcher->dispatch('meta');

            echo '<title>';
            $this->eventDispatcher->dispatch('headTitle');
            echo "</title>\n";

            // print <link> elements (other than stylesheets)
            $this->eventDispatcher->dispatch('headLinks');

            // print stylesheets
            $this->eventDispatcher->dispatch('headStylesheets');

            // print scripts
            $this->eventDispatcher->dispatch('headScripts');

            // print close head, open body
            echo "</head>\n<body";
            $this->eventDispatcher->dispatch('bodyAttributes');
            echo ">\n";
            $this->eventDispatcher->dispatch('bodyOpened');

            // print body content
            echo $content;

            // print scripts
            $this->eventDispatcher->dispatch('bodyScripts');

            // print close body, close html
            echo "\n</body>\n</html>";
        } finally {
            $content = ob_get_clean();
        }

        return $content;
    }

    public function isRenderable($template)
    {
        return $this->htmlRenderer->isRenderable($template);
    }
}
