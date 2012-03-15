<?php
namespace Pmc;

class Svn extends Cli {

	public function updatePath($localPath = '.') {
        $output = $this->exec('cd ' . $localPath . '; svn update ' . $this->getSvnAuthenticationString());
	}

    public function svnTag($source, $tagName, $tagPath = '/tags') {
        // If $source doesn't already contain a full path, assume we should add the currently-set svn host
        if ( strpos($source, '://') === false ) {
            $source = $this->getSvnHost() . $source;
        }

        // If $destination doesn't already contain a full path, assume we should add the currently-set svn host
        if ( strpos($tagName, '://') === false ) {
            $destination = $this->getSvnHost() . $tagPath . '/' . $tagName;
        } else {
            $destination = $tagName;
        }

        $commitMessage = $this->getSitename() . ': tagging build ' . $tagName;

        $output = $this->exec('svn copy --message="' . $commitMessage . '" ' . trim($source) . ' ' . trim($destination) . $this->getSvnAuthenticationString());

        // Expect $output[1] to contain the "Committed revision 123." message
        if ( isset($output[1]) ) {
            return $output[1];
        }
    }


    public function getPointerDestination($svnPath = '') {
        if ( empty($svnPath) ) {
            $svnPath = $this->getSvnPath();
        }

        // If $svnPath doesn't already contain a full path, assume we should add the currently-set svn host
        if ( strpos($svnPath, '://') === false ) {
            $svnPath = $this->getSvnHost() . $svnPath;
        }

        $this->exec('svn propget svn:externals --xml --revision=' . $this->getSvnRevision() . ' ' . $svnPath . $this->getSvnAuthenticationString());

        if ( !is_null($this->getXmlOutput()) && isset($this->getXmlOutput()->target->property) ) {
            $propertyParts = explode(' ', $this->getXmlOutput()->target->property->__toString());
			$this->_data['pointer_destination'] = trim(str_replace($this->getSvnHost(), '', array_pop($propertyParts)));
        }

        return (isset($this->_data['pointer_destination'])) ? $this->_data['pointer_destination'] : null;
    }


    public function checkRevision($revision = 'HEAD', $svnPath = '') {
        if ( empty($svnPath) ) {
            $svnPath = $this->getSvnPath();
        }

        // If $svnPath doesn't already contain a full path, assume we should add the currently-set svn host
        if ( strpos($svnPath, '://') === false ) {
            $svnPath = $this->getSvnHost() . $svnPath;
        }

        $this->exec('svn info --xml --revision=' . $revision . ' ' . $svnPath . $this->getSvnAuthenticationString());

        if ( !is_null($this->getXmlOutput()) && isset($this->getXmlOutput()->entry->commit) ) {
            return $this->getXmlOutput()->entry[0]->commit->attributes()->revision->__toString();
        }

        return null;
    }


    public function setXmlOutput($output) {
        libxml_use_internal_errors(true);

        $xml = simplexml_load_string(implode("\n", $output));

        if ( !$xml ) {
            $errors = libxml_get_errors();

            foreach ( $errors as $error ) {
                echo $error->message;
            }

            libxml_clear_errors();

            trigger_error('Error parsing XML', E_USER_ERROR);
        }

        $this->_data['xml_output'] = $xml;


        return $this;
    }

    public function getSvnAuthenticationString() {
        $string = ' --username="' . $this->getSvnUsername() . '"';
        $string .= ' --password="' . $this->getSvnPassword() . '"';
        $string .= ' --non-interactive';

        return $string;
    }

    public function exec($command) {
        if ( $this->getVerbose() ) {
            echo '# ' . $command . "\n";
        }

        // Clear any existing output
        unset($output);

        exec($command, $output, $exitCode);

        if ( strpos($command, '--xml') !== false ) {
            $this->setXmlOutput($output);
        }

        if ( $this->getVerbose() && !empty($output) ) {
            for ( $i=0; $i < count($output); $i++ ) {
                echo $output[$i] . "\n";
            }
            echo "\n";
        }


        if ( $exitCode > 0 ) {
            exit($exitCode);
        }

        return $output;
    }
}