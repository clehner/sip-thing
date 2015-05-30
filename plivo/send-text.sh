#!/bin/bash
. $(dirname $0)/config.sh

send_sms() {
	local text=$(sed 's/[\\"]/\\&/g')
	curl -s "https://api.plivo.com/v1/Account/$plivo_auth_id/Message/"\
		-d "{\"src\":\"$1\",\"dst\":\"$2\",\"text\":\"$text\"}"\
		-H 'Content-type: application/json'\
		-u "$plivo_auth_id:$plivo_auth_token"
}

recipient="$1"
if [[ -z "$recipient" ]]
then
	echo "Usage: $0 recipient" >&2
	exit 1
fi

# Assume the address is in the form from_address.to_address.etc
args=(${recipient//./ })
from=${args[0]}
to=${args[1]}

# Skip headers
sed -un '/^$/q'

# Get the email body, skipping quoted reply stuff
sed '/^On .*,.*:.*:\| wrote:$\|^>/d' |\
	send_sms $from $to
