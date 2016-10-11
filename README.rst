.. _start:

=============
Documentation
=============

This extension integrates the swiftmailer spool transport for TYPO3.

:memory:
    Messages get sent at the end of frontend generating if no error occured.
:file:
    Messages get stored on the filesystem and sent over the spool command.
:classname:
    Any class which implements Swift_Spool interface.

Commands (CLI)
---------------

See ``./typo3/cli_dispatch.phpsh extbase help spool:send`` for details.


Scheduler
---------

You can set up a scheduler for sending the messages in the file spool queue.


Configuration
-------------

You can configure the type of spool and the location where the messsages get stored in the extension configurations.

.. note::
    This extension changes the mail transport configuration to ``R3H6\MailSpool\Mail\SpoolTransport``.
    Messages get sent over whatever transport is defined in the install tool.