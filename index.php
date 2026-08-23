<?php
$page_title='Home';
require 'partials/header.php';
?>
<section class="hero">
<div class="container">
<span class="pill">Modern Complaint & Feedback Management</span>
<h1>Every complaint deserves a <span class="gradient">clear resolution.</span></h1>
<p>ResolveHub gives citizens and organizations one simple place to submit, track, assign and resolve complaints without losing them in messages or paperwork.</p>
<div class="actions"><a class="btn btn-primary" href="register.php">Submit a Complaint →</a><a class="btn btn-secondary" href="#features">Explore Features</a></div>
<div class="preview reveal">
<div class="preview-label"><span>LIVE PRODUCT PREVIEW</span><small>Citizen workspace</small></div>
<div class="dash">
<aside class="dash-side">
  <div class="dash-brand"><span class="brand-mark">R</span><span>ResolveHub</span></div>
  <div class="dash-nav-label">MY WORKSPACE</div>
  <a class="active" href="dashboard.php"><span>▣</span> Dashboard</a>
  <a href="my_complaints.php"><span>▤</span> My Complaints</a>
  <a href="new_complaint.php"><span>＋</span> New Complaint</a>
  <a href="my_complaints.php"><span>◷</span> Track Complaint</a>
  <div class="dash-nav-note">Your complaints, status and updates in one place.</div>
</aside>
<div class="dash-main"><div class="dash-top"><h3>Complaint Dashboard</h3><span class="live">● System Active</span></div>
<div class="stats"><div class="stat"><small>Total Complaints</small><b>1,284</b></div><div class="stat"><small>Pending</small><b>42</b></div><div class="stat"><small>Resolved</small><b>156</b></div><div class="stat"><small>Avg. Response</small><b>4.2h</b></div></div>
<div class="dash-grid"><div class="white-panel"><h4>Recent Complaints</h4><div class="ticket"><span>#CMP-9042 · Connectivity Issue</span><span class="orange">Pending</span></div><div class="ticket"><span>#CMP-9038 · Street Light</span><span class="green">Resolved</span></div><div class="ticket"><span>#CMP-9031 · Waste Collection</span><span class="orange">In Progress</span></div></div><div class="white-panel"><h4>Quick Actions</h4><p class="small">＋ Create Complaint</p><p class="small">↗ Assign Complaint</p><p class="small">▣ View Reports</p></div></div>
</div></div></div>
</div>
</section>

<section class="section reveal" id="features"><div class="container"><div class="section-head"><div class="eyebrow">Features</div><h2>Everything needed to manage complaints</h2><p>Keep every complaint visible from submission to resolution.</p></div>
<div class="cards">
<div class="card"><div class="icon">📝</div><h3>Easy Submission</h3><p>Submit a complaint with category, description and supporting details.</p></div>
<div class="card"><div class="icon">🔔</div><h3>Status Updates</h3><p>Keep users informed as complaints move through each stage.</p></div>
<div class="card"><div class="icon">🎯</div><h3>Smart Assignment</h3><p>Route complaints to the appropriate department or staff member.</p></div>
<div class="card"><div class="icon">📊</div><h3>Clear Analytics</h3><p>View complaint counts, priorities and resolution trends.</p></div>
<div class="card"><div class="icon">👥</div><h3>Role Management</h3><p>Separate access for users, staff and administrators.</p></div>
<div class="card"><div class="icon">🔎</div><h3>Complaint Tracking</h3><p>Find the current status and history using a complaint ID.</p></div>
</div></div></section>

<section class="section reveal" id="how"><div class="container"><div class="section-head"><div class="eyebrow">How it works</div><h2>From complaint to resolution</h2></div><div class="cards">
<div class="card"><div class="eyebrow">01 / SUBMIT</div><h3>Raise a Complaint</h3><p>Enter the issue, choose a category and submit it through the portal.</p></div>
<div class="card"><div class="eyebrow">02 / ASSIGN</div><h3>Review & Assign</h3><p>Admin reviews the complaint and assigns it to the responsible department.</p></div>
<div class="card"><div class="eyebrow">03 / RESOLVE</div><h3>Resolve & Close</h3><p>Staff update the status and close the complaint after resolution.</p></div>
</div></div></section>

<section class="section reveal"><div class="container"><div class="cta"><div class="eyebrow">ResolveHub</div><h2>Make every complaint count.</h2><p>Give users a clear path from complaint submission to final resolution.</p><a class="btn btn-primary" href="register.php">Create Your Account →</a></div></div></section>
<?php require 'partials/footer.php'; ?>
