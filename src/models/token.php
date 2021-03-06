<?php
namespace SebRenauld\OAuth2\Models;
use Response;
use Config;
use Eloquent;
class Token extends Eloquent {
	public function __construct()
	{
		$this->table = Config::get('oauth2-sp::oauth2.tokenTable', 'oa_tokens');
		parent::__construct();
	}
	public static function generateAuthToken($clientID) {
		$r = new Token();
		$r->code = static::generateEntropy();
		$r->type = 3;
		$r->client_id = $clientID;
		$r->expiry = time()+Config::get("oauth2-sp::oauth2.auth_lifetime");
		$r->save();
		return $r;
	}
	public function hasExpired() {
		return !!($this->expiry < time());
	}
	public static function generateAccessToken($clientID) {
		$refresh = new Token();
		$refresh->code = static::generateEntropy();
		$refresh->type = 2;
		$refresh->client_id = $clientID;
		$refresh->expiry = time()+Config::get("oauth2-sp::oauth2.access_lifetime");
		$refresh->save();
		
		$r = new Token();
		$r->code = static::generateEntropy();
		$r->type = 1;
		$r->client_id = $clientID;
		$r->refresh = $refresh->code;
		$r->expiry = time()+Config::get("oauth2-sp::oauth2.access_lifetime");
		$r->save();
		return $r;
	}
	public function scopes() {
		return $this->belongsToMany("SebRenauld\\OAuth2\\Models\\Scope",Config::get('oauth2-sp::oauth2.tokenScopeTable', 'oa_token_scope'));
	}
	public function printToken() {
		if ($this->type !== 1) {
			return Redirect::to("http://www.icanhascheezburger.com");
		}
		$k = array("access_token" => $this->code,"token_type" => "Bearer", "expires_in" => $this->expiry-time());
		if (!empty($this->refresh)) $k['refresh_token'] = $this->refresh;
		return Response::json($k);
	}
	protected static function generateEntropy() {
		return md5(uniqid(mt_rand(), true));
	}
}
