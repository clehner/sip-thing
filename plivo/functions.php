<?php

function nice_format($number) {
	if (preg_match('/^(?:\\+?1)?(...)(...)(....)$/', $number, $matches)) {
		list(, $area, $prefix, $line) = $matches;
		return "($area) $prefix-$line";
	} else {
		return $number;
	}
}
