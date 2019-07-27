<?php

    /**
     * LinkedIn pages
     */

    namespace IdnoPlugins\LinkedIn\Pages {

        /**
         * Default class to serve LinkedIn settings in administration
         */
        class Admin extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->adminGatekeeper(); // Admins only
                $t = \Idno\Core\site()->template();
                $body = $t->draw('admin/linkedin');
                $t->__(['title' => 'LinkedIn', 'body' => $body])->drawPage();
            }

            function postContent() {
                $this->adminGatekeeper(); // Admins only
                $appId = $this->getInput('appId');
                $secret = $this->getInput('secret');
                \Idno\Core\site()->config()->config['linkedin'] = [
                    'appId' => $appId,
                    'secret' => $secret
                ];
                \Idno\Core\site()->config()->save();
                \Idno\Core\site()->session()->addMessage('Your LinkedIn application details were saved.');
                $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'admin/linkedin/');
            }

        }

    }
