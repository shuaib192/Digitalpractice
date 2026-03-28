<?php
/**
 * Digital Practice - Contact Page (Corporate Edition)
 */
$page_title = 'Contact Us';
$page_description = 'Get in touch with Digital Practice to discuss your enterprise tech requirements.';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

// Handle contact form submission
$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check (simplified for this structure)
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = "Security validation failed. Please try again.";
        $message_type = "error";
    } else {
        $first_name = sanitize($_POST['first_name'] ?? '');
        $last_name = sanitize($_POST['last_name'] ?? '');
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $phone = sanitize($_POST['phone'] ?? '');
        $company = sanitize($_POST['company'] ?? '');
        $job_title = sanitize($_POST['job_title'] ?? '');
        $service_interest = sanitize($_POST['service_interest'] ?? '');
        $msg_content = sanitize($_POST['message'] ?? '');

        if (empty($first_name) || empty($last_name) || empty($email) || empty($msg_content)) {
            $message = "Please fill in all required fields.";
            $message_type = "error";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = "Please provide a valid email address.";
            $message_type = "error";
        } else {
            try {
                $stmt = $pdo->prepare("INSERT INTO contact_messages (first_name, last_name, email, phone, company, job_title, service_interest, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$first_name, $last_name, $email, $phone, $company, $job_title, $service_interest, $msg_content]);
                $message = "Thank you. Your message has been received by our enterprise team.";
                $message_type = "success";
            } catch (PDOException $e) {
                $message = "A system error occurred. Please try contacting us directly via email.";
                $message_type = "error";
                error_log("Contact Error: " . $e->getMessage());
            }
        }
    }
}

// Generate new CSRF token
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));

include __DIR__ . '/includes/header.php';
?>

<!-- Premium Corporate Header -->
<section class="page-header" style="background-color: var(--color-primary); padding: calc(var(--header-height) + 5rem) 0 4rem;">
    <div class="container text-center animate-on-scroll">
        <h1 style="color: var(--color-white); font-size: 3.5rem; margin-bottom: 1rem;">Let's Talk Business.</h1>
        <p style="color: rgba(255,255,255,0.7); max-width: 600px; margin: 0 auto; font-size: 1.15rem;">Our architects and consulting teams are ready to discuss your specific infrastructure and digital transformation requirements.</p>
    </div>
</section>

<!-- Contact Architecture -->
<section class="section">
    <div class="container">
        <div class="grid-2" style="max-width: 1200px; margin: 0 auto; align-items: start;">
            
            <!-- Information Panel (Solid Box, No Radius) -->
            <div class="animate-on-scroll" style="background-color: var(--color-gray-50); padding: 3rem; border: 1px solid var(--color-gray-100);">
                <h3 style="font-size: 1.6rem; color: var(--color-primary); margin-bottom: 2rem;">Global Presence. <br>Local Expertise.</h3>
                
                <div style="margin-bottom: 2.5rem;">
                    <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-gray-500); margin-bottom: 0.5rem;">Corporate Headquarters</h4>
                    <p style="font-size: 1.05rem; color: var(--color-gray-800); display: flex; align-items: flex-start; gap: 10px;">
                        <i class="fas fa-building" style="color: var(--color-accent); margin-top: 5px;"></i>
                        <?php echo SITE_ADDRESS; ?>
                    </p>
                </div>
                
                <div style="margin-bottom: 2.5rem;">
                    <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-gray-500); margin-bottom: 0.5rem;">Direct Line</h4>
                    <p style="font-size: 1.05rem; color: var(--color-gray-800); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-phone-alt" style="color: var(--color-accent);"></i>
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', SITE_PHONE); ?>" style="color: inherit; text-decoration: none;"><?php echo SITE_PHONE; ?></a>
                    </p>
                </div>

                <div style="margin-bottom: 2.5rem;">
                    <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-gray-500); margin-bottom: 0.5rem;">Electronic Mail</h4>
                    <p style="font-size: 1.05rem; color: var(--color-gray-800); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-envelope" style="color: var(--color-accent);"></i>
                        <a href="mailto:<?php echo SITE_EMAIL; ?>" style="color: inherit; text-decoration: none;"><?php echo SITE_EMAIL; ?></a>
                    </p>
                </div>
                
                <hr style="border: 0; border-top: 1px solid var(--color-gray-200); margin: 3rem 0;">
                
                <h4 style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: var(--color-gray-500); margin-bottom: 1rem;">Connect Professionally</h4>
                <div style="display: flex; gap: 10px;">
                    <a href="#" style="width: 45px; height: 45px; background: white; border: 1px solid var(--color-gray-200); display: flex; align-items: center; justify-content: center; color: var(--color-primary); transition: all 0.3s;"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" style="width: 45px; height: 45px; background: white; border: 1px solid var(--color-gray-200); display: flex; align-items: center; justify-content: center; color: var(--color-primary); transition: all 0.3s;"><i class="fab fa-x-twitter"></i></a>
                </div>
            </div>

            <!-- Contact Form (Clean, Zero-Radius Inputs) -->
            <div class="animate-on-scroll" style="transition-delay: 150ms;">
                <?php if ($message): ?>
                    <div style="padding: 1rem 1.5rem; margin-bottom: 2rem; background-color: <?php echo $message_type === 'success' ? '#ECFDF5' : '#FEF2F2'; ?>; color: <?php echo $message_type === 'success' ? '#065F46' : '#991B1B'; ?>; border-left: 4px solid <?php echo $message_type === 'success' ? '#10B981' : '#EF4444'; ?>;">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="contact-form" style="display: grid; gap: 1.5rem;">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="first_name" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: var(--color-gray-600); margin-bottom: 0.5rem;">First Name *</label>
                            <input type="text" id="first_name" name="first_name" required style="width: 100%; padding: 1rem; border: 1px solid var(--color-gray-300); background: var(--color-gray-50); outline: none; transition: border-color 0.3s;" class="flat-input">
                        </div>
                        <div class="form-group">
                            <label for="last_name" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: var(--color-gray-600); margin-bottom: 0.5rem;">Last Name *</label>
                            <input type="text" id="last_name" name="last_name" required style="width: 100%; padding: 1rem; border: 1px solid var(--color-gray-300); background: var(--color-gray-50); outline: none; transition: border-color 0.3s;" class="flat-input">
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="email" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: var(--color-gray-600); margin-bottom: 0.5rem;">Business Email *</label>
                            <input type="email" id="email" name="email" required style="width: 100%; padding: 1rem; border: 1px solid var(--color-gray-300); background: var(--color-gray-50); outline: none;" class="flat-input">
                        </div>
                        <div class="form-group">
                            <label for="phone" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: var(--color-gray-600); margin-bottom: 0.5rem;">Phone Number</label>
                            <input type="tel" id="phone" name="phone" style="width: 100%; padding: 1rem; border: 1px solid var(--color-gray-300); background: var(--color-gray-50); outline: none;" class="flat-input">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="company" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: var(--color-gray-600); margin-bottom: 0.5rem;">Company / Organization</label>
                        <input type="text" id="company" name="company" style="width: 100%; padding: 1rem; border: 1px solid var(--color-gray-300); background: var(--color-gray-50); outline: none;" class="flat-input">
                    </div>

                    <div class="form-group">
                        <label for="message" style="display: block; font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: var(--color-gray-600); margin-bottom: 0.5rem;">How can we help? *</label>
                        <textarea id="message" name="message" rows="5" required style="width: 100%; padding: 1rem; border: 1px solid var(--color-gray-300); background: var(--color-gray-50); outline: none; resize: vertical;" class="flat-input"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary" style="padding: 1.2rem; margin-top: 1rem; cursor: none;">Submit Inquiry</button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
/* Scoped flat styles */
.flat-input:focus {
    border-color: var(--color-accent) !important;
    background: var(--color-white) !important;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
