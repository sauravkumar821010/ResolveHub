<?php
require 'auth.php'; require_login();
$u=current_user(); $errors=[];
$departments=db()->query("SELECT id,name FROM departments ORDER BY name")->fetchAll();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $subject=trim($_POST['subject']??''); $category=trim($_POST['category']??''); $description=trim($_POST['description']??''); $priority=$_POST['priority']??'Medium'; $dept=(int)($_POST['department_id']??0);
    if ($subject==='') $errors[]='Subject is required.';
    if ($category==='') $errors[]='Category is required.';
    if ($description==='') $errors[]='Description is required.';
    if (!$errors) {
        $ticket='CMP-'.date('ymd').'-'.strtoupper(substr(bin2hex(random_bytes(3)),0,6));
        $stmt=db()->prepare("INSERT INTO complaints(ticket_id,user_id,department_id,subject,category,description,priority,status) VALUES(?,?,?,?,?,?,?,'Pending')");
        $stmt->execute([$ticket,$u['id'],$dept?:null,$subject,$category,$description,$priority]);
        $id=(int)db()->lastInsertId();
        $stmt=db()->prepare("INSERT INTO complaint_updates(complaint_id,user_id,status,remark) VALUES(?,?,?,?)");
        $stmt->execute([$id,$u['id'],'Pending','Complaint submitted.']);
        flash('success',"Complaint #$ticket submitted successfully.");
        redirect('complaint.php?id='.$id);
    }
}
$page_title='New Complaint'; $active_nav=(in_array($u['role'],['admin','staff'],true)?'new':''); require 'partials/header.php';
?>
<div class="page <?=in_array($u['role'],['admin','staff'],true)?'admin-page page-transition':''?>"><div class="container <?=in_array($u['role'],['admin','staff'],true)?'wide':''?>">
<div class="page-title"><h1>Submit a Complaint</h1><p>Tell us what happened. We will route it to the right department.</p></div>
<?php foreach($errors as $err): ?><div class="alert error"><?=e($err)?></div><?php endforeach; ?>
<div class="panel hoverable reveal">
<form method="post">
<div class="grid-2"><div class="form-group"><label>Subject</label><input class="input" name="subject" required value="<?=e($_POST['subject']??'')?>"></div>
<div class="form-group"><label>Category</label><select class="select" name="category" required><option value="">Select category</option><?php foreach(['Road & Street','Water Supply','Electricity','Waste Management','Public Safety','Infrastructure','Other'] as $cat): ?><option <?=($_POST['category']??'')===$cat?'selected':''?>><?=$cat?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Department</label><select class="select" name="department_id"><option value="0">Let admin assign</option><?php foreach($departments as $d): ?><option value="<?=$d['id']?>" <?=((int)($_POST['department_id']??0)==$d['id'])?'selected':''?>><?=e($d['name'])?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Priority</label><select class="select" name="priority"><?php foreach(['Low','Medium','High','Critical'] as $p): ?><option <?=($_POST['priority']??'Medium')===$p?'selected':''?>><?=$p?></option><?php endforeach; ?></select></div></div>
<div class="form-group"><label>Description</label><textarea class="textarea" name="description" required placeholder="Describe the problem clearly..."><?=e($_POST['description']??'')?></textarea></div>
<button class="btn btn-primary">Submit Complaint</button> <a class="btn btn-secondary" href="dashboard.php">Cancel</a>
</form></div></div></div>
<?php require 'partials/footer.php'; ?>
