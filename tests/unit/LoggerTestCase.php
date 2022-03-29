<?php

namespace Pangolia\LogsTests\Unit;

use Brain\Monkey;
use PHPUnit\Framework\TestCase;
use Pangolia\Logs\Logger;
use Pangolia\Logs\LogLevel;

class LoggerTestCase extends TestCase {
	protected Logger $logger;
	protected string $log_name = 'TestProject';
	protected string $log_slug = 'test-project';
	protected string $log_path = 'logs';
	protected string $log_contents;

	/**
	 * Setup which calls \WP_Mock setup
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();
		Monkey\setUp();
		Monkey\Functions\when( '__' )->returnArg( 1 );
		Monkey\Functions\when( '_e' )->returnArg( 1 );
		Monkey\Functions\when( '_n' )->returnArg( 1 );
		Monkey\Functions\when( 'sanitize_title' )->justReturn( $this->log_slug );
	}

	/**
	 * Setup logger
	 *
	 * @return void
	 */
	public function setUpLogger(): void {
		$this->logger = new Logger( [
			'name' => $this->log_name,
			'path' => $this->log_path,
		] );
		$this->log_contents = '';
	}

	/**
	 * @return string
	 */
	public function getLoggerFullPath(): string {
		return \WP_CONTENT_DIR . '/' . $this->log_path . '/' . \date( 'Y' ) . '/' . \date( 'm' ) . '/';
	}

	/**
	 * @return string
	 */
	public function getLoggerFilePath(): string {
		return $this->getLoggerFullPath() . \date( 'd' ) . '.log';
	}

	/**
	 * @return void
	 */
	public function getLoggerFileContents() {
		$this->log_contents = \file_get_contents( $this->getLoggerFilePath() );
	}

	/**
	 * @return array
	 */
	public function getLoggerLevels(): array {
		return [
			LogLevel::EMERGENCY,
			LogLevel::ALERT,
			LogLevel::CRITICAL,
			LogLevel::ERROR,
			LogLevel::WARNING,
			LogLevel::NOTICE,
			LogLevel::INFO,
			LogLevel::DEBUG,
		];
	}

	/**
	 * @return void
	 */
	public function removeLoggerTestFiles() {
		unlink( $this->getLoggerFilePath() );
		rmdir( $this->getLoggerFullPath() );
		rmdir( \WP_CONTENT_DIR . '/' . $this->log_path . '/' . \date( 'Y' ) . '/' );
		rmdir( \WP_CONTENT_DIR . '/' . $this->log_path . '/' );
	}

	/**
	 * Teardown which calls \WP_Mock tearDown
	 *
	 * @return void
	 */
	public function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}
}