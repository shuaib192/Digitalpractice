<?php
/**
 * Digital Practice - Admin Dashboard
 */
$admin_title = 'Dashboard';
require_once __DIR__ . '/includes/admin_header.php';

// Statistics
$stats = [
    'services' => $pdo->query('SELECT COUNT(*) FROM services')->fetchColumn(),
    'products' => $pdo->query('SELECT COUNT(*) FROM products')->fetchColumn(),
    'team' => $pdo->query('SELECT COUNT(*) FROM team_members')->fetchColumn(),
    'submissions' => $pdo->query('SELECT COUNT(*) FROM contact_submissions')->fetchColumn(),
    'unread' => $pdo->query('SELECT COUNT(*) FROM contact_submissions WHERE is_read = 0')->fetchColumn(),
];

// Pagination Orchestration: Inbound Leads
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$total_leads = $pdo->query('SELECT COUNT(*) FROM contact_submissions')->fetchColumn();
$total_pages = ceil($total_leads / $limit);

// Recent submissions (Paginated)
$recent = $pdo->query("SELECT * FROM contact_submissions ORDER BY created_at DESC LIMIT $limit OFFSET $offset")->fetchAll();

// --- Intelligence Analytics Data ---
// Lead Distribution by Interest
try {
    $lead_dist = $pdo->query("SELECT COALESCE(NULLIF(service_interest,''), 'General') as label, COUNT(*) as value FROM contact_submissions GROUP BY label")->fetchAll();
} catch (Exception $e) { $lead_dist = []; }

// Platform Asset Mix
$asset_labels = ['Services', 'Products', 'Mission Personnel', 'Intelligence (Blog)'];
$assets_data = [
    $stats['services'],
    $stats['products'],
    $stats['team'],
    $pdo->query('SELECT COUNT(*) FROM blog_posts')->fetchColumn()
];
?>

<div class="admin-header" style="flex-wrap: wrap; gap: 2rem;">
    <div>
        <h1>Command Center</h1>
        <p style="color:var(--color-gray-400); font-weight: 500;">Welcome back, <?php echo sanitize($_SESSION['admin_name']); ?>. Platform status: Operational.</p>
    </div>
    
    <!-- Global Intelligence Search -->
    <div style="flex: 1; min-width: 300px; max-width: 500px;">
        <form method="GET" action="blog.php" style="display: flex; gap: 0; border: 1px solid var(--color-gray-100); background: white;">
            <input type="text" name="s" placeholder="Search Global Intelligence..." style="flex: 1; padding: 0.8rem 1.2rem; border: none; font-size: 0.9rem; outline: none;">
            <button type="submit" style="background: var(--color-primary); color: white; border: none; padding: 0 1.5rem; cursor: pointer;"><i class="fas fa-search"></i></button>
        </form>
    </div>

    <a href="<?php echo SITE_URL; ?>" target="_blank" class="btn btn-outline" style="border: 1px solid var(--color-gray-200); color: var(--color-gray-600);">
        <i class="fas fa-external-link-alt"></i> External Preview
    </a>
</div>

<!-- Stats Grid: Elite Visuals -->
<div class="stats-grid" style="margin-bottom: 3rem;">
    <!-- ... same as before, keep existing cards but we will add the charts below them ... -->
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-cogs"></i></div>
        <div>
            <div class="stat-number"><?php echo $stats['services']; ?></div>
            <div class="stat-label">Provisioned Services</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-box"></i></div>
        <div>
            <div class="stat-number"><?php echo $stats['products']; ?></div>
            <div class="stat-label">Active Products</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon"><i class="fas fa-users"></i></div>
        <div>
            <div class="stat-number"><?php echo $stats['team']; ?></div>
            <div class="stat-label">Mission Personnel</div>
        </div>
    </div>
    <div class="stat-card" style="border-left-color: <?php echo $stats['unread'] > 0 ? '#EF4444' : 'var(--color-accent)'; ?>">
        <div class="stat-icon"><i class="fas fa-envelope"></i></div>
        <div>
            <div class="stat-number"><?php echo $stats['submissions']; ?></div>
            <div class="stat-label">Inbound Leads <?php if($stats['unread'] > 0): ?><span style="color:#EF4444;">(<?php echo $stats['unread']; ?> New)</span><?php endif; ?></div>
        </div>
    </div>
</div>

<!-- Executive Intelligence Suite -->
<div class="grid-2" style="margin-bottom: 3rem; gap: 2rem;">
    <div class="admin-card" style="margin-bottom: 0;">
        <h3 style="font-size:0.9rem; font-weight:800; text-transform:uppercase; letter-spacing:1px; margin-bottom: 2rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1rem;">
            Strategic Interest Topology
        </h3>
        <div style="height: 300px; position: relative; display: flex; justify-content: center;">
            <canvas id="leadsChart"></canvas>
        </div>
    </div>
    <div class="admin-card" style="margin-bottom: 0;">
        <h3 style="font-size:0.9rem; font-weight:800; text-transform:uppercase; letter-spacing:1px; margin-bottom: 2rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1rem;">
            Platform Asset Matrix
        </h3>
        <div style="height: 300px; position: relative;">
            <canvas id="assetsChart"></canvas>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shared Corporate Palette
    const colors = ['#0F172A', '#005BEA', '#FF9D00', '#F1F5F9', '#6366F1'];

    // Leads Chart
    const leadsCtx = document.getElementById('leadsChart').getContext('2d');
    new Chart(leadsCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode(array_column($lead_dist, 'label')); ?>,
            datasets: [{
                data: <?php echo json_encode(array_column($lead_dist, 'value')); ?>,
                backgroundColor: colors,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 10, weight: 600 } } }
            },
            cutout: '70%'
        }
    });

    // Assets Chart
    const assetsCtx = document.getElementById('assetsChart').getContext('2d');
    new Chart(assetsCtx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($asset_labels); ?>,
            datasets: [{
                label: 'Volume',
                data: <?php echo json_encode($assets_data); ?>,
                backgroundColor: '#005BEA',
                borderRadius: 0,
                barThickness: 20
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { display: false }, ticks: { font: { family: 'Inter', size: 10 } } },
                x: { grid: { display: false }, ticks: { font: { family: 'Inter', size: 10, weight: 600 } } }
            }
        }
    });
});
</script>

<!-- Recent Submissions: Elite Data Table -->
<div class="admin-card">
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 2.5rem; border-bottom: 1px solid var(--color-gray-50); padding-bottom: 1.5rem;">
        <h2 style="font-size:1.15rem; font-weight:800; text-transform:uppercase; letter-spacing:1px;">Active Inbound Leads</h2>
        <a href="<?php echo SITE_URL; ?>/admin/contact" style="font-size:0.75rem; font-weight:800; color:var(--color-accent); text-transform:uppercase; text-decoration:none; letter-spacing:1px;">Access Inbox <i class="fas fa-chevron-right" style="margin-left:5px;"></i></a>
    </div>

    <?php if (empty($recent)): ?>
        <div style="text-align:center; padding: 4rem 0;">
            <i class="fas fa-inbox" style="font-size:3rem; color:var(--color-gray-100); margin-bottom:1.5rem;"></i>
            <p style="color:var(--color-gray-400); font-weight:500;">Your lead pool is currently empty.</p>
        </div>
    <?php else: ?>
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Lead Entity</th>
                        <th>Email Vector</th>
                        <th>Interest Area</th>
                        <th>Timestamp</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recent as $sub): ?>
                    <tr>
                        <td><strong><?php echo sanitize($sub['first_name'] . ' ' . $sub['last_name']); ?></strong></td>
                        <td style="color:var(--color-gray-500);"><?php echo sanitize($sub['email']); ?></td>
                        <td><span style="font-weight:700; color:var(--color-primary); font-size:0.85rem;"><?php echo strtoupper(sanitize($sub['service_interest'] ?: 'General')); ?></span></td>
                        <td style="font-size:0.85rem; color:var(--color-gray-500);"><?php echo timeAgo($sub['created_at']); ?></td>
                        <td>
                            <?php if ($sub['is_read']): ?>
                                <span class="badge badge-active">Processed</span>
                            <?php else: ?>
                                <span class="badge badge-inactive" style="background:#FEF2F2 !important; color:#991B1B !important;">Urgent / New</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Command Center Pagination -->
        <?php echo renderPagination($page, $total_pages, 'dashboard.php'); ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/admin_footer.php'; ?>
