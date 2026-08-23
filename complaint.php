<?php
require 'auth.php'; require_login(); $u=current_user(); $id=(int)($_GET['id']??0);
$stmt=db()->prepare("SELECT c.*,u.name user_name,u.email user_email,d.name department FROM complaints c JOIN users u ON u.id=c.user_id LEFT JOIN departments d ON d.id=c.department_id WHERE c.id=?"); $stmt->execute([$id]); $c=$stmt->fetch();
if(!$c) exit('Complaint not found.');
if($u['role']==='citizen' && (int)$c['user_id']!==(int)$u['id']) exit('Access denied.');
$stmt=db()->prepare("SELECT cu.*,u.name FROM complaint_updates cu JOIN users u ON u.id=cu.user_id WHERE cu.complaint_id=? ORDER BY cu.created_at ASC"); $stmt->execute([$id]); $updates=$stmt->fetchAll();
$msg=flashes();
$page_title='Complaint #'.$c['ticket_id']; $active_nav=in_array($u['role'],['admin','staff'],true)?'complaints':''; require 'partials/header.php';
?>
<div class="page <?=in_array($u['role'],['admin','staff'],true)?'admin-page page-transition':''?>"><div class="container <?=in_array($u['role'],['admin','staff'],true)?'wide':''?>">
<?php foreach($msg as $m): ?><div class="alert <?=$m['type']?>"><?=e($m['message'])?></div><?php endforeach; ?>
<div class="toolbar"><div><div class="eyebrow">Complaint</div><h1>#<?=e($c['ticket_id'])?></h1></div><?php if(in_array($u['role'],['admin','staff'],true)): ?><a class="btn btn-primary" href="manage_complaint.php?id=<?=$c['id']?>">Manage Complaint</a><?php endif; ?></div>
<div class="grid-2"><div class="panel hoverable"><h3><?=e($c['subject'])?></h3><div class="meta"><div class="meta-item"><small>Category</small><span><?=e($c['category'])?></span></div><div class="meta-item"><small>Priority</small><span><?=e($c['priority'])?></span></div><div class="meta-item"><small>Status</small><span><?=e($c['status'])?></span></div><div class="meta-item"><small>Department</small><span><?=e($c['department']??'Unassigned')?></span></div></div><p><?=nl2br(e($c['description']))?></p></div>
<div class="panel hoverable"><h3>Complaint Timeline</h3><div class="timeline"><?php foreach($updates as $ev): ?><div class="event"><strong><?=e($ev['status'])?></strong><p><?=e($ev['remark'])?></p><p><?=e($ev['name'])?> · <?=date('d M Y, h:i A',strtotime($ev['created_at']))?></p></div><?php endforeach; ?></div></div></div>
</div></div>
<?php require 'partials/footer.php'; ?>
