<?php

/**
 * @package Third Party Replies
 * @copyright (c) 2024 Daniel James
 * @license https://opensource.org/license/mit MIT license
 */

namespace danieltj\thirdpartyreplies;

class ext extends \phpbb\extension\base {

	/**
	 * Require 3.3.x or later
	 */
	public function is_enableable() {

		$config = $this->container->get( 'config' );

		return phpbb_version_compare( $config[ 'version' ], '3.3.0', '>=' );

	}

}
