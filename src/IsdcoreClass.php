<?php

namespace Isdgroup\Isdcore;

class IsdcoreClass
{
    /**
     * Create a new Skeleton Instance
     */
    public function __construct()
    {
        // constructor body
    }

    /**
     * Friendly welcome
     *
     * @param string $phrase Phrase to return
     *
     * @return string Returns the phrase passed in
     */
    public function echoPhrase($phrase)
    {
        return $phrase;
    }

    /**
     * Get Google doc
     *
     * @param $id
     * @param int $gid
     * @param string $format
     * @return mixed
     * @throws \Exception
     */
    public function getGoogleDoc($id, $gid = 0, $format = 'csv')
    {
        $client = new \GuzzleHttp\Client();
        $url = "https://docs.google.com/spreadsheet/pub?key=" . $id . "&single=true&gid=" . ((int)$gid) . "&output=" . $format . "&ndplr=1";
        $res = $client->get(
            $url,
            [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.5 (KHTML, like Gecko) Chrome/19.0.1084.52 Safari/536.5',
                ]
            ]
        );
        if ($res->getStatusCode() != '200') {
            throw new \Exception('Request status expected 200 but received ' . $res->getStatusCode(), 500);
        }

        return $res->getBody();
    }

    /**
     *
     * Saving lang files from $res
     *
     * @param string $res
     * @param string $lang
     * @param string $fname
     * @return bool
     * @throws \Exception
     */
    public function parseLangCSV($res, $lang = '', $fname = 'main')
    {
        $path = \App::langPath() . DIRECTORY_SEPARATOR;
        $path_js = \App::publicPath() . DIRECTORY_SEPARATOR . 'js/lang/' . DIRECTORY_SEPARATOR;
        $file = \App::storagePath() . DIRECTORY_SEPARATOR . 'temp.csv';
        file_put_contents($file, $res);
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
            //dd($langs);
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
                $r = $this->writeLangFile($data, $path . $lang . DIRECTORY_SEPARATOR , $fname . '.php');
                $s = $this->writeLangJsFile($data, $path_js . $lang . DIRECTORY_SEPARATOR , $fname . '.js');
                if ($r && $s) {
                    echo 'writing ' . $lang . ': ' . $count . " entries\n";
                } else {
                    echo 'error writing ' . $lang . " (php: $r / js: $s) \n";
                }
            }

            return true;
        } else {
            throw new \Exception('Empty Data', 500);
        }
    }

    /**
     * @param $array
     * @param $dir
     * @param $file
     * @param bool $format
     * @return int
     */
    public function writeLangFile($array, $dir,  $file, $format = true)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
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

        return file_put_contents($dir.$file, $str);
    }

    /**
     * @param $array
     * @param $dir
     * @param $file
     * @return int
     */
    public function writeLangJsFile($array, $dir, $file)
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        $pre = "(function(){_l=";
        $post = ";window.lang=window.lang||{};for(var _p in _l){window.lang[_p]=_l[_p]}})();";

        return file_put_contents($dir.$file, $pre . json_encode($array) . $post);
    }
}
