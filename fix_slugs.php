<?php
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';
$model = new \App\Models\ProfileModel();
$profiles = $model->where('slug', '')->orWhere('slug IS NULL', null)->findAll();
echo "Found " . count($profiles) . " profiles with empty slugs.\n";
foreach ($profiles as $p) {
    $base = $p['name'] ?: $p['position'] ?: 'profile';
    $slug = url_title($base, '-', true) ?: 'profile-' . $p['id'];
    // Ensure uniqueness
    $count = 1;
    $finalSlug = $slug;
    while ($model->where('slug', $finalSlug)->where('id !=', $p['id'])->countAllResults() > 0) {
        $finalSlug = $slug . '-' . $count++;
    }
    $model->update($p['id'], ['slug' => $finalSlug]);
    echo "Updated ID {$p['id']} to slug: {$finalSlug}\n";
}
