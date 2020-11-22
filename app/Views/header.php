<!doctype html>
<html lang="en">
<head>
    <?php foreach ( service('assets')->getPaths('css') as  $path) { ?>
        <?= '<link rel="preload" href="'.config('Assets')->getWebBase().$path.'" as="style">' ?>
    <?php } ?>

    <?php foreach ( service('assets')->getPaths('js') as  $path) { ?>
        <?= '<link rel="preload" href="'.config('Assets')->getWebBase().$path.'" as="script">' ?>
    <?php } ?>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?= service('assets')->css() ?>

    <title>Miernik temperatury</title>
</head>
<body>

