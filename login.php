<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
    <div class="container">
        <form id="form" action="loginLogic.php" method="POST">
            <h1>Login</h1>
            <div class="input-control">
                <label for="email">Email</label>
                <input id="email" name="email" type="text" required>
                <div class="error"></div>
            </div>
            <div class="input-control">
                <label for="password">Password</label>
                <input id="password" name="password" type="password" required>
                <div class="error"></div>
            </div>
            <button type="submit">Sign In</button>
            <p class="register-link">
                Don't have an account? <a href="register.php">Register here</a>
            </p>
        </form>
    </div>

    <script>
        const form = document.getElementById('form');
        const email = document.getElementById('email');
        const password = document.getElementById('password');

        const setError = (element, message) => {
            const inputControl = element.parentElement;
            const errorDisplay = inputControl.querySelector('.error');

            errorDisplay.innerText = message;
            inputControl.classList.add('error');
            inputControl.classList.remove('success');
        };

        const setSuccess = (element) => {
            const inputControl = element.parentElement;
            const errorDisplay = inputControl.querySelector('.error');

            errorDisplay.innerText = '';
            inputControl.classList.add('success');
            inputControl.classList.remove('error');
        };

        const isValidEmail = (email) => {
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        };

        const validateInputs = () => {
            let isValid = true;

            const emailValue = email.value.trim();
            const passwordValue = password.value.trim();

            if (emailValue === '') {
                setError(email, 'Email is required');
                isValid = false;
            } else if (!isValidEmail(emailValue)) {
                setError(email, 'Provide a valid email address');
                isValid = false;
            } else {
                setSuccess(email);
            }

            if (passwordValue === '') {
                setError(password, 'Password is required');
                isValid = false;
            } else if (passwordValue.length < 8) {
                setError(password, 'Password must be at least 8 characters');
                isValid = false;
            } else {
                setSuccess(password);
            }

            return isValid;
        };

     
        form.addEventListener('submit', (e) => {
            e.preventDefault();

            if (validateInputs()) {
                form.submit(); 
            }
        });
    </script>
</body>
</html>
