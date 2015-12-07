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

use Gobline\Container\Container;
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
class ViewHelperRegistry
{
    private $registry = [];
    private $container;
    private $defaultViewHelpersRegistered = false;

    public function __construct(Container $container)
    {
        $this->container = $container;

        $this->eventDispatcher = new ViewEventDispatcher();
        $this->container->share($this->eventDispatcher);
    }

    public function add($className)
    {
        $this->registry[$className::getName()] = new ViewHelperCallable(
            function () use ($className) {
                return $this->container->get($className);
            });

        return $this;
    }

    public function getArrayCopy()
    {
        if (!$this->defaultViewHelpersRegistered) {
            $this->registerDefaultViewHelpers();
            $this->defaultViewHelpersRegistered = true;
        }

        return $this->registry;
    }

    public function getViewEventDispatcher()
    {
        return $this->eventDispatcher;
    }

    public function registerDefaultViewHelpers()
    {
        $this->container->share(BasePath::class);
        $this->add(BasePath::class);

        $this->container->factory(Css::class);
        $this->add(Css::class);

        $this->container->factory(CssCollectionFactory::class);
        $this->container->alias(CssCollection::class, CssCollectionFactory::class);
        $this->add(CssCollection::class);

        $this->container->share(Description::class);
        $this->add(Description::class);

        $this->container->share(Escape::class);
        $this->add(Escape::class);

        $this->container->share(Flash::class);
        $this->add(Flash::class);

        $this->container->share(Form::class);
        $this->add(Form::class);

        $this->container->share(Hreflang::class);
        $this->add(Hreflang::class);

        $this->container->share(Identity::class);
        $this->add(Identity::class);

        $this->container->factory(Js::class);
        $this->add(Js::class);

        $this->container->factory(JsCollectionFactory::class);
        $this->container->alias(JsCollection::class, JsCollectionFactory::class);
        $this->add(JsCollection::class);

        $this->container->share(Lang::class);
        $this->add(Lang::class);

        $this->container->share(Meta::class);
        $this->add(Meta::class);

        $this->container->share(NoIndex::class);
        $this->add(NoIndex::class);

        $this->container->share(Placeholder::class);
        $this->add(Placeholder::class);

        $this->container->share(Responsive::class);
        $this->add(Responsive::class);

        $this->container->share(Route::class);
        $this->add(Route::class);

        $this->container->share(Title::class);
        $this->add(Title::class);

        $this->container->share(Translate::class);
        $this->add(Translate::class);

        $this->container->alias(MinifierInterface::class, Minifier::class);
    }
}
