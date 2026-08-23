<?php
require 'auth.php';
require_role(['admin']);

$errors = [];

/* Add department */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');

    if ($name === '') {
        $errors[] = 'Department name is required.';
    } else {
        try {
            $stmt = db()->prepare(
                "INSERT INTO departments(name) VALUES(?)"
            );
            $stmt->execute([$name]);

            flash('success', 'Department added successfully.');
            redirect('departments.php');
        } catch (PDOException $e) {
            $errors[] = 'Department already exists.';
        }
    }
}

/* Get departments with complaint count */
$departments = db()->query("
    SELECT 
        d.*,
        COUNT(c.id) AS complaints
    FROM departments d
    LEFT JOIN complaints c 
        ON c.department_id = d.id
    GROUP BY d.id
    ORDER BY d.name
")->fetchAll();

/* Statistics */
$total_departments = count($departments);
$total_complaints = 0;
$highest_department = 'None';
$highest_count = 0;

foreach ($departments as $d) {
    $count = (int)$d['complaints'];

    $total_complaints += $count;

    if ($count > $highest_count) {
        $highest_count = $count;
        $highest_department = $d['name'];
    }
}

/* Used for workload percentage */
$max_complaints = $highest_count > 0 ? $highest_count : 1;

$page_title = 'Departments';
$active_nav = 'departments';

require 'partials/header.php';
?>

<div class="page admin-page">

    <div class="container wide">

        <!-- PAGE HEADER -->
        <div class="page-title">
            <div class="eyebrow">Workspace Management</div>

            <div class="dashboard-heading">
                <div>
                    <h1>Departments</h1>
                    <p>
                        Organize complaint routing, monitor workload
                        and keep every team accountable.
                    </p>
                </div>

                <a href="#add-department" class="btn btn-primary">
                    + Add Department
                </a>
            </div>
        </div>


        <!-- ALERTS -->
        <?php foreach (flashes() as $m): ?>
            <div class="alert <?= $m['type'] ?>">
                <?= e($m['message']) ?>
            </div>
        <?php endforeach; ?>

        <?php foreach ($errors as $err): ?>
            <div class="alert error">
                <?= e($err) ?>
            </div>
        <?php endforeach; ?>


        <!-- STATISTICS -->
        <div class="kpi-grid">

            <div class="kpi interactive-kpi">
                <small>Total Departments</small>
                <strong><?= $total_departments ?></strong>
                <span>Active routing groups</span>
            </div>

            <div class="kpi interactive-kpi">
                <small>Assigned Complaints</small>
                <strong><?= $total_complaints ?></strong>
                <span>Across all departments</span>
            </div>

            <div class="kpi interactive-kpi">
                <small>Highest Workload</small>

                <strong style="font-size:22px;">
                    <?= e($highest_department) ?>
                </strong>

                <span>
                    <?= $highest_count ?>
                    <?= $highest_count == 1 ? 'complaint' : 'complaints' ?>
                </span>
            </div>

        </div>


        <!-- ADD DEPARTMENT -->
        <div class="panel reveal" id="add-department" style="margin-bottom:20px;">

            <div class="toolbar">
                <div>
                    <h3>Add a new department</h3>

                    <p class="small" style="margin-top:4px;">
                        Create a clear department name so administrators
                        can route complaints correctly.
                    </p>
                </div>
            </div>

            <form method="post">

                <div class="form-group">
                    <label>Department Name</label>

                    <input
                        class="input"
                        name="name"
                        placeholder="e.g. Public Works"
                        required
                    >
                </div>

                <button class="btn btn-primary">
                    Create Department
                </button>

            </form>

        </div>


        <!-- DEPARTMENT LIST -->
        <div class="panel reveal department-panel">

            <div class="toolbar">

                <div>
                    <div class="eyebrow">
                        Active Routing Groups
                    </div>

                    <h3 style="font-size:24px;margin-top:3px;">
                        All Departments
                    </h3>

                    <p class="small">
                        <?= $total_departments ?>
                        <?= $total_departments == 1 ? 'group' : 'groups' ?>
                    </p>
                </div>

            </div>


            <div class="department-grid">

                <?php foreach ($departments as $d): ?>

                    <?php
                        $count = (int)$d['complaints'];

                        $workload = $max_complaints > 0
                            ? round(($count / $max_complaints) * 100)
                            : 0;
                    ?>

                    <div class="department-card">

                        <div class="department-top">

                            <strong>
                                <?= e($d['name']) ?>
                            </strong>

                            <span>
                                <?= $count ?>
                                <?= $count == 1 ? 'complaint' : 'complaints' ?>
                            </span>

                        </div>


                        <div class="progress-track">
                            <i style="width:<?= $workload ?>%;"></i>
                        </div>


                        <div class="department-meta">

                            <span>
                                Workload
                                <strong><?= $workload ?>%</strong>
                            </span>

                            <span>
                                Active
                            </span>

                        </div>


                        <div style="margin-top:12px;">

                            <a
                                href="complaints.php?department=<?= (int)$d['id'] ?>"
                                class="manage-link"
                            >
                                View complaints →
                            </a>

                        </div>

                    </div>

                <?php endforeach; ?>

            </div>

        </div>

    </div>

</div>

<?php require 'partials/footer.php'; ?>