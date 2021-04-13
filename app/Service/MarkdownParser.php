<?php

namespace App\Service;

use Parsedown;

class MarkdownParser {
	
	/**
	 * Converts markdown input to safe HTML.
	 * @param string $markdown Markdown input to parse.
	 * @return string HTML output.
	 */
	public static function parse (string $markdown) {
		$parser = new Parsedown();
		return $parser->text($markdown);
	}
}

