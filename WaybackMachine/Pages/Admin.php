<?php

    namespace IdnoPlugins\WaybackMachine\Pages {

        /**
         * Default class to serve LinkedIn settings in administration
         */
        class Admin extends \Idno\Common\Page
        {

            function getContent()
            {
                $this->adminGatekeeper(); // Admins only
                $t = \Idno\Core\site()->template();
                $body = $t->draw('admin/waybackmachine');
                $t->__(['title' => 'Wayback Machine', 'body' => $body])->drawPage();
            }

            function postContent() {
                $this->adminGatekeeper(); // Admins only
		
                $enabled = $this->getInput('enabled') == 'true';
                $savebookmarks = $this->getInput('savebookmarks') == 'true';
                \Idno\Core\site()->config->config['waybackmachine'] = [
                    'enabled' => $enabled,
                    'savebookmarks' => $savebookmarks
                ];
                \Idno\Core\site()->config()->save();
                \Idno\Core\site()->session()->addMessage('Your Wayback Machine application details were saved.');
                $this->forward(\Idno\Core\site()->config()->getDisplayURL() . 'admin/waybackmachine/');
            }

        }

    }