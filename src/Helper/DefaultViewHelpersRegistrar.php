<?php

/*
 * Gobline Framework
 *
 * (c) Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gobline\View\Helper;

use Gobline\Container\ContainerInterface;
use Gobline\View\Helper\ViewEventDispatcher;
use Gobline\View\Helper\BasePath\BasePath;
use Gobline\View\Helper\Asset\Css\Css;
use Gobline\View\Helper\Asset\Collection\CssCollection;
use Gobline\View\Helper\Asset\Collection\CssCollectionFactory;
use Gobline\View\Helper\Description\Description;
use Gobline\View\Helper\Escape\Escape;
use Gobline\View\Helper\Flash\Flash;
use Gobline\View\Helper\Form\Form;
use Gobline\View\Helper\Hreflang\Hreflang;
use Gobline\View\Helper\Identity\Identity;
use Gobline\View\Helper\Asset\Js\Js;
use Gobline\View\Helper\Asset\Collection\JsCollection;
use Gobline\View\Helper\Asset\Collection\JsCollectionFactory;
use Gobline\View\Helper\Lang\Lang;
use Gobline\View\Helper\Meta\Meta;
use Gobline\View\Helper\NoIndex\NoIndex;
use Gobline\View\Helper\Placeholder\Placeholder;
use Gobline\View\Helper\Responsive\Responsive;
use Gobline\View\Helper\Route\Route;
use Gobline\View\Helper\Title\Title;
use Gobline\View\Helper\Translate\Translate;
use Gobline\View\Helper\Asset\MinifierInterface;
use Gobline\View\Helper\Asset\Minifier;

/**
 * @author Mathieu Decaffmeyer <mdecaffmeyer@gmail.com>
 */
class DefaultViewHelpersRegistrar
{
    private $registry;

    public function __construct(ViewHelperRegistry $registry)
    {
        $this->eventDispatcher = new ViewEventDispatcher();

        $this->container = $container;
        $this->container->share($this->eventDispatcher);

        $this->registry = [
            BasePath::class,
            Css::class,
            CssCollection::class,
            Description::class,
            Escape::class,
            Flash::class,
            Form::class,
            Hreflang::class,
            Identity::class,
            Js::class,
            JsCollection::class,
            Lang::class,
            Meta::class,
            NoIndex::class,
            Placeholder::class,
            Responsive::class,
            Route::class,
            Title::class,
            Translate::class,
        ];

        $this->registerDefaultViewHelpers();
    }

    public function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    public function get($className)
    {
        return $this->container->get($className);
    }

    public function register($className)
    {
        $this->registry[] = $className;
    }

    public function getRegistry()
    {
        return $this->registry;
    }

    public function registerDefaultViewHelpers()
    {
        $this->container->share(BasePath::class);

        $this->container->factory(Css::class);

        $this->container->factory(CssCollectionFactory::class);

        $this->container->share(Description::class);

        $this->container->share(Escape::class);

        $this->container->share(Flash::class);

        $this->container->share(Form::class);

        $this->container->share(Hreflang::class);

        $this->container->share(Identity::class);

        $this->container->factory(Js::class);

        $this->container->factory(JsCollectionFactory::class);

        $this->container->share(Lang::class);

        $this->container->share(Meta::class);

        $this->container->share(NoIndex::class);

        $this->container->share(Placeholder::class);

        $this->container->share(Responsive::class);

        $this->container->share(Route::class);

        $this->container->share(Title::class);

        $this->container->share(Translate::class);

        $this->container->alias(CssCollection::class, CssCollectionFactory::class);
        $this->container->alias(JsCollection::class, JsCollectionFactory::class);
        $this->container->alias(MinifierInterface::class, Minifier::class); 
    }
}
