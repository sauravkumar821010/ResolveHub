<?php
require 'auth.php'; require_role(['admin','staff']); $u=current_user(); $messages=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name=trim($_POST['name']??''); $phone=trim($_POST['phone']??'');
  if($name==='') $messages[]=['type'=>'error','message'=>'Name cannot be empty.'];
  else { $stmt=db()->prepare('UPDATE users SET name=?,phone=? WHERE id=?');$stmt->execute([$name,$phone?:null,$u['id']]);$_SESSION['user']['name']=$name;flash('success','Profile settings updated.');redirect('settings.php'); }
}
$page_title='Settings'; $active_nav='settings'; require 'partials/header.php';
?>
<div class="page admin-page page-transition"><div class="container wide"><div class="page-title"><div class="eyebrow">Workspace Preferences</div><h1>Settings</h1><p>Manage your administrator profile and workspace preferences.</p></div>
<?php foreach(flashes() as $m): ?><div class="alert <?=$m['type']?>"><?=e($m['message'])?></div><?php endforeach; foreach($messages as $m): ?><div class="alert <?=$m['type']?>"><?=e($m['message'])?></div><?php endforeach; ?>
<div class="settings-layout">
  <aside class="settings-menu reveal"><a class="active" href="#profile">👤 Profile</a><a href="#security">🔒 Security</a><a href="admin.php">← Back to Dashboard</a></aside>
  <div>
    <section class="panel reveal hoverable" id="profile"><div class="toolbar"><div><div class="eyebrow">Account</div><h3>Profile Information</h3><p class="small">This information is displayed in your administration workspace.</p></div><div class="avatar"><?=e(strtoupper(substr($u['name'],0,1)))?></div></div><form method="post"><div class="grid-2"><div class="form-group"><label>Name</label><input class="input" name="name" value="<?=e($u['name'])?>" required></div><div class="form-group"><label>Phone</label><input class="input" name="phone" value="<?=e($u['phone']??'')?>"></div><div class="form-group"><label>Email</label><input class="input" value="<?=e($u['email'])?>" disabled></div><div class="form-group"><label>Role</label><input class="input" value="<?=e(ucfirst($u['role']))?>" disabled></div></div><button class="btn btn-primary">Save Changes</button></form></section>
    <section class="panel reveal security-note hoverable" id="security"><div class="eyebrow">Security</div><h3>Account Protection</h3><p class="small" style="margin-top:7px">For this academic prototype, keep the admin account private and use a strong password. Production deployment should add CSRF protection, rate limiting, HTTPS and stronger audit controls.</p><div class="security-chip">● Session protected · Role: <?=e(ucfirst($u['role']))?></div></section>
  </div>
</div></div></div>
<?php require 'partials/footer.php'; ?>
