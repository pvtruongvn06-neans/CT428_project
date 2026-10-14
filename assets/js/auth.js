
document.addEventListener('DOMContentLoaded', function () {
    const alertBox = document.getElementById('auth-alert');
    const registerForm = document.getElementById('register-form');
    const loginForm = document.getElementById('login-form');

    // Hien thi thong bao tren giao dien
    function showMessage(message, type = 'danger') {
        if (!alertBox) {
            alert(message);
            return;
        }

        alertBox.textContent = message;
        alertBox.className =
            `alert py-2 fs-7 mb-3 alert-${type}`;
    }

    // Khoa nut submit khi dang gui request
    function setLoading(form, loading) {
        const button = form.querySelector(
            'button[type="submit"]'
        );

        if (!button) return;

        if (loading) {
            if (!button.dataset.originalHtml) {
                button.dataset.originalHtml = button.innerHTML;
            }

            button.disabled = true;
            button.textContent = 'Dang xu ly...';
        } else {
            button.disabled = false;

            if (button.dataset.originalHtml) {
                button.innerHTML = button.dataset.originalHtml;
            }
        }
    }

    // Gui request den API PHP va doc JSON phan hoi
    async function callAuthAPI(action, data) {
        const response = await fetch(
            `../api/auth.php?action=${encodeURIComponent(action)}`,
            {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            }
        );

        let result;

        try {
            result = await response.json();
        } catch {
            throw new Error(
                'API khong tra ve JSON hop le. Hay kiem tra loi PHP.'
            );
        }

        return { response, result };
    }

    // =========================================
    // DANG KY
    // =========================================
    if (registerForm) {
        registerForm.addEventListener('submit', async function (event) {
            // Ngan form tai lai trang theo cach mac dinh
            event.preventDefault();

            if (!registerForm.reportValidity()) return;

            const formData = new FormData(registerForm);

            const fullname = String(
                formData.get('fullname') || ''
            ).trim();

            const email = String(
                formData.get('email') || ''
            ).trim();

            const password = String(
                formData.get('password') || ''
            );

            const confirmPassword = String(
                formData.get('confirm_password') || ''
            );

            const role = String(
                formData.get('role') || ''
            );

            const companyName = String(
                formData.get('company_name') || ''
            ).trim();

            // Kiem tra mat khau nhap lai
            if (password !== confirmPassword) {
                showMessage(
                    'Mat khau nhap lai khong trung khop.',
                    'danger'
                );
                return;
            }

            // Chuyen doi vai tro giao dien sang gia tri CSDL
            let backendRole;

            if (role === 'candidate') {
                backendRole = 'ung_vien';
            } else if (role === 'employer') {
                backendRole = 'nha_tuyen_dung';
            } else {
                showMessage('Vai tro khong hop le.', 'danger');
                return;
            }

            if (role === 'employer' && !companyName) {
                showMessage(
                    'Vui long nhap ten cong ty.',
                    'danger'
                );
                return;
            }

            // Tao payload dung voi ten truong API dang cho
            const payload = {
                ho_ten: fullname,
                email: email,
                mat_khau: password,
                vai_tro: backendRole,
                company_name: companyName
            };

            setLoading(registerForm, true);

            try {
                const { response, result } = await callAuthAPI(
                    'register',
                    payload
                );

                if (!response.ok || result.status !== 'success') {
                    showMessage(
                        result.message || 'Dang ky that bai.',
                        'danger'
                    );
                    return;
                }

                showMessage(
                    'Dang ky thanh cong. Ban co the dang nhap ngay.',
                    'success'
                );

            } catch (error) {
                console.error(error);

                showMessage(
                    error.message ||
                    'Khong the ket noi may chu.',
                    'danger'
                );
            } finally {
                setLoading(registerForm, false);
            }
        });
    }

    // =========================================
    // DANG NHAP
    // =========================================
    if (loginForm) {
        loginForm.addEventListener('submit', async function (event) {
            event.preventDefault();

            if (!loginForm.reportValidity()) return;

            const formData = new FormData(loginForm);

            const email = String(
                formData.get('email') || ''
            ).trim();

            const password = String(
                formData.get('password') || ''
            );

            const redirectPath = String(
                formData.get('redirect') || 'index.php'
            );

            setLoading(loginForm, true);

            try {
                const { response, result } = await callAuthAPI(
                    'login',
                    {
                        email: email,
                        mat_khau: password
                    }
                );

                if (!response.ok || result.status !== 'success') {
                    showMessage(
                        result.message ||
                        'Dang nhap that bai.',
                        'danger'
                    );
                    return;
                }

                showMessage(
                    'Dang nhap thanh cong.',
                    'success'
                );

                // Chi dieu huong den trang cung nguon
                const fallback = new URL(
                    'index.php',
                    window.location.href
                );

                let destination;

                try {
                    destination = new URL(
                        redirectPath,
                        window.location.href
                    );
                } catch {
                    destination = fallback;
                }

                if (destination.origin !== window.location.origin) {
                    destination = fallback;
                }

                window.location.assign(destination.href);

            } catch (error) {
                console.error(error);

                showMessage(
                    error.message ||
                    'Khong the ket noi may chu.',
                    'danger'
                );
            } finally {
                setLoading(loginForm, false);
            }
        });
    }
});
