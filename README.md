# sip thing

This is a thing to assist with using text messages and phone calls from your
computer. It is intended sortof as a Google Voice replacement. It doesn't store
messages anywhere but routes them through email.

You can pick some combination of these services to use:
- [Twilio](https://www.twilio.com/): incoming calls and texts
- [CallWithUs](https://www.callwithus.com/): outgoing calls and texts
- [Plivo](https://www.callwithus.com/): outgoing calls; incoming and outgoing texts

## Basic outline

- SMS &harr; email
- Voice &harr; SIP
- Voicemail &rarr; email

## Features

- Get text messages by email (including MMS attachments if using Twilio).
- Send text messages using email.
- Get voicemail recordings by email (currently implemented only with Plivo).

## Setup

### Incoming voice/text

Handling incoming calls and texts can be set up using Twilio or Plivo. Twilio
is more popular, gives out promotional credits more often, and has more
features. Plivo is cheaper and open source.

#### Twilio

- Copy `twilio/config.example.php` to `twilio/config.php`.
  Replace the addresses in the config file with your addresses.

- On Twilio, set the request URLs for your phone number to point to the voice
  and text scripts, e.g.:

    - Voice: `https://example.org/sip/twilio/voice.php`
    - Messaging: `https://example.org/sip/twilio/text.php`

#### Plivo

- Copy `plivo/config.example.php` to `plivo/config.php`.
  Replace the addresses in the config file with your addresses.

- In the `plivo` directory, create a file, `voicemail.mp3`, with the recording
  that you want callers to hear as a voicemail prompt.

- In Plivo, create an application. Call it something like "Forward to Email".
  Set the URLs to point to your text and voice scripts, e.g.:

    - Answer URL: `https://example.org/sip/plivo/voice.php`
    - Message URL: `https://example.org/sip/plivo/text.php`

- Set your phone number's application to be the application you just created.

### Outgoing text messages via email

This section sets up your mail server so that you can send mail to addresses
like `yournumber.theirnumber.sms@yourhost.example.com` and have them be
delivered as text messages.

To use CallWithUs instead of Plivo for outgoing texts, follow these steps using
`cwu` instead of `plivo`.

- Copy `plivo/config.example.sh` to `plivo/config.sh`.
  Replace the API key with yours, from your Account Info page.

- In `/etc/postfix/main.cf`, add `alias_maps = pcre:/etc/postfix/aliases`.

- In `/etc/postfix/aliases`, add `/\.sms@/ root`

- Run `postmap /etc/postfix/aliases`

- In `/etc/postfix/main.cf`, in the item `smtpd_recipient_restrictions`, add to
  the end of the list (somewhere after `permit_sasl_authenticated` and `permit_mynetworks`): `check_recipient_access pcre:/etc/postfix/recipient_access`

- In `/etc/postfix/recipient_access`, add a line `/.sms@/ REJECT`. This is to
  prevent unauthorized people from using your server's sms service.

- In `/etc/postfix/transports`, add two lines. First `/sms/ sms`. Then `/./ `
  followed by what you had in `mailbox_transport` previously. e.g. if you are
  using Dovecot for mail delivery, you may add something like `/./ lmtp:unix:private/dovecot-lmtp`.

- In `/etc/postfix/master.cf`, add two lines: 
```
sms  unix  -       n       n       -       -       pipe
  user=cel argv=/path/to/this/repo/plivo/send-text.sh ${recipient}
```
This defines the `sms` transport used the previous step.

- Run `postfix reload`

## Provider cost comparison

Service       | [Twilio][twl] | [CallWithUs][cwu] | [Plivo][plv]
------------- | -------------:| -----------------:|-----------:
DID           | $1/month      | $2.74-$3.49/month | $0.80/month
Incoming call | $0.0075/min   | -                 | $0.0085/min
Outgoing call | $0.015/min    | $0.0095/min       | $0.012/min
SIP calls     | $0.005/min    | -                 | $0.003/min
Incoming text | $0.0075       | -                 | free
Outgoing text | $0.0075       | $0.05             | $0.0035
Recording     | $0.0025/min   | -                 | free
Transcription | $0.05/min     | -                 | $0.05/min

Note that terminating a PSTN call to a SIP address with Plivo or Twilio counts
as both an incoming call and SIP call. I think this is true for outbound SIP
&rarr; PSTN calls as well, which would be counted as both outgoing calls and
SIP calls.

## Todo

- Twilio: record and send voicemails
- Twilio: delete resources after sent/received
- Twilio, Plivo: Consider making and sending voicemail transcriptions
- Make it a web app (just kidding)
- Make email messages thread properly

[twl]: http://www.twilio.com/voice/pricing#extras
[plv]: https://www.plivo.com/pricing/
[cwu]: http://www.callwithus.com/showrates
