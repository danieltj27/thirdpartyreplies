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
	'ACL_F_REPLY_THIRD_PARTY_TOPICS' => 'Can reply to third party topics<br /><em>The <strong>Can reply to topics</strong> permission must be set to <strong>Yes</strong> for this permission to have any effect.</em>',
] );
