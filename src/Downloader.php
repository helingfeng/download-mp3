<?php

namespace DownloadMp3;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class Downloader
{
    protected $httpClient;

    public function __construct()
    {
        $config['handler'] = HandlerStack::create();
        $config['handler']->push(Middleware::addHeader('csrf', 'P91N3R0HOM9'));
        $config['handler']->push(Middleware::addHeader('Cookie', 'kw_token=P91N3R0HOM9'));
        $config['handler']->push(Middleware::addHeader('referer', 'https://www.kuwo.cn/'));
        $config['handler']->push(Middleware::addHeader('User-Agent', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/103.0.0.0 Safari/537.36'));
        $this->httpClient = new Client($config);
    }

    public function downloadMusic($kw, $save = 1)
    {
        $result = $this->searchMusic($kw, 1, 1);
        if (empty($result['list'])) {
            throw new \Exception('找不到音乐歌单');
        }
        $music = current($result['list']);

        $param['rid'] = $music['rid'];
        $param['type'] = 'convert_url';
        $param['format'] = 'mp3';
        $param['response'] = 'url';
        $options['query'] = $param;
        $response = $this->httpClient->get('https://antiserver.kuwo.cn/anti.s', $options);
        if ($response->getStatusCode() != 200) {
            throw new \Exception($response->getBody()->getContents());
        }
        if ($save) {
            $filename = __DIR__ . "/../data/{$music['name']}.mp3";
            file_put_contents($filename, file_get_contents($response->getBody()->getContents()));
        } else {
            return ['name' => $music['name'], 'mp3' => $response->getBody()->getContents()];
        }
        return $filename;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function searchMusic($kw, $pn = 1, $rn = 20)
    {
        $options['query'] = ['key' => $kw, 'pn' => $pn, 'rn' => $rn];
        $response = $this->httpClient->get('https://www.kuwo.cn/api/www/search/searchMusicBykeyWord', $options);
        $result = $this->jsonDecode($response->getBody()->getContents());
        $response->getBody()->rewind();
        if (!isset($result['code']) || $result['code'] != 200) {
            throw new \Exception($response->getBody()->getContents());
        }
        return $result['data'] ?? [];
    }

    protected function jsonDecode($json)
    {
        return json_decode($json, true);
    }
}