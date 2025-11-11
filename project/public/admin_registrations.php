<?php
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../config/db.php';

if (!isset($pdo) || !$pdo) {
  http_response_code(500);
  exit('Database connection failed');
}

require_admin(); // only admin

$sql = "
    SELECT
        r.reg_id,
        r.created_at AS registered_at,
        e.event_id,
        e.title       AS event_title,
        e.date        AS event_date,
        e.venue,
        u.user_id,
        u.name        AS user_name,
        u.email,
        u.student_id
    FROM registrations r
    JOIN events e ON r.event_id = e.event_id
    JOIN users  u ON r.user_id = u.user_id
    ORDER BY e.date ASC, e.title ASC, r.created_at DESC
";

$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

include __DIR__ . '/../includes/header.php';
?>

<div class="gh-page">
  <h3 class="mb-3">Registrations</h3>

  <?php if (empty($rows)): ?>
    <div class="alert alert-success text-center" style="background-color:#1a472a; color:#c9f7d0; border:1px solid #26734d;">
  No registrations yet.
</div>
  <?php else: ?>
    <div class="table-responsive">
      <table class="table table-dark table-hover align-middle">
        <thead>
          <tr>
            <th>#</th>
            <th>Event</th>
            <th>Date</th>
            <th>Student</th>
            <th>Student ID</th>
            <th>Email</th>
            <th>Registered At</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($rows as $i => $r): ?>
          <tr>
            <td><?= $i + 1 ?></td>
            <td><?= htmlspecialchars($r['event_title']) ?></td>
            <td><?= htmlspecialchars($r['event_date']) ?></td>
            <td><?= htmlspecialchars($r['user_name']) ?></td>
            <td><?= htmlspecialchars($r['student_id'] ?? '-') ?></td>
            <td><?= htmlspecialchars($r['email']) ?></td>
            <td><?= htmlspecialchars($r['registered_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
