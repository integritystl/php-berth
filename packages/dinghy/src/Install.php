<?php

/**
 * @file
 * Dinghy Utilities.
 */

namespace Integrity\Dinghy;

class Install {

    /**
     * 
     */
    public function execute()
    {
        $this->publishDockerCompose();
        $this->publishConfig();
    }

    public function publishDockerCompose()
    {
        // get the contents of the docker-compose
        $this->content = file_get_contents('./vendor/integrity/dinghy/stubs/docker-compose.yml');


        // write the docker-compose to the project root
        file_put_contents('./docker-compose.yml', $this->content);
    }

    public function publishConfig()
    {
        // get the contents of the docker-compose
        $this->content = file_get_contents('./vendor/integrity/dinghy/stubs/config.json');


        // write the docker-compose to the project root
        file_put_contents('./config.json', $this->content);
    }
}