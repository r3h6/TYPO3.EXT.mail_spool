.. _start:

.. image:: https://travis-ci.org/r3h6/TYPO3.EXT.mail_spool.svg?branch=master
    :target: https://travis-ci.org/r3h6/TYPO3.EXT.mail_spool

=============
Documentation
=============

This extension integrates the swiftmailer spool transport for TYPO3.


Installation
------------

Through `TER <https://typo3.org/extensions/repository/view/mail_spool/>`_ or with `composer <https://composer.typo3.org/satis.html#!/mail_spool>`_ (typo3-ter/mail-spool).

.. warning::
   After installation this extension overwrites in the file "ext_localconf" the mail transport configuration to ``R3H6\MailSpool\Mail\SpoolTransport``!


Configuration
-------------

You can configure the type of spool and the location where the messsages get stored in the extension configurations.


:spool:
   **file** Messages get stored to the file system till they get sent through the scheduler or cli command.

   **memory** Messages get send at the end of the running process if no error occurs.

   **classname** Custom class which implements the Swift_Spool interface.

:spool_file_path:
   Path to directory where the spooled messages get stored. Should not be accessible from outside!

:transport_real:
   Transport used for sending e-mails. Default is same as defined in install tool.


Integration
-----------

If you are using the file spool, you must set up an extbase scheduler task or execute the command "spool:send".


Scheduler
---------

You can set up an extbase scheduler for sending the messages in the file spool queue.

.. warning::
   The option **daemon** is only for CLI usage.


Commands (CLI)
---------------

See ``./typo3/cli_dispatch.phpsh extbase help spool:send`` for details.

.. note::
   If you like run the command as a daemon on linux systems you can try `Upstart <https://en.wikipedia.org/wiki/Upstart>`_.

.. code-block:: bash
   :linenos:

   # Example

   # /etc/init/myscript.conf
   # sudo service myscript start
   # sudo service myscript stop
   # sudo service myscript status

   # Your script information
   description "Send spooled messages."
   author      "R3H6"

   # Describe events for your script
   start on startup
   stop on shutdown

   # Respawn settings
   respawn
   # respawn limit COUNT INTERVAL
   respawn limit unlimited

   # Run your script!
   script
   /var/www/dev7.local.typo3.org/typo3/cli_dispatch.phpsh extbase spool:send --daemon >/dev/null 2>&1
   end script


FAQ
---

After installing, no e-mails get send anymore, why?
   Please read the section "Integration".



Contributing
------------

Bug reports and pull request are welcome through `GitHub <https://github.com/r3h6/TYPO3.EXT.mail_spool/>`_.

Pull request
^^^^^^^^^^^^
Pull request to the master branch will be ignored. Please use develop branch.