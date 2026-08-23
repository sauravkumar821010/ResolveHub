<?php
require_once __DIR__ . '/../auth.php';
$user = current_user();
$page_title = $page_title ?? APP_NAME;
$active_nav = $active_nav ?? '';
$is_admin_area = $user && in_array($user['role'], ['admin','staff'], true);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($page_title) ?> | <?= APP_NAME ?></title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="<?= $is_admin_area ? 'admin-body' : '' ?>">
<?php if ($is_admin_area): ?>
<div class="admin-app">
  <aside class="admin-sidebar">
    <a class="admin-brand" href="admin.php"><span class="brand-mark">R</span><span>ResolveHub</span></a>
    <div class="sidebar-label">Workspace</div>
    <nav class="sidebar-nav">
      <a class="<?= $active_nav==='dashboard'?'active':'' ?>" href="admin.php"><span>▣</span> Dashboard</a>
      <a class="<?= $active_nav==='complaints'?'active':'' ?>" href="complaints.php"><span>▤</span> All Complaints</a>
      <a class="<?= $active_nav==='new'?'active':'' ?>" href="new_complaint.php"><span>＋</span> New Complaint</a>
      <a class="<?= $active_nav==='track'?'active':'' ?>" href="track.php"><span>◷</span> Track Status</a>
      <a class="<?= $active_nav==='departments'?'active':'' ?>" href="departments.php"><span>◉</span> Departments</a>
      <a class="<?= $active_nav==='settings'?'active':'' ?>" href="settings.php"><span>⚙</span> Settings</a>
    </nav>
    <div class="sidebar-bottom">
      <a href="index.php">← Public Portal</a>
      <a href="logout.php">↪ Logout</a>
    </div>
  </aside>
  <div class="admin-main-wrap">
    <header class="admin-topbar">
      <div class="admin-mobile-brand"><span class="brand-mark">R</span> ResolveHub</div>
      <div class="topbar-spacer"></div>
      <span class="system-pill"><span></span> System Active</span>
      <div class="admin-user"><div class="avatar"><?= e(strtoupper(substr($user['name'],0,1))) ?></div><div><strong><?= e($user['name']) ?></strong><small><?= e(ucfirst($user['role'])) ?></small></div></div>
    </header>
<?php else: ?>
<nav class="topbar">
  <div class="container nav-inner">
    <a class="brand" href="index.php"><span class="brand-mark">R</span>ResolveHub</a>
    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
    <div class="nav-links">
      <a class="<?= $current_page==='index.php' ? 'active' : '' ?>" href="index.php">Home</a>
      <?php if ($user): ?>
        <a class="<?= $current_page==='dashboard.php' ? 'active' : '' ?>" href="dashboard.php">Dashboard</a>
        <a class="<?= $current_page==='my_complaints.php' ? 'active' : '' ?>" href="my_complaints.php">My Complaints</a>
      <?php else: ?>
        <a href="index.php#features">Features</a>
        <a href="index.php#how">How it works</a>
      <?php endif; ?>
    </div>
    <div class="nav-actions">
      <?php if ($user): ?>
        <span class="user-chip"><?= e($user['name']) ?></span>
        <a class="btn btn-secondary" href="logout.php">Logout</a>
      <?php else: ?>
        <a class="btn btn-secondary" href="login.php">Login</a>
        <a class="btn btn-primary" href="register.php">Get Started</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
<?php endif; ?>
<main>
