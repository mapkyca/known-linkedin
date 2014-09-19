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
                // Push "notes" to LinkedIn
               /* \Idno\Core\site()->addEventHook('post/note',function(\Idno\Core\Event $event) {
                    $object = $event->data()['object'];
                    if ($this->hasLinkedIn()) {
                        if ($linkedinAPI = $this->connect()) {
                            $linkedinAPI->setAccessToken(\Idno\Core\site()->session()->currentUser()->linkedin['access_token']);
                            $message = strip_tags($object->getDescription());
                            //$message .= "\n\n" . $object->getURL();
                            if (!empty($message) && substr($message,0,1) != '@') {
                                $params = array(
                                    'message' => $message
                                );
                                if (preg_match('/(?<!=)(?<!["\'])((ht|f)tps?:\/\/[^\s\r\n\t<>"\'\(\)]+)/i',$message,$matches)) {
                                    $params['link'] = $matches[0];  // Set the first discovered link as the match
                                }
                                try {
                                    $result = $linkedinAPI->api('/me/feed', 'POST', $params);
                                    if (!empty($result['id'])) {
										$object->setPosseLink('linkedin','https://linkedin.com/' . $result['id']);
										$object->save();
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
                            $result = $linkedinAPI->api('/me/feed', 'POST',
                                array(
                                    'link' => $object->getURL(),
                                    'message' => $object->getTitle()
                                ));
                            if (!empty($result['id'])) {
								$object->setPosseLink('linkedin','https://linkedin.com/' . $response['id']);
								$object->save();
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
									//$message .= "\n\n" . $object->getURL();
                                    try {
                                        $linkedinAPI->setFileUploadSupport(true);
                                        $response = $linkedinAPI->api(
                                            '/me/photos/',
                                            'post',
                                            array(
                                                'message' => $message,
                                                'url' => $attachment['url']
                                            )
                                        );
                                        if (!empty($response['id'])) {
                                        	$object->setPosseLink('linkedin','https://linkedin.com/' . $response['id']);
                                        	$object->save();
                                        }
                                    }
                                    catch (\LinkedInApiException $e) {
                                        error_log('Could not post image to LinkedIn: ' . $e->getMessage());
                                    }
                                }
                            }
                        }
                    }
                }); */
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
             * Can the current user use Twitter?
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
