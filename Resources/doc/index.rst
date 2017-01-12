ChainCommandBundle
######################

ChainCommandBundle provides functionality:

* Allows Symfony bundle register console command to be a member of some command chain.
* When a user runs the main command in a chain, all other commands registered in this chain will be executed as well.
* Commands registered as chain members can no longer be executed on their own.

Usage
================

Specifying chains in config files (in Resources/config/services.yml)

.. code-block:: yaml

    services:
        barbundle.hellocommand:
            class: krasavchegUa\BarBundle\Command\HelloCommand
            tags:
                - {name: 'chain_command', main: 'foo:hello'}



