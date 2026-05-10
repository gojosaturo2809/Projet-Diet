<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? esc($title) : 'NutriPlan' ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/login.css') ?>">
</head>
<body>
    <!-- Messages d'alerte -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>

    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-error">
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>

    <?php if(isset($errors)): ?>
        <div class="alert alert-error">
            <?php if(is_array($errors)): ?>
                <ul>
                    <?php foreach($errors as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <?= esc($errors) ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <?= $this->renderSection('content') ?>
</body>
</html>
