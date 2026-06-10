<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/api/debug/admin-token?secret=dev', 'GET');
$response = $kernel->handle($request);
$content = $response->getContent();
$data = json_decode($content, true);
if (!isset($data['token'])) {
    echo "Failed to get token: \n" . $content . "\n";
    exit;
}
$token = $data['token'];

$urls = [
    '/api/admin/dashboard/stats',
    '/api/admin/users',
    '/api/admin/plans',
    '/api/admin/payments',
    '/api/admin/reports'
];

foreach ($urls as $url) {
    $req = Illuminate\Http\Request::create($url, 'GET');
    $req->headers->set('Authorization', 'Bearer ' . $token);
    $res = $kernel->handle($req);
    echo "=====================================\n";
    echo "GET $url\n";
    echo "Status: " . $res->getStatusCode() . "\n";
    if ($res->getStatusCode() >= 400) {
        echo "Error: " . substr($res->getContent(), 0, 500) . "\n";
    } else {
        echo "Success\n";
    }
}
