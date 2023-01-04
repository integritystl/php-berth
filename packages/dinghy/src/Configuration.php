<?php

/**
 * @file
 * Wordpress Configuration Utilities.
 */

namespace Integrity\Dinghy;

use PHLAK\StrGen\Generator;
use PHLAK\StrGen\CharSet;

class Configuration {

    /** @var array An array of the  keys for the authentication keys and salts in the config file */ 
    const AUTHENTICATION_CONFIGS = [
        'AUTH_KEY', 
        'SECURE_AUTH_KEY',  
        'LOGGED_IN_KEY',    
        'NONCE_KEY',
        'AUTH_SALT',
        'SECURE_AUTH_SALT',
        'LOGGED_IN_SALT',
        'NONCE_SALT'
    ];
    // The regex used to match config lines. This will be used with sprintf() function
    // to replace the %s token in the string when finding and replacing configs
    const CONFIG_PATTERN = '#([a-z]*)(\(\s*)([\'\"])(%s)([\'\"])(,)(\s*)([\'\"]?)([^\'\"]*)([\'\"]?)(\s*)(\);)#';
    // The config line replacement string. This will be used with sprintf function
    // to replace the %s tokens for key and value when writing new configs to the file
    const CONFIG_REPLACEMENT = 'define( \'%s\', %s );';
    // The regex to match the line to add our custom configs after
    const CONFIG_CUSTOM_START = '#([\/][*])( Add any custom values between this line and the "stop editing" line. )([*][\/])#';
    // The regex to match the table prefix assignment
    const CONFIG_TABLE_PREFIX = '#\$table_prefix = \'wp_\'\;#';
    // The table prefix config line replacement string. This will be used with sprintf function
    // to replace the %s token for value when writing new config to the file
    const CONFIG_TABLE_PREFIX_REPLACEMENT = '$table_prefix = \'%s\';';
    /** @var string The contents of the congig.json at the root of the project */
    private $configuration;
    /** @var string The contents of the wp-config-sample.php file */
    private $content;
    /** @var Generator Instance of the random string generator */
    private $strGen;
    /** @var array An array of custom config strings */
    private $custom = [];

    /**
     * 
     */
    public function generate()
    {
        $this->configuration = json_decode(file_get_contents("config.json"), true);
        $this->content = file_get_contents('./wordpress/wp-config-sample.php');
        // Initialize the Generator
        $this->strGen = new Generator([CharSet::ALPHA_NUMERIC,'-_=+#?']);
        // Automatically generate auth values. These will be overridden
        // later if a value is provided in the config.json
        $this->generateAuth();
        // replace configs, and/or add custom configs
        $this->generateConfigs();
        // insert the custom configs 
        $this->insertCustomConfigs();
        // add the autoloader
        $this->addAutoload();
        // write the finished config file;
        file_put_contents('./wordpress/wp-config.php', $this->content);
    }

    /**
     * 
     */
    private function addCustomConfig($key, $value)
    {
        $this->custom[] = sprintf(
            self::CONFIG_REPLACEMENT, 
            $key, 
            $this->quoteConfigValue($value)
        );
    }

    /**
     * 
     */
    private function insertCustomConfigs()
    {
        $replacement = <<<END
        /* Add any custom values between this line and the "stop editing" line. */
        END;

        foreach($this->custom as $customConfig) {
            $replacement .= "\n" . $customConfig ;
        }

        // add the HTTPS on configuration
        $https .= "\n";
        $https = 'if ($_SERVER["HTTP_X_FORWARDED_PROTO"] == "https")';
        $https .= "\n";
        $https .= '  $_SERVER["HTTPS"]="on"';
        $https .= "\n";

        $replacement .= $https;


        $this->content = preg_replace(
            self::CONFIG_CUSTOM_START,
            $replacement,
            $this->content,
            1
        );
    }

    /**
     * 
     */
    private function generateConfigs()
    {
        foreach($this->configuration as $key => $value) {
            
            $pattern = sprintf(self::CONFIG_PATTERN, $key);
            $subject = $this->content;

            // check if config is in the default wordpress 
            // wp-config content or the $table_prefix
            if(preg_match($pattern, $subject)) {
                // it is a default config, so replace it
                $this->replaceConfig($key, $value);
            } else if ($key === 'TABLE_PREFIX') {
                $this->replaceTablePrefix($value);
            } else {
                // is is not a default config, so add it in the custom section
                $this->addCustomConfig($key, $value);
            }
        }
    }
    
    /**
     * Authentication unique keys and salts
     */
    private function generateAuth()
    {
        foreach(self::AUTHENTICATION_CONFIGS as $authKey)
        {
            // Generate a random string of characters
            $randStr = $this->strGen->length(32)->generate();

            $this->replaceConfig($authKey, $randStr);
        }
    }

    /**
     * replace a config in wp-config
     */ 
    private function replaceConfig($key, $value)
    {
        // plug the key into the placeholder on the config pattern
        $pattern = sprintf(self::CONFIG_PATTERN, $key);
        // plug the key and value into the placeholders on the replacement pattern
        $replacement = sprintf(
            self::CONFIG_REPLACEMENT, 
            $key, 
            $this->quoteConfigValue($value)
        );
        //assign the content as the subject
        $subject = $this->content;
        // replace the config in the content
        $this->content = preg_replace(
            $pattern,
            $replacement,
            $subject
        );
    }

    /**
     * 
     */
    private function addAutoload()
    {
        $replacement = <<<END
        <?php

        require __DIR__ . '/../vendor/autoload.php';

        END;

        $this->content = preg_replace(
            '#(\<\?php)#',
            $replacement,
            $this->content,
            1
        );
    }

    /**
     * 
     */
    private function replaceTablePrefix($value)
    {
        $replacement = sprintf(self::CONFIG_TABLE_PREFIX_REPLACEMENT, $value);

        $this->content = preg_replace(
            self::CONFIG_TABLE_PREFIX,
            $replacement,
            $this->content,
            1
        );
    }

    // if the value evaluates to a boolean string dont quote,
    // otherwise surround with single quotes
    // also convert booleans to string
    private function quoteConfigValue(string $value)
    {
        $value = (null !== filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE)) ? 
        $value ? 'true' : 'false': 
        "'" . $value . "'";

        return $value;
    }
}