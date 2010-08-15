<?php

/**
 * Generic class for all basic interaction with the API of the third party
 * online service Twitter, www.twitter.com
 *
 * This class requires PHP 5.2 and the cURL-library. Please bear in mind that
 * all in- and outputs are handled in UTF-8!
 *
 * Copyright 2009 by Simon Wippich, www.wippich.org
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 * @version 1.7.1
 * @todo direct message implementations, Twitter search API
 * @author Simon Wippich <development@wippich.org>
 * @copyright Copyright (c) 2009, Simon Wippich
 * @license http://www.gnu.org/licenses/ GNU Lesser General Public License
 */
class Twitter{

	/**
	 * Classvariable for the singleton instance of this class
	 *
	 * @access private
	 * @var Object
	 */
	private static $instance;

	/**
	 * Classvariable for the maximum amount of characters that is allowed
	 * for each tweet
	 *
	 * @access protected
	 * @var Integer
	 */
	protected static $maxPostLength;

	/**
	 * Classvariable for the maximum amount of timeline items
	 *
	 * @since version 1.7 - 2009-07-08
	 * @access private
	 * @var Integer
	 */
	private static $maxTimelineItems;

	/**
	 * Classvariable for the username credential
	 *
	 * @access private
	 * @var String
	 */
	private static $Username;

	/**
	 * Classvariable for the password credential
	 *
	 * @access private
	 * @var String
	 */
	private static $Password;

	/**
	 * Classvariable containing the URL for posting updates to Twitter
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlPost;

	/**
	 * Classvariable containing the URL for following another Twitter user
	 *
	 * @since version 1.6 - 2009-07-06
	 * @access private
	 * @var String
	 */
	private static $twitterUrlFollowUser;

	/**
	 * Classvariable containing the URL for unfollowing another Twitter user
	 *
	 * @since version 1.6 - 2009-07-06
	 * @access private
	 * @var String
	 */
	private static $twitterUrlUnfollowUser;

	/**
	 * Classvariable containing the URL for deleting a single tweet
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlDeletePost;

	/**
	 * Classvariable containing the URL for retrieving the details of a single
	 * Twitter user
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlGetUser;

	/**
	 * Classvariable containing the URL for fetching a single tweet
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlGetPost;

	/**
	 * Classvariable containing the URL for retrieving the friends of a single
	 * Twitter user
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlGetFriends;

	/**
	 * Classvariable containing the URL for fetching one user's followers
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlGetFollowers;

	/**
	 * Classvariable containing the URL for fetching featured Twitter users
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlGetFeaturedUsers;

	/**
	 * Classvariable containing the URL for user mentions
	 *
	 * @since version 1.7 - 2009-07-07
	 * @access private
	 * @var String
	 */
	private static $twitterUrlGetMentionsTimeline;

	/**
	 * Classvariable containing the URL for fetching the usertimeline
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlGetUserTimeline;

	/**
	 * Classvariable containing the URL for fetching the timeline of one single
	 * user's friends
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlGetFriendsTimeline;

	/**
	 * Classvariable containing the URL for fetching the public timeline
	 *
	 * @access private
	 * @var String
	 */
	private static $twitterUrlGetPublicTimeline;

	/**
	 * Constructor for this class
	 *
	 * Private access prevents the direct creation of a new object for keeping
	 * the singleton intact. Use the method getInstance() instead...
	 * 
	 * @access private 
	 */
	private function __construct(){}

	/**
	 * Clone-method
	 *
	 * Private access prevents the direct cloning of an object for keeping
	 * the singleton intact.
	 * 
	 * @access private 
	 */
	private function __clone(){}

	/**
	 * Getter for fetching the singleton-instance of this class.
	 *
	 * This method is used whenever the singleton instance of this
	 * class is required.
	 *
	 * @access public
	 * @return Object The singleton instance
	 */
	final public static function getInstance(){
		// Check if another instance already has been set up
		if(!isset(self::$instance)){
			// Create and initialize a new object
			$class = __CLASS__;
			self::$instance = new $class();
			self::$instance->init();
		}
		// Return the singleton-instance
		return self::$instance;
	}

	/**
	 * Method for performing object-initializations.
	 *
	 * Implementations using this class require an object that is already up and
	 * running, therefore initialization needs to be done after creation of the 
	 * singleton. In this case, the method initializes all required class-
	 * variables that do not have to be modified by the application.
	 *
	 * @access private
	 * @return true
	 */
	private function init(){
		$this->maxPostLength = 140;
		$this->maxTimelineItems = 200;
		$this->twitterUrlPost = 'http://twitter.com/statuses/update.xml?status={STATUS}';
		$this->twitterUrlDeletePost = 'http://twitter.com/statuses/destroy/{ID}.xml';
		$this->twitterUrlGetUser = 'http://twitter.com/users/show/{NAME}.xml';
		$this->twitterUrlGetPost = 'http://twitter.com/statuses/show/{ID}.xml';
		$this->twitterUrlGetFriends = 'http://twitter.com/statuses/friends.xml';
		$this->twitterUrlGetFollowers = 'http://twitter.com/statuses/followers.xml';
		$this->twitterUrlGetFeaturedUsers = 'http://twitter.com/statuses/featured.xml';
		$this->twitterUrlGetMentionsTimeline = 'http://twitter.com/statuses/mentions.xml';
		$this->twitterUrlGetUserTimeline = 'http://twitter.com/statuses/user_timeline.xml';
		$this->twitterUrlGetFriendsTimeline = 'http://twitter.com/statuses/friends_timeline.xml';
		$this->twitterUrlGetPublicTimeline = 'http://twitter.com/statuses/public_timeline.xml';
		$this->twitterUrlFollowUser = 'http://twitter.com/friendships/create/{NAME}.xml';
		$this->twitterUrlUnfollowUser = 'http://twitter.com/friendships/destroy/{NAME}.xml';
		return true;
	}

	/**
	 * Public setter for Twitter userdata
	 *
	 * This method expects valid usercredentials to provide all API
	 * functionalities with the correct logindata.
	 *
	 * @access public
	 * @param $username String Username
	 * @param $password String Password
	 * @return Boolean true in case of success, String in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	public function setUser($username,$password){
		// Set the returning variable to the only possible errorcode by
		// default to save an else-clause
		$returnValue = $this->error(1,__FUNCTION__);
		// Check if the credential-parameters are empty
		if(!empty($username) && !empty($password)){
			// All userinput is bad! Sanitize strings before using them...
			$username = filter_var($username, FILTER_SANITIZE_STRING);
			$password = filter_var($password, FILTER_SANITIZE_STRING);
			// Set the classvariables to the new values
			$this->Username = $username;
			$this->Password = $password;
			// We have a success, return true
			$returnValue = true;
		}
		// Return the result
		return $returnValue;
	}

	/**
	 * Method to validate if the usercredentials already have been set
	 *
	 * @access protected
	 * @return Boolean True or false dependant on available userdata
	 */
	protected function validateUserdata(){
		// Initialize the returning variable as false
		$returnValue = false;
		// Set the returning variable to true in case that usercredentials
		// have been set and are not empty
		if(!empty($this->Username) && !empty($this->Password)){
			$returnValue = true;
		}
		// Return the variable
		return $returnValue;
	}

	/**
	 * Method for posting messages
	 *
	 * @access public
	 * @param String $status
	 * @return Boolean true in case of success, String in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	final public function post($status){
		// Initialize the returning variable
		$returnValue = '';
		// Initialize other required variables
		$response = '';
		$transferUrl = '';
		// Check if we have valid usercredentials
		if($this->validateUserdata() === false){
			// Return an error in case of failed validation
			$returnValue = $this->error(1,__FUNCTION__);
		} else{
			// Check if $status has been set and contains not more than the
			// allowed amount of characters
			if(!empty($status) && strlen($status) <= $this->maxPostLength){
				// All userinput is bad! Sanitize the status-string before
				// using it...
				$status = filter_var($status, FILTER_SANITIZE_STRING);
				// Compose URL for posting to Twitter
				$transferUrl = str_replace(
					'{STATUS}',
					urlencode(stripslashes(urldecode($status))),
					$this->twitterUrlPost
				);
				// Delete the status variable for it is no longer required
				unset($status);
				// Execute the cURL-query and write the results into a new
				// variable; Delete the variable for the transfer-URL
				// afterwards.
				$response = $this->curlQuery($transferUrl,true,true);
				unset($transferUrl);
				// Check if the cURL-process succeeded
				if(is_array($response)){
					// We have an answer from cURL, now process the results
					switch ($response['Statuscode']){
						case 0:
						case 200:
							// We have a success, return true
							$returnValue = true;
							break;
						case 401:
							// Wrong username/password
							$returnValue = $this->error(4,__FUNCTION__);
							break;
						case 404:
							// Invalid URL
							$returnValue = $this->error(5,__FUNCTION__);
							break;
						default:
							// Unknown cause
							$returnValue = $this->error(9,__FUNCTION__);
							break;
					}
				} else{
					// Write errormessage into the returning variable in case
					// of an occuring cURL-error
					$returnValue = $response;
				}
				// Validation is done, delete the cURL-response that is no
				// longer required
				unset($response);
			} else{
				// New statusmessage has been empty or contained more
				// characters than allowed
				$returnValue = $this->error(3,__FUNCTION__);
			}
		}
		// Return true or the regarding errormessage
		return $returnValue;
	}

	/**
	 * Method for following a Twitter user
	 *
	 * @since version 1.6 - 2009-07-06
	 * @param $username String The name of the user you want to follow
	 * @return Boolean true in case of success, String in case of an error
	 */
	final public function followUser($username){
		// Initialize a returning variable
		$returnValue = '';
		try{
			// Initialize other required variables
			$response = '';
			$transferUrl = '';
			// Check if we have valid usercredentials
			if($this->validateUserdata() === false){
				// Throw an exception in case of failed validation
				throw new Exception($this->error(1,__FUNCTION__));
			} else{
				if(empty($username)){
					// Throw an exception in case of an invalid parameter
					throw new Exception($this->error(10,__FUNCTION__));
				} else{
					// Compose URL for following a Twitter user
					$transferUrl = str_replace(
						'{NAME}',
						$username,
						$this->twitterUrlFollowUser
					);
					// Delete the username variable for it is no longer required
					unset($username);
					// Execute the cURL-query and write the results into a new
					// variable; Delete the variable for the transfer-URL
					// afterwards.
					$response = $this->curlQuery($transferUrl,true,true);
					unset($transferUrl);
					// Check if the cURL-process succeeded
					if(is_array($response)){
						// We have an answer from cURL, now process the results
						switch ($response['Statuscode']){
							case 0:
							case 200:
								// We have a success, return true
								$returnValue = true;
								break;
							case 404:
								// Invalid URL
								throw new Exception(
									$this->error(5,__FUNCTION__)
								);
								break;
							case 401:
								// Wrong username/password
								throw new Exception(
									$this->error(4,__FUNCTION__)
								);
								break;
							default:
								// Unknown cause
								throw new Exception(
									$this->error(9,__FUNCTION__)
								);
								break;
						}
					} else{
						// Write errormessage into the returning variable in
						// case of an occuring cURL-error
						$returnValue = $response;
					}
					// Validation is done, delete the cURL-response that is no
					// longer required
					unset($response);
				}
			}
		} catch(Exception $e){
			// Catch all occuring exceptions form the preceding codeblock
			// and write the errormessages into the returning variable
			$returnValue = $e->getMessage();
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for unfollowing a Twitter user
	 *
	 * @since version 1.6 - 2009-07-06
	 * @param $username String The name of the user you want to unfollow
	 * @return Boolean true in case of success, String in case of an error
	 */
	final public function unfollowUser($username){
		// Initialize a returning variable
		$returnValue = '';
		try{
			// Initialize other required variables
			$response = '';
			$transferUrl = '';
			// Check if we have valid usercredentials
			if($this->validateUserdata() === false){
				// Throw an exception in case of failed validation
				throw new Exception($this->error(1,__FUNCTION__));
			} else{
				if(empty($username)){
					// Throw an exception in case of an invalid parameter
					throw new Exception($this->error(10,__FUNCTION__));
				} else{
					// Compose URL for following a Twitter user
					$transferUrl = str_replace(
						'{NAME}',
						$username,
						$this->twitterUrlUnfollowUser
					);
					// Delete the username variable for it is no longer required
					unset($username);
					// Execute the cURL-query and write the results into a new
					// variable; Delete the variable for the transfer-URL
					// afterwards.
					$response = $this->curlQuery($transferUrl,true,true);
					unset($transferUrl);
					// Check if the cURL-process succeeded
					if(is_array($response)){
						// We have an answer from cURL, now process the results
						switch ($response['Statuscode']){
							case 0:
							case 200:
								// We have a success, return true
								$returnValue = true;
								break;
							case 404:
								// Invalid URL
								throw new Exception(
									$this->error(5,__FUNCTION__)
								);
								break;
							case 401:
								// Wrong username/password
								throw new Exception(
									$this->error(4,__FUNCTION__)
								);
								break;
							default:
								// Unknown cause
								throw new Exception(
									$this->error(9,__FUNCTION__)
								);
								break;
						}
					} else{
						// Write errormessage into the returning variable in
						// case of an occuring cURL-error
						$returnValue = $response;
					}
					// Validation is done, delete the cURL-response that is no
					// longer required
					unset($response);
				}
			}
		} catch(Exception $e){
			// Catch all occuring exceptions form the preceding codeblock
			// and write the errormessages into the returning variable
			$returnValue = $e->getMessage();
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for deleting a published post
	 *
	 * @access public
	 * @param $id Integer ID of the post that needs to be deleted
	 * @return Boolean true in case of success, String in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	final public function deletePost($id){
		// Initialize the returning variable
		$returnValue = '';
		// Initialize other required variables
		$response = '';
		$transferUrl = '';
		// Check if we have valid usercredentials
		if($this->validateUserdata() === false){
			// Return an error in case of failed validation
			$returnValue = $this->error(1,__FUNCTION__);
		} else{
			// Cast the ID to string for comparison
			$id = (string) $id;
			// Check if an ID is available and matches the type digit
			if(!empty($id) && (ctype_digit($id)===true)){
				// Compose URL for deleting a Twitter post
				$transferUrl = str_replace(
					'{ID}',
					$id,
					$this->twitterUrlDeletePost
				);
				// Delete the status variable for it is no longer required
				unset($id);
				// Execute the cURL-query and write the results into a new
				// variable; Delete the variable for the transfer-URL
				// afterwards.
				$response = $this->curlQuery($transferUrl,true,true);
				unset($transferUrl);
				// Check if the cURL-process succeeded
				if(is_array($response)){
					// We have an answer from cURL, now process the results
					switch ($response['Statuscode']){
						case 0:
						case 200:
							// We have a success, return true
							$returnValue = true;
							break;
						case 404:
							// Invalid URL
							$returnValue = $this->error(5,__FUNCTION__);
							break;
						case 401:
							// Wrong username/password
							$returnValue = $this->error(4,__FUNCTION__);
							break;
						default:
							// Unknown cause
							$returnValue = $this->error(9,__FUNCTION__);
							break;
					}
				} else{
					// Write errormessage into the returning variable in case
					// of an occuring cURL-error
					$returnValue = $response;
				}
				// Validation is done, delete the cURL-response that is no
				// longer required
				unset($response);
			} else{
				// Throw an error in case that we do not hava a vaild ID-
				// parameter
				$returnValue = $this->error(6,__FUNCTION__);
			}
		}
		return $returnValue;
	}

	/**
	 * Method for fetching the details of a single Twitter-user either by
	 * username or ID
	 *
	 * All returned contents are filtered and consist of the correct data
	 * type. No further validation is required!
	 *
	 * @access public
	 * @param $identifier String: Twitter-ID of the user or his username
	 * @return Array List of userdata or String in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	final public function getUser($identifier){
		// Assume that the parameter matches the type string
		settype($identifier, 'string');
		// Initialize the returning variable
		$returnValue = '';
		// Initialize further variables
		$transferUrl = '';
		$response = '';
		// Check if we have valid usercredentials
		if($this->validateUserdata() === false){
			// Return an error in case of failed validation
			$returnValue = $this->error(1,__FUNCTION__);
		} else{
			// Proceed if the method-parameter has been filled
			if(!empty($identifier)){
				// Compose URL for fetching a single tweet
				$transferUrl = str_replace(
					'{NAME}',
					$identifier,
					$this->twitterUrlGetUser
				);
				unset($identifier);
				// Execute the cURL-query and write the results into a new
				// variable; Delete the variable for the transfer-URL
				// afterwards.
				$response = $this->curlQuery($transferUrl, true);
				unset($transferUrl);
				// Check if the cURL-process succeeded
				if(is_array($response)){
					// We have an answer from cURL, now process the results
					switch($response['Statuscode']){
						case 200:
							// We have a successful response.
							// Check if we are authorized to display the
							// user's details...
							if ($response['Response'] ==
								'You are not authorized to see this user.'
							){
								$returnValue = $this->error(7,__FUNCTION__);
							} else{
								// Parse the XML-response from Twitter and
								// write it into the returning variable
								$returnValue = $this->parseUsers(
									$response['Response'],
									false
								);
							}
							break;
						case 401:
							// Wrong username/password
							$returnValue = $this->error(4,__FUNCTION__);
							break;
						case 404:
							// Invalid URL
							$returnValue = $this->error(5,__FUNCTION__);
							break;
						default:
							// Unknown cause
							$returnValue = $this->error(9,__FUNCTION__);
							break;
					}
				}
			} else{
				// Write errormessage into the returning variable in case of
				// an occuring cURL-error
				$returnValue = $response;
			}
			// Validation is done, delete the cURL-response that is no longer
			// required
			unset($response);
		}
		// Return an array containing the userdata or an errormessage
		return $returnValue;
	}

	/**
	 * Method for fetching the contents of a single tweet
	 * 
	 * @access public
	 * @param Integer $id The ID of the wanted post
	 * @return 
	 */
	final public function getPost($id){
		// Initialize the returning variable
		$returnValue = '';
		// Check if we have valid usercredentials
		if($this->validateUserdata() === false){
			// Return an error in case of failed validation
			$returnValue = $this->error(1,__FUNCTION__);
		} else{
			// Cast the ID to string for comparison
			$id = (string) $id;
			// Check if an ID is available and matches the type digit
			if(!empty($id) && (ctype_digit($id)===true)){
				// Compose URL for fetching a single tweet
				$transferUrl = str_replace(
					'{ID}',
					$id,
					$this->twitterUrlGetPost
				);
				// Execute the cURL-query and write the results into a new
				// variable; Delete the variable for the transfer-URL
				// afterwards.
				$response = $this->curlQuery($transferUrl);
				unset($transferUrl);
				// Check if the cURL-process succeeded
				if(is_array($response)){
					// We have an answer from cURL, now process the results
					switch($response['Statuscode']){
						case 0:
						case 200:
							// We have a successful response.
							// Parse the XML-response from Twitter and
							// write it into the returning variable
							$returnValue = $this->parsePost(
								$response['Response']
							);
							break;
						case 401:
							// Wrong username/password
							$returnValue = $this->error(4,__FUNCTION__);
							break;
						case 403:
							// We have a protected user!
							$returnValue = $this->error(7,__FUNCTION__);
							break;
						case 404:
							// Invalid URL
							$returnValue = $this->error(5,__FUNCTION__);
							break;
						default:
							// Unknown cause
							$returnValue = $this->error(9,__FUNCTION__);
							break;
					}
				} else{
					// Write errormessage into the returning variable in case of
					// an occuring cURL-error
					$returnValue = $response;
				}
				// Validation is done, delete the unused variable
				unset($response);
			} else{
				// Throw an error in case that the parameter has not been filled
				// or does not match the type integer
				$returnValue = $this->error(6,__FUNCTION__);
			}
		}
		// Return an array containing the specified post or an errormessage
		return $returnValue;
	}

	/**
	 * Method for retrieving the friends of a single Twitter user
	 *
	 * @access public
	 * @return Array List of friends or String in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	final public function getFriends(){
		// Fetch the friendslist and return the resulting variable
		$returnValue = $this->getUserList($this->twitterUrlGetFriends);
		return $returnValue;
	}

	/**
	 * Method for retrieving the followers of a single Twitter user
	 *
	 * @access public
	 * @return Array List of followers or String in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	final public function getFollowers(){
		// Fetch the followerlist and return the resulting variable
		$returnValue = $this->getUserList($this->twitterUrlGetFollowers);
		return $returnValue;
	}

	/**
	 * Method for retrieving a list of featured Twitter users
	 *
	 * @access public
	 * @return Array List of featured users or string in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	final public function getFeaturedUsers(){
		// Fetch the list of featured Twitter users and return the resulting
		// variable
		$returnValue = $this->getUserList($this->twitterUrlGetFeaturedUsers);
		return $returnValue;
	}

	/**
	 * Method for retrieving the mentions-timeline of logged in Twitter user
	 *
	 * @since version 1.7 - 2009-07-07
	 * @access public
	 * @param optional integer Amount of mentions to be displayed
	 * @return Array Timeline-list containing mentions or String in case of
	 * failure (for errorcodes and -messages @see error())
	 */
	final public function getMentions($count = 0){
		// Fetch the mention-timeline and return the resulting variable
		$returnValue = $this->getTimeline(
			$this->twitterUrlGetMentionsTimeline,
			$count
		);
		return $returnValue;
	}

	/**
	 * Method for fetching the timeline of a single Twitter user
	 *
	 * @access public
	 * @param optional integer Amount of items to be displayed
	 * @return Array Timeline of the current user or string in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	final public function getUserTimeline($count = 0){
		// Fetch the usertimeline and return the resulting variable
		$returnValue = $this->getTimeline(
			$this->twitterUrlGetUserTimeline,
			$count
		);
		return $returnValue;
	}

	/**
	 * Method for fetching the friendstimeline of a single Twitter user
	 *
	 * @access public
	 * @param optional integer Amount of items to be displayed
	 * @return Array Timeline of the current user or string in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	final public function getFriendsTimeline($count = 0){
		// Fetch the public timeline and return the resulting variable
		$returnValue = $this->getTimeline(
			$this->twitterUrlGetFriendsTimeline,
			$count
		);
		return $returnValue;
	}

	/**
	 * Method for fetching the Twitter public timeline
	 *
	 * @access public
	 * @return Array Timeline of the current user or string in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	final public function getPublicTimeline(){
		// Fetch the public timeline and return the resulting variable
		$returnValue = $this->getTimeline($this->twitterUrlGetPublicTimeline);
		return $returnValue;
	}

	/**
	 * Method for fetching only the usertimeline contents of a single
	 * Twitter user
	 *
	 * @since version 1.1 - 2009-03-09
	 * @access public
	 * @return Array Timelinecontents of the current user or string in case of
	 * failure (for errorcodes and -messages @see error())
	 * @deprecated 1.7.1 - 2009-08-28; Will be removed in 1.8!
	 */
	final public function getUserTimelineContents(){
		// Initialize the returning variable
		$returnValue = '';
		// Check if we have valid usercredentials
		if($this->validateUserdata() === false){
			// Return an error in case of failed validation
			$returnValue = $this->error(1,__FUNCTION__);
		} else{
			// Fetch the usertimeline
			$timeline = $this->getUserTimeline();
			// Check if getUserTimeline threw an error
			if(is_array($timeline)){
				// Calculate the amount of available datasets
				$amount = sizeof($timeline);
				// Reinitialize the returning variable as an array
				$returnValue = array();
				// Walk through all datasets and fetch only the contents
				// in combination with the regarding post-ID
				for($i=0;$i<$amount;$i++){
					$returnValue[$timeline[$i]['Post-ID']] =
						$timeline[$i]['Content'];
				}
				// Delete all variables that are no longer required
				unset($amount);
				unset($timeline);
			} else{
				// Pass through the message in case of occuring errors
				$returnValue = $timeline;
			}
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for fetching only the friendstimeline contents of a single
	 * Twitter user
	 *
	 * @since version 1.1 - 2009-03-09
	 * @access public
	 * @return Array Timelinecontents of the current user or string in case of
	 * failure (for errorcodes and -messages @see error())
	 * @deprecated 1.7.1 - 2009-08-28; Will be removed in 1.8!
	 */
	final public function getFriendsTimelineContents(){
		// Initialize the returning variable
		$returnValue = '';
		// Check if we have valid usercredentials
		if($this->validateUserdata() === false){
			// Return an error in case of failed validation
			$returnValue = $this->error(1,__FUNCTION__);
		} else{
			// Fetch the friendstimeline
			$timeline = $this->getFriendsTimeline();
			// Check if getUserTimeline threw an error
			if(is_array($timeline)){
				// Calculate the amount of available datasets
				$amount = sizeof($timeline);
				// Reinitialize the returning variable as an array
				$returnValue = array();
				// Walk through all datasets and fetch only the contents
				// in combination with the regarding post-ID
				for($i=0;$i<$amount;$i++){
					$returnValue[$timeline[$i]['Post-ID']] =
						$timeline[$i]['Content'];
				}
				// Delete all variables that are no longer required
				unset($amount);
				unset($timeline);
			} else{
				// Pass through the message in case of occuring errors
				$returnValue = $timeline;
			}
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for fetching only tagged usertimeline contents of a single
	 * Twitter user
	 *
	 * @since version 1.3 - 2009-03-17
	 * @access public
	 * @param String $tagName The wanted hashtag
	 * @param optional boolean $returnRejections Decides if only the tagged
	 * contents are returned (false) or if the rejected contents should be
	 * returned, too (true) within a second subarray
	 * @param optional boolean $removeTag specifies whether you want to
	 * remove the hashtag itself before returning the results
	 * @return Array Timelinecontents of the current user or string in case of
	 * failure (for errorcodes and -messages @see error())
	 * @deprecated 1.7.1 - 2009-08-28; Will be removed in 1.8!
	 */
	final public function getUserTimelineTagContents(
		$tagName,
		$returnRejections = false,
		$removeTag = false
	){
		// Use the private method for fetching tagged timeline contents
		// and add the type-parameter; All further parameters are simply
		// passed through, errors and validation are handled by
		// getTimelineTagContents().
		return $this->getTimelineTagContents(
			$tagName,
			0,
			$returnRejections,
			$removeTag
		);
	}

	/**
	 * Method for fetching only tagged friendstimeline contents of a single
	 * Twitter user
	 *
	 * @since version 1.3 - 2009-03-17
	 * @access public
	 * @param String $tagName The wanted hashtag
	 * @param optional boolean $returnRejections Decides if only the tagged
	 * contents are returned (false) or if the rejected contents should be
	 * returned, too (true) within a second subarray
	 * @param optional boolean $removeTag specifies whether you want to
	 * remove the hashtag itself before returning the results
	 * @return Array Timelinecontents of the current user or string in case of
	 * failure (for errorcodes and -messages @see error())
	 * @deprecated 1.7.1 - 2009-08-28; Will be removed in 1.8!
	 */
	final public function getFriendsTimelineTagContents(
		$tagName,
		$returnRejections = false,
		$removeTag = false
	){
		// Use the private method for fetching tagged timeline contents
		// and add the type-parameter; All further parameters are simply
		// passed through, errors and validation are handled by
		// getTimelineTagContents().
		return $this->getTimelineTagContents(
			$tagName,
			1,
			$returnRejections,
			$removeTag
		);
	}

	/**
	 * Method for fetching Twitter userlists
	 *
	 * @access private
	 * @param String $url The URL for the cURL-query
	 * @return Array Userlist or string in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	private function getUserList($url){
		// Initialize the returning variable
		$returnValue = '';
		if(filter_var($url,FILTER_VALIDATE_URL) === false){
			// Throw an error in case that the provided URL is invalid
			$returnValue = $this->error(10,__FUNCTION__);
		} else{
			// Check if we have valid usercredentials
			if($this->validateUserdata() === false){
				// Return an error in case of failed validation
				$returnValue = $this->error(1,__FUNCTION__);
			} else{
				// Initialize an empty variable for the cURL-response
				$response = '';
				// Execute cURL-query to fetch the userlist from Twitter
				$response = $this->curlQuery(
					$url,
					true
				);
				// Check if the cURL-process succeeded
				if(is_array($response)){
					// We have an answer from cURL, now process the results
					switch($response['Statuscode']){
						case 200:
							// We have a success. Parse userlist and fetch
							// the result
							$returnValue = $this->parseUsers(
								$response['Response'],
								true
							);
							break;
						case 401:
							// Wrong username/password
							$returnValue = $this->error(4,__FUNCTION__);
							break;
						case 404:
							// Invalid URL
							$returnValue = $this->error(5,__FUNCTION__);
							break;
						default:
							// Unknown cause
							$returnValue = $this->error(9,__FUNCTION__);
							break;
					}
				} else{
					// Write errormessage into the returning variable in case of
					// an occuring cURL-error
					$returnValue = $response;
				}
				// Validation is done, delete the cURL-response that is no
				// longer required
				unset($response);
			}
		}
		// Return an array containing the userlist or an errormessage
		return $returnValue;
	}

	/**
	 * Method for fetching Twitter timelines
	 *
	 * @access private
	 * @param $url The URL for the cURL-query
	 * @return Array Timeline or string in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	private function getTimeline($url,$count = 0){
		// Initialize the returning variable
		$returnValue = '';
		try{
			// Initialize other required variables
			$response = '';
			$transferUrl = '';
			// Check if we have valid usercredentials
			if($this->validateUserdata() === false){
				// Throw an exception in case of failed validation
				throw new Exception($this->error(1,__FUNCTION__));
			} else{
				if(filter_var($url,FILTER_VALIDATE_URL) === false){
					// Throw an exception in case that the provided URL is
					// invalid
					throw new Exception($this->error(10,__FUNCTION__));
				} else{
					// Fetch the integer value of the count parameter
					$count = intval($count);
					// Set up the transfer URL; Add the count parameter in case
					// of a specific amount of mentions
					if($count > 0){
						// Limit count to the API-maximum
						if($count > $this->maxTimelineItems){
							$count = $this->maxTimelineItems;
						}
						$transferUrl = $url.'?count='.$count;
					} else{
						// Use only the default URL if no count parameter has
						// been set
						$transferUrl = $url;
					}
					// Execute the cURL-query and write the results into a new
					// variable; Delete the variable for the transfer-URL
					// afterwards.
					$response = $this->curlQuery($transferUrl,true,false);
					unset($transferUrl);
					// Check if the cURL-process succeeded
					if(is_array($response)){
						// We have an answer from cURL, now process the results
						switch($response['Statuscode']){
							case 200:
								// We have a success, parse all timeline
								// contents and write the results into the
								// returning variable
								$returnValue = $this->parseTimeline(
									$response['Response']
								);
								break;
							case 404:
								// Invalid URL
								throw new Exception(
									$this->error(5,__FUNCTION__)
								);
								break;
							case 401:
								// Wrong username/password
								throw new Exception(
									$this->error(4,__FUNCTION__)
								);
								break;
							default:
								// Unknown cause
								throw new Exception(
									$this->error(9,__FUNCTION__)
								);
								break;
						}
					} else{
						// Write errormessage into the returning variable in
						// case of an occuring cURL-error
						$returnValue = $response;
					}
					// Validation is done, delete the cURL-response that is no
					// longer required
					unset($response);
				}
			}
		} catch(Exception $e){
			// Catch all occuring exceptions form the preceding codeblock
			// and write the errormessages into the returning variable
			$returnValue = $e->getMessage();
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for fetching only tagged timeline contents of a single Twitter
	 * user
	 *
	 * @since version 1.3 - 2009-03-17
	 * @access public
	 * @param String $tagName The wanted hashtag
	 * @param optional Integer $type Type of timeline to be retrieved (0
	 * equals usertimeline, 1 equals friendstimeline)
	 * @param optional boolean $returnRejections Decides if only the tagged
	 * contents are returned (false) or if the rejected contents should be
	 * returned, too (true) within a second subarray
	 * @param optional boolean $removeTag specifies, whether you want to
	 * remove the hashtag itself before returning the results
	 * @return Array Timelinecontents of the current user or string in case of
	 * failure (for errorcodes and -messages @see error())
	 * @deprecated 1.7.1 - 2009-08-28; Will be removed in 1.8!
	 */
	private function getTimelineTagContents(
		$tagName,
		$type = 0,
		$returnRejections = false,
		$removeTag = false
	){
		// Initialize a returning variable
		$returnValue = '';
		try{
			// Check for valid parameters
			if(
				!is_string($tagName) ||
				empty($tagName) ||
				!is_bool($returnRejections) ||
				!is_bool($removeTag)
			){
				// Throw an exception in case of invalid parameters
				throw new Exception($this->error(10,__FUNCTION__));
			}
			switch($type){
				case 1:
					// Fetch the friendstimeline
					$contents = $this->getFriendsTimelineContents();
					break;
				default:
					// Fetch the usertimeline
					$contents = $this->getUserTimelineContents();
					break;
			}
			// Check if the preceding method threw an error
			if(is_array($contents)){
				// Reinitialize the returning variable as an array containing
				// two subarrays
				$returnValue = array();
				$returnValue[0] = array();
				$returnValue[1] = array();
				// Walk through all available datasets
				foreach($contents as $datasetId => $datasetContent){
					// Check if the provided hashtag is contained within the
					// current dataset
					$appearance = stripos($datasetContent, '#'.$tagName);
					if($appearance !== false) {
						// Remove the hashtag itself from the content if
						// required
						if($removeTag === true){
							$datasetContent = str_replace(
								'#'.$tagName,
								'',
								$datasetContent
							);
							// Trim spaces
							trim($datasetContent);
						}
						// We have an appearance, write the dataset into the
						// first subarray
						$returnValue[0][$datasetId] = $datasetContent;
					} else{
						// No appearance, write the dataset into the second
						// subarray
						$returnValue[1][$datasetId] = $datasetContent;
					}
					// Unset the decision-parameter
					unset($appearance);
				}
				// Delete all variables that are no longer required
				unset($datasetId);
				unset($datasetContent);
				// Decide if we need to return all results or only the ones
				// containing the given tagname
				if($returnRejections !== true){
					// Return only the tagged results
					if(isset($returnValue[0]) && !empty($returnValue[0])){
						$returnValue = $returnValue[0];
					} else{
						// Throw an exception in case of an empty result
						throw new Exception($this->error(13,__FUNCTION__));
					}
				}
			} else{
				// Throw an exception in case of occuring errors while fetching
				// the timeline
				throw new Exception($contents);
			}
			// Delete all variables that are no longer required
			unset($contents);
		} catch(Exception $e){
			// Catch all occuring exceptions form the preceding codeblock
			// and write the errormessages into the returning variable
			$returnValue = $e->getMessage();
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for parsing XML-postdetails that have been returned from Twitter
	 *
	 * @access private
	 * @param $userData String containing the XML-response
	 * @return Array containing the prepared postdata or String in case of
	 * failure (for errorcodes and -messages @see error())
	 */
	private function parsePost($postData){
		// Initialize a returning variable
		$returnValue = '';
		// Check if we have an input at all
		if(!empty($postData)){
			// Parse the XML-data that has been returned from Twitter
			$post = $this->xmlParser($postData);
			// Check if the XML-parser threw an error
			if(is_array($post)){
				// Shrink the result by two levels if possible
				if(isset($post[0]['children'])){
					$post = $post[0]['children'];
					// Check if we have a valid structure
					if(is_array($post)){
						// Reinitialize $postData as an empty array for
						// the returning userdata
						$postData = array();
						// Iterate through all array items
						foreach($post as $dataset){
							// Check if the current item contains child elements
							if(!isset($dataset['children'])){
								// Build up key-value-pairs
								$postData[$dataset['name']] = $dataset['value'];
							// Build up subarrays for all child elements
							} else{
								// Create a temporary array for the key-value-
								// pairs of the children
								$subDatasets = array();
								// Walk through all child-elements
								foreach($dataset['children'] as $subDataset){
									$subDatasets[$subDataset['name']] =
										$subDataset['value'];
								}
								// Write all children into the original array
								$postData[$dataset['name']] = $subDatasets;
								// Delete the temporary array for it is no
								// longer required
								unset($subDatasets);
							}
						}
						// Delete the original input as it is no longer required
						unset($post);
						// Reinitialize the returning variable as an array
						$returnValue = array();
						// Filter the current post and write the processed data
						// into the returning variable
						$returnValue = $this->filterPost($postData);
						// Delete the original datastructure for it is no longer
						// required
						unset($postData);
					} else{
						// Return an errormessage in case that we have an
						// unexpected answer from Twitter
						$returnValue = $this->error(11,__FUNCTION__);
					}
				} else{
					// Return an errormessage in case that we have an
					// unexpected answer from Twitter
					$returnValue = $this->error(11,__FUNCTION__);
				}
			} else{
				// Return an errormessage in case that we have an unexpected
				// answer from Twitter
				$returnValue = $this->error(11,__FUNCTION__);
			}
		} else{
			// Return an errormessage in case that no XML-input is available
			$returnValue = $this->error(8,__FUNCTION__);
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for parsing an XML-userlist that has been returned from Twitter
	 *
	 * @access private
	 * @param $list String XML-structure returned from Twitter
	 * @param $multipleUsers Boolean value that determines if we expect the
	 * data of a single user (false) or multiple users (true)
	 * @return Array List of friends or String in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	private function parseUsers($userList,$multipleUsers){
		// Initialize a returning variable
		$returnValue = '';
		// Check if we have an input at all
		if(!empty($userList)){
			// Parse the XML-data that has been returned from Twitter
			$userData = $this->xmlParser($userList);
			// Return an error if the processed variable is not an array
			if(!is_array($userData)){
				$returnValue = $this->error(11,__FUNCTION__);
			} else{
				// Shrink the result by two levels in case that we expect
				// multiple users (this is caused by the additional tag 'users')
				if($multipleUsers === true){
					$userData = $userData[0]['children'];
				}
				// Initialize a new temporary array for the useritems
				$userList = array();
				// Walk through all users
				foreach($userData as $user){
					// Fetch only relevant userdata
					$user = $user['children'];
					// Initialize a temporary array
					$tempData = array();
					// Iterate through all array items
					foreach($user as $dataset){
						// Check if the current item contains child elements
						if(!isset($dataset['children'])){
							// Build up key-value-pairs
							$tempData[$dataset['name']] = $dataset['value'];
						// Build up subarrays for all child elements
						} else{
							// Create a temporary array for the key-value-pairs
							// of the children
							$subDatasets = array();
							// Walk through all child-elements
							foreach($dataset['children'] as $subDataset){
								$subDatasets[$subDataset['name']] =
									$subDataset['value'];
							}
							// Write all children into the original array
							$tempData[$dataset['name']] = $subDatasets;
							// Delete the temporary array for it is no
							// longer required
							unset($subDatasets);
						}
					}
					// Filter and format all userdata
					$tempData = $this->filterUser($tempData);
					// Delete unused information in case of multiple users
					if($multipleUsers === true){
						unset($tempData['Created_at']);
						unset($tempData['Time_zone']);
						unset($tempData['UTC_offset']);
						unset($tempData['Friends']);
						unset($tempData['Updates']);
						unset($tempData['Favorites']);
						unset($tempData['Following']);
						unset($tempData['Notifications']);
						unset($tempData['Profile']);
					}
					// Write back all processed data
					$userList[] = $tempData;
					// Delete the temporary array
					unset($tempData);
				}
				// Shrink the list by one level in case of single users
				if($multipleUsers === false){
					$userList = $userList[0];
				}
				// Write the entire userlist into the returning variable
				$returnValue = $userList;
				// Delete the userlist
				unset($userList);
			}
		} else{
			// Return an errormessage in case that no XML-input is available
			$returnValue = $this->error(8,__FUNCTION__);
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for parsing an XML-timeline that has been returned from Twitter
	 *
	 * @access private
	 * @param $timeline String XML-structure returned from Twitter
	 * @return Array Timeline or String in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	private function parseTimeline($timeline){
		// Initialize a returning variable
		$returnValue = '';
		// Check if we have an input at all
		if(!empty($timeline)){
			// Parse the XML-data that has been returned from Twitter
			$timeline = $this->xmlParser($timeline);
			// Return an error if the processed variable is not an array
			if(!is_array($timeline)){
				$returnValue = $this->error(11,__FUNCTION__);
			} else{
				// Shrink the result by two levels
				$timeline = $timeline[0]['children'];
				// Initialize an empty array for all timeline datasets
				$tempData = array();
				// Reinitialize the returning variable as an array
				$returnValue = array();
				// Check if timeline items are available
				if(sizeof($timeline)>0){
					// Walk through all post-datasets
					foreach($timeline as $post){
						// Shrink each dataset by one level to skip the 'status'-tag
						$post = $post['children'];
						// Iterate through all array items
						foreach($post as $dataset){
							// Check if the current item contains child elements
							if(!isset($dataset['children'])){
								// Build up key-value-pairs
								$tempData[$dataset['name']] = (
									!empty($dataset['value']) ? $dataset['value'] : ''
								);
								// Build up subarrays for all child elements
							} else{
								// Create a temporary array for the key-value-
								// pairs of the children
								$subDatasets = array();
								// Walk through all child-elements
								foreach($dataset['children'] as $subDataset){
									$subDatasets[$subDataset['name']] = (
										!empty($subDataset['value']) ? $subDataset['value'] : ''
									);
								}
								// Write all children into the original array
								$tempData[$dataset['name']] = $subDatasets;
								// Delete the temporary array for it is no
								// longer required
								unset($subDatasets);
							}
						}
						// Filter the current post and write the processed data
						// into the returning variable
						$returnValue[] = $this->filterPost($tempData);
						// Delete all temporary data
						unset($tempData);
					}
					// Unset the original data for it is no longer required
					unset($timeline);
				} else{
					// Return an errormessage in case that no timeline item is
					// available
					$returnValue = $this->error(13,__FUNCTION__);
				}
			}
		} else{
			// Return an errormessage in case that no XML-input is available
			$returnValue = $this->error(8,__FUNCTION__);
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for filtering and formatting the contents of postdata
	 *
	 * @param $postData Array containing the postdata
	 * @return Array containing the processed data or String in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	private function filterPost($postData){
		// Initialize the returning variable
		$returnValue = '';
		if(!empty($postData) && is_array($postData)){
			// Reinitialize the returning variable as an array
			$returnValue = array();
			// Fetch the integer value of the current post's ID
			$returnValue['Post-ID'] = floatval($postData['id']);
			// Fetch the creation date of the current post formatted as
			// UNIX-timestamp
			$returnValue['Created_at'] = strtotime($postData['created_at']);
			// Fetch the postcontent and sanitize the resulting string
			$returnValue['Content'] = filter_var(
				$postData['text'],
				FILTER_SANITIZE_STRING
			);
			// If this message has been a reply, provide the user-ID that this
			// post is related to
			$returnValue['In_reply_to_user'] = floatval(
				$postData['in_reply_to_user_id']
			);
			if($returnValue['In_reply_to_user'] === 0){
				$returnValue['In_reply_to_user'] = NULL;
			}
			// If this message has been a reply, provide the sanitized
			// username that this post is related to
			$returnValue['In_reply_to_username'] = filter_var(
				$postData['in_reply_to_screen_name'],
				FILTER_SANITIZE_STRING
			);
			$returnValue['In_reply_to_post'] = floatval(
				$postData['in_reply_to_status_id']
			);
			if($returnValue['In_reply_to_post'] === 0) {
				$returnValue['In_reply_to_post'] = NULL;
			}
			// Check if this post has been truncated and provide the boolean
			// value
			if($postData['truncated'] === 'true'){
				$returnValue['Truncated'] = true;
			} else{
				$returnValue['Truncated'] = false;
			}
			// Check if this is a favorite-post and provide the boolean value
			if($postData['favorited'] === 'true'){
				$returnValue['Favorited'] = true;
			} else{
				$returnValue['Favorited'] = false;
			}
			// Fetch the information which source application the user
			// used to post this update and sanitize the resulting string
			$returnValue['Source'] = filter_var(
				$postData['source'],
				FILTER_SANITIZE_STRING
			);
			// Fetch the Twitter-ID of the author
			$returnValue['User']['Twitter-ID'] = floatval(
				$postData['user']['id']
			);
			// Fetch the real name of the current user and sanitize the
			// resulting string
			$returnValue['User']['Name'] = filter_var(
				$postData['user']['name'],
				FILTER_SANITIZE_STRING
			);
			// Fetch the username of the current user and sanitize the
			// resulting string
			$returnValue['User']['Username'] = filter_var(
				$postData['user']['screen_name'],
				FILTER_SANITIZE_STRING
			);
			// Fetch the location and sanitize the resulting string
			$returnValue['User']['Location'] = filter_var(
				$postData['user']['location'],
				FILTER_SANITIZE_STRING
			);
			// Fetch the user's description and sanitize the resulting string
			$returnValue['User']['Description'] = filter_var(
				$postData['user']['description'],
				FILTER_SANITIZE_STRING
			);
			// Fetch the URL of the user's profile image and sanitize the
			// resulting string
			$returnValue['User']['Image'] = filter_var(
				$postData['user']['profile_image_url'],
				FILTER_SANITIZE_URL
			);
			// Replace the array item with an empty string in case of a failed
			// URL-validation
			if($returnValue['User']['Image'] === false){
				$returnValue['User']['Image'] = '';
			}
			// Fetch the sanitized URL of the user's website
			$returnValue['User']['URL'] = filter_var(
				$postData['user']['url'],
				FILTER_SANITIZE_URL
			);
			// Replace the array item with an empty string in case of a failed
			// URL-validation
			if($returnValue['User']['URL'] === false){
				$returnValue['User']['URL'] = '';
			}
			// Fetch the protection status of the current user's profile and
			// return the boolean value in the correct datatype (not formatted
			// as string)
			if($postData['user']['protected'] === 'true'){
				$returnValue['User']['Protected'] = true;
			} else{
				$returnValue['User']['Protected'] = false;
			}
			// Fetch the amount of followers formatted as integer
			$returnValue['User']['Followers'] = intval(
				$postData['user']['followers_count']
			);
			// Delete the original data for it is no longer required
			unset($postData);
		} else{
			$returnValue = $this->error(10,__FUNCTION__);
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for filtering and formatting the contents of userdata
	 *
	 * @param $postData Array containing the userdata
	 * @return Array containing the processed data
	 */
	private function filterUser($userData) {
		// Initialize the returning variable
		$returnValue = '';
		if(!empty($userData) && is_array($userData)){
			// Reinitialize the returning variable as an array
			$returnValue = array();
			// Fetch the integer value of the current user's Twitter-ID
			$returnValue['Twitter-ID'] = floatval($userData['id']);
			// Fetch the creation date of the current profile formatted as
			// UNIX-timestamp
			$returnValue['Created_at'] = strtotime(
				$userData['created_at']
			);
			// Fetch the real name of the current user and sanitize the
			// resulting string
			$returnValue['Name'] = filter_var(
				$userData['name'],
				FILTER_SANITIZE_STRING
			);
			// Fetch the Twitter username of the current user and sanitize the
			// resulting string
			$returnValue['Username'] = filter_var(
				$userData['screen_name'],
				FILTER_SANITIZE_STRING
			);
			// Fetch the location and sanitize the resulting string
			$returnValue['Location'] = filter_var(
				$userData['location'],
				FILTER_SANITIZE_STRING
			);
			// Fetch the user's time zone and UTC offset (formatted
			// as integer value); Sanitize the time zone string to
			// ensure the data type string
			$returnValue['Time_zone'] = filter_var(
				$userData['time_zone'],
				FILTER_SANITIZE_STRING
			);
			$returnValue['UTC_offset'] = intval(
				$userData['utc_offset']
			);
			// Fetch the user's description and sanitize the resulting string
			$returnValue['Description'] = filter_var(
				$userData['description'],
				FILTER_SANITIZE_STRING
			);
			// Fetch the URL of the user's profile image and sanitize the
			// resulting string
			$returnValue['Image'] = filter_var(
				$userData['profile_image_url'],
				FILTER_VALIDATE_URL
			);
			// Replace the array item with an empty string in case of a failed
			// URL-validation
			if($returnValue['Image'] === false){
				$userData['Image'] = '';
			}
			// Fetch the user's website-URL and sanitize the resulting URL
			$returnValue['URL'] = filter_var(
				$userData['url'],
				FILTER_VALIDATE_URL
			);
			// Replace the array item with an empty string in case of a
			// failed URL-validation
			if($returnValue['URL'] === false){
				$returnValue['URL'] = '';
			}
			// Fetch the protection status of the current profile
			// and return the boolean value in the correct datatype
			// (not formatted as string)
			if($userData['protected'] === 'true'){
				$returnValue['Protected'] = true;
			} else{
				$returnValue['Protected'] = false;
			}
			// Fetch the amount of followers and return its integer value
			$returnValue['Followers'] = intval(
				$userData['followers_count']
			);
			// Fetch the amount of friends and return its integer value
			$returnValue['Friends'] = intval(
				$userData['friends_count']
			);
			// Fetch the amount of updates and return its integer value
			$returnValue['Updates'] = intval(
				$userData['statuses_count']
			);
			// Fetch the amount of favorites and return its integer value
			$returnValue['Favorites'] = intval(
				$userData['favourites_count']
			);
			// Check if the currently active user (that has been set
			// by the method setUser()) is following the requested
			// user and return the boolean value
			if($userData['following'] === 'true'){
				$returnValue['Following'] = true;
			} else{
				$returnValue['Following'] = false;
			}
			// Check if the currently active user (that has been set
			// by our method setUser()) has received notifications
			// from the requested user and return the boolean value
			if($userData['notifications'] === 'true'){
				$returnValue['Notifications'] = true;
			} else{
				$returnValue['Notifications'] = false;
			}
			// Retrieve the background color and image of the
			// current user's profile and sanitize the resulting
			// string or URL
			$returnValue['Profile']['Background_color'] = filter_var(
				$userData['profile_background_color'],
				FILTER_SANITIZE_STRING
			);
			$returnValue['Profile']['Background_image'] = filter_var(
				$userData['profile_background_image_url'],
				FILTER_SANITIZE_URL
			);
			// Replace the array item with an empty string in case
			// of a failed URL-validation
			if($returnValue['Profile']['Background_image'] === false){
				$returnValue['Profile']['Background_image'] = '';
			}
			// Check if the background image is a tile
			if($userData['profile_background_tile'] === 'true'){
				$returnValue['Profile']['Background_tile'] = true;
			} else{
				$returnValue['Profile']['Background_tile'] = false;
			}
			// Fetch the colors of the sidebar and sanitize the resulting
			// strings
			$returnValue['Profile']['Sidebar_fill_color'] = filter_var(
				$userData['profile_sidebar_fill_color'],
				FILTER_SANITIZE_STRING
			);
			$returnValue['Profile']['Sidebar_border_color'] = filter_var(
				$userData['profile_sidebar_border_color'],
				FILTER_SANITIZE_STRING
			);
			// Fetch text and link colors of the current profile and sanitize
			// the resulting strings
			$returnValue['Profile']['Text_color'] = filter_var(
				$userData['profile_text_color'],
				FILTER_SANITIZE_STRING
			);
			$returnValue['Profile']['Link_color'] = filter_var(
				$userData['profile_link_color'],
				FILTER_SANITIZE_STRING
			);
			// Check if we have a latestpost at all; Proceed if available...
			if(isset($userData['status']['id'])){
				// Fetch the ID of the user's latest post formatted as
				// integer value
				$returnValue['Latestpost']['Post-ID'] = floatval(
					$userData['status']['id']
				);
				// Fetch the creation time of the user's latest post formatted
				// as UNIX-timestamp
				$returnValue['Latestpost']['Created_at'] = strtotime(
					$userData['status']['created_at']
				);
				// Fetch the latest post from the current user and sanitize
				// the resulting string
				$returnValue['Latestpost']['Content'] = filter_var(
					$userData['status']['text'],
					FILTER_SANITIZE_STRING
				);
				// If this message has been a reply, provide the user-ID
				// that this post is related to
				$returnValue['Latestpost']['In_reply_to_user'] = floatval(
					$userData['status']['in_reply_to_user_id']
				);
				if($returnValue['Latestpost']['In_reply_to_user'] === 0){
					$returnValue['Latestpost']['In_reply_to_user'] = NULL;
				}
				// If this message has been a reply, provide the sanitized
				// username that this post is related to
				$returnValue['Latestpost']['In_reply_to_username'] = filter_var(
					$userData['status']['in_reply_to_screen_name'],
					FILTER_SANITIZE_STRING
				);
				// If this message has been a reply to another post, provide
				// the post-ID that this post is related to
				$returnValue['Latestpost']['In_reply_to_post'] = floatval(
					$userData['status']['in_reply_to_status_id']
				);
				if($returnValue['Latestpost']['In_reply_to_post'] === 0){
					$returnValue['Latestpost']['In_reply_to_post'] = NULL;
				}
				// Check if this is a truncated post and provide the boolean
				// value
				if($userData['status']['truncated'] === 'true'){
					$returnValue['Latestpost']['Truncated'] = true;
				} else{
					$returnValue['Latestpost']['Truncated'] = false;
				}
				// Check if this is a favorite-post and provide the boolean
				// value
				if($userData['status']['favorited'] === 'true'){
					$returnValue['Latestpost']['Favorited'] = true;
				} else{
					$returnValue['Latestpost']['Favorited'] = false;
				}
				// Fetch the information which source application
				// the current user used to post his last update and
				// sanitize the resulting string
				$returnValue['Latestpost']['Source'] = filter_var(
					$userData['status']['source'],
					FILTER_SANITIZE_STRING
				);
			}
			// Delete the original data for it is no longer required
			unset($userData);
		} else{
			$returnValue = $this->error(10,__FUNCTION__);
		}
		// Return the filled variable
		return $returnValue;
	}

	/**
	 * Method for parsing all XML-data that is returned from Twitter
	 *
	 * @access private
	 * @param $xmlData String The XML-data that has been returned from the
	 * Twitter webservice
	 * @return Array containing the transformed XML-data or String in case of
	 * failure (for errorcodes and -messages @see error())
	 */
	private function xmlParser($xmlData){
		// Initialize the returning variable
		$returnValue = array();
		// Initialize the XML-parser on its first run
		if(!is_array($xmlData)){
			$rawXml = $xmlData;
			$parser = xml_parser_create();
			// Set parser options
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
			// Parse all given XML-data
			xml_parse_into_struct($parser, $rawXml, $xmlData, $index);
			// Delete the parserinstance for it is no longer required
			xml_parser_free($parser);
			// Delete variables that are no longer required
			unset($parser);
			unset($rawXml);
			unset($index);
		}
		// Count all items that have been returned by the parser
		$amount = count($xmlData,1);
		// Walk through all these items for processing
		for($i=0;$i<$amount;$i++){
			// Set the current level depth
			if(!isset($xmlData[$i]['level'])) break;
			$level = $xmlData[$i]['level'];
			if($level < 1) break;
			// Mark this level's tag in the array
			$keys[$level] = '['.$i.']';
			// If we have reached a deeper level, sort output and destroy the
			// upper level
			if(count($keys)>$level) unset($keys[count($keys)]);
			// Ignore close tags as they are useless
			if(
				($xmlData[$i]['type'] == "open") ||
				($xmlData[$i]['type'] == "complete")
			){
				// Build up the string for evaluation
				$e = '$returnValue'.implode('[\'children\']',$keys);
				// Set the tag name
				eval($e.'[\'name\'] = $xmlData[$i][\'tag\'];');
				// Set the attributes
				if(!empty($xmlData[$i]['attributes'])){
					eval($e.'[\'attributes\'] = $xmlData[$i][\'attributes\'];');
				}
				// Set the value
				if(!empty($xmlData[$i]['value'])){
					eval($e.'[\'value\'] = trim($xmlData[$i][\'value\']);');
				}
			}
		}
		// Return the processed array
		return $returnValue;
	}

	/**
	 * Method for processing cURL-queries
	 * 
	 * @access private
	 * @param $url String The URL for the cURL-query
	 * @param $authentication Boolean[optional] switch for authentication;
	 * True in case for required authentication (uses classvariables for
	 * usercredentials) or default false for no authentication
	 * @param $post Boolean[optional] switch for POST- or GET-requests.
	 * @return Array containing the results (Statuscode and contents) in case
	 * of success or errormessage formatted as string in case of failure
	 * (for errorcodes and -messages @see error())
	 */
	private function curlQuery($url,$authentication = false,$post = false){
		// Initialize the returning value
		$returnValue = '';
		// Check if the URL-parameter has been supplied
		if(!empty($url)){
			// All user input is bad! Check if the given URL really matches
			// the definition of a URL
			if(filter_var($url, FILTER_VALIDATE_URL) !== false){
				// Check if we have usercredentials in case of authentication
				if(
					($authentication === true) &&
					($this->validateUserdata() === false)
				){
					// Return an error in case of failed validation
					$returnValue = $this->error(1,__FUNCTION__);
				} else{
					// Check if the cURL-library is installed
					if(function_exists('curl_init')){
						// Initialize cURL
						$transfer = curl_init();
						// Initialize variables for the query-results
						$response = '';
						$responseHeader = '';
						$responseStatusCode = '';
						// Set cURL-options:
						// Provide the URL for the upcoming update
						curl_setopt(
							$transfer,
							CURLOPT_URL,
							$url
						);
						// Delete the variable that is no longer needed
						unset($url);
						// Activate the returning cURL-channel to validate
						// success or failure of this process
						curl_setopt(
							$transfer,
							CURLOPT_RETURNTRANSFER,
							1
						);
						// Setup HTTP-header correctly
						curl_setopt(
							$transfer,
							CURLOPT_HTTPHEADER,
							array('Expect:')
						);
						// Add cURL-options for authentication if neccessary
						if($authentication === true){
							// Compose string for submitting the usercredentials
							$userCredentials = $this->Username.':'.$this->Password;
							// Setup credentials for the update
							curl_setopt(
								$transfer,
								CURLOPT_USERPWD,
								$userCredentials
							);
							// Delete the variable that is no longer needed
							unset($userCredentials);
						}
						// Set transfermode to post if required
						if($post === true){
							curl_setopt(
								$transfer,
								CURLOPT_POST,
								true
							);
						} else{
							// Set transfermode to get
							curl_setopt(
								$transfer,
								CURLOPT_POST,
								false
							);
						}
						// Configure cURL to follow redirects
						curl_setopt(
							$transfer,
							CURLOPT_FOLLOWLOCATION,
							true
						);
						// Execute the cURL-query
						$response = curl_exec($transfer);
						// Receive responseheaders
						$responseHeader = curl_getinfo($transfer);
						// Close cURL-session and delete the regarding variable
						curl_close($transfer);
						unset($transfer);
						// Fetch the returning HTTP-statuscode and delete
						// the preceding variable
						$responseStatusCode = $responseHeader['http_code'];
						unset($responseHeader);
						// Check if the user has been rejected
						if($responseStatusCode == '401'){
							// Throw an error in case of missing authentication
							// or wrong userdata
							$returnValue = $this->error(7,__FUNCTION__);
						} else{
							// Return the queryresults as an array
							$returnValue = array();
							$returnValue['Statuscode'] = $responseStatusCode;
							$returnValue['Response'] = $response;
						}
						// Delete all variables that are no longer required
						unset($responseStatusCode);
						unset($response);
					} else{
						// Throw an error in case that cURL is not installed
						$returnValue = $this->error(2,__FUNCTION__);
					}
				}
			} else{
				// Throw an error in case of an invalid URL
				$returnValue = $this->error(5,__FUNCTION__);
			}
		} else{
			// Throw an error in case of an empty URL
			$returnValue = $this->error(8,__FUNCTION__);
		}
		// Return the resulting variable
		return $returnValue;
	}

	/**
	 * Method for errorhandling within this class
	 * 
	 * @access private
	 * @param Integer $errorCode Errorcode
	 * @param optional String $callingMethod Name of the calling method
	 * @return String Errordescription
	 */
	private function error($errorCode,$callingMethod = ''){
		// Add preceding comment to all errormessages; Include methodname
		// if available
		if(is_string($callingMethod) && !empty($callingMethod)){
			$returnValue .=
				'Method "'.
				$callingMethod.
				'" of class "Twitter" reported an error:'.
				chr(13);
		} else{
			$returnValue = 'Class "Twitter" reported an error:'.chr(13);
		}
		// Seperate the different errormessages on behalf of the integer value
		// of the given $errorCode parameter
		switch (intval($errorCode)) {
			case 1:
				$returnValue .= 'Username and/or password not set.';
				break;
			case 2:
				$returnValue .= 'CURL library not installed.';
				break;
			case 3:
				$returnValue .= 'Post value too long/not set.';
				break;
			case 4:
				$returnValue .= 'Invalid username/password.';
				break;
			case 5:
				$returnValue .= 'Invalid URL for CURL request or dataset not found.';
				break;
			case 6:
				$returnValue .= 'Invalid ID-value.';
				break;
			case 7:
				$returnValue .= 'You are not authorized to view this page.';
				break;
			case 8:
				$returnValue .= 'Parameters for the requested method have not been set.';
				break;
			case 9:
				$returnValue .= 'Unknown cause.';
				break;
			case 10:
				$returnValue .= 'Parameters for the requested method are not valid.';
				break;
			case 11:
				$returnValue .= 'Unexpected response from Twitter.';
				break;
			case 12:
				$returnValue .= 'No users available.';
				break;
			case 13:
				$returnValue .= 'The request returned an empty result.';
				break;
			default:
				$returnValue .= 'Invalid errorcode.';
				break;
		}
		// Return the entire errormessage
		return ($returnValue);
	}

}

?>