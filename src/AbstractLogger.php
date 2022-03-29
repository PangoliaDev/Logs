<?php
declare( strict_types = 1 );

namespace Pangolia\Logs;

abstract class AbstractLogger {

	/**
	 * @var string Project name
	 */
	protected string $name;

	/**
	 * @var string Path to folder for custom log files
	 */
	protected string $log_path;

	/**
	 * @var string The log file extension
	 */
	protected string $log_format;

	/**
	 * @var bool Determines if we log to debug.log in wp-content
	 */
	protected bool $log_to_wp;

	/**
	 * @var bool Determines if we log to query monitor
	 */
	protected bool $log_to_qm;

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed                                  $level   PSR-3 compliant log level.
	 * @param string|array<string|int, mixed>|object $message The log message.
	 * @param array<string, string>                  $context Interpolates context values.
	 * @return bool true on success, false on failure or void if debug log is disabled.
	 */
	public function write( $level, $message, array $context = [] ): bool {
		// Create the message string
		$message = $this->create_log( $this->name, $level, $message, $context );

		// Log to Query Monitor
		if ( $this->log_to_qm ) {
			$this->write_to_qm( $message, $level );
		}

		// Add a WP log to wp-content/debug.log
		if ( \WP_DEBUG_LOG && $this->log_to_wp ) {
			\error_log( $message );
		}

		// A listener to do something with the log
		\do_action( \sanitize_title( $this->name ) . '_logger_write', $message, $level ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound

		// Add a WP log to a custom folder inside wp_content
		return $this->write_to_file( \trailingslashit( \WP_CONTENT_DIR . '/' . $this->log_path ), $message );
	}

	/**
	 * Creates the message
	 *
	 * @param string                                 $name    Can be the plugin or project name
	 * @param mixed                                  $level   PSR-3 compliant log level.
	 * @param string|array<string|int, mixed>|object $message The log.
	 * @param array<string, string>                  $context Interpolates context values.
	 * @return string
	 * @since 0.2.0
	 */
	protected function create_log( string $name, $level, $message, array $context = [] ): string {
		if ( \is_callable( $message ) ) {
			$message = call_user_func( $message );
		}

		$message = \is_array( $message ) || \is_object( $message )
			? \print_r( \array_merge(
				[
					'name'  => $name,
					'level' => \strtoupper( $level ),
				], [ 'array' => $message ]
			), true )
			: \sprintf( '[%s] [%s] %s', $name, \strtoupper( $level ), $message );
		return $this->interpolate( $message, $context );
	}

	/**
	 * Write to custom log file.
	 *
	 * @param string $path
	 * @param string $message The log.
	 * @return bool
	 * @since 0.2.0
	 */
	protected function write_to_file( string $path, string $message ): bool {
		$log_format = $this->log_format;
		$log_path = $path . '/' . \date( 'Y' ) . '/' . \date( 'm' ) . '/';

		if ( ! \is_dir( $log_path ) ) {
			\mkdir( $log_path, 0775, true );
		}

		$file_path = $log_path
			. \apply_filters( \sanitize_title( $this->name ) . '_logger_file_name', \date( 'd' ) ) // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound
			. ".{$log_format}";

		if ( @is_file( $file_path ) === false ) {
			@file_put_contents( $file_path, '---- started logging on ' . \date( 'Y-m-d H:i:s' ) . ' ----' ) && \chmod( $file_path, 0775 );
		}

		$logs = @file_get_contents( $file_path );
		$logs .= PHP_EOL . \date( 'Y-m-d H:i:s' ) . ': ' . $message;
		$logger = @file_put_contents( $file_path, $logs );

		return ! ( $logger === false );
	}

	/**
	 * Query monitor logs.
	 *
	 * @param string $log
	 * @param string $level
	 * @since 0.2.0
	 */
	protected function write_to_qm( string $log, string $level ) {
		switch ( $level ) :
			case LogLevel::DEBUG:
				\do_action( 'qm/debug', $log );
				break;
			case LogLevel::INFO:
				\do_action( 'qm/info', $log );
				break;
			case LogLevel::NOTICE:
				\do_action( 'qm/notice', $log );
				break;
			case LogLevel::WARNING:
				\do_action( 'qm/warning', $log );
				break;
			case LogLevel::ERROR:
				\do_action( 'qm/error', $log );
				break;
			case LogLevel::CRITICAL:
				\do_action( 'qm/critical', $log );
				break;
			case LogLevel::ALERT:
				\do_action( 'qm/alert', $log );
				break;
			case LogLevel::EMERGENCY:
				\do_action( 'qm/emergency', $log );
				break;
			default:
		endswitch;
	}

	/**
	 * Interpolates context values into the message placeholders.
	 *
	 * @param string                $message
	 * @param array<string, string> $context
	 * @return string
	 */
	protected function interpolate( string $message, array $context ): string {
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
				$replacements["{{$key}}"] = \json_encode( $val ); // phpcs:ignore WordPress.WP.AlternativeFunctions.json_encode_json_encode
			}
		}

		return \strtr( $message, $replacements );
	}
}
