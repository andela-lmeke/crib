<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use LaravelBook\Ardent\Ardent;

class User extends Ardent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	public $dummy = "this is a dummy";
	

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	protected $fillable = array('email', 'username');

	protected $guarded = array('id', 'password');

	public static $rules = array(
		'username' => 'required|between:4,16',
		'email' => 'required|email',
		'password' => 'required|alpha_num|min:8|confirmed',
  		'password_confirmation' => 'required|alpha_num|min:8'
	);

	public $autoPurgeRedundantAttributes = true;

	public function isValid($data)
	{
		$validation = Validator::make($data, static::$rules);

		if ($validation->passes()) return true;

		$this->errors = $validation->messages();

		return false;
	}

	public function posts()
	{
		return $this->hasMany('Post');
	}

	public function follow()
	{
	  return $this->belongsToMany('User', 'user_follows', 'user_id', 'follow_id');
	}

	public function followers()
	{
	  return $this->belongsToMany('User', 'user_follows', 'follow_id', 'user_id');
	}

	public function clique(){
 		return $this->belongsToMany('Clique');
	}
	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the token value for the "remember me" session.
	 *
	 * @return string
	 */
	public function getRememberToken()
	{
		return $this->remember_token;
	}

	/**
	 * Set the token value for the "remember me" session.
	 *
	 * @param  string  $value
	 * @return void
	 */
	public function setRememberToken($value)
	{
		$this->remember_token = $value;
	}

	/**
	 * Get the column name for the "remember me" token.
	 *
	 * @return string
	 */
	public function getRememberTokenName()
	{
		return 'remember_token';
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

}
