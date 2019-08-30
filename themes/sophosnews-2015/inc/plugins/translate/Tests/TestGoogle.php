<?php

require_once dirname( dirname( __FILE__ ) ) . '/Google.php';

class TestGoogle extends PHPUnit_Framework_TestCase
{
	const KEY = 'AIzaSyAMqmBxA4VVOj6zR06qWmWBqqgX8TncCBc';
	const EN  = 'This is my test, show me yours';
	const DE  = 'Dies ist mein Test, zeig mir deins';

	protected $api;

	function setUp ()
	{
		$this->api = new Google( self::KEY );
	}

	/**
	 * @test
	 */
	function translate ()
	{
		$google = $this->api;
		$params = array(
			'source' => 'en',
			'target' => 'de',
			'text'   => self::EN
 		);

 		$this->assertEquals( $google->translate( $params ), self::DE );
	
        // Test with a source that Google would reject    
        $params = array(
			'source' => 'en-US',
			'target' => 'de',
			'text'   => self::EN
 		);
 		
        $this->assertEquals( $google->translate( $params ), self::DE );
	}

	/**
	 * @test
	 */
	function languages ()
	{
		$google = $this->api;

		$languageCodes = $google->languages();
		$lang          = array_pop( $languageCodes );

		$this->assertTrue( is_array( $languageCodes ) );
		$this->assertArrayHasKey( 'language', $lang );
		$this->assertArrayNotHasKey( 'name',  $lang );
		$this->assertRegExp('/^[a-z][a-z](?:-[A-Z][A-Z]?)$/', $lang['language']);

		$languageNames = $google->languages( 'en-US' ); // en-US is invalid but should be transformed to en
		$lang          = array_pop( $languageNames );

		$this->assertTrue( is_array( $languageNames ) );
		$this->assertArrayHasKey( 'language', $lang );
		$this->assertArrayHasKey( 'name',  $lang );
	}
}

?>
