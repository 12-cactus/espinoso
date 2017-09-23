<?php namespace App\Espinoso\Handlers;

class GoogleStaticMapsHandler extends EspinosoCommandHandler
{
    protected $ignorePrefix = true;

    /**
     * gsm [param] address
     * -z : numeric zoom
     * example: gsm -z:10 malvinas argentinas
     *          gsm malvinas argentinas
     * @var string
     */
    protected $pattern = "(?'gsm'\b(gsm)\b)\s+(?'params'(\S+:\S+\s+)*)(?'address'.+)$";

    protected $signature   = "[espi] gsm <lugar>";
    protected $description = "te tiro un mapa... tiene algunos params pero me da paja decírtelos";

    /**
     * Default options
     *
     * @var array
     */
    protected $options = [
        'maptype' => 'roadmap',
        'zoom'    => 12,
        'size'    => '600x500',
        'color'   => 'blue'
    ];

    /**
     * @var array
     */
    protected $shortcuts = [
        't' => 'maptype',
        'z' => 'zoom',
        's' => 'size',
        'c' => 'color',
    ];

    /**
     *
     */
    public function handle(): void
    {
        $address  = $this->getAddress();
        $image    = $this->getMap($address, $this->getOptions($address));
        $address .= str_contains(strtolower($address), 'malvinas') ? ', Argentinas!' : '';

        $this->espinoso->replyImage($image, $address);
    }

    /**
     * @return string
     */
    protected function getAddress()
    {
        return $this->matches['address'] ?? '';
    }

    /**
     * It's not a really cool method...
     *
     * @param string $address
     * @return string
     */
    protected function getOptions(string $address)
    {
        $options = collect($this->options);

        if (!empty($this->matches['params'])) {
            $params = isset($this->matches['params']) ? clean_string($this->matches['params']) : '';
            $params = explode(' ', $params);
            $params = collect($params)->mapWithKeys(function ($param) {
                $param = explode(':', $param);
                return [$this->parseParamKey($param[0]) => $param[1]];
            });
            $options = $options->merge($params);
        }
        $color = $options->get('color');
        $options->forget('color');
        $options->put('markers', "color:{$color}|label:X|{$address}");

        return $options->map(function ($value, $key) {
            return "{$key}={$value}";
        })->implode('&');
    }

    /**
     * If key is shortcut, return return origin param name
     *
     * @param string $key
     * @return mixed|string
     */
    protected function parseParamKey(string $key)
    {
        $shortcuts = collect($this->shortcuts);

        return $shortcuts->has($key) ? $shortcuts->get($key) : $key;
    }

    /**
     * Just url...
     *
     * @param $address
     * @param $options
     * @return string
     */
    protected function getMap($address, $options)
    {
        $address = urlencode($address);

        return config('espinoso.url.map') . "?center={$address}&{$options}";
    }
}
