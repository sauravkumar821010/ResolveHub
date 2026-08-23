<?php
require 'auth.php'; require_role(['admin','staff']); $u=current_user(); $id=(int)($_GET['id']??0);
$stmt=db()->prepare("SELECT * FROM complaints WHERE id=?"); $stmt->execute([$id]); $c=$stmt->fetch(); if(!$c) exit('Complaint not found.');
if($u['role']==='staff' && (int)$c['assigned_to']!==(int)$u['id'] && $c['assigned_to']!==null) exit('This complaint is not assigned to you.');
$departments=db()->query("SELECT id,name FROM departments ORDER BY name")->fetchAll();
$staff=db()->query("SELECT id,name FROM users WHERE role='staff' ORDER BY name")->fetchAll();
$errors=[];
if($_SERVER['REQUEST_METHOD']==='POST'){
    $status=$_POST['status']??$c['status']; $dept=(int)($_POST['department_id']??0); $assigned=(int)($_POST['assigned_to']??0); $remark=trim($_POST['remark']??'');
    $allowed=['Pending','In Progress','Resolved','Closed','Rejected'];
    if(!in_array($status,$allowed,true)) $errors[]='Invalid status.';
    if(!$errors){
        if($u['role']==='staff') $assigned=$u['id'];
        $statusChanged = $status !== $c['status'];
        $assignmentChanged = ($dept ?: null) != ($c['department_id'] ?: null) || ($assigned ?: null) != ($c['assigned_to'] ?: null);
        $stmt=db()->prepare("UPDATE complaints SET status=?,department_id=?,assigned_to=?,updated_at=NOW() WHERE id=?"); $stmt->execute([$status,$dept?:null,$assigned?:null,$id]);
        if($remark!=='' || $statusChanged || $assignmentChanged){
            $timelineRemark = $remark !== '' ? $remark : ($statusChanged ? 'Status changed to '.$status.'.' : 'Complaint assignment updated.');
            $stmt=db()->prepare("INSERT INTO complaint_updates(complaint_id,user_id,status,remark) VALUES(?,?,?,?)"); $stmt->execute([$id,$u['id'],$status,$timelineRemark]);
        }
        flash('success','Complaint updated successfully.'); redirect('complaint.php?id='.$id);
    }
}
$page_title='Manage Complaint'; $active_nav='complaints'; require 'partials/header.php';
?>
<div class="page admin-page page-transition"><div class="container wide"><div class="page-title"><div class="eyebrow">Admin / Complaint</div><h1>Manage #<?=e($c['ticket_id'])?></h1></div>
<?php foreach($errors as $err): ?><div class="alert error"><?=e($err)?></div><?php endforeach; ?>
<div class="manage-grid"><div class="complaint-summary-card hoverable"><h3><?=e($c['subject'])?></h3><p class="small" style="margin-top:8px"><?=nl2br(e($c['description']))?></p></div>
<div class="panel manage-form-card reveal"><form method="post">
<div class="form-group"><label>Status</label><select class="select" name="status"><?php foreach(['Pending','In Progress','Resolved','Closed','Rejected'] as $s): ?><option <?=($c['status']===$s?'selected':'')?>><?=$s?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Department</label><select class="select" name="department_id"><option value="0">Unassigned</option><?php foreach($departments as $d): ?><option value="<?=$d['id']?>" <?=((int)$c['department_id']===$d['id']?'selected':'')?>><?=e($d['name'])?></option><?php endforeach; ?></select></div>
<?php if($u['role']==='admin'): ?><div class="form-group"><label>Assign Staff</label><select class="select" name="assigned_to"><option value="0">Unassigned</option><?php foreach($staff as $s): ?><option value="<?=$s['id']?>" <?=((int)$c['assigned_to']===$s['id']?'selected':'')?>><?=e($s['name'])?></option><?php endforeach; ?></select></div><?php endif; ?>
<div class="form-group"><label>Update Remark</label><textarea class="textarea" name="remark" placeholder="Add a short update for the complaint timeline..."></textarea></div>
<button class="btn btn-primary">Save Changes</button> <a class="btn btn-secondary" href="complaint.php?id=<?=$id?>">Cancel</a>
</form></div></div></div></div>
<?php require 'partials/footer.php'; ?>
