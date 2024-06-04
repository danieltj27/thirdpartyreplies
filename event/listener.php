<?php

/**
 * @package Third Party Replies
 * @copyright (c) 2024 Daniel James
 * @license https://opensource.org/license/gpl-2-0 GNU Public License
 */

namespace danieltj\thirdpartyreplies\event;

use phpbb\auth\auth;
use phpbb\template\template;
use phpbb\language\language;
use danieltj\thirdpartyreplies\includes\functions;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface {

	/**
	 * @var auth
	 */
	protected $auth;

	/**
	 * @var functions
	 */
	protected $functions;

	/**
	 * @var language
	 */
	protected $language;

	/**
	 * @var template
	 */
	protected $template;

	/**
	 * Constructor
	 */
	public function __construct( auth $auth, functions $functions, language $language, template $template ) {

		$this->auth = $auth;
		$this->functions = $functions;
		$this->language = $language;
		$this->template = $template;

	}

	/**
	 * Events
	 */
	static public function getSubscribedEvents() {

		return [
			'core.user_setup'					=> 'add_languages',
			'core.permissions'					=> 'add_permissions',
			'core.viewtopic_get_post_data'		=> 'can_reply_to_topic',
			'core.viewtopic_modify_post_row'	=> 'can_quote_in_topics',
			'core.modify_posting_auth'			=> 'can_post_topic_reply',
		];

	}

	/**
	 * Add langauge files
	 */
	public function add_languages( $event ) {

		$this->language->add_lang( [ 'common', 'permissions' ], 'danieltj/thirdpartyreplies' );

	}

	/**
	 * Add permissions
	 */
	public function add_permissions( $event ) {

		$event->update_subarray( 'permissions', 'f_reply_third_party_topics', [ 'lang' => 'ACL_F_REPLY_THIRD_PARTY_TOPICS', 'cat' => 'post' ] );

	}

	/**
	 * viewtopic.php: can user reply to topic
	 */
	public function can_reply_to_topic( $event ) {

		// Check if this is a third party topic
		if ( $this->functions->is_third_party_topic( $event[ 'topic_data' ][ 'topic_id' ] ) ) {

			// Can the user reply AND reply to third party topics?
			$can_reply = ( $this->auth->acl_get( 'f_reply', $event[ 'topic_data' ][ 'forum_id' ] ) && $this->auth->acl_get( 'f_reply_third_party_topics', $event[ 'topic_data' ][ 'forum_id' ] ) ) ? true : false;

			$this->template->assign_vars( [
				'S_DISPLAY_REPLY_INFO' => $can_reply,
			] );

		}

	}

	/**
	 * viewtopic.php: can user quote in topics
	 */
	public function can_quote_in_topics( $event ) {

		// Check if this is a third party topic
		if ( $this->functions->is_third_party_topic( $event[ 'topic_data' ][ 'topic_id' ] ) ) {

			/**
			 * Check if the user can reply to third party topics but only set
			 * U_QUOTE to false (no) if they can't because this shouldn't overwrite
			 * any other permission checks that are done in viewtopic.php.
			 */
			if ( ! $this->auth->acl_get( 'f_reply_third_party_topics', $event[ 'row' ][ 'forum_id' ] ) ) {

				$event[ 'post_row' ] = array_merge( $event[ 'post_row' ], [
					'U_QUOTE' => false,
				] );

			}

		}

	}

	/**
	 * posting.php: can the user post a reply
	 */
	public function can_post_topic_reply( $event ) {

		// Check if this is a third party topic
		if ( $this->functions->is_third_party_topic( $event[ 'topic_id' ] ) ) {

			if ( ! $this->auth->acl_get( 'f_reply_third_party_topics', $event[ 'forum_id' ] ) ) {

				$event[ 'error' ] = array_merge( $event[ 'error' ], [
					$this->language->lang( 'POSTING_CANNOT_THIRD_PARTY_REPLY' )
				] );

				$event[ 'is_authed' ] = false;

			}

		}

	}

}
