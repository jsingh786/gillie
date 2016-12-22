<?php

class Helper_common
{
    /**
     * function used to move all the files from one directory to another.
     * @param source and destination path
     * @since 07, Nov 2013
     * @author JSINGH7
     */
    public static function rcopy($src, $dst)
    {
        if (file_exists ( $dst ))
            @rmdir ( $dst );
        if (is_dir ( $src )) {
            @mkdir ( $dst );
            $files = scandir ( $src );
            foreach ( $files as $file )
                if ($file != "." && $file != "..")
                    Helper_common::rcopy ( "$src/$file", "$dst/$file" );
        } else if (file_exists ( $src ))
            copy ( $src, $dst );
    }


    /**
     * move all the files from one directory to other directories.
     * @param source and destination path
     * @author hkaur5.
     */
    public static function recurse_copy($src,$dst)
    {
        $dir = opendir($src);
        while(false !== ( $file = readdir($dir)) )
        {
            if (( $file != '.' ) && ( $file != '..' ))
            {
                if ( is_dir($src . '/' . $file) )
                {
                    self::recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else
                {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    /**
     * function used to calculate number of files present with in the directory.
     * @param directory path
     * @return total number of files with in the directory.
     * @since 07, Nov 2013
     * @author Sunny Patial.
     */
    public static function countDirectoryFiles($dir){
        $i = 0;
        if ( $handle = opendir($dir) )
        {
            while ( ($file = readdir($handle)) !== false )
            {
                if (!in_array($file, array('.', '..')) && !is_dir($dir.$file))
                    $i++;
            }
        }
        // prints out how many were in the directory
        return $i;
    }
    /**
     * function used for remove define path directory with all inner files and directories.
     * @param directory path
     * @since 07, Nov 2013
     * @author JSINGH7
     */
    public static function deleteDir($dir)
    {
        // open the directory

        if(is_dir($dir))
        {
            $dhandle = opendir($dir);

            if ($dhandle)
            {
                // loop through it
                while (false !== ( $fname = readdir($dhandle) ) )
                {
                    // if the element is a directory, and
                    // does not start with a '.' or '..'
                    // we call deleteDir function recursively
                    // passing this element as a parameter
                    if (is_dir( "{$dir}/{$fname}" ))
                    {
                        if (($fname != '.') && ($fname != '..'))
                        {
                            //echo "<u>Deleting Files in the Directory</u>: {$dir}/{$fname} <br />";
                            self::deleteDir("$dir/$fname");
                        }
                        // the element is a file, so we delete it
                    }
                    else
                    {
                        // echo "Deleting File: {$dir}/{$fname} <br />";
                        unlink("{$dir}/{$fname}");
                    }
                }
                closedir($dhandle);
            }
            // now directory is empty, so we can use
            // the rmdir() function to delete it
            //	echo "<u>Deleting Directory</u>: {$dir} <br />";
            rmdir($dir);
        }
    }

    /**
     * function used for generating random string
     * containing atleast one special character and one number.
     *
     * @param integer length [ min length should be 8 ]
     * @return string
     * @author Jsingh7
     */
    public static function generateRandomString($len = 8)
    {
        $alpha_string_length = $len-2;
        $numbers = "0123456789";
        $characters = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $spl_chars = "!@#$%^&*";

        for ($p = 0; $p < $alpha_string_length; $p++)
        {
            $alphas = $characters[mt_rand(0, strlen($characters)-1)];
        }

        $nums =  $numbers[mt_rand(0, strlen($numbers)-1)];
        $spls =  $spl_chars[mt_rand(0, strlen($spl_chars)-1)];

        $temp_arr = array($alphas, $nums, $spls);
        shuffle($temp_arr);
        return implode('', $temp_arr);
    }

    /**
     * A time difference function that outputs
     * the time passed in facebook's
     * style: 1 day ago, or 4 months ago.
     *
     * @author jsingh7 [actual author : yasmary at gmail dot com]
     * @param datetime $date [format : "2009-03-04 17:45:18"]
     * @return string
     * @see http://php.net/manual/en/function.time.php
     */
    public static function nicetime($date)
    {

        if(empty($date))
        {
            return "No date provided";
        }

        $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
        $lengths         = array("60","60","24","7","4.35","12","10");

        $now             = time();
        $unix_date         = strtotime($date);

        // check validity of date
        if(empty($unix_date)) {
            return "Bad date";
        }

        // is it future date or past date
        if($now > $unix_date) {
            $difference     = $now - $unix_date;
            $tense         = "ago";

        } else {
            $difference     = $unix_date - $now;
            $tense         = "from now";
        }

        for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
            $difference /= $lengths[$j];
        }

        $difference = round($difference);

        if($difference != 1) {
            $periods[$j].= "s";
        }

        return "$difference $periods[$j] {$tense}";
    }

    /**
     * Returns broken string with symbol (...),
     * if length exceeds.
     *
     * @param string $str
     * @param integer $len
     * @param boolean $tail[optional]
     * @param string $tail_str[optional](by default it is "...")
     * @author jsingh7
     * @version 1.0
     */
    public static function showCroppedText($str, $len, $tail = true, $tail_str = "...")
    {
        if( $tail ):
            $dot_dot_dot = $tail_str;
        else:
            $dot_dot_dot = "";
        endif;
        if(strlen(trim($str)) > $len+1 ):return utf8_decode(substr($str,0, $len)).$dot_dot_dot;
        else:return utf8_decode($str);
        endif;
    }

    /**
     * Returns unique file name.
     *
     * @param str $file_name
     * @author jsingh7
     * @version 1.0
     */
    static public function getUniqueNameForFile( $file_name )
    {
        if( $file_name )
        {
            $temp_img_name = preg_replace( '/\s./', '_', $file_name );
            $chunks = explode( ".", $temp_img_name );
            $concat_img_name = "";
            $numItems = count($chunks);
            $i = 0;
            $ext="";
            foreach($chunks as $key=>$value)
            {
                if(++$i === $numItems)// last element(http://stackoverflow.com/questions/665135/find-the-last-element-of-an-array-while-using-a-foreach-loop-in-php)
                {
                    $ext = ".".$value;
                }
                else
                {
                    $concat_img_name .= $value."_";
                }
            }
            return self::showCroppedText($concat_img_name, 30, false)."_".strtotime(date( "Y-m-d H:i:s" )).$ext;
        }
        else
        {
            throw new Exception("File name not provided in getUniqueNameForFile function!");
        }
    }

    /**
     * Removing all special characters
     * and spaces from string.
     *
     * @author jsingh7
     * @version 1.0
     * @param string $str
     * @return string
     */
    public static function getCleanString( $str )
    {
        $string = str_replace(' ', '', $str); // Removes all spaces.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }



    /**
     * Checks the string for hyperlink and makes hypperlink clickable by adding html in them.
     *
     * @param $text
     * @param $hrefWithDoubleCodes [optional]
     * @param $target_blank [optional]
     * @author hkaur5
     */
    public static function makeHyperlinkClickable( $text, $hrefWithDoubleCodes = 0, $target_blank = 1 )
    {
        if( $target_blank == 1 )
        {
            if( $hrefWithDoubleCodes == 1 )
            {
                $text= preg_replace('/(^|[\n ])([\w]*?)([\w]*?:\/\/[\w].[^ \,\"\n\r\t<]*)/is', '$1$2<a href="$3" target = "_blank">$3</a>', $text);
                $text= preg_replace('/(^|[\n ])([\w]*?)((www)\.[^ \,\"\t\n\r<]*)/is', '$1$2<a href="http://$3" target = "_blank">$3</a>', $text);
                $text= preg_replace('/(^|[\n ])([\w]*?)((ftp)\.[^ \,\"\t\n\r<]*)/is', '$1$2<a href="ftp://$3" >$3</a>', $text);
                $text= preg_replace('/(^|[\n ])([a-z0-9&\-_\.].?)@([\w\-].\.([\w\-\.].).)/i', '$1<a href="mailto:$2@$3" target = "_blank">$2@$3</a>', $text);
            }
            else
            {
                $text= preg_replace("/(^|[\n ])([\w]*?)([\w]*?:\/\/[\w].[^ \,\"\n\r\t<]*)/is", "$1$2<a href='$3' target = '_blank'>$3</a>", $text);
                $text= preg_replace("/(^|[\n ])([\w]*?)((www)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href='http://$3' target = '_blank'>$3</a>", $text);
                $text= preg_replace("/(^|[\n ])([\w]*?)((ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href='ftp://$3' >$3</a>", $text);
                $text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.].?)@([\w\-].\.([\w\-\.].).)/i", "$1<a href='mailto:$2@$3' target = '_blank'>$2@$3</a>", $text);
            }
        }
        else
        {
            if( $hrefWithDoubleCodes == 1 )
            {
                $text= preg_replace('/(^|[\n ])([\w]*?)([\w]*?:\/\/[\w].[^ \,\"\n\r\t<]*)/is', '$1$2<a href="$3">$3</a>', $text);
                $text= preg_replace('/(^|[\n ])([\w]*?)((www)\.[^ \,\"\t\n\r<]*)/is', '$1$2<a href="http://$3">$3</a>', $text);
                $text= preg_replace('/(^|[\n ])([\w]*?)((ftp)\.[^ \,\"\t\n\r<]*)/is', '$1$2<a href="ftp://$3" >$3</a>', $text);
                $text= preg_replace('/(^|[\n ])([a-z0-9&\-_\.].?)@([\w\-].\.([\w\-\.].).)/i', '$1<a href="mailto:$2@$3">$2@$3</a>', $text);
            }
            else
            {
                $text= preg_replace("/(^|[\n ])([\w]*?)([\w]*?:\/\/[\w].[^ \,\"\n\r\t<]*)/is", "$1$2<a href='$3'>$3</a>", $text);
                $text= preg_replace("/(^|[\n ])([\w]*?)((www)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href='http://$3'>$3</a>", $text);
                $text= preg_replace("/(^|[\n ])([\w]*?)((ftp)\.[^ \,\"\t\n\r<]*)/is", "$1$2<a href='ftp://$3' >$3</a>", $text);
                $text= preg_replace("/(^|[\n ])([a-z0-9&\-_\.].?)@([\w\-].\.([\w\-\.].).)/i", "$1<a href='mailto:$2@$3'>$2@$3</a>", $text);
            }
        }

        return($text);
    }

    /**
     * Clear the item from
     * the string.
     *
     * @param string $str
     * @param string or integer $item
     *
     * @return string
     * @author jsingh7
     * @version 1.0
     */
    public static function removeItemFromString($str, $item) {
        $parts = explode(',', $str);

        while(($i = array_search($item, $parts)) !== false) {
            unset($parts[$i]);
        }

        return implode(',', $parts);
    }

    /**
     * Logs message.
     *
     * @param string $msg
     * @param integer $msg_color [color of msg will be according to the color passed.]
     * @param string $filename [optional]
     * @author jsingh7
     */
    public static function logInfo($msg, $msg_color='black', $filename = 'logs/app_log.html')
    {
        $log_msg = "<span style='color:".$msg_color."; display:block;'>".date('d-M-Y H:i:s l T').": ".$msg."</span>";
        file_put_contents($filename, $log_msg.PHP_EOL , FILE_APPEND);
    }

    /**
     * <Add Description>
     *
     * @param string $format
     * @author kaurguneet
     * @date 17/june/2016
     *
     * TODO Make this function more customizable.
     */
    function getCurrentDate($format){
        $Now = new DateTime();
        return $Now->format('Y-m-d');
    }

    /**
     * Create a multidimenional array containing directories inside given directory.
     *
     * @param string $dir_path ( path of directory)
     * @return directory array or false in case no directories are
     * present inside given directory.
     */
    public static function dirToArray($dir_path)
    {

        $result = array();

        $cdir_path = scandir($dir_path);
        foreach ($cdir_path as $key => $value)
        {
            if (!in_array($value,array(".","..")))
            {
                if (is_dir($dir_path . DIRECTORY_SEPARATOR . $value))
                {
                    $result[$value] = self::dirToArray($dir_path . DIRECTORY_SEPARATOR . $value);

                }
                else
                {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }

    /**
     * Returns filename after removing extension of file.
     * @param string $file_name
     * @return string
     * @author hkaur5
     */
    public static function removeFileExtension($file_name)
    {
        //Get file name without extension.------------------------------
        $file_name_exploded_arr = explode('.', $file_name);
        $file_name_exploded_arr_count = count($file_name_exploded_arr);
        unset($file_name_exploded_arr[$file_name_exploded_arr_count - 1]);//Removing video ext.
        return implode('.', $file_name_exploded_arr);

        // Get name - END ------------------------------------------
    }

    /**
     * Pass HTML string and it will return
     * text only. Text extracted will have spaces between words
     * and multiple spaces will be replaced with single space.
     *
     * @param string $htmlString (This is HTML string.)
     * @returns string
     * @author jsingh7
     * @see http://stackoverflow.com/questions/12824899/strip-tags-replace-tags-by-space-rather-than-deleting-them
     * @see http://stackoverflow.com/questions/2368539/php-replacing-multiple-spaces-with-a-single-space
     * @version 1.0
     */
    public static function extractContentFromHTML($htmlString)
    {
        return preg_replace('!\s+!', ' ', preg_replace('#<[^>]+>#', ' ', $htmlString));
    }
    
}