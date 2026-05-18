USE laravel;
UPDATE users SET password='$2y$10$7W8mC3ftIpZB/QX.N2WzEuOlaJ5sXQWuiHG1ionmkQolCC39y66MS' WHERE email='owner@jjc-dimsum.test';
SELECT id, email, password FROM users WHERE email='owner@jjc-dimsum.test';
