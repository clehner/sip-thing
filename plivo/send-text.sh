#!/bin/bash
. $(dirname $0)/config.sh

send_sms() {
	local text=$(sed 's/"/\\"/g' <<<"$3")
	curl -s "https://api.plivo.com/v1/Account/$plivo_auth_id/Message/"\
		-d @-\
		-H 'Content-type: application/json'\
		-u "$plivo_auth_id:$plivo_auth_token"\
		<<<'{"src":"'$1'","dst":"'$2'","text":"'$text'"}'
}

#"message(s) queued"

if [[ -z "$3" ]]
then
	echo "Usage: $0 sender recipient message" >&2
	exit 1
fi

send_sms "$@"
