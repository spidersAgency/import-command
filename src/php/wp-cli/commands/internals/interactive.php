<?php

WP_CLI::add_command( 'interactive', new Interactive_Command );

class Interactive_Command extends WP_CLI_Command {

	/**
	 * Open an interactive shell environment.
	 */
	public function __invoke() {
		if ( function_exists( 'readline' ) ) {
			$repl = 'repl_readline';
		} else {
			$repl = 'repl_basic';
		}

		while ( true ) {
			$in = call_user_func( array( __CLASS__, $repl ), 'wp> ' );

			if ( 'exit' == $in )
				return;

			$r = eval( $in );

			if ( false === $r )
				continue;

			if ( null === $r )
				\WP_CLI::line();
			else
				var_export( $r );
		}
	}

	private static function repl_readline( $str ) {
		$line = readline( $str );
		readline_add_history( $line );
		return $line;
	}

	private static function repl_basic( $str ) {
		\WP_CLI::out( $str );
		return \cli\input();
	}
}

