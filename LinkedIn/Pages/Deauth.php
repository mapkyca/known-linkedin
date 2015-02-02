<?php

    /**
     * LinkedIn pages
     */

    namespace IdnoPlugins\LinkedIn\Pages {

        /**
         * Default class to serve LinkedIn-related account settings
         */
        class Deauth extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->gatekeeper(); // Logged-in users only
                if (($account = $this->getInput('remove'))) {
                    $user           = \Idno\Core\site()->session()->currentUser();
                    if (array_key_exists($account, $user->linkedin)) {
                        unset($user->linkedin[$account]);
                    } else {
                        $user->linkedin = [];
                    }
                    $user->save();
                    if (!empty($user->link_callback)) {
                        error_log($user->link_callback);
                        $this->forward($user->link_callback); exit;
                    }
                }
                $this->forward($_SERVER['HTTP_REFERER']);
            }

            function postContent()
            {
                $this->getContent();
            }

        }

    }