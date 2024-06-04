<?php

/**
 * @package Third Party Replies
 * @copyright (c) 2024 Daniel James
 * @license https://opensource.org/license/mit MIT license
 */

namespace danieltj\thirdpartyreplies\migrations;

class install extends \phpbb\db\migration\migration {

	/**
	 * Require at least 3.3.x version.
	 */
	static public function depends_on() {

		return [ '\phpbb\db\migration\data\v33x\v331rc1' ];

	}

	/**
	 * Add new permissions
	 */
	public function update_data() {

		return [
			[ 'permission.add', [ 'f_reply_third_party_topics', false, 'f_reply' ] ],
			[ 'if', [
				[ 'permission.role_exists', [ 'ROLE_FORUM_FULL' ] ],
				[ 'permission.permission_set', [ 'ROLE_FORUM_FULL', 'f_reply_third_party_topics' ] ],
			] ],
			[ 'if', [
				[ 'permission.role_exists', [ 'ROLE_FORUM_STANDARD' ] ],
				[ 'permission.permission_set', [ 'ROLE_FORUM_STANDARD', 'f_reply_third_party_topics' ] ],
			] ],
		];

	}

}
