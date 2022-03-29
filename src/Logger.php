<?php
declare( strict_types = 1 );

namespace Pangolia\Logs;

/**
 * Example Logger
 *
 * @since 0.2.0
 */
class Logger extends AbstractLogger {

	/**
	 * @param $config
	 */
	public function __construct( $config ) {
		$this->name = $config['name'] ?? '';
		$this->log_path = $config['path'] ?? '';
		$this->log_format = $config['format'] ?? 'log';
		$this->log_to_wp = $config['log_to_wp'] ?? true;
		$this->log_to_qm = $config['log_to_qm'] ?? true;
	}

	/**
	 * System is unusable.
	 *
	 * @param string|array<string|int, mixed>|object $message
	 * @param array<string, string>                  $context
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public function emergency( $message, array $context = [] ): bool {
		return $this->write( LogLevel::EMERGENCY, $message, $context );
	}

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string|array<string|int, mixed>|object $message
	 * @param array<string, string>                  $context
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public function alert( $message, array $context = [] ): bool {
		return $this->write( LogLevel::ALERT, $message, $context );
	}

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string|array<string|int, mixed>|object $message
	 * @param array<string, string>                  $context
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public function critical( $message, array $context = [] ): bool {
		return $this->write( LogLevel::CRITICAL, $message, $context );
	}

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string|array<string|int, mixed>|object $message
	 * @param array<string, string>                  $context
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public function error( $message, array $context = [] ): bool {
		return $this->write( LogLevel::ERROR, $message, $context );
	}

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string|array<string|int, mixed>|object $message
	 * @param array<string, string>                  $context
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public function warning( $message, array $context = [] ): bool {
		return $this->write( LogLevel::WARNING, $message, $context );
	}

	/**
	 * Normal but significant events.
	 *
	 * @param string|array<string|int, mixed>|object $message
	 * @param array<string, string>                  $context
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public function notice( $message, array $context = [] ): bool {
		return $this->write( LogLevel::NOTICE, $message, $context );
	}

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string|array<string|int, mixed>|object $message
	 * @param array<string, string>                  $context
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public function info( $message, array $context = [] ): bool {
		return $this->write( LogLevel::INFO, $message, $context );
	}

	/**
	 * Detailed debug information.
	 *
	 * @param string|array<string|int, mixed>|object $message
	 * @param array<string, string>                  $context
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public function debug( $message, array $context = [] ): bool {
		return $this->write( LogLevel::DEBUG, $message, $context );
	}
}
