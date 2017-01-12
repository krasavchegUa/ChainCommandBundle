# ChainCommandBundle

ChainCommandBundle is a Symfony bundle for command chaining functionality.

Installation
---------------------------

###Step 1: Download the Bundle

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require krasavchegUa/chain-command-bundle
```

###Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list (FooBundle and BarBundle provided for demo purposes) of registered bundles
in the `app/AppKernel.php` file of your project:

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new krasavchegUa\FooBundle\FooBundle(),
            new krasavchegUa\BarBundle\BarBundle(),
            new krasavchegUa\ChainCommandBundle\ChainCommandBundle(),
        );

        // ...
    }

    // ...
}
```

Usage?
-------------------------

See [documentation](Resources/doc/index.rst).
