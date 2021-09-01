<?php
declare( strict_types = 1 );

namespace Pangolia\Logs;

/**
 * Example Logger
 *
 * @since 0.2.0
 */
class ExampleLogger extends AbstractLogger implements LoggerInterface {

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed               $level   PSR-3 compliant log level.
	 * @param string|array|object $message The log message.
	 * @param array               $context Interpolates context values.
	 * @return bool|void true on success, false on failure or void if debug log is disabled.
	 * @return false|void
	 */
	public static function write( $level, $message, array $context = [] ): bool {
		// Create the message string
		$message = static::create( 'Project name', $level, $message, $context );

		// Log to Query Monitor
		static::write_to_qm( $message, $level );

		// Add a WP log to wp-content/debug.log
		if ( \WP_DEBUG_LOG ) {
			\error_log( $message );
		}

		// Add a WP log to a custom folder inside wp_content
		return static::write_to( trailingslashit( WP_CONTENT_DIR ) . 'path/to/logs', $message );
	}

	/**
	 * System is unusable.
	 *
	 * @param string|array|object $message
	 * @param array               $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function emergency( $message, array $context = [] ) {
		static::write( LogLevel::EMERGENCY, $message, $context );
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string|array|object $message
	 * @param array               $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function alert( $message, array $context = [] ) {
		static::write( LogLevel::ALERT, $message, $context );
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string|array|object $message
	 * @param array               $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function critical( $message, array $context = [] ) {
		static::write( LogLevel::CRITICAL, $message, $context );
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string|array|object $message
	 * @param array               $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function error( $message, array $context = [] ) {
		static::write( LogLevel::ERROR, $message, $context );
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string|array|object $message
	 * @param array               $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function warning( $message, array $context = [] ) {
		static::write( LogLevel::WARNING, $message, $context );
	}

	/**
	 * Normal but significant events.
	 *
	 * @param string|array|object $message
	 * @param array               $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function notice( $message, array $context = [] ) {
		static::write( LogLevel::NOTICE, $message, $context );
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string|array|object $message
	 * @param array               $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function info( $message, array $context = [] ) {
		static::write( LogLevel::INFO, $message, $context );
	}

	/**
	 * Detailed debug information.
	 *
	 * @param string|array|object $message
	 * @param array               $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public static function debug( $message, array $context = [] ) {
		static::write( LogLevel::DEBUG, $message, $context );
	}
}
