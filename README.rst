.. _start:

=============
Documentation
=============

This extension integrates swift's memory and file spool transport.

:Memory:
    Messages get sent at the end of frontend generating if no error occured.
:File:
    Messages get stored on the filesystem and sent over the spool command.


Commands (CLI)
---------------

See ``./typo3/cli_dispatch.phpsh extbase help`` for details.


Scheduler
---------

You can set up a scheduler for sending the messages in the file spool queue.


Configuration
-------------

You can configure the type of spool and the location where the messsages get stored in the extension configurations.

.. note::
    This extension changes the mail transport configuration to ``TYPO3\MailSpool\Mail\SpoolTransport``.
    Messages get sent over whatever transport is defined in the install tool.