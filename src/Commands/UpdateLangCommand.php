<?php namespace Isdgroup\Isdcore\Commands;

use Illuminate\Console\AppNamespaceDetectorTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Composer;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App;
use Lang;
use Isdgroup\Isdcore\IsdcoreClass as Core;

class UpdateLangCommand extends Command {

    use AppNamespaceDetectorTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'isdgroup:update:lang';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates lang from google doc.';

    protected $core;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->core = new Core();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $pubid = $this->argument('pubid'); //1SJxI629NnhciF3YcSt0vH3btACcXbNtglRy9VkTmGtc
        App::setLocale('ua');
        #echo Lang::get('deseases.hepa_b');
        $data = $this->core->getGoogleDoc($pubid, 0);
        #$path = App::storagePath() . DIRECTORY_SEPARATOR . 'lang.csv';
        #file_put_contents($path, $data);
        $this->core->parseLangCSV($data);
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            	['pubid', InputArgument::REQUIRED, 'Google doc PUB ID.'],
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            //	['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
        ];
    }

}
