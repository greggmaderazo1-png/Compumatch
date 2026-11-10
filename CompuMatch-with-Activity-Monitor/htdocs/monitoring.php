<?php
// CompuMatch Activity Monitor — read-only dashboard for System Admin.
require_once __DIR__ . '/db.php';
startSecureSession();
if (empty($_SESSION['systemadmin'])) {
    header('Location: systemadmin.php');
    exit;
}
$pdo = getPDO();
activityLogReady($pdo);
$filter = trim((string)($_GET['type'] ?? ''));
$allowed = ['successful_login','failed_login','inventory_added','inventory_updated','inventory_deleted','build_saved'];
if (!in_array($filter, $allowed, true)) $filter = '';
$where = $filter ? 'WHERE event_type = ?' : '';
$sql = 'SELECT id,event_type,actor_type,actor_id,actor_name,details,source_ip,created_at FROM activity_logs ' . $where . ' ORDER BY created_at DESC, id DESC LIMIT 250';
$stmt = $pdo->prepare($sql); $stmt->execute($filter ? [$filter] : []); $events = $stmt->fetchAll();
$counts = [];
foreach ($pdo->query('SELECT event_type, COUNT(*) AS total FROM activity_logs WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY) GROUP BY event_type') as $r) $counts[$r['event_type']] = (int)$r['total'];
function h($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
function ph($pdo, $v) { return formatPHTime($pdo, $v); }
$labels = ['successful_login'=>'Successful login','failed_login'=>'Failed login','inventory_added'=>'Inventory added','inventory_updated'=>'Inventory updated','inventory_deleted'=>'Inventory deleted','build_saved'=>'PC build saved'];
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>CompuMatch | Activity Monitor</title>
<style>
:root{--bg:#0b1220;--panel:#121d30;--line:#263650;--muted:#91a2bb;--text:#edf4ff;--blue:#60a5fa;--green:#34d399;--red:#fb7185;--amber:#fbbf24}*{box-sizing:border-box}body{margin:0;background:linear-gradient(135deg,#0b1220,#101c31);color:var(--text);font:15px/1.5 Inter,system-ui,Segoe UI,sans-serif}.wrap{max-width:1200px;margin:auto;padding:32px 22px}.top{display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap}.eyebrow{color:var(--blue);font-size:12px;font-weight:800;letter-spacing:.14em;text-transform:uppercase}h1{font-size:30px;margin:5px 0}.sub{color:var(--muted);margin:0}.link{color:#bfdbfe;text-decoration:none;border:1px solid var(--line);padding:10px 14px;border-radius:10px}.cards{display:grid;grid-template-columns:repeat(auto-fit,minmax(165px,1fr));gap:14px;margin:26px 0}.card,.panel{background:rgba(18,29,48,.94);border:1px solid var(--line);border-radius:15px}.card{padding:18px}.num{font-size:28px;font-weight:800;margin-top:4px}.muted{color:var(--muted);font-size:13px}.panel{overflow:hidden}.panelhead{padding:18px 20px;display:flex;justify-content:space-between;gap:12px;align-items:center;flex-wrap:wrap;border-bottom:1px solid var(--line)}select{background:#0b1424;color:var(--text);border:1px solid var(--line);padding:10px;border-radius:9px}.tablewrap{overflow-x:auto}table{width:100%;border-collapse:collapse;min-width:850px}th,td{text-align:left;padding:13px 16px;border-bottom:1px solid #223149;vertical-align:top}th{font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--muted);background:#101a2b}td{font-size:13px}.event{font-weight:750}.pill{display:inline-block;border-radius:30px;padding:4px 9px;font-size:11px;font-weight:800;background:#24334a;color:#dbeafe}.pill.successful_login,.pill.inventory_added,.pill.build_saved{background:#12382f;color:#86efac}.pill.failed_login,.pill.inventory_deleted{background:#411e2a;color:#fda4af}.pill.inventory_updated{background:#3d3016;color:#fde68a}.empty{text-align:center;padding:45px;color:var(--muted)}.foot{color:var(--muted);font-size:12px;margin-top:15px}@media(max-width:600px){.wrap{padding:22px 13px}h1{font-size:25px}}
</style></head><body><main class="wrap"><div class="top"><div><div class="eyebrow">CompuMatch · IT401</div><h1>System Activity Monitor</h1><p class="sub">Live record of important events performed in CompuMatch.</p></div><a class="link" href="systemadmin.php">← Back to System Admin</a></div>
<div class="cards">
<?php foreach ([['successful_login','Successful logins'],['failed_login','Failed logins'],['inventory_added','Items added'],['inventory_updated','Items updated'],['inventory_deleted','Items deleted'],['build_saved','Builds saved']] as $c): ?><div class="card"><div class="muted"><?=h($c[1])?> · 7 days</div><div class="num"><?=number_format($counts[$c[0]] ?? 0)?></div></div><?php endforeach; ?>
</div><section class="panel"><div class="panelhead"><div><strong>Recent activity</strong><div class="muted">Showing up to 250 newest events · Philippine time</div></div><form method="get"><select name="type" onchange="this.form.submit()"><option value="">All event types</option><?php foreach($labels as $k=>$v): ?><option value="<?=h($k)?>" <?=$filter===$k?'selected':''?>><?=h($v)?></option><?php endforeach; ?></select></form></div>
<?php if (!$events): ?><div class="empty">No activity recorded yet. Sign in to CompuMatch or perform an inventory/build action to generate events.</div><?php else: ?><div class="tablewrap"><table><thead><tr><th>Date &amp; time (PHT)</th><th>Event</th><th>User / source</th><th>Details</th><th>IP address</th></tr></thead><tbody>
<?php foreach($events as $e): ?><tr><td><?=h(ph($pdo, $e['created_at']))?></td><td><span class="pill <?=h($e['event_type'])?>"><?=h($labels[$e['event_type']] ?? $e['event_type'])?></span></td><td><strong><?=h($e['actor_name'] ?: 'Unknown')?></strong><br><span class="muted"><?=h($e['actor_type'])?> · <?=h($e['actor_id'])?></span></td><td><?=h($e['details'])?></td><td><?=h($e['source_ip'])?></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section><p class="foot">Access restricted to authenticated System Admin. Activity logs are stored in the same MySQL database as CompuMatch. Passwords are never recorded.</p></main></body></html>
