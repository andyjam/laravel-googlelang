<?php
/**
 * Created by PhpStorm.
 * User: andy
 * Date: 09.06.2015
 * Time: 16:23
 */
if (!function_exists('get_url_data')) {
    function get_google_doc($id, $gid = 0, $format = 'csv')
    {
        $url = "https://docs.google.com/spreadsheet/pub?key=" . $id . "&single=true&gid=" . ((int)$gid) . "&output=" . $format . "&ndplr=1";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);// . "&z=" . rand(1000000, 99999999);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt(
            $ch,
            CURLOPT_USERAGENT,
            "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5"
        );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
    }
}
if (!function_exists('parse_lang_csv')) {
    function parse_lang_csv($csv, $lang = '', $fname = 'main')
    {
        $path = \App::langPath() . DIRECTORY_SEPARATOR;
        $path_js = \App::publicPath() . DIRECTORY_SEPARATOR . 'js/lang/' . DIRECTORY_SEPARATOR;
        $file = \App::storagePath() . DIRECTORY_SEPARATOR . 'temp.csv';
        file_put_contents($file, $csv);
        $csvFile = fopen($file, 'r');
        while (!feof($csvFile)) {
            $csv = fgetcsv($csvFile);
            $csvArr[] = $csv;
        }
        $csv = $csvArr;
        if (!empty($csv)) {
             /*foreach ($csv as $k => $v) {
                 $csv[$k] = str_getcsv($v);
             }*/
            $langs = $csv[0];
            unset($csv[0], $langs[0]);
            //
            foreach ($langs as $k => $lang) {
                $data = array();
                $count = 0;
                foreach ($csv as $line) {
                    if (empty($line[0])) {
                        continue;
                    }
                    if (count($line) < 2) {
                        continue;
                    }
                    if ($line[0][0] == '#') {
                        continue;
                    }
                    if (isset($line[$k])) {
                        $data[$line[0]] = $line[$k];
                        $count++;
                    }
                }
                $r = write_lang_file($data, $path . $lang . DIRECTORY_SEPARATOR . $fname . '.php');
                $s = write_lang_js_file($data, $path_js . $lang . DIRECTORY_SEPARATOR . $fname . '.js');
                if ($r && $s) {
                    echo 'writing ' . $lang . ': ' . $count . " entries\n";
                } else {
                    echo 'error writing ' . $lang . " (php: $r / js: $s) \n";
                }
            }
        }
    }
}
function getGoogleDoc($id)
{
    $content = file_get_contents("https://docs.google.com/document/pub?id=" . $id);
    $start = strpos($content, '<div id="contents">');
    $end = strpos($content, '<div id="footer">');
    $content = substr($content, $start, ($end - $start));
    // Fix all embeded image references
    $content = str_replace('src="', 'src="https://docs.google.com/document/', $content);
    return $content;
}
if (!function_exists('write_lang_file')) {
    function write_lang_file($array, $file, $format = true)
    {
        $nl = $format ? "\n" : "";
        $tab = $format ? "\t" : "";
        $str = '<?php' . $nl;
        $str .= $tab . 'return array(' . $nl;
        foreach ($array as $k => $v) {
            $k = str_replace("'", "\'", $k);
            $v = str_replace("'", "\'", $v);
            //$v = nl2br($v);
            $str .= $tab . $tab . "'" . $k . "' => '" . $v . "'," . $nl;
        }
        $str .= $tab . ');';
        return file_put_contents($file, $str);
    }
}
if (!function_exists('write_lang_js_file')) {
    function write_lang_js_file($array, $file)
    {
        $pre = "(function(){_l=";
        $post = ";window.lang=window.lang||{};for(var _p in _l){window.lang[_p]=_l[_p]}})();";
        return file_put_contents($file, $pre . json_encode($array) . $post);
    }
}