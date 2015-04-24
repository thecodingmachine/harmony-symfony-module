Symfony2's Harmony module
=========================

This package contains a module that connects the Harmony user interface to the Symfony 2 PHP framework.

Step 1: Download the Module
---------------------------

Include the package in your **composer.json**:

**composer.json**
```
{
  "require-dev": {
    "harmony/harmony": "~1.0",
    "harmony/symfony-module": "~2.0"
  }
}
```

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```bash
$ composer update
```

Step 2: Install the bundle in your Symfony application
------------------------------------------------------

Then, enable the bundle by adding the following line in the `app/AppKernel.php`
file of your project:

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

            new Harmony\Bundle\SymfonyBundle\HarmonySymfonyBundle(),
        );

        // ...
    }

    // ...
}
```

Step 3: Register the Symfony module with Harmony
------------------------------------------------

Now, create a **modules.php** file at the root of your package and instantiate
the Symfony module:

**modules.php**
```php
<?php
return [
    new Harmony\Bundle\SymfonyBundle\SymfonyModule()
];
```

You are done!
