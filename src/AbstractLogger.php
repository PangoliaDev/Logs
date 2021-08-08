<?php
declare( strict_types = 1 );

namespace Pangolia\Logs;

abstract class AbstractLogger implements LoggerInterface {

	/**
	 * System is unusable.
	 *
	 * @param string|array|object $message
	 * @param array               $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function emergency( $message, array $context = [] ) {
		$this->log( LogLevel::EMERGENCY, $message, $context );
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
	public function alert( $message, array $context = [] ) {
		$this->log( LogLevel::ALERT, $message, $context );
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
	public function critical( $message, array $context = [] ) {
		$this->log( LogLevel::CRITICAL, $message, $context );
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
	public function error( $message, array $context = [] ) {
		$this->log( LogLevel::ERROR, $message, $context );
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
	public function warning( $message, array $context = [] ) {
		$this->log( LogLevel::WARNING, $message, $context );
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
	public function notice( $message, array $context = [] ) {
		$this->log( LogLevel::NOTICE, $message, $context );
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
	public function info( $message, array $context = [] ) {
		$this->log( LogLevel::INFO, $message, $context );
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
	public function debug( $message, array $context = [] ) {
		$this->log( LogLevel::DEBUG, $message, $context );
	}
}