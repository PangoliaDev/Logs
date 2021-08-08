<?php
/**
 * WPStrap core
 *
 * @package Pangolia\Logs
 */
declare( strict_types = 1 );

namespace Pangolia\Logs;

interface LoggerInterface {

	/**
	 * System is unusable.
	 *
	 * @param string|array|object $message
	 * @param mixed[]             $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function emergency( $message, array $context = [] );

	/**
	 * Action must be taken immediately.
	 *
	 * Example: Entire website down, database unavailable, etc. This should
	 * trigger the SMS alerts and wake you up.
	 *
	 * @param string|array|object $message
	 * @param mixed[]             $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function alert( $message, array $context = [] );

	/**
	 * Critical conditions.
	 *
	 * Example: Application component unavailable, unexpected exception.
	 *
	 * @param string|array|object $message
	 * @param mixed[]             $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function critical( $message, array $context = [] );

	/**
	 * Runtime errors that do not require immediate action but should typically
	 * be logged and monitored.
	 *
	 * @param string|array|object $message
	 * @param mixed[]             $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function error( $message, array $context = [] );

	/**
	 * Exceptional occurrences that are not errors.
	 *
	 * Example: Use of deprecated APIs, poor use of an API, undesirable things
	 * that are not necessarily wrong.
	 *
	 * @param string|array|object $message
	 * @param mixed[]             $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function warning( $message, array $context = [] );

	/**
	 * Normal but significant events.
	 *
	 * @param string|array|object $message
	 * @param mixed[]             $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function notice( $message, array $context = [] );

	/**
	 * Interesting events.
	 *
	 * Example: User logs in, SQL logs.
	 *
	 * @param string|array|object $message
	 * @param mixed[]             $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function info( $message, array $context = [] );

	/**
	 * Detailed debug information.
	 *
	 * @param string|array|object $message
	 * @param mixed[]             $context
	 *
	 * @return void
	 * @since 0.1.0
	 */
	public function debug( $message, array $context = [] );

	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed               $level   PSR-3 compliant log level.
	 * @param string|array|object $message The log.
	 * @return bool|void true on success, false on failure or void if debug log is disabled.
	 * @since 0.1.0
	 */
	public function log( $level, $message, array $context = [] );

}