<?php
namespace Pmc;

class AbstractModel {
	protected $_data = array(
		'verbose' => true,
	);

	public function __construct( $options = array() ) {
		if ( !empty($options) ) {
			// Parse command line options, long options only
			$this->_data = getopt('', $options);

			// Check for presence of required options
			foreach ( $options as $option ) {
				if ( '::' === substr($option, -2) ) {
					$type = 'optional';
					$option = substr($option, 0, -2);
				} elseif ( ':' === substr($option, -1) ) {
					$type = 'required';
					$option = substr($option, 0, -1);
				}
				if ( ( 'required' === $type && !isset($this->_data[$option]) ) || ( 'optional' === $type && isset($this->_data[$option]) && empty($this->_data[$option]) ) ) {
					$errors[] = $type . ' ' . $option . ' needs a value.';
				}
			}

			// If missing some required options, say what and die
			if ( !empty($errors) ) {
				for ( $i=0; $i<count($errors); $i++ ) {
					echo $errors[$i] . "\n";
				}
				die(2);
			}
		}
	}

	public function __call($name, $args) {
		$name = strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
		$parts = explode('_', $name, 2);

		$method = $parts[0];
		$key = (isset($parts[1])) ? $parts[1] : null;

		switch ( $method ) {
			case 'get':
				$return = null;
				if ( isset($this->_data[$key]) ) {
					$return = $this->_data[$key];
				}
				// If no value but a default is specified, set the default and use it
				if ( empty($return) && isset($args[0]) ) {
					$this->_data[$key] = $args[0];
					$return = $args[0];
				}

				return $return;
				break;
			case 'set':
				$value = isset($args[0]) ? $args[0] : null;
				$this->_data[$key] = $value;

				return $this;
				break;
			case 'has':
				return isset($this->_data[$key]);
				break;
			case 'unset':
				unset($this->_data[$key]);
				break;
			default:
				break;
		}

		return null;
	}

	public function __set($key, $value) {
		$this->_data[$key] = $value;
	}

	public function __get($key) {
		if ( isset($this->_data[$key]) ) {
			return $this->_data[$key];
		}

		return null;
	}

	public function __isset($key) {
		return isset($this->_data[$key]);
	}

	public function __unset($key) {
		unset($this->_data[$key]);
	}

	public function printOptions(){
		var_dump($this->_data);
	}

}
