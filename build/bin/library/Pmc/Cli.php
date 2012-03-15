<?php
namespace Pmc;

class Cli extends AbstractModel {

    public function exec($command) {
        if ( $this->getVerbose() ) {
            echo '# ' . $command . "\n";
        }

        exec($command, $output, $exitCode);

        if ( $this->getVerbose() && !empty($output) ) {
            for ( $i=0; $i < count($output); $i++ ) {
                echo $output[$i] . "\n";
            }
            echo "\n";
        }


        if ( $exitCode > 0 ) {
            exit($exitCode);
        }
    }
}