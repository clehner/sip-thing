# sip thing

A thing to assist with sending text messages and phone calls from your computer. Intended as a Google Voice replacement.

Services used:
- [Twilio](https://www.twilio.com/): incoming calls and texts
- [CallWithUs](https://www.callwithus.com/): outgoing calls and texts

Basic approach:
- SMS &darr; email
- Voice &darr; SIP

## Features

- Get text messages by email, including MMS attachments.
- Send text messages using email.

## Setup

### Incoming voice/text

- Copy `twilio/config.example.php` to `twilio/config.php`.
  Replace the addresses in the config file with your addresses.

- On Twilio, set the request URLs for your phone number to point to the voice
  and text scripts, e.g.:

    - Voice: `https://example.org/sip/twilio/voice.php`
    - Messaging: `https://example.org/sip/twilio/text.php`

### Outgoing text messages via email

- Copy `cwu/config.example.sh` to `cwu/config.sh`.
  Replace the API key and From number with your information, from your Account
  Info page.

- In `/etc/postfix/main.cf`, add `alias_maps = pcre:/etc/postfix/aliases`.

- In `/etc/postfix/aliases`, add `/^(.*\.sms)@/ |/path/to/this/repo/cwu/send-text.sh $1`.

- Run `postmap /etc/postfix/aliases`

- In `/etc/postfix/main.cf`, in the item `smtpd_recipient_restrictions`, add to
  the end of the list (somewhere after permit_sasl_authenticated and permit_mynetworks): `check_recipient_access pcre:/etc/postfix/recipient_access`

- In `/etc/postfix/recipient_access`, add a line `/.sms@/ REJECT`. This is to
  prevent unauthorized people from using your server's sms service.

- In `/etc/postfix/main.cf`, add `mailbox_transport_maps =
  pcre:/etc/postfix/transports`. Remove `mailbox_transport` if you have it.

- In `/etc/postfix/transports`, add two lines. First `/sms/ sms`. Then `/./ `
  followed by what you had in `mailbox_transport` previously. e.g. if you are
  using Dovecot for mail delivery, you may add something like `/./ lmtp:unix:private/dovecot-lmtp`.

- Run `postfix reload`

I'm not completely sure why I had to add the command to the alias map instead of
just using the transport map. If you find a simpler config method, let me know.

## Costs

Service       | Twilio        | CallWithUs
------------- | -------------:| -------------:
DID           | $1/month      | - (no longer offered)
Incoming call | $0.0075/min   | -
Outgoing call | $0.015/min    | $0.0095/min
Incoming text | $0.0075       | -
Outgoing text | $0.0225       | $0.05

## Todo

- Record and transcribe voicemail
- Handle HTML email and attachments.
- Delete Twilio resources after sent/received.
