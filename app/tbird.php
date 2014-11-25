<?php

if ( ! function_exists('glob_recursive'))
{
    // Does not support flag GLOB_BRACE
    
    function glob_recursive($path, $pattern, $flags = 0)
    {
        $files = glob($path . '/' . $pattern, $flags);
        
        foreach (glob(dirname($path).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
        {        	
            $files = array_merge($files, glob_recursive($dir.'/'.basename($pattern), $flags));
        }
        
        return $files;
    }
}

function FindFiles($path, $pattern)
{
	$Directory = new RecursiveDirectoryIterator($path);
	$Iterator = new RecursiveIteratorIterator($Directory);
	$find_pattern = "/^.+\.{$pattern}$/i";
	$Regex = new RegexIterator($Iterator, $find_pattern, RecursiveRegexIterator::GET_MATCH);
	return $Regex;
}
