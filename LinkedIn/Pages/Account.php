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
                    //if (!$linkedin->hasLinkedIn()) {
                        if ($linkedinAPI = $linkedin->connect()) {
                            $login_url = $linkedinAPI->getAuthenticationUrl(
                                \IdnoPlugins\LinkedIn\Main::$AUTHORIZATION_ENDPOINT,
                                \IdnoPlugins\LinkedIn\Main::getRedirectUrl(),
                                ['scope' => 'w_share,rw_company_admin,r_basicprofile', 'response_type' => 'code', 'state' => \IdnoPlugins\LinkedIn\Main::getState()]
                            );
			    
                        }

                    //}
                }
                $t = \Idno\Core\site()->template();
                $body = $t->__(['login_url' => $login_url])->draw('account/linkedin');
                $t->__(['title' => 'LinkedIn', 'body' => $body])->drawPage();
            }

            function postContent() {
                $this->gatekeeper(); // Logged-in users only
                if (($account = $this->getInput('remove'))) {
                    $user           = \Idno\Core\site()->session()->currentUser();
                    if (array_key_exists($account, $user->linkedin)) {
                        unset($user->linkedin[$account]);
                    } else {
                        $user->linkedin = [];
                    }
                    $user->save();
                    \Idno\Core\site()->session()->addMessage('Your LinkedIn settings have been removed from your account.');
                }
                $this->forward('/account/linkedin/');
            }

        }

    }