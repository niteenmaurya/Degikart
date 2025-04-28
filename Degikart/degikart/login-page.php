<?php
/* Template Name: Sign-Up/Sign-In Page */

// Redirect if the user is logged in
if (is_user_logged_in()) {
    wp_redirect(home_url());
    exit;
}
// Get the redirect URL from the query parameter
$redirect_to = isset($_GET['redirect_to']) ? urldecode($_GET['redirect_to']) : home_url();

 // Google OAuth Settings
$google_client_id = get_option('degikart_google_client_id');  // Example: '1234567890-abcdefg.apps.googleusercontent.com'
$google_redirect_uri = get_option('degikart_google_redirect_uri');  // Example: 'https://yourwebsite.com/oauth/google/callback'

// Facebook OAuth Settings
$facebook_app_id = get_option('degikart_facebook_app_id');  // Example: '123456789012345'
$facebook_redirect_uri = get_option('degikart_facebook_redirect_uri');  // Example: 'https://yourwebsite.com/oauth/facebook/callback'

// Apple OAuth Settings
$apple_service_id = get_option('degikart_apple_service_id');  // Example: 'com.yourcompany.app'
$apple_redirect_uri = get_option('degikart_apple_redirect_uri');  // Example: 'https://yourwebsite.com/oauth/apple/callback'

 
// Handle login or registration
$error_messages = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        // Handle login
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);

        if (empty($username) || empty($password)) {
            if (empty($username)) {
                $error_messages['username'] = 'Please enter a username or email.';
            }
            if (empty($password)) {
                $error_messages['password'] = 'Please enter your password.';
            }
        } else {
            // Check if username/email exists
            if (!username_exists($username) && !email_exists($username)) {
                $error_messages['username'] = 'The username or email you entered is not registered.';
            }

            // Attempt login if username/email exists
            if (empty($error_messages)) {
                $creds = array(
                    'user_login'    => $username,
                    'user_password' => $password,
                    'remember'      => true
                );
                $user = wp_signon($creds, false);

                if (!is_wp_error($user)) {
                    wp_redirect($redirect_to);  // Redirect to the payment page (or the page user came from)
                    exit;
                } else {
                    $error_messages['password'] = 'The password you entered is incorrect.';
                }
            }
        }
    } elseif (isset($_POST['register'])) {
        // Handle registration
        $username = sanitize_text_field($_POST['username']);
        $password = sanitize_text_field($_POST['password']);
        $email = sanitize_email($_POST['email']);

        // Validate registration
        if (empty($username) || empty($password) || empty($email)) {
            if (empty($username)) {
                $error_messages['username'] = 'Please enter a username.';
            }
            if (empty($password)) {
                $error_messages['password'] = 'Please enter a password.';
            }
            if (empty($email)) {
                $error_messages['email'] = 'Please enter an email.';
            }
        } else {
            // Check if username or email already exists
            if (username_exists($username)) {
                $error_messages['username'] = 'Username already taken.';
            }
            if (email_exists($email)) {
                $error_messages['email'] = 'Email already registered.';
            }

            // If no errors, create the user
            if (empty($error_messages)) {
                $user_id = wp_create_user($username, $password, $email);

                if (is_wp_error($user_id)) {
                    $error_messages['register'] = 'Registration failed, please try again.';
                } else {
                    // Log the user in automatically
                    $creds = array(
                        'user_login'    => $username,
                        'user_password' => $password,
                        'remember'      => true,
                    );
                    $user = wp_signon($creds, false);

                    if (is_wp_error($user)) {
                        $error_messages['login'] = 'Login failed, please try again.';
                    } else {
                        wp_redirect(home_url());
                        exit;
                    }
                }
            }
        }
    }
}

get_header();
?>

<div id="site-width" class="login-cont">
    <div class="login-container">
        <header class="kart-logo"> Kartnic </header>
        <div class="login-content">
            <h2>Great to have you back!</h2>
          <!-- Check if any social login link is available, if so display buttons -->
          <?php
if ($google_client_id && $google_redirect_uri && 
    $facebook_app_id && $facebook_redirect_uri && 
    $apple_service_id && $apple_redirect_uri):
?>
    <div class="social-login-buttons">
        <?php if ($google_client_id && $google_redirect_uri): ?>
            <div class="google-login">
                <a href="<?php echo esc_url($google_redirect_uri); ?>">Login with Google</a>
            </div>
        <?php endif; ?>

        <?php if ($facebook_app_id && $facebook_redirect_uri): ?>
            <div class="facebook-login">
                <a href="<?php echo esc_url($facebook_redirect_uri); ?>">Login with Facebook</a>
            </div>
        <?php endif; ?>

        <?php if ($apple_service_id && $apple_redirect_uri): ?>
            <div class="apple-login">
                <a href="<?php echo esc_url($apple_redirect_uri); ?>">Login with Apple</a>
            </div>
        <?php endif; ?>
    </div>
   
<?php endif; ?>

 

            <!-- Login Form -->
            <div class="login-form-container" id="login-form-container">
                <form action="" method="post" id="login-form">
                <div class="spacror"></div>
                    <div class="form-group">
                        <label for="username" class="form-label">Username or Email:</label>
                        <input type="text" id="username" name="username" class="form-input" value="<?php echo isset($username) ? $username : ''; ?>" required>
                        <?php if (isset($error_messages['username'])): ?>
                            <div class="error-notice"><?php echo $error_messages['username']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" id="password" name="password" class="form-input" required>
                        <?php if (isset($error_messages['password'])): ?>
                            <div class="error-notice"><?php echo $error_messages['password']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="login" class="login-button">Login</button>
                    </div>
                </form>
            </div>

            <!-- New Here? Create Account link -->
            <div class="create-account-link" id="create-account-link-container">
                New Here? 
                <a href="#" id="create-account-link">Create an Account</a>
            </div>

            <div class="spacror"></div>

            <!-- Registration Form -->
            <div class="registration-form-container" id="registration-form-container" style="display:none;">
                <form action="" method="post" id="registration-form">
                    <div class="form-group">
                        <label for="reg-username" class="form-label">Username:</label>
                        <input type="text" id="reg-username" name="username" class="form-input" required>
                        <?php if (isset($error_messages['username'])): ?>
                            <div class="error-notice"><?php echo $error_messages['username']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="reg-email" class="form-label">Email:</label>
                        <input type="email" id="reg-email" name="email" class="form-input" required>
                        <?php if (isset($error_messages['email'])): ?>
                            <div class="error-notice"><?php echo $error_messages['email']; ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="reg-password" class="form-label">Password:</label>
                        <input type="password" id="reg-password" name="password" class="form-input" required>
                        <span class="login-notic">Use 8 or more characters with a mix of letters, numbers, and symbols.</span>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="register" class="form-button">Register</button>
                    </div>
                    <div class="create-account-link">Already have an Account? 
                        <a href="#" id="sign-in-link">Sign in here.</a>
                    </div>
                    
                    <div class="spacror"></div>
                </form>
            </div>

            <span> By continuing, you confirm you are 18 or over and agree to our <a href="#">Privacy Policy</a> and <a href="#">Terms of Use</a>.</span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show registration form and hide login form + link
        document.getElementById('create-account-link').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('login-form-container').style.display = 'none';  // Hide login form
            document.getElementById('create-account-link-container').style.display = 'none';  // Hide "New Here?" link
            document.getElementById('registration-form-container').style.display = 'block';  // Show registration form
        });

        // Show login form and hide registration form + link
        document.getElementById('sign-in-link').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('registration-form-container').style.display = 'none';  // Hide registration form
            document.getElementById('create-account-link-container').style.display = 'block';  // Show "New Here?" link
            document.getElementById('login-form-container').style.display = 'block';  // Show login form
        });
    });
</script>

<style>
    /* Error messages styling */
    .error-notice {
        color: red;
        font-size: 14px;
        margin-top: 5px;
    }

    /* Social Login Buttons */
    .social-login-buttons {
        width: 100%;
        justify-content: space-around;
        margin-bottom: 20px;
    }

    .google-login a, .apple-login a, .facebook-login a {
        justify-content: center;
        align-items: center;
        text-decoration: none;
        color: black;
        padding: 12px 25px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        font-size: 16px;
        border: 1px solid rgb(188, 180, 180);
        margin: 15px 0;
    }

    .google-login a {
        background-color: #fff;
    }

    .facebook-login a {
        background-color: #3b5998;
        color: white;
    }

    .apple-login a {
        background-color: #000;
        color: white;
    }

    /* Styling for form elements */
    .form-group {
        margin-bottom: 20px;
    }

    .form-input {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-button, .login-button {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .form-button:hover, .login-button:hover {
        background-color: #45a049;
    }

    .login-notic {
        font-size: 15px;
        color: #5d5d5d;
    }
</style>

<?php get_footer(); ?>
