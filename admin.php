<?php
require 'auth.php'; require_role(['admin','staff']);
$u=current_user();
$where = '';
$params = [];
if ($u['role'] === 'staff') { $where=' WHERE c.assigned_to=? '; $params=[$u['id']]; }

function admin_count(string $condition=''): int {
    global $where,$params;
    $sql='SELECT COUNT(*) FROM complaints c'.$where;
    $sql .= $condition ? ($where ? ' AND ' : ' WHERE ').$condition : '';
    $stmt=db()->prepare($sql); $stmt->execute($params); return (int)$stmt->fetchColumn();
}
$total=admin_count();
$pending=admin_count("c.status='Pending'");
$progress=admin_count("c.status='In Progress'");
$resolved=admin_count("c.status IN ('Resolved','Closed')");
$critical=admin_count("c.priority='Critical' AND c.status NOT IN ('Resolved','Closed','Rejected')");

$avgSql="SELECT AVG(TIMESTAMPDIFF(MINUTE,c.created_at,c.updated_at)) FROM complaints c ".($where?'WHERE c.assigned_to=? AND c.status IN (\'Resolved\',\'Closed\')':'WHERE c.status IN (\'Resolved\',\'Closed\')');
$stmt=db()->prepare($avgSql); $stmt->execute($params); $avgMin=$stmt->fetchColumn();
$avgResponse=$avgMin!==null ? ($avgMin>=60 ? number_format($avgMin/60,1).'h' : round($avgMin).'m') : '—';

$base="SELECT c.*,u.name user_name,d.name department,st.name assigned_name FROM complaints c JOIN users u ON u.id=c.user_id LEFT JOIN departments d ON d.id=c.department_id LEFT JOIN users st ON st.id=c.assigned_to";
$stmt=db()->prepare($base.$where." ORDER BY c.created_at DESC LIMIT 8"); $stmt->execute($params); $rows=$stmt->fetchAll();

$deptSql="SELECT d.id,d.name,COUNT(c.id) total,SUM(c.status='Pending') pending,SUM(c.status IN ('Resolved','Closed')) resolved FROM departments d LEFT JOIN complaints c ON c.department_id=d.id ".($u['role']==='staff'?'AND c.assigned_to=? ':'')."GROUP BY d.id ORDER BY total DESC,d.name";
$stmt=db()->prepare($deptSql); $stmt->execute($u['role']==='staff'?[$u['id']]:[]); $deptRows=$stmt->fetchAll();

$page_title='Admin Dashboard'; $active_nav='dashboard'; require 'partials/header.php';
?>
<div class="page admin-page"><div class="container wide">
  <div class="page-title dashboard-heading">
    <div><div class="eyebrow"><?=e(ucfirst($u['role']))?> Workspace</div><h1>Complaint Dashboard</h1><p>Monitor complaints, assignments and resolution progress from one place.</p></div>
    <a class="btn btn-primary" href="new_complaint.php">＋ New Complaint</a>
  </div>

  <div class="kpi-grid admin-kpis">
    <a class="kpi interactive-kpi" href="complaints.php"><small>Total Complaints</small><strong><?=$total?></strong><span>View all →</span></a>
    <a class="kpi interactive-kpi warning-card" href="complaints.php?status=Pending"><small>Pending</small><strong><?=$pending?></strong><span>Needs attention →</span></a>
    <a class="kpi interactive-kpi progress-card" href="complaints.php?status=In%20Progress"><small>In Progress</small><strong><?=$progress?></strong><span>Being handled →</span></a>
    <a class="kpi interactive-kpi success-card" href="complaints.php?status=Resolved"><small>Resolved</small><strong><?=$resolved?></strong><span>Completed →</span></a>
  </div>

  <div class="dashboard-alerts">
    <div class="attention-card"><div class="attention-icon">!</div><div><strong><?=$critical?> critical complaint<?= $critical===1?'':'s' ?> need attention</strong><span>Review high-priority unresolved complaints before they become overdue.</span></div><a href="complaints.php?priority=Critical">Review →</a></div>
    <div class="response-card"><span>Average response time</span><strong><?=$avgResponse?></strong><small>Based on resolved complaints</small></div>
  </div>

  <div class="dashboard-grid-main">
    <section class="panel reveal"><div class="toolbar"><div><h3>Recent Complaints</h3><p class="small">Latest submissions across your workspace.</p></div><a class="btn btn-secondary" href="complaints.php">View All</a></div>
      <?php if(!$rows): ?><div class="empty">No complaints available.</div><?php else: ?>
      <div class="complaint-list">
      <?php foreach($rows as $c): ?>
        <a class="complaint-row" href="complaint.php?id=<?=$c['id']?>">
          <div class="complaint-icon <?=strtolower($c['priority'])?>">●</div>
          <div class="complaint-info"><strong>#<?=e($c['ticket_id'])?> · <?=e($c['subject'])?></strong><span><?=e($c['user_name'])?> · <?=e($c['department']??'Unassigned')?> · <?=date('d M Y, h:i A',strtotime($c['created_at']))?></span></div>
          <span class="badge <?=strtolower(str_replace(' ','',$c['status']))?>"><?=e($c['status'])?></span><span class="row-arrow">→</span>
        </a>
      <?php endforeach; ?></div><?php endif; ?>
    </section>

    <section class="panel reveal"><div class="toolbar"><div><h3>Quick Actions</h3><p class="small">Jump directly to common tasks.</p></div></div>
      <div class="quick-actions">
        <a href="complaints.php"><span>▤</span><div><strong>Review complaints</strong><small>Search and filter every ticket</small></div><b>→</b></a>
        <a href="track.php"><span>◷</span><div><strong>Track a ticket</strong><small>Find a complaint by ticket ID</small></div><b>→</b></a>
        <a href="departments.php"><span>◉</span><div><strong>Manage departments</strong><small>Organize routing and workload</small></div><b>→</b></a>
      </div>
    </section>
  </div>

  <section class="panel reveal department-panel"><div class="toolbar"><div><h3>Department Overview</h3><p class="small">See where complaint volume is concentrated.</p></div><a href="departments.php" class="btn btn-secondary">Manage</a></div>
    <div class="department-grid">
    <?php foreach($deptRows as $d): $pct=$d['total']?(int)round(($d['resolved']/$d['total'])*100):0; ?>
      <a class="department-card" href="complaints.php?department=<?=$d['id']?>"><div class="department-top"><strong><?=e($d['name'])?></strong><span><?=$d['total']?> total</span></div><div class="progress-track"><i style="width:<?=$pct?>%"></i></div><div class="department-meta"><span><?=$d['pending']?> pending</span><span><?=$pct?>% resolved</span></div></a>
    <?php endforeach; ?>
    </div>
  </section>
</div></div>
<?php require 'partials/footer.php'; ?>
