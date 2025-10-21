<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CampussPass | Student Registration</title>
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/theme.css">
    <link rel="stylesheet" href="/projects/campus_event_ticketing/assets/css/register.css">
    <style>
        .error { color: red; font-size: 13px; margin-left: 8px; }
        input:invalid, select:invalid { border-color: #e74c3c; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header h2 { margin: 5px 0 0; font-size: 18px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>CampussPass</h1>
        <h2>Student Registration</h2>
    </div>
    <form id="regForm" action="/projects/campus_event_ticketing/app/controllers/AuthController.php?action=register" method="POST" autocomplete="off" novalidate>
        <label>Full Name:</label><input type="text" name="full_name" id="full_name" required><span class="error" id="err_full_name"></span><br>
        <label>Username:</label><input type="text" name="username" id="username" required><span class="error" id="err_username"></span><br>
        <label>Admission Number:</label><input type="text" name="admission_number" id="admission_number" required><span class="error" id="err_admission_number"></span><br>
        <label>Department:</label>
        <select name="department" id="department" required>
            <option value="">Select</option>
            <option value="B.Tech">B.Tech</option>
            <option value="Computer Applications">Computer Applications</option>
        </select><span class="error" id="err_department"></span><br>
        <label>Phone Number:</label><input type="text" name="phone" id="phone" required><span class="error" id="err_phone"></span><br>
        <label>Email:</label><input type="email" name="email" id="email" required><span class="error" id="err_email"></span><br>
        <label>Password:</label><input type="password" name="password" id="password" required><span class="error" id="err_password"></span><br>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="/projects/campus_event_ticketing/app/views/login.php">Login here</a></p>
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
