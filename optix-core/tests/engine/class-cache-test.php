<?php
declare(strict_types=1);

namespace OptixCore\Tests\Engine;

use OptixCore\Engine\Cache;
use PHPUnit\Framework\TestCase;

$plugin_root = dirname( __DIR__, 2 );
require_once $plugin_root . '/includes/engine/class-cache.php';

class Cache_Test extends TestCase {
	private Cache $cache;

	protected function setUp(): void {
		parent::setUp();
		$this->cache = Cache::get_instance();
		// Clear both in-memory caches and persistent options
		$this->cache->flush_group();
		foreach ( array_keys( \_wp_test_options() ) as $opt ) {
			if ( str_starts_with( $opt, 'optix_cache_' ) ) {
				\delete_option( $opt );
			}
		}
	}

	public function test_get_instance(): void {
		$instance = Cache::get_instance();
		$this->assertInstanceOf( Cache::class, $instance );
		$this->assertSame( $this->cache, $instance );
	}

	public function test_set_and_get(): void {
		$this->cache->set( 'test_key', 'test_value' );
		$this->assertEquals( 'test_value', $this->cache->get( 'test_key' ) );
	}

	public function test_get_default(): void {
		$result = $this->cache->get( 'non_existent', 'default_value' );
		$this->assertEquals( 'default_value', $result );
	}

	public function test_get_returns_null_default_when_not_specified(): void {
		$result = $this->cache->get( 'non_existent' );
		$this->assertNull( $result );
	}

	public function test_delete(): void {
		$this->cache->set( 'delete_me', 'value' );
		$this->assertEquals( 'value', $this->cache->get( 'delete_me' ) );

		$this->cache->delete( 'delete_me' );
		$this->assertNull( $this->cache->get( 'delete_me' ) );
	}

	public function test_remember_callback_not_called_when_cached(): void {
		$this->cache->set( 'cached_key', 'existing_value' );

		$called = false;
		$result = $this->cache->remember( 'cached_key', function () use ( &$called ) {
			$called = true;
			return 'new_value';
		} );

		$this->assertFalse( $called, 'Callback should not be invoked when value is cached' );
		$this->assertEquals( 'existing_value', $result );
	}

	public function test_remember_callback_called_when_missing(): void {
		$call_count = 0;
		$result = $this->cache->remember( 'new_key', function () use ( &$call_count ) {
			$call_count++;
			return 'computed_value';
		} );

		$this->assertEquals( 1, $call_count, 'Callback should be invoked once when key is missing' );
		$this->assertEquals( 'computed_value', $result );

		$second = $this->cache->remember( 'new_key', function () use ( &$call_count ) {
			$call_count++;
			return 'should_not_run';
		} );
		$this->assertEquals( 1, $call_count, 'Callback should NOT be invoked on second call' );
		$this->assertEquals( 'computed_value', $second );
	}

	public function test_stats(): void {
		$this->cache->set( 'stat_key', 'stat_value' );
		$this->cache->get( 'stat_key' );
		$this->cache->get( 'stat_key' );

		$stats = $this->cache->stats();
		$this->assertArrayHasKey( 'static_count', $stats );
		$this->assertArrayHasKey( 'object_cache_hits', $stats );
		$this->assertArrayHasKey( 'total_keys', $stats );
		$this->assertEquals( 2, $stats['static_count'] );
		$this->assertEquals( 1, $stats['total_keys'] );
	}

	public function test_flush_group(): void {
		$this->cache->set( 'flush_key', 'value' );
		$this->assertEquals( 'value', $this->cache->get( 'flush_key' ) );

		$this->cache->flush_group();

		// flush_group clears in-memory caches; persistent DB options remain
		$stats = $this->cache->stats();
		$this->assertEquals( 0, $stats['total_keys'] );
		$this->assertEquals( 0, $stats['static_count'] );
	}

	public function test_on_option_update_triggers_delete(): void {
		$this->cache->set( 'opt_key', 'opt_value' );
		$this->assertEquals( 'opt_value', $this->cache->get( 'opt_key' ) );

		$this->cache->on_option_update( 'optix_opt_key', 'old', 'new' );

		$this->assertNull( $this->cache->get( 'opt_key' ) );
	}

	public function test_on_option_update_ignores_non_optix(): void {
		$this->cache->set( 'other_key', 'value' );
		$this->cache->on_option_update( 'something_else', 'old', 'new' );
		$this->assertEquals( 'value', $this->cache->get( 'other_key' ) );
	}

	public function test_on_option_added_triggers_delete(): void {
		$this->cache->set( 'added_key', 'value' );
		$this->cache->on_option_added( 'optix_added_key', 'value' );
		$this->assertNull( $this->cache->get( 'added_key' ) );
	}

	public function test_static_cache_hit(): void {
		$this->cache->set( 'hit_key', 'hit_value' );

		$this->cache->get( 'hit_key' );
		$stats_after_first = $this->cache->stats();
		$this->assertEquals( 1, $stats_after_first['static_count'] );

		$this->cache->get( 'hit_key' );
		$stats_after_second = $this->cache->stats();
		$this->assertEquals( 2, $stats_after_second['static_count'] );
	}

	public function test_remember_with_expiry(): void {
		$result = $this->cache->remember( 'exp_key', function () {
			return 'exp_value';
		}, 3600 );

		$this->assertEquals( 'exp_value', $result );
		$this->assertEquals( 'exp_value', $this->cache->get( 'exp_key' ) );
	}

	public function test_set_overwrites_existing(): void {
		$this->cache->set( 'overwrite', 'original' );
		$this->assertEquals( 'original', $this->cache->get( 'overwrite' ) );

		$this->cache->set( 'overwrite', 'updated' );
		$this->assertEquals( 'updated', $this->cache->get( 'overwrite' ) );
	}

	public function test_delete_non_existent_returns_false(): void {
		$result = $this->cache->delete( 'does_not_exist' );
		$this->assertIsBool( $result );
	}

	public function test_flush_group_resets_stats(): void {
		$this->cache->set( 'a', '1' );
		$this->cache->get( 'a' );
		$this->cache->flush_group();
		$stats = $this->cache->stats();
		$this->assertEquals( 0, $stats['static_count'] );
		$this->assertEquals( 0, $stats['total_keys'] );
	}
}
