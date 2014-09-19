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
			    ['code' => $this->getInput('code'), 'redirect_uri' => \IdnoPlugins\LinkedIn\Main::getRedirectUrl(), 'state' => \IdnoPlugins\LinkedIn\Main::getState()])) {
			    
			    $user = \Idno\Core\site()->session()->currentUser();
                            $user->linkedin = ['access_token' => $response['result']['access_token']];
                            $user->save();
                            \Idno\Core\site()->session()->addMessage('Your LinkedIn account was connected.');
			    
			}
                    }
                }
                $this->forward('/account/linkedin/');
            }

        }

    }