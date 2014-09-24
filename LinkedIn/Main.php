<?php

    namespace IdnoPlugins\LinkedIn {

        class Main extends \Idno\Common\Plugin {
	    
	    public static $AUTHORIZATION_ENDPOINT = 'https://www.linkedin.com/uas/oauth2/authorization';
	    public static $TOKEN_ENDPOINT         = 'https://www.linkedin.com/uas/oauth2/accessToken';
	    
	    public static function getRedirectUrl() {
		return \Idno\Core\site()->config()->url . 'linkedin/callback';
	    }
	    
	    public static function getState() {
		return md5(\Idno\Core\site()->config()->url . dirname(__FILE__));
	    }

            function registerPages() {
                // Register the callback URL
                    \Idno\Core\site()->addPageHandler('linkedin/callback','\IdnoPlugins\LinkedIn\Pages\Callback');
                // Register admin settings
                    \Idno\Core\site()->addPageHandler('admin/linkedin','\IdnoPlugins\LinkedIn\Pages\Admin');
                // Register settings page
                    \Idno\Core\site()->addPageHandler('account/linkedin','\IdnoPlugins\LinkedIn\Pages\Account');

                /** Template extensions */
                // Add menu items to account & administration screens
                    \Idno\Core\site()->template()->extendTemplate('admin/menu/items','admin/linkedin/menu');
                    \Idno\Core\site()->template()->extendTemplate('account/menu/items','account/linkedin/menu');
            }

            function registerEventHooks() {
		
		// Register syndication services
		\Idno\Core\site()->syndication()->registerService('linkedin', function() {
                    return $this->hasLinkedIn();
                }, ['note','article','image']);
		
                // Push "notes" to LinkedIn
                \Idno\Core\site()->addEventHook('post/note',function(\Idno\Core\Event $event) {
                    $object = $event->data()['object'];
                    if ($this->hasLinkedIn()) {
                        if ($linkedinAPI = $this->connect()) {
                            $linkedinAPI->setAccessToken(\Idno\Core\site()->session()->currentUser()->linkedin['access_token']);
                            $message = strip_tags($object->getDescription());
                            //$message .= "\n\n" . $object->getURL();
                            if (!empty($message) && substr($message,0,1) != '@') {
                                
                                try {
				    				    
				    $result = \Idno\Core\Webservice::post('https://api.linkedin.com/v1/people/~/shares?oauth2_access_token='.\Idno\Core\site()->session()->currentUser()->linkedin['access_token'],
					    '
<share>
<comment>'.htmlentities($message).'</comment>
<visibility> 
<code>anyone</code> 
</visibility>
</share>
'				    ,[
					"Content-Type: application/xml",
				    ]);
				    
				    if ($result['response'] == 201) {
					// Success
					$link = "";
					if (preg_match('/<update-url>(.*?)<\/update-url>/', $result['content'], $matches)) {
					    $link = $matches[1];
					}

					$object->setPosseLink('linkedin',$link);
					$object->save();
					
				    }
				    else
				    {
					\Idno\Core\site()->logging->log("LinkedIn Syndication: " . print_r($result, true), LOGLEVEL_ERROR);
				    }
				    
                                } catch (\Exception $e) {
                                    \Idno\Core\site()->session()->addMessage('There was a problem posting to LinkedIn: ' . $e->getMessage());
                                }
                            }
                        }
                    }
                });

                // Push "articles" to LinkedIn
                \Idno\Core\site()->addEventHook('post/article',function(\Idno\Core\Event $event) {
                    $object = $event->data()['object'];
                    if ($this->hasLinkedIn()) {
                        if ($linkedinAPI = $this->connect()) {
                            $linkedinAPI->setAccessToken(\Idno\Core\site()->session()->currentUser()->linkedin['access_token']);
                            
			    $result = \Idno\Core\Webservice::post('https://api.linkedin.com/v1/people/~/shares?oauth2_access_token='.\Idno\Core\site()->session()->currentUser()->linkedin['access_token'],
					    '
<share>
<content>
<title>'.htmlentities(strip_tags($object->getTitle())).'</title>
<submitted-url>'.htmlentities($object->getUrl()).'</submitted-url>
</content>
<visibility> 
<code>anyone</code> 
</visibility>
</share>
'				    ,[
					"Content-Type: application/xml",
				    ]);
			
			    
			    if ($result['response'] == 201) {
				// Success
				$link = "";
				if (preg_match('/<update-url>(.*?)<\/update-url>/', $result['content'], $matches)) {
				    $link = $matches[1];
				}

				$object->setPosseLink('linkedin',$link);
				$object->save();
			    }
			    else
			    {
				\Idno\Core\site()->logging->log("LinkedIn Syndication: " . print_r($result, true), LOGLEVEL_ERROR);
			    }
			    
                        }
                    }
                });

                // Push "images" to LinkedIn
                \Idno\Core\site()->addEventHook('post/image',function(\Idno\Core\Event $event) {
                    $object = $event->data()['object'];
                    if ($attachments = $object->getAttachments()) {
                        foreach($attachments as $attachment) {
                            if ($this->hasLinkedIn()) {
                                if ($linkedinAPI = $this->connect()) {
				    $linkedinAPI->setAccessToken(\Idno\Core\site()->session()->currentUser()->linkedin['access_token']);

				    
				    $message = strip_tags($object->getDescription());
				    $message .= "\n\nOriginal: " . $object->getURL();
				    
				    $result = \Idno\Core\Webservice::post('https://api.linkedin.com/v1/people/~/shares?oauth2_access_token='.\Idno\Core\site()->session()->currentUser()->linkedin['access_token'],
						    '
	<share>
	<content>
	<title>'.htmlentities(strip_tags($object->getTitle())).'</title>
	<description>'.htmlentities($message).'</description>
	<submitted-url>'.htmlentities($object->getUrl()).'</submitted-url>
	<submitted-image-url>'.$attachment['url'].'</submitted-image-url>
	</content>
	<visibility> 
	<code>anyone</code> 
	</visibility>
	</share>
	'				    ,[
						"Content-Type: application/xml",
					    ]);


				    if ($result['response'] == 201) {
					// Success
					$link = "";
					if (preg_match('/<update-url>(.*?)<\/update-url>/', $result['content'], $matches)) {
					    $link = $matches[1];
					}

					$object->setPosseLink('linkedin',$link);
					$object->save();
				    }
				    else
				    {
					\Idno\Core\site()->logging->log("LinkedIn Syndication: " . print_r($result, true), LOGLEVEL_ERROR);
				    }

				}
                            }
                        }
                    }
                }); 
            }

            /**
             * Connect to LinkedIn
             * @return bool|\LinkedIn
             */
            function connect() {
                if (!empty(\Idno\Core\site()->config()->linkedin)) {
                    require_once (dirname(__FILE__) .'/vendor/PHP-OAuth2/src/OAuth2/Client.php');
                    require_once (dirname(__FILE__) .'/vendor/PHP-OAuth2/src/OAuth2/GrantType/IGrantType.php');
                    require_once (dirname(__FILE__) .'/vendor/PHP-OAuth2/src/OAuth2/GrantType/AuthorizationCode.php');
                    
                    $linkedin = new \OAuth2\Client(
                            \Idno\Core\site()->config()->linkedin['appId'],
                            \Idno\Core\site()->config()->linkedin['secret']
                    );
                    return $linkedin;
                }
                return false;
            }

            /**
             * Can the current user use Linkedin?
             * @return bool
             */
            function hasLinkedIn() {
                if (\Idno\Core\site()->session()->currentUser()->linkedin) {
                    return true;
                }
                return false;
            }

        }

    }
