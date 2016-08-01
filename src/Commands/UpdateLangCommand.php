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
        $fname = $this->argument('fname'); //main
	if(empty($fname)) $fname = 'main';
        App::setLocale('ua');
        $data = $this->core->getGoogleDoc($pubid, 0);
        $this->core->parseLangCSV($data, '', $fname);
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
            	['fname', InputArgument::OPTIONAL, 'Resource file name.'],
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
