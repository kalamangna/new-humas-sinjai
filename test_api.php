<?php
$client = \Config\Services::curlrequest();
$response = $client->get('http://apps.sinjaikab.go.id/api/pegawai/get_wilayah', [
    'query' => ['tipe' => 'Kelurahan']
]);
echo substr($response->getBody(), 0, 100);
