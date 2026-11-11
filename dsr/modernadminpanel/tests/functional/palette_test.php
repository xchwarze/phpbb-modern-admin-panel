<?php
/**
 *
 * Modern Admin Panel. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026, DSR! https://github.com/xchwarze
 * @license GNU General Public License, version 2 (GPL-2.0)
 *
 */

/**
 * @group functional
 */
class dsr_modernadminpanel_palette_test extends \phpbb_functional_test_case
{
	static protected function setup_extensions()
	{
		return ['dsr/modernadminpanel'];
	}

	public function test_acp_page_and_palette_endpoint()
	{
		$this->login();
		$this->admin_login();

		$crawler = self::request('GET', 'adm/index.php?sid=' . $this->sid, [], false);
		self::assert_response_html();

		$endpoint = $crawler->filter('#studio-palette')->attr('data-endpoint');
		$this->assertNotEmpty($endpoint);

		$parts = parse_url($endpoint);
		$query = [];
		parse_str($parts['query'] ?? '', $query);
		$valid_hash = $query['hash'];
		$query['q'] = 'board';
		$path = ltrim($parts['path'], '/');
		$request_path = $path . '?' . http_build_query($query);

		self::request('GET', $request_path, [], false);
		self::assert_response_status_code(200);

		$response = self::$client->getResponse();
		$this->assertSame('application/json; charset=UTF-8', $response->getHeader('Content-Type'));
		$this->assertSame('no-store', $response->getHeader('Cache-Control'));
		$this->assertSame('nosniff', $response->getHeader('X-Content-Type-Options'));

		$payload = json_decode($response->getContent(), true);
		$this->assertSame(JSON_ERROR_NONE, json_last_error());
		$this->assertArrayHasKey('results', $payload);
		$this->assertArrayHasKey('truncated', $payload);

		$query['hash'] = 'invalid';
		self::request('GET', $path . '?' . http_build_query($query), [], false);
		self::assert_response_status_code(403);
		$this->assertSame(['error' => 'forbidden'], json_decode(self::get_content(), true));

		$query['hash'] = $valid_hash;
		self::request('POST', $path . '?' . http_build_query($query), [], false);
		self::assert_response_status_code(405);
		$this->assertSame('GET', self::$client->getResponse()->getHeader('Allow'));
	}
}
