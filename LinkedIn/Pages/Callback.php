<?php

    /**
     * LinkedIn pages
     */

    namespace IdnoPlugins\LinkedIn\Pages {

        /**
         * Default class to serve the LinkedIn callback
         */
        class Callback extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($linkedin = \Idno\Core\site()->plugins()->get('LinkedIn')) {
                    if ($linkedinAPI = $linkedin->connect()) {

                        if ($response = $linkedinAPI->getAccessToken(\IdnoPlugins\LinkedIn\Main::$TOKEN_ENDPOINT,
                            'authorization_code',
                            ['code' => $this->getInput('code'), 'redirect_uri' => \IdnoPlugins\LinkedIn\Main::getRedirectUrl(), 'state' => \IdnoPlugins\LinkedIn\Main::getState()])
                        ) {

			    // Catch some errors
			    if (($this->getInput('error')) && ($error = $this->getInput('error_description')))
				    throw new \Exception($error);	
		    
                            $user  = \Idno\Core\site()->session()->currentUser();

                            $basic_profile = $linkedinAPI->fetch('https://api.linkedin.com/v1/people/~:(id,first-name,last-name,site-standard-profile-request)', array('oauth2_access_token' => $response['result']['access_token'], 'format' => 'json'));
			    
			    if (!$basic_profile['result']['id'])
			    {
				if ($basic_profile['result']['message'])
				    throw new \Exception($basic_profile['result']['message']);
				else 
				    throw new \Exception("Sorry, there was a problem getting your profile. Does your app have appropriate permissions?");
			    }
			    
                            $id = $basic_profile['result']['id'];
                            $name = $basic_profile['result']['firstName'] . ' ' . $basic_profile['result']['lastName'];
                            $user->linkedin[$id] = ['access_token' => $response['result']['access_token'], 'name' => $name];

                            // Get company pages
                            if (\Idno\Core\site()->config()->multipleSyndicationAccounts()) {
                                $admin_pages = $linkedinAPI->fetch('https://api.linkedin.com/v1/companies', array('oauth2_access_token' => $response['result']['access_token'], 'format' => 'json', 'is-company-admin' => 'true'));
                                if (!empty($admin_pages['result'])) {
                                    if (!empty($admin_pages['result']['values'])) {
                                        foreach($admin_pages['result']['values'] as $company) {
                                            $id = $company['id'];
                                            $name = $company['name'];
                                            $user->linkedin[$id] = ['access_token' => $response['result']['access_token'], 'name' => $name, 'company' => true];
                                        }
                                    }
                                }
                            }

                            $user->save();
                            \Idno\Core\site()->session()->addMessage('Your LinkedIn account was connected.');
			    
			}
                    }
                }
		
                if (!empty($_SESSION['onboarding_passthrough'])) {
                    unset($_SESSION['onboarding_passthrough']);
                    $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'begin/connect-forwarder');
                }
                $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'account/linkedin');
            }

        }

    }