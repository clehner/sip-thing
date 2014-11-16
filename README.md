# twilio sip thing

Use as a replacement for Google Voice

## Features

- Emails you text messages, including MMS attachments.

## Setup

- Copy `twilio/config.example.php` to `twilio/config.php`.
  Replace the addresses in the config file with your addresses.

- On Twilio, set the request URLs for your phone number to point to the voice
  and text scripts, e.g.:

    - Voice: `https://example.org/sip/twilio/voice.php`
    - Messaging: `https://example.org/sip/twilio/text.php`

## Todo

- Record and transcribe voicemail
- Send SMS
