<?php
require 'auth.php'; require_login(); $u=current_user();
$stmt=db()->prepare("SELECT c.*,d.name department FROM complaints c LEFT JOIN departments d ON d.id=c.department_id WHERE c.user_id=? ORDER BY c.created_at DESC"); $stmt->execute([$u['id']]); $rows=$stmt->fetchAll();
$page_title='My Complaints'; require 'partials/header.php';
?>
<div class="page citizen-page">
<div class="container">
  <div class="citizen-page-heading reveal">
    <div>
      <div class="eyebrow">COMPLAINT HISTORY</div>
      <h1>My Complaints</h1>
      <p>Review every complaint submitted from your account and open any ticket for its full timeline.</p>
    </div>
    <a class="btn btn-primary" href="new_complaint.php">＋ New Complaint</a>
  </div>
<div class="panel reveal"><div class="toolbar"><h3>All Your Complaints</h3><span class="small"><?=count($rows)?> complaint<?=count($rows)===1?'':'s'?></span></div>
<?php if(!$rows): ?><div class="empty">No complaints found.</div><?php else: ?><div class="table-wrap"><table class="table"><tr><th>Ticket</th><th>Subject</th><th>Category</th><th>Department</th><th>Priority</th><th>Status</th><th>Date</th></tr>
<?php foreach($rows as $c): ?><tr><td><a href="complaint.php?id=<?=$c['id']?>">#<?=e($c['ticket_id'])?></a></td><td><?=e($c['subject'])?></td><td><?=e($c['category'])?></td><td><?=e($c['department']??'Unassigned')?></td><td><?=e($c['priority'])?></td><td><span class="badge <?=strtolower(str_replace(' ','',$c['status']))?>"><?=e($c['status'])?></span></td><td><?=date('d M Y',strtotime($c['created_at']))?></td></tr><?php endforeach; ?></table></div><?php endif; ?>
</div></div></div>
<?php require 'partials/footer.php'; ?>
