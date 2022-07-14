<?php

namespace App\PAM;

use App\Settings\ParentSetting;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Psr\Http\Message\ResponseInterface;

class ParentManager
{
    static string $getFacadeAccessor = 'core.support.parents';
    static string $sessionFlashKey = 'core.support.parent.session';
    protected array $requestOptions = [];

    private Client $_client;
    private ParentSetting $_setting;

    public function __construct()
    {
        $this->_client = new Client([
            'http_errors' => false
        ]);
        $this->_setting = app(ParentSetting::class);
    }

    protected function setRequestOption($key, $value): static
    {
        Arr::set($this->requestOptions, $key, $value);

        return $this;
    }

    protected function makeRequest($endpoint, bool $collect = true, $method = 'GET'): Collection|ResponseInterface
    {
        $response = $this->_client->request($method, $endpoint, $this->requestOptions);

        return $collect
            ? new Collection(json_decode($response->getBody()->getContents(), true))
            : $response;
    }

    public function withAuth(): static
    {
        return $this
            ->setRequestOption('query.username', $this->_setting->auth_user)
            ->setRequestOption('query.password', $this->_setting->auth_password);
    }

    public function withQuantity($quantity = 1): static
    {
        return $this->setRequestOption('query.count', $quantity);
    }

    public function withType($type): static
    {
        return $this->setRequestOption('query.type', $type);
    }

    public function getCount($type = null): Collection|int
    {
        if (Session::has(self::$sessionFlashKey)) {
            $data = Session::get(self::$sessionFlashKey);
        } else {
            $data = $this->makeRequest($this->_setting->api_count);
            Session::flash(self::$sessionFlashKey, $data);
        }

        return filled($type)
            ? $data->get($type, 0)
            : $data;
    }

    public function getMail(): Collection
    {
        return new Collection($this->makeRequest($this->_setting->api_create)->get('message', []));
    }
}
