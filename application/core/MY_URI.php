<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MY_URI extends CI_URI
{

	/**
	 * Deklarasikan properti $config untuk kompatibilitas PHP 8.2
	 * @var CI_Config
	 */
	public $config;

	public function __construct()
	{
		parent::__construct();
	}
}
