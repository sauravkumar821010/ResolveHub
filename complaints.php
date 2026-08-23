<?php
require 'auth.php'; require_role(['admin','staff']); $u=current_user();
$status=trim($_GET['status']??''); $priority=trim($_GET['priority']??''); $department=(int)($_GET['department']??0); $q=trim($_GET['q']??'');
$where=[]; $params=[];
if($u['role']==='staff'){ $where[]='c.assigned_to=?'; $params[]=$u['id']; }
if(in_array($status,['Pending','In Progress','Resolved','Closed','Rejected'],true)){ $where[]='c.status=?'; $params[]=$status; }
if(in_array($priority,['Low','Medium','High','Critical'],true)){ $where[]='c.priority=?'; $params[]=$priority; }
if($department>0){ $where[]='c.department_id=?'; $params[]=$department; }
if($q!==''){ $where[]='(c.ticket_id LIKE ? OR c.subject LIKE ? OR u.name LIKE ? OR c.category LIKE ?)'; $like='%'.$q.'%'; array_push($params,$like,$like,$like,$like); }
$sql="SELECT c.*,u.name user_name,d.name department,st.name assigned_name FROM complaints c JOIN users u ON u.id=c.user_id LEFT JOIN departments d ON d.id=c.department_id LEFT JOIN users st ON st.id=c.assigned_to";
if($where)$sql.=' WHERE '.implode(' AND ',$where); $sql.=' ORDER BY c.created_at DESC';
$stmt=db()->prepare($sql); $stmt->execute($params); $rows=$stmt->fetchAll();
$departments=db()->query("SELECT id,name FROM departments ORDER BY name")->fetchAll();
$page_title='All Complaints'; $active_nav='complaints'; require 'partials/header.php';
?>
<div class="page admin-page"><div class="container wide">
<div class="page-title"><div class="eyebrow">Complaint Management</div><h1>All Complaints</h1><p>Search, filter, inspect and manage every complaint in your workspace.</p></div>
<div class="panel filter-panel reveal"><form method="get" class="filter-form"><div class="search-box"><span>⌕</span><input class="input" name="q" value="<?=e($q)?>" placeholder="Search ticket, subject, citizen or category..."></div><select class="select" name="status"><option value="">All statuses</option><?php foreach(['Pending','In Progress','Resolved','Closed','Rejected'] as $s): ?><option <?= $status===$s?'selected':'' ?>><?=$s?></option><?php endforeach; ?></select><select class="select" name="priority"><option value="">All priorities</option><?php foreach(['Low','Medium','High','Critical'] as $p): ?><option <?= $priority===$p?'selected':'' ?>><?=$p?></option><?php endforeach; ?></select><select class="select" name="department"><option value="0">All departments</option><?php foreach($departments as $d): ?><option value="<?=$d['id']?>" <?=$department===$d['id']?'selected':''?>><?=e($d['name'])?></option><?php endforeach; ?></select><button class="btn btn-primary">Filter</button><?php if($q||$status||$priority||$department): ?><a class="btn btn-secondary" href="complaints.php">Clear</a><?php endif; ?></form></div>
<div class="panel reveal"><div class="toolbar"><div><h3><?=$q||$status||$priority||$department?'Filtered':'All'?> Complaints</h3><p class="small"><?=count($rows)?> result<?=count($rows)===1?'':'s'?></p></div></div>
<?php if(!$rows): ?><div class="empty">No complaints match your filters.</div><?php else: ?><div class="table-wrap"><table class="table enhanced-table"><thead><tr><th>Ticket</th><th>Complaint</th><th>Citizen</th><th>Department</th><th>Priority</th><th>Status</th><th>Updated</th><th></th></tr></thead><tbody>
<?php foreach($rows as $c): ?><tr><td><a class="ticket-link" href="complaint.php?id=<?=$c['id']?>">#<?=e($c['ticket_id'])?></a></td><td><strong><?=e($c['subject'])?></strong><small><?=e($c['category'])?></small></td><td><?=e($c['user_name'])?></td><td><?=e($c['department']??'Unassigned')?></td><td><span class="priority-dot <?=strtolower($c['priority'])?>"></span><?=e($c['priority'])?></td><td><span class="badge <?=strtolower(str_replace(' ','',$c['status']))?>"><?=e($c['status'])?></span></td><td><?=date('d M, h:i A',strtotime($c['updated_at']))?></td><td><a class="manage-link" href="manage_complaint.php?id=<?=$c['id']?>">Manage →</a></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></div>
</div></div>
<?php require 'partials/footer.php'; ?>
