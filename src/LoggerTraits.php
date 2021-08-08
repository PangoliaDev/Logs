<?php
declare( strict_types = 1 );

namespace Pangolia\Logs;

trait LoggerTraits {

	/**
	 * Interpolates context values into the message placeholders.
	 *
	 * @since 0.1.0
	 */
	private function interpolate( string $message, array $context ): string {
		if ( \strpos( $message, '{' ) === false ) {
			return $message;
		}

		$replacements = [];
		foreach ( $context as $key => $val ) {
			if ( $val === null || \is_scalar( $val ) || ( \is_object( $val ) && \method_exists( $val, '__toString' ) ) ) {
				$replacements["{{$key}}"] = $val;
			} elseif ( $val instanceof \DateTimeInterface ) {
				$replacements["{{$key}}"] = $val->format( \DateTime::RFC3339 );
			} elseif ( \is_object( $val ) ) {
				$replacements["{{$key}}"] = '{object ' . \get_class( $val ) . '}';
			} elseif ( \is_resource( $val ) ) {
				$replacements["{{$key}}"] = '{resource}';
			} else {
				$replacements["{{$key}}"] = \json_encode( $val );
			}
		}

		return \strtr( $message, $replacements );
	}

	/**
	 * Query monitor logs.
	 *
	 * @param $log
	 * @param $level
	 * @since 0.1.0
	 */
	protected function log_to_qm( $log, $level ) {
		switch ( $level ) :
			case LogLevel::DEBUG :
				\do_action( 'qm/debug', $log );
				break;
			case LogLevel::INFO :
				\do_action( 'qm/info', $log );
				break;
			case LogLevel::NOTICE :
				\do_action( 'qm/notice', $log );
				break;
			case LogLevel::WARNING :
				\do_action( 'qm/warning', $log );
				break;
			case LogLevel::ERROR :
				\do_action( 'qm/error', $log );
				break;
			case LogLevel::CRITICAL :
				\do_action( 'qm/critical', $log );
				break;
			case LogLevel::ALERT :
				\do_action( 'qm/alert', $log );
				break;
			case LogLevel::EMERGENCY :
				\do_action( 'qm/emergency', $log );
				break;
			default:
		endswitch;
	}

	/**
	 * Write to custom log file.
	 *
	 * @param $path
	 * @param $message
	 * @return bool
	 * @since 0.1.0
	 */
	protected function log_to( $path, $message ): bool {
		$this->create_path( $path );

		$file = \date( 'Y' ) . '-' . \date( 'm' ) . '.txt';;
		$path = $path . '/' . $file;

		if ( @is_file( $path ) === false ) {
			return @file_put_contents( $path, '---- started logging on ' . \date( "Y-m-d H:i:s" ) . ' ----' ) && \chmod( $path, 0775 );
		}

		$logs = @file_get_contents( $path );
		$logs .= PHP_EOL . \date( "Y-m-d H:i:s" ) . ': ' . $message;
		$logger = @file_put_contents( $path, $logs );

		return ! ( $logger === false );
	}

	/**
	 * This will take a path, possibly with a long chain of uncreated directories, and keep going up one directory until
	 * it gets to an existing directory. Then it will attempt to create the next directory in that directory,
	 * and continue till it's created all the directories. It returns true if successful.
	 *
	 * @param $path
	 * @return bool
	 * @since 0.1.0
	 */
	public function create_path( $path ): bool {
		if ( \is_dir( $path ) ) {
			return true;
		}

		$prev_path = \substr( $path, 0, \strrpos( $path, '/', -2 ) + 1 );
		$return = $this->create_path( $prev_path );

		return $return && \is_writable( $prev_path ) && \mkdir( $path ) && \chmod( $path, 0775 );
	}
}
