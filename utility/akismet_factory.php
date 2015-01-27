<?php
/**
 *
 * @package phpBB Extension - Akismet
 * @copyright (c) 2015 Matt Gibson gothick@gothick.org.uk
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */
namespace gothick\akismet\utility;

/**
 * Akismet Factory
 */
class akismet_factory
{

	/* @var \phpbb\config\config */
	protected $config;

	/* @var \phpbb\log\log */
	protected $log;

	/* @var \phpbb\user */
	protected $user;

	protected $akismet_api_key;

	/**
	 * Constructor
	 *
	 * Lightweight initialisation of the API key and user ID.
	 * Heavy lifting is done only if the user actually tries
	 * to post a message.
	 *
	 * @param \phpbb\config\config $request        	
	 * @param \phpbb\log\log $log        	
	 * @param \phpbb\user $user        	
	 */
	public function __construct (\phpbb\config\config $config, 
			\phpbb\log\log $log, \phpbb\user $user)
	{
		$this->config = $config;
		$this->log = $log;
		$this->user = $user;
		
		if (! empty($config['gothick_akismet_api_key']))
		{
			$this->akismet_api_key = $config['gothick_akismet_api_key'];
		}
	}

	public function createAkismet ()
	{
		if (empty($this->akismet_api_key))
		{
			$this->log->add('critical', ANONYMOUS, 
					$this->user->data['session_ip'], 
					'AKISMET_LOG_NO_KEY_CONFIGURED');
			return false;
		}
		else
		{
			return new \TijsVerkoyen\Akismet\Akismet($this->akismet_api_key, 
					generate_board_url());
		}
	}
}
