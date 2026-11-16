<?php
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');

$rss_url = 'https://humas.sinjaikab.go.id/v1/rss';

function load_rss($url)
{
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL            => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT      => 'Mozilla/5.0',
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_PROTOCOLS      => CURLPROTO_HTTPS | CURLPROTO_HTTP,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_FOLLOWLOCATION => false,
    ]);
    $data = curl_exec($ch);
    curl_close($ch);

    if ($data === false || empty($data)) {
        return false;
    }

    return @simplexml_load_string($data, 'SimpleXMLElement', LIBXML_NONET | LIBXML_NOCDATA);
}

$rss = load_rss($rss_url);
if (!$rss) {
    echo json_encode(['error' => 'Gagal memuat RSS']);
    exit;
}

$items = [];
foreach ($rss->channel->item as $item) {
    // Ambil thumbnail jika ada
    $thumbnail = '';
    if (isset($item->enclosure['url'])) {
        $thumbnail = (string)$item->enclosure['url'];
    } elseif (isset($item->children('media', true)->thumbnail)) {
        $thumbnail = (string)$item->children('media', true)->thumbnail->attributes()->url;
    } elseif (isset($item->children('media', true)->content)) {
        $thumbnail = (string)$item->children('media', true)->content->attributes()->url;
    }

    $items[] = [
        'title' => (string)$item->title,
        'link' => (string)$item->link,
        'pubDate' => date('d M Y', strtotime((string)$item->pubDate)),
        'thumbnail' => $thumbnail
    ];
}

echo json_encode($items);
