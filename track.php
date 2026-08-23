<?php
require 'auth.php'; require_role(['admin','staff']); $u=current_user();
$ticket=trim($_GET['ticket']??''); $c=null; $updates=[]; $error='';
if($ticket!==''){
  $sql="SELECT c.*,u.name user_name,u.email user_email,d.name department,st.name assigned_name FROM complaints c JOIN users u ON u.id=c.user_id LEFT JOIN departments d ON d.id=c.department_id LEFT JOIN users st ON st.id=c.assigned_to WHERE c.ticket_id=?";
  $params=[$ticket]; if($u['role']==='staff'){$sql.=' AND c.assigned_to=?';$params[]=$u['id'];}
  $stmt=db()->prepare($sql);$stmt->execute($params);$c=$stmt->fetch();
  if($c){$stmt=db()->prepare("SELECT cu.*,u.name FROM complaint_updates cu JOIN users u ON u.id=cu.user_id WHERE cu.complaint_id=? ORDER BY cu.created_at ASC");$stmt->execute([$c['id']]);$updates=$stmt->fetchAll();} else $error='No complaint found for that ticket in your workspace.';
}
$page_title='Track Status'; $active_nav='track'; require 'partials/header.php';
?>
<div class="page admin-page page-transition"><div class="container narrow-admin">
<div class="page-title"><div class="eyebrow">Ticket Tracking</div><h1>Track Complaint Status</h1><p>Enter a ticket ID to see its current status and full activity timeline.</p></div>
<div class="track-search track-search-enhanced panel reveal"><form method="get"><label>Complaint Ticket ID</label><div class="track-input"><input class="input" name="ticket" value="<?=e($ticket)?>" placeholder="e.g. CMP-260823-A1B2C3" required><button class="btn btn-primary">Track Ticket</button></div></form></div>
<?php if($error): ?><div class="alert error"><?=e($error)?></div><?php endif; ?>
<?php if($c): $statuses=['Pending','In Progress','Resolved','Closed']; $current=array_search($c['status'],$statuses,true); if($current===false)$current=0; ?>
<div class="panel track-result reveal"><div class="track-result-head"><div><span class="eyebrow">Ticket</span><h2>#<?=e($c['ticket_id'])?></h2><p><?=e($c['subject'])?></p></div><span class="badge <?=strtolower(str_replace(' ','',$c['status']))?>"><?=e($c['status'])?></span></div>
<div class="status-steps"><?php foreach($statuses as $i=>$s): ?><div class="status-step <?= $i<=$current?'done':'' ?> <?= $i===$current?'current':'' ?>"><div class="step-dot"><?= $i<=$current?'✓':($i+1) ?></div><span><?=e($s)?></span></div><?php endforeach; ?></div>
<div class="meta"><div class="meta-item"><small>Citizen</small><span><?=e($c['user_name'])?></span></div><div class="meta-item"><small>Department</small><span><?=e($c['department']??'Unassigned')?></span></div><div class="meta-item"><small>Priority</small><span><?=e($c['priority'])?></span></div><div class="meta-item"><small>Assigned Staff</small><span><?=e($c['assigned_name']??'Unassigned')?></span></div></div>
<h3>Activity Timeline</h3><div class="timeline"><?php foreach($updates as $ev): ?><div class="event"><strong><?=e($ev['status'])?></strong><p><?=e($ev['remark']?:'Status updated.')?></p><p><?=e($ev['name'])?> · <?=date('d M Y, h:i A',strtotime($ev['created_at']))?></p></div><?php endforeach; ?></div>
<a class="btn btn-primary" href="manage_complaint.php?id=<?=$c['id']?>">Manage Complaint →</a></div>
<?php endif; ?></div></div>
<?php require 'partials/footer.php'; ?>
