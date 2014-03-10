<?php
namespace SebRenauld\OAuth2\Models;
use Eloquent;
use Config;
class Scope extends Eloquent {
	public function __construct()
	{
		$this->table = Config::get('oauth2::oauth2.scopeTable', 'oauth_scope');
		parent::__construct();
	}
	public static function listScopes() {
		$r = array();
		foreach (static::get() as $v) {
			$r[] = $v->name;
		}
		return $r;
	}
}