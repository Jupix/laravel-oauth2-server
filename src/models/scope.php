<?php
namespace SebRenauld\OAuth2\Models;
use Eloquent;
use Config;
class Scope extends Eloquent {
	public function __construct()
	{
		$this->table = Config::get('oauth2-sp::oauth2.scopeTable', 'oa_scopes');
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