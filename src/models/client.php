<?phpnamespace SebRenauld\OAuth2\Models;use Eloquent;use Config;class Client extends Eloquent {	public function __construct()	{		$this->table = Config::get('oauth2-sp::oauth2.clientTable', 'oa_clients');		parent::__construct();	}	public function scopes() {		return $this->belongsToMany("SebRenauld\\OAuth2\\Models\\Scope", Config::get('oauth2-sp::oauth2.clientScopeTable', 'oa_client_scope'));	}}