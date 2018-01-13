<?php
/**
 * Allows you to defeat those hidden errors! :)
 *
 * Please see the readme for how to use this script:
 *
 * https://github.com/mbissett/error-revealer/blob/master/README.md
 *
 * Please remember to uninstall this script after debugging the error!
 *
 * -----------------------------------------------------------------------------
 *
 * Author of the original script (wp-no-white-screen): Philipp Stracker (philipp@stracker.net)
 * Author of this fork: Michael Bissett (@BissettMedia on Twitter)
 */

class Error_Revealer {
	static function instance() {
		static $Inst = null;

		if ( null === $Inst ) {
			$Inst = new Error_Revealer();
		}

		return $Inst;
	}

	private function __construct() {
		$this->init();

		// Make sure to use THIS error handler after any action was fired.
		add_action( 'all', array( $this, 'init' ), 1 );
		add_action( 'all', array( $this, 'init' ), 9999 );
	}

	public function process_exception( $exception ) {
		$this->dump(
			$exception->getMessage(),
			'Exception',
			$exception->getTrace(),
			$exception->getFile(),
			$exception->getLine()
		);
	}

	public function process_error( $errno, $errstr, $errfile, $errline ) {
		switch ( $errno ) {
			case E_STRICT:
			case E_NOTICE:
			case E_DEPRECATED:
			case E_USER_NOTICE:
				$type = 'notice';
				$fatal = false;
				break;

			case E_WARNING:
			case E_USER_WARNING:
				$type = 'warning';
				$fatal = false;
				break;

			default:
				$type = 'fatal error';
				$fatal = true;
				break;
		}


		$trace = debug_backtrace();
		$this->dump( $errstr, $type, $trace, $errfile, $errline );

		if ( $fatal ) {
			error_log('Fatal error. Terminate request!');
			exit( 1 );
		}
	}

	private function dump( $message, $type, $trace, $err_file = false, $err_line = false ) {
		if ( ! empty( $err_file ) ) {
			$file_pos = "In $err_file [line $err_line]";
		} else {
			$file_pos = '';
		}

		if ( ini_get( 'log_errors' ) ) {
			$items = array();
			foreach ( $trace as $item ) {
				$items[] = (isset($item['file']) ? $item['file'] : '<unknown file>') . ' ' .
					(isset($item['line']) ? $item['line'] : '<unknown line>') .
					' calling ' . $item['function'] . '()';
			}
			$message = 'Backtrace from ' . $type . ' "' . $message . '"' . $file_pos . '' . join( ' | ', $items );
			error_log( $message );
		}

	}

	public function init() {
		if ( defined( 'WP_DEBUG_CORE' ) && ! WP_DEBUG_CORE ) { return; }

		error_reporting( E_ALL ); // Not sure if this is needed, but we'll add it!

		set_error_handler( array( $this, 'process_error' ) );
		set_exception_handler( array( $this, 'process_exception' ) );
	}
}

if ( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) || ( defined( 'WP_DEBUG_CORE' ) && WP_DEBUG_CORE ) ) {
	Error_Revealer::instance();
}
