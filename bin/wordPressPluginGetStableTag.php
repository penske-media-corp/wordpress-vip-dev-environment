<?php
include __DIR__ . '/library/configure.php';
/**
 * Usage:
 * php /path/to/wordPressPluginGetStableTag.php --readmeurl=http://plugins.svn.wordpress.org/debug-bar/trunk/readme.txt --readmepath=/Users/gabrielk/Sites/WordPressBuild/src/wp-content/plugins/debug-bar/readme.txt
 */
$options = array(
    'readmeurl:',
    'readmepath:',
);

$svn = new Pmc\Cli($options);

$readmeUrlHeaders = get_headers( $svn->getReadmeurl(), 1 );
if ( isset($readmeUrlHeaders[0]) && strpos($readmeUrlHeaders[0], '200') !== false ) {
	$readmeRemote = file_get_contents($svn->getReadmeurl());
	preg_match('/Stable tag:(.*?)\n/', $readmeRemote, $matches);
	if ( isset( $matches[1] ) ) {
		$svn->setRemoteTag(trim($matches[1]));
	}
} else {
	// File does not exist
	echo 'Cannot check out or compare plugin tag.' . "\n";
	echo 'Readme URL does not exist: ' . $svn->getReadmeurl() . "\n";
	exit(2);
}


if ( file_exists( $svn->getReadmepath() ) ) {
	$readmeLocal = file_get_contents($svn->getReadmepath());
	preg_match('/Stable tag:(.*?)\n/', $readmeLocal, $matches);
	if ( isset( $matches[1] ) ) {
		$svn->setLocalTag(trim($matches[1]));
	}
}

if ( file_exists( $svn->getReadmepath() ) && ( $svn->getRemoteTag() === $svn->getLocalTag() ) ) {
	echo $svn->getReadmepath() . ' is up to date.' . "\n";
	exit(0);
}

if ( 'trunk' === $svn->getRemoteTag() ) {
	$svn->setTagUrl( str_replace( basename( $svn->getReadmeurl() ), '', $svn->getReadmeurl() ) );
} else {
	$svn->setTagUrl( str_replace( 'trunk/' . basename( $svn->getReadmeurl() ), 'tags/' . $svn->getRemoteTag(), $svn->getReadmeurl() ) );
}

// If stable tag doesn't exist for some reason, just check out trunk
$tagHeaders = get_headers( $svn->getTagUrl(), 1 );
if ( ! isset($tagHeaders[0]) || strpos($tagHeaders[0], '200') === false ) {
	echo 'Stable tag ' . $svn->getRemoteTag() . ' does not exist, checking out trunk instead.' . "\n";
	$svn->setTagUrl( str_replace( basename( $svn->getReadmeurl() ), '', $svn->getReadmeurl() ) );
}

// Set local tag path
$svn->setTagPath( str_replace( '/' . basename( $svn->getReadmepath() ), '', $svn->getReadmepath() ) );

// Remove the existing copy
if ( $svn->getTagPath() && '/' !== $svn->getTagPath() && file_exists( $svn->getTagPath() ) ) {
	echo 'Updating plugin from ' . $svn->getLocalTag() . ' to ' . $svn->getRemoteTag() . "\n";
	$svn->exec('rm -rf ' . $svn->getTagPath());
} else {
	echo 'Checking out plugin version ' . $svn->getRemoteTag() . "\n";
}

// Check out a new copy
$svn->exec('svn checkout ' . $svn->getTagUrl() . ' ' . $svn->getTagPath());

exit(0);

//EOF
