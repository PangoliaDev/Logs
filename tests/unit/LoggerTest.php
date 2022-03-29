<?php

namespace Pangolia\LogsTests\Unit;

class LoggerTest extends LoggerTestCase {

	public function testLogCreation() {
		$this->setUpLogger();
		$this->assertTrue( $this->logger->alert( 'test log' ) );
		$this->assertTrue( is_dir( $this->getLoggerFullPath() ) );
		$this->assertTrue( is_file( $this->getLoggerFilePath() ) );
		$this->removeLoggerTestFiles();
	}

	public function testLogLevels() {
		$this->setUpLogger();
		foreach ( $this->getLoggerLevels() as $level ) {
			$this->logger->write( $level, "Test {$level} level" );
		}
		$this->getLoggerFileContents();
		foreach ( $this->getLoggerLevels() as $level ) {
			$level_uppercase = strtoupper( $level );
			$this->assertTrue( strpos( $this->log_contents, "[TestProject] [{$level_uppercase}] Test {$level} level" ) !== false );
		}
		$this->removeLoggerTestFiles();
	}

	public function testLogArrayMessage() {
		$this->setUpLogger();
		$this->logger->debug( [
			'intArrayValue'     => 123,
			'boolArrayValue'    => true,
			'stringArrayValue'  => 'stringValue',
			'arrayInArrayValue' => [
				'subArrayKey' => 'arrayValue',
			],
		] );
		$this->getLoggerFileContents();
		$this->assertTrue( strpos( $this->log_contents, '(
    [name] => TestProject
    [level] => DEBUG
    [array] => Array
        (
            [intArrayValue] => 123
            [boolArrayValue] => 1
            [stringArrayValue] => stringValue
            [arrayInArrayValue] => Array
                (
                    [subArrayKey] => arrayValue
                )

        )' ) !== false );
		$this->removeLoggerTestFiles();
	}

	public function testLogClosureMessage() {
		$this->setUpLogger();
		$this->logger->debug( fn() => 'callbackValue' );
		$this->getLoggerFileContents();
		$this->assertTrue( strpos( $this->log_contents, '[TestProject] [DEBUG] callbackValue' ) !== false );
		$this->removeLoggerTestFiles();
	}

	public function testLogObjectMessage() {
		$this->setUpLogger();
		$object = new \stdClass();
		$object->objectProperty = 'objectPropertyValue';
		$this->logger->debug( $object );
		$this->getLoggerFileContents();
		$this->assertTrue( strpos( $this->log_contents, '(
    [name] => TestProject
    [level] => DEBUG
    [array] => stdClass Object
        (
            [objectProperty] => objectPropertyValue
        )' ) !== false );
		$this->removeLoggerTestFiles();
	}

	public function testInterpolatingStringMessages() {
		$this->setUpLogger();
		$this->logger->debug( 'User {username} created', [ 'username' => 'bolivar' ] );
		$this->getLoggerFileContents();
		$this->assertTrue( strpos( $this->log_contents, '[TestProject] [DEBUG] User bolivar created' ) !== false );
		$this->removeLoggerTestFiles();
	}

	public function testInterpolatingArrayMessages() {
		$this->setUpLogger();
		$this->logger->debug(
			[
				'user_name_message' => 'User {username} created',
				'user_{group}'      => 'User group created',
			],
			[ 'username' => 'bolivar', 'group' => 'golden_membership', ]
		);
		$this->getLoggerFileContents();
		$this->assertTrue( strpos( $this->log_contents, '(
    [name] => TestProject
    [level] => DEBUG
    [array] => Array
        (
            [user_name_message] => User bolivar created
            [user_golden_membership] => User group created
        )' ) !== false );
		$this->removeLoggerTestFiles();
	}

	public function testInterpolatingObjectMessages() {
		$this->setUpLogger();
		$object = new \stdClass();
		$object->userMessage = 'User {username} created';
		$object->userArrayMessage = [ 'user_{group}' => 'User group created for {username}' ];
		$this->logger->debug( $object, [ 'username' => 'bolivar', 'group' => 'golden_membership' ] );
		$this->getLoggerFileContents();
		$this->assertTrue( strpos( $this->log_contents, '(
    [name] => TestProject
    [level] => DEBUG
    [array] => stdClass Object
        (
            [userMessage] => User bolivar created
            [userArrayMessage] => Array
                (
                    [user_golden_membership] => User group created for bolivar
                )

        )' ) !== false );
		$this->removeLoggerTestFiles();
	}

	public function testInterpolatingClosureMessage() {
		$this->setUpLogger();
		$this->logger->debug( fn() => 'User {username} created with {group}', [ 'username' => 'bolivar', 'group' => 'golden_membership' ] );
		$this->getLoggerFileContents();
		$this->assertTrue( strpos( $this->log_contents, '[TestProject] [DEBUG] User bolivar created with golden_membership' ) !== false );
		$this->removeLoggerTestFiles();
	}
}