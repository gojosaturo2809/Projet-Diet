<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - NutriPlan</title>

    <link rel="stylesheet" href="<?= base_url('css/admin.css') ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<header class="admin-header">

    <div class="admin-header-left">
        🥗 NutriPlan Admin
    </div>

    <div class="admin-header-right">
        👤 <?= session()->get('user_email') ?? 'Admin' ?>
    </div>

</header>
<main class="admin-content">
    
      
 