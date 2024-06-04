<?php

/**
 * @package Third Party Replies
 * @copyright (c) 2024 Daniel James
 * @license https://opensource.org/license/mit MIT license
 */

namespace danieltj\thirdpartyreplies\includes;

use phpbb\db\driver\driver_interface as database;
use phpbb\notification\manager as notifications;

final class functions {

	/**
	 * @var driver_interface
	 */
	protected $db;

	/**
	 * @var string
	 */
	protected $table_prefix;

	/**
	 * @var string
	 */
	protected $root_path;

	/**
	 * @var string
	 */
	protected $php_ext;

	/**
	 * Constructor.
	 */
	public function __construct( database $db, string $table_prefix, string $root_path, string $php_ext ) {

		$this->db = $db;
		$this->table_prefix = $table_prefix;
		$this->root_path = $root_path;
		$this->php_ext = $php_ext;

	}

	/**
	 * Check if the given topic belongs to this user.
	 * 
	 * @param  integer $topic_id The ID of the topic to check against.
	 * @param  integer $user_id  (optional) The user ID to check against.
	 * @return boolean           Either true or false based on the check.
	 */
	public function is_third_party_topic( $topic_id, $user_id = 0 ) {

		global $user;

		$topic_id = (int) $topic_id;
		$topic_author = ( 0 === $user_id || ! is_int( $user_id ) ) ? $user->data[ 'user_id' ] : $user_id;

		$sql = 'SELECT * FROM ' . TOPICS_TABLE . ' WHERE ' . $this->db->sql_build_array( 'SELECT', [
			'topic_id' => $topic_id
		] );

		// Fetch the results we got
		$result = $this->db->sql_query( $sql );
		$topic = $this->db->sql_fetchrow( $result );
		$this->db->sql_freeresult( $result );

		// Topic doesn't exist (not third party topic)
		if ( empty( $topic ) ) {

			return false;

		}

		// The selected user did NOT start this topic (is a third party topic)
		if ( $topic_author !== $topic[ 'topic_poster' ] ) {

			return true;

		}

		return false;

	}

}
