<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - CampusPass</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/register.css">
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo-circle">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <h1>Create Account</h1>
                <p class="subtitle">Join CampusPass today</p>
            </div>
            <form id="regForm" action="/projects/campus_event_ticketing/app/controllers/AuthController.php?action=register" method="POST" autocomplete="off" novalidate class="register-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" name="full_name" id="full_name" placeholder="John Doe" required>
                        <span class="error" id="err_full_name"></span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" placeholder="johndoe" required>
                        <span class="error" id="err_username"></span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group half">
                        <label for="admission_number">Admission Number</label>
                        <input type="text" name="admission_number" id="admission_number" placeholder="12345" required>
                        <span class="error" id="err_admission_number"></span>
                    </div>
                    <div class="form-group half">
                        <label for="department">Department</label>
                        <select name="department" id="department" required>
                            <option value="">Select Department</option>
                            <option value="B.Tech">B.Tech</option>
                            <option value="Computer Applications">Computer Applications</option>
                        </select>
                        <span class="error" id="err_department"></span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="text" name="phone" id="phone" placeholder="1234567890" required>
                        <span class="error" id="err_phone"></span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" name="email" id="email" placeholder="your.email@example.com" required>
                        <span class="error" id="err_email"></span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Create a strong password" required>
                        <span class="error" id="err_password"></span>
                    </div>
                </div>
                
                <button type="submit" class="register-btn">
                    <span>Create Account</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="m12 5 7 7-7 7"></path>
                    </svg>
                </button>
            </form>
            
            <div class="register-footer">
                <p>Already have an account? <a href="/projects/campus_event_ticketing/app/views/login.php" class="login-link">Sign in</a></p>
            </div>
        </div>
        
        <div class="background-decoration">
            <div class="circle circle-1"></div>
            <div class="circle circle-2"></div>
            <div class="circle circle-3"></div>
        </div>
    </div>
    <script>
    function showError(id, msg) {
        document.getElementById(id).textContent = msg;
    }
    function clearError(id) {
        document.getElementById(id).textContent = '';
    }
    document.getElementById('full_name').addEventListener('input', function() {
        let v = this.value;
        if (v.trim() === '' || /^\s|\s$/.test(v)) {
            showError('err_full_name', 'Cannot be empty or start/end with space');
        } else if (!/^[A-Za-z]+(\s[A-Za-z]+)*$/.test(v)) {
            showError('err_full_name', 'Only letters and spaces allowed');
        } else {
            clearError('err_full_name');
        }
    });
    document.getElementById('username').addEventListener('input', function() {
        let v = this.value;
        if (v.trim() === '' || /^\s|\s$/.test(v)) {
            showError('err_username', 'Cannot be empty or start/end with space');
        } else if (!/^[a-z0-9._-]+$/.test(v)) {
            showError('err_username', 'Only lowercase letters, numbers, . _ - allowed');
        } else {
            clearError('err_username');
        }
    });
    document.getElementById('admission_number').addEventListener('input', function() {
        let v = this.value;
        if (!/^\d{1,5}$/.test(v)) {
            showError('err_admission_number', 'Must be 1 to 5 digits');
        } else {
            clearError('err_admission_number');
        }
    });
    document.getElementById('department').addEventListener('change', function() {
        if (this.value !== 'B.Tech' && this.value !== 'Computer Applications') {
            showError('err_department', 'Select a valid department');
        } else {
            clearError('err_department');
        }
    });
    document.getElementById('phone').addEventListener('input', function() {
        let v = this.value;
        if (!/^\d{10}$/.test(v)) {
            showError('err_phone', 'Must be exactly 10 digits');
        } else {
            clearError('err_phone');
        }
    });
    document.getElementById('email').addEventListener('input', function() {
        let v = this.value;
        let re = /^([a-zA-Z0-9_\.-]+)@([a-zA-Z0-9\.-]+)\.([a-zA-Z]{2,6})$/;
        if (!re.test(v)) {
            showError('err_email', 'Invalid email address');
        } else {
            clearError('err_email');
        }
    });
    document.getElementById('password').addEventListener('input', function() {
        let v = this.value;
        let err = [];
        if (!/[A-Z]/.test(v)) err.push('uppercase');
        if (!/[a-z]/.test(v)) err.push('lowercase');
        if (!/\d/.test(v)) err.push('number');
        if (!/[^A-Za-z0-9]/.test(v)) err.push('special symbol');
        if (v === '') err = ['required'];
        if (err.length) {
            showError('err_password', 'Include: ' + err.join(', '));
        } else {
            clearError('err_password');
        }
    });
    document.getElementById('regForm').addEventListener('submit', function(e) {
        let errors = document.querySelectorAll('.error');
        for (let i = 0; i < errors.length; i++) {
            if (errors[i].textContent !== '') {
                e.preventDefault();
                alert('Please fix errors before submitting.');
                return false;
            }
        }
    });
    </script>
</body>
</html>
