<?php

    /**
     * LinkedIn pages
     */

    namespace IdnoPlugins\LinkedIn\Pages {

	use \Idno\Core\Idno;
	    
        /**
         * Default class to serve the LinkedIn callback
         */
        class Callback extends \Idno\Common\Page
        {
            
            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                try {
		    if ($linkedin = \Idno\Core\site()->plugins()->get('LinkedIn')) {
			if ($linkedinAPI = $linkedin->connect()) {

			    if ($response = $linkedinAPI->getAccessToken(\IdnoPlugins\LinkedIn\Main::$TOKEN_ENDPOINT,
				'authorization_code',
				['code' => $this->getInput('code'), 'redirect_uri' => \IdnoPlugins\LinkedIn\Main::getRedirectUrl(), 'state' => \IdnoPlugins\LinkedIn\Main::getState()])
			    ) {
				/** Catch various errors */
				if ($response->error) {
				    throw new \Exception($response->error_description);
				}
				
				// Verify access token
				if (!$response->access_token) {
				    throw new \Exception(Idno::site()->language()->_('Sorry, access token is unavailable.'));
				}
				
				if (($this->getInput('error')) && ($error = $this->getInput('error_description'))) {
				    throw new \Exception($error);	
				}
				/**/

				$user  = \Idno\Core\site()->session()->currentUser();

				$result = \Idno\Core\Webservice::get(
                                \IdnoPlugins\LinkedIn\Main::$PEOPLE_URL, 
				    [
					'oauth2_access_token' => $response->access_token, 
					'format' => 'json',
				    ]
				);
				$basic_profile = json_decode($result['content']);

				if (!$basic_profile->id)
				{
				    if ($basic_profile->message)
					throw new \Exception($basic_profile->message);
				    else 
					throw new \Exception(Idno::site()->language()->_("Sorry, there was a problem getting your profile. Does your app have appropriate permissions?"));
				}

				$id = $basic_profile->id;
				$name = $basic_profile->firstName . ' ' . $basic_profile->lastName;
				$user->linkedin[$id] = ['access_token' => $response->access_token, 'name' => $name];

				// Get company pages
				if (\Idno\Core\site()->config()->multipleSyndicationAccounts()) {
				    
				    $result = \Idno\Core\Webservice::get(\IdnoPlugins\LinkedIn\Main::$COMPANIES_URL, array('oauth2_access_token' => $response->access_token, 'format' => 'json', 'is-company-admin' => 'true'));
				    $admin_pages = $result['content'];
				    
				    if (!empty($admin_pages)) {
					if (!empty($admin_pages->values)) {
					    foreach($admin_pages->values as $company) {
						$id = $company['id'];
						$name = $company['name'];
						$user->linkedin[$id] = ['access_token' => $response->access_token, 'name' => $name, 'company' => true];
					    }
					}
				    }
				}

				$user->save();
				\Idno\Core\site()->session()->addMessage(Idno::site()->language()->_('Your LinkedIn account was connected.'));

			    }
			}
		    }
		} catch (\Exception $e) {
		    \Idno\Core\site()->session()->addErrorMessage($e->getMessage());
		}
		
                if (!empty($_SESSION['onboarding_passthrough'])) {
                    unset($_SESSION['onboarding_passthrough']);
                    $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'begin/connect-forwarder');
                }
                $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'account/linkedin');
            }

        }

    }