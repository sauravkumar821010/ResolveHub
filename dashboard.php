<?php
require 'auth.php'; require_login();
$u=current_user();
if (in_array($u['role'],['admin','staff'],true)) redirect('admin.php');
$stmt=db()->prepare("SELECT COUNT(*) FROM complaints WHERE user_id=?"); $stmt->execute([$u['id']]); $total=(int)$stmt->fetchColumn();
$stmt=db()->prepare("SELECT COUNT(*) FROM complaints WHERE user_id=? AND status='Pending'"); $stmt->execute([$u['id']]); $pending=(int)$stmt->fetchColumn();
$stmt=db()->prepare("SELECT COUNT(*) FROM complaints WHERE user_id=? AND status='Resolved'"); $stmt->execute([$u['id']]); $resolved=(int)$stmt->fetchColumn();
$stmt=db()->prepare("SELECT c.*,d.name department FROM complaints c LEFT JOIN departments d ON d.id=c.department_id WHERE c.user_id=? ORDER BY c.created_at DESC LIMIT 5"); $stmt->execute([$u['id']]); $recent=$stmt->fetchAll();
$page_title='Dashboard'; require 'partials/header.php';
?>
<div class="page citizen-dashboard">
<div class="container">
  <div class="citizen-welcome reveal">
    <div>
      <div class="eyebrow">CITIZEN WORKSPACE</div>
      <h1>Good to see you, <?=e($u['name'])?>.</h1>
      <p>Everything about your complaints, from submission to resolution, in one place.</p>
    </div>
    <a class="btn btn-primary dashboard-main-action" href="new_complaint.php">＋ Submit a Complaint</a>
  </div>

  <div class="kpi-grid citizen-kpis">
    <div class="kpi citizen-kpi reveal"><span class="kpi-icon">▣</span><small>Total Complaints</small><strong><?=$total?></strong><span class="kpi-note">All submissions</span></div>
    <div class="kpi citizen-kpi reveal"><span class="kpi-icon pending-icon">◷</span><small>Pending</small><strong><?=$pending?></strong><span class="kpi-note">Awaiting action</span></div>
    <div class="kpi citizen-kpi reveal"><span class="kpi-icon resolved-icon">✓</span><small>Resolved</small><strong><?=$resolved?></strong><span class="kpi-note">Successfully closed</span></div>
    <div class="kpi citizen-kpi reveal"><span class="kpi-icon account-icon">R</span><small>Account</small><strong class="citizen-account">Citizen</strong><span class="kpi-note">Active account</span></div>
  </div>

  <div class="citizen-dashboard-grid">
    <div class="panel reveal">
      <div class="toolbar">
        <div>
          <div class="eyebrow">YOUR ACTIVITY</div>
          <h3>Recent Complaints</h3>
        </div>
        <a href="my_complaints.php" class="btn btn-secondary">View All →</a>
      </div>
      <?php if(!$recent): ?>
        <div class="empty">No complaints yet. Submit your first complaint to get started.</div>
      <?php else: ?>
        <div class="citizen-complaint-list">
        <?php foreach($recent as $c): ?>
          <a class="citizen-complaint-row" href="complaint.php?id=<?=$c['id']?>">
            <div class="complaint-ticket">#<?=e($c['ticket_id'])?></div>
            <div class="complaint-main">
              <strong><?=e($c['subject'])?></strong>
              <span><?=e($c['department']??'Unassigned')?> · <?=date('d M Y',strtotime($c['created_at']))?></span>
            </div>
            <span class="badge <?=strtolower(str_replace(' ','',$c['status']))?>"><?=e($c['status'])?></span>
            <span class="complaint-arrow">→</span>
          </a>
        <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="panel reveal">
      <div class="eyebrow">QUICK ACTIONS</div>
      <h3 class="quick-title">What would you like to do?</h3>
      <div class="citizen-actions">
        <a href="new_complaint.php"><span>＋</span><div><strong>New Complaint</strong><small>Report a new issue</small></div><b>→</b></a>
        <a href="my_complaints.php"><span>▤</span><div><strong>My Complaints</strong><small>See your complaint history</small></div><b>→</b></a>
        <a href="my_complaints.php"><span>◷</span><div><strong>Track Status</strong><small>Check your latest updates</small></div><b>→</b></a>
      </div>
    </div>
  </div>
</div>
</div>
<?php require 'partials/footer.php'; ?>
