<?php
/**
 * CompuMatch Email Mailer - Brevo API Integration
 * Using compumatchsystem@gmail.com as verified sender
 */

// ============ BREVO CONFIGURATION ============
require_once __DIR__ . '/config.php';   // BREVO_API_KEY lives in config.php (private)
define('BREVO_ENDPOINT', 'https://api.brevo.com/v3/smtp/email');
define('FROM_NAME', 'CompuMatch');
define('FROM_EMAIL', 'compumatchsystem@gmail.com');  // ✅ Now verified in Brevo!

/**
 * Send email via Brevo API
 */
function sendMail($to, $subject, $html) {
    $data = array(
        'sender' => array(
            'name' => FROM_NAME,
            'email' => FROM_EMAIL
        ),
        'to' => array(
            array('email' => $to)
        ),
        'subject' => $subject,
        'htmlContent' => $html
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, BREVO_ENDPOINT);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'api-key: ' . BREVO_API_KEY
    ));
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    // Never let a slow Brevo response hold the page until the host shows a 502.
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return ($httpCode == 201);
}

/**
 * Send email verification link
 */
function sendVerificationEmail($email, $verifyToken, $userName) {
    $verifyLink = 'https://compumatch.infinityfreeapp.com/verify_email.php?token=' . urlencode($verifyToken);
    
    $subject = 'CompuMatch - Email Verification';
    
    $html = '
    <html>
    <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2 style="color: #333; text-align: center;">Welcome to CompuMatch, ' . htmlspecialchars($userName) . '!</h2>
            <p style="color: #666; line-height: 1.6;">Thank you for signing up. Please verify your email address by clicking the button below:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . $verifyLink . '" style="background-color: #007bff; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Verify Email</a>
            </div>
            
            <p style="color: #666; font-size: 12px;">Or copy this link: <a href="' . $verifyLink . '">' . $verifyLink . '</a></p>
            <p style="color: #999; font-size: 12px; margin-top: 20px;">This link expires in 24 hours.</p>
            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
            <p style="color: #999; font-size: 12px; text-align: center;">CompuMatch Team</p>
        </div>
    </body>
    </html>';
    
    return sendMail($email, $subject, $html);
}

/**
 * Send password reset link
 */
function sendPasswordResetEmail($email, $resetToken, $userName) {
    $resetLink = 'https://compumatch.infinityfreeapp.com/reset_password.php?type=user&token=' . urlencode($resetToken);
    
    $subject = 'CompuMatch - Password Reset Request';
    
    $html = '
    <html>
    <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2 style="color: #333;">Password Reset Request</h2>
            <p style="color: #666; line-height: 1.6;">Hi ' . htmlspecialchars($userName) . ',</p>
            <p style="color: #666; line-height: 1.6;">We received a request to reset your password. Click the button below to create a new password:</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . $resetLink . '" style="background-color: #dc3545; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Reset Password</a>
            </div>
            
            <p style="color: #666; font-size: 12px;">Or copy this link: <a href="' . $resetLink . '">' . $resetLink . '</a></p>
            <p style="color: #999; font-size: 12px; margin-top: 20px;">This link expires in 1 hour. If you did not request this, please ignore this email.</p>
            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
            <p style="color: #999; font-size: 12px; text-align: center;">CompuMatch Team</p>
        </div>
    </body>
    </html>';
    
    return sendMail($email, $subject, $html);
}

/**
 * Send store approval notification
 */
function sendStoreApprovalEmail($storeName, $storeEmail, $storeId) {
    $dashboardLink = 'https://compumatch.infinityfreeapp.com/admin.php?store_id=' . $storeId;
    
    $subject = 'CompuMatch - Store Approved!';
    
    $html = '
    <html>
    <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2 style="color: #28a745;">🎉 Congratulations, ' . htmlspecialchars($storeName) . '!</h2>
            <p style="color: #666; line-height: 1.6;">Your store has been approved and is now live on CompuMatch. You can now start managing your inventory and permits.</p>
            
            <div style="text-align: center; margin: 30px 0;">
                <a href="' . $dashboardLink . '" style="background-color: #28a745; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;">Go to Dashboard</a>
            </div>
            
            <p style="color: #666; line-height: 1.6;">Next steps:</p>
            <ul style="color: #666; line-height: 1.8;">
                <li>Add your inventory items</li>
                <li>Update your store information</li>
                <li>Manage permits and documents</li>
            </ul>
            
            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
            <p style="color: #999; font-size: 12px; text-align: center;">Welcome to CompuMatch! Happy selling! 🚀</p>
        </div>
    </body>
    </html>';
    
    return sendMail($storeEmail, $subject, $html);
}

/**
 * Send store rejection notification
 */
function sendStoreRejectionEmail($storeName, $storeEmail, $storeId, $rejectionReason) {
    $subject = 'CompuMatch - Store Application Status';
    
    $html = '
    <html>
    <body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
        <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2 style="color: #dc3545;">Store Application Update</h2>
            <p style="color: #666; line-height: 1.6;">Hello ' . htmlspecialchars($storeName) . ',</p>
            <p style="color: #666; line-height: 1.6;">Thank you for your interest in joining CompuMatch. After reviewing your application, we are unable to approve it at this time.</p>
            
            <div style="background-color: #f8f9fa; padding: 15px; border-left: 4px solid #dc3545; margin: 20px 0;">
                <p style="color: #666; margin: 0;"><strong>Reason:</strong></p>
                <p style="color: #666; margin: 10px 0 0 0;">' . htmlspecialchars($rejectionReason) . '</p>
            </div>
            
            <p style="color: #666; line-height: 1.6;">You may reapply with the necessary adjustments. If you have any questions, please feel free to contact us.</p>
            
            <hr style="border: none; border-top: 1px solid #eee; margin: 20px 0;">
            <p style="color: #999; font-size: 12px; text-align: center;">CompuMatch Support Team</p>
        </div>
    </body>
    </html>';
    
    return sendMail($storeEmail, $subject, $html);
}
?>
