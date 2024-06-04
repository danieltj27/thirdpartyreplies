<?php

/**
 * @package Third Party Replies
 * @copyright (c) 2024 Daniel James
 * @license https://opensource.org/license/mit MIT license
 */

if ( ! defined( 'IN_PHPBB' ) ) {

	exit;

}

if ( empty( $lang ) || ! is_array( $lang ) ) {

	$lang = [];

}

$lang = array_merge( $lang, [
	'POSTING_CANNOT_THIRD_PARTY_REPLY' => 'You do not have permission to reply to topics that you have not started yourself.',
] );
