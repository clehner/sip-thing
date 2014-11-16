#!/bin/bash
. $(dirname $0)/config.sh

send_sms() {
	result=$(curl -s 'https://www.callwithus.com/api/sms/'\
		--data-urlencode "key=$cwu_api_key"\
		--data-urlencode "from=$1"\
		--data-urlencode "to=$2"\
		--data-urlencode "text=$3")
	case $result in
		OK) return;;
		-1000) echo 'Invalid API key.';;
		-1001) echo 'Missing recipient number';;
		-1002) echo 'Missing sender number';;
		-1003) echo 'Invalid recipient number';;
		-1004) echo 'SMS delivery failed';;
		-1005) echo 'SMS failed (low account balance)';;
		default) echo 'Unknown error sending SMS';;
	esac
	return 1
}

recipient=$1
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
msg=$(sed '/^On .*,.*:.*:\| wrote:$\|^>/d')

# Uncomment this to make the API request fail:
#to=debug

send_sms $from $to "$msg"
