<?php

    /**
     * LinkedIn pages
     */

    namespace IdnoPlugins\LinkedIn\Pages {

        /**
         * Default class to serve LinkedIn-related account settings
         */
        class Account extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if ($linkedin = \Idno\Core\site()->plugins()->get('LinkedIn')) {
                    if (!$linkedin->hasLinkedIn()) {
                        if ($linkedinAPI = $linkedin->connect()) {
                            $login_url = $linkedinAPI->getAuthenticationUrl(
				\IdnoPlugins\LinkedIn\Main::$AUTHORIZATION_ENDPOINT,
				\IdnoPlugins\LinkedIn\Main::getRedirectUrl(),
				['scope' => 'basic', 'response_type' => 'code', 'state' => \IdnoPlugins\LinkedIn\Main::getState()] 
                            );
			    
                        }
                    } else {
                        $login_url = '';
                    }
                }
                $t = \Idno\Core\site()->template();
                $body = $t->__(['login_url' => $login_url])->draw('account/linkedin');
                $t->__(['title' => 'LinkedIn', 'body' => $body])->drawPage();
            }

            function postContent() {
                $this->gatekeeper(); // Logged-in users only
                if (($this->getInput('remove'))) {
                    $user = \Idno\Core\site()->session()->currentUser();
                    $user->linkedin = [];
                    $user->save();
                    \Idno\Core\site()->session()->addMessage('Your LinkedIn settings have been removed from your account.');
                }
                $this->forward('/account/linkedin/');
            }

        }

    }