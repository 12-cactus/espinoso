<?php namespace App\Espinoso\Handlers;

//use App\Facades\TranslateGClient;
use Stichoza\GoogleTranslate\TranslateClient;
class TraductorHandler extends EspinosoCommandHandler
{

    protected $ignorePrefix = true;
    protected $pattern = "(?'i'\b(gt)\b)(?'query'.+)$";

    protected $signature   = "gt";
    protected $description = "traductor";

    public function handle(): void
    {

        $tr = new TranslateClient(null,'es');
        //$tr->setSource('en');
        //$tr->setTarget('es');
        $tr->setUrlBase(config('espinoso.url.traductor'));

        $this->espinoso->reply($tr->translate(trim($this->matches['query'])));
    }
}