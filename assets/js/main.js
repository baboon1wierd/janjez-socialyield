const API_BASE = (window.JANJEZ_API_URL || '').replace(/\/$/, '') || '';

async function api(path, options = {}) {
    const url = `${API_BASE}${path}`;
    const defaults = {
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    };
    const config = {
        ...defaults,
        ...options,
        headers: { ...defaults.headers, ...options.headers }
    };
    const res = await fetch(url, config);
    const data = await res.json();
    if (!res.ok || !data.success) {
        throw new Error(data.message || 'Request failed');
    }
    return data;
}

const api = {
    get: (path) => api(path),
    post: (path, body) => api(path, { method: 'POST', body: JSON.stringify(body) }),
    put: (path, body) => api(path, { method: 'PUT', body: JSON.stringify(body) }),
    del: (path) => api(path, { method: 'DELETE' })
};

async function handleSignup(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Creating account...';

    try {
        const data = {
            email: form.email.value,
            username: form.username.value,
            password: form.password.value,
            full_name: form.full_name.value || ''
        };
        const result = await api.post('/api/?action=signup', data);
        alert('Account created! Please log in.');
        closeModal('signup-modal');
        openModal('login-modal');
    } catch (err) {
        alert(err.message);
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}

async function handleLogin(e) {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    btn.disabled = true;
    btn.textContent = 'Logging in...';

    try {
        const data = {
            email: form.email.value,
            password: form.password.value
        };
        const result = await api.post('/api/?action=login', data);
        closeModal('login-modal');
        showToast('Welcome back, ' + (result.user.username || result.user.email) + '!');
    } catch (err) {
        alert(err.message);
    } finally {
        btn.disabled = false;
        btn.textContent = originalText;
    }
}

async function handleGeneratePost(platform, campaignGoal) {
    const btn = document.querySelector('[data-action="generate"]');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
    }

    try {
        const data = { platform, campaign_goal: campaignGoal };
        const result = await api.post('/api/?action=generate', data);
        showToast('Post generated successfully! ID: ' + result.post_id);
        return result;
    } catch (err) {
        showToast('Error: ' + err.message, true);
    } finally {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-magic"></i> Start creating for free';
        }
    }
}

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
}

function showToast(message, isError = false) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.style.cssText = 'position:fixed;bottom:20px;right:20px;z-index:1000;display:flex;flex-direction:column;gap:10px;';
        document.body.appendChild(container);
    }
    const toast = document.createElement('div');
    toast.style.cssText = `background:${isError ? '#ef4444' : '#10b981'};color:white;padding:12px 20px;border-radius:12px;box-shadow:0 10px 25px rgba(0,0,0,0.15);font-size:14px;font-weight:500;animation:slideIn 0.3s ease;max-width:320px;`;
    toast.textContent = message;
    container.appendChild(toast);
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}

function addStyles() {
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 999; display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-overlay.active { display: flex; }
        .modal { background: white; border-radius: 24px; padding: 40px; max-width: 420px; width: 90%; box-shadow: 0 25px 50px rgba(0,0,0,0.25); }
        .modal h2 { font-size: 24px; margin-bottom: 8px; }
        .modal p { color: #666; margin-bottom: 24px; font-size: 14px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-weight: 600; margin-bottom: 6px; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px 14px; border: 1.5px solid #e5e5e5; border-radius: 10px; font-size: 15px; outline: none; transition: border 0.2s; }
        .form-group input:focus { border-color: #6c4bff; }
        .modal-close { position: absolute; top: 16px; right: 16px; background: none; border: none; font-size: 20px; cursor: pointer; color: #999; }
    `;
    document.head.appendChild(style);
}

function createModals() {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    overlay.id = 'auth-modals';
    overlay.innerHTML = `
        <div id="login-modal" class="modal" style="position:relative;">
            <button class="modal-close" onclick="closeModal('login-modal')">&times;</button>
            <h2>Welcome back</h2>
            <p>Log in to Janjez-Socio</p>
            <form onsubmit="handleLogin(event)">
                <div class="form-group"><label>Email or username</label><input type="text" name="email" required></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required></div>
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Log in</button>
            </form>
            <p style="text-align:center;margin-top:16px;font-size:13px;">No account? <a href="#" onclick="closeModal('login-modal');openModal('signup-modal');" style="color:#6c4bff;font-weight:600;">Sign up</a></p>
        </div>
        <div id="signup-modal" class="modal" style="position:relative;">
            <button class="modal-close" onclick="closeModal('signup-modal')">&times;</button>
            <h2>Create account</h2>
            <p>Start your AI social growth</p>
            <form onsubmit="handleSignup(event)">
                <div class="form-group"><label>Email</label><input type="email" name="email" required></div>
                <div class="form-group"><label>Username</label><input type="text" name="username" required minlength="3"></div>
                <div class="form-group"><label>Full name</label><input type="text" name="full_name"></div>
                <div class="form-group"><label>Password</label><input type="password" name="password" required minlength="8"></div>
                <button type="submit" class="btn-primary" style="width:100%;justify-content:center;">Sign up</button>
            </form>
            <p style="text-align:center;margin-top:16px;font-size:13px;">Have an account? <a href="#" onclick="closeModal('signup-modal');openModal('login-modal');" style="color:#6c4bff;font-weight:600;">Log in</a></p>
        </div>
    `;
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) {
            closeModal('login-modal');
            closeModal('signup-modal');
        }
    });
    document.body.appendChild(overlay);
}

function wireUI() {
    const authNav = document.getElementById('auth-nav');
    if (authNav) {
        const btns = authNav.querySelectorAll('a');
        if (btns[0]) btns[0].setAttribute('onclick', "openModal('login-modal')");
        if (btns[1]) btns[1].setAttribute('onclick', "openModal('login-modal')");
    }

    const startTrialBtn = document.querySelector('a[href="#"][onclick*="Start free trial"], a.btn-primary');
    if (startTrialBtn) {
        startTrialBtn.setAttribute('data-action', 'generate');
        startTrialBtn.removeAttribute('href');
        startTrialBtn.addEventListener('click', (e) => {
            e.preventDefault();
            handleGeneratePost('all', 'showcase');
        });
    }

    const heroActions = document.querySelector('.hero-actions');
    if (heroActions) {
        const primaryBtn = heroActions.querySelector('.btn-primary');
        if (primaryBtn && !primaryBtn.hasAttribute('data-action')) {
            primaryBtn.setAttribute('data-action', 'generate');
            primaryBtn.removeAttribute('href');
            primaryBtn.addEventListener('click', (e) => {
                e.preventDefault();
                handleGeneratePost('all', 'showcase');
            });
        }
    }

    const quoteBtn = document.querySelector('.quote-block span[style*="Start for free"]');
    if (quoteBtn) {
        quoteBtn.style.cursor = 'pointer';
        quoteBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('login-modal');
        });
    }

    const faqCtaBtn = document.querySelector('.faq-grid + div .btn-primary');
    if (faqCtaBtn) {
        faqCtaBtn.setAttribute('data-action', 'generate');
        faqCtaBtn.removeAttribute('href');
        faqCtaBtn.addEventListener('click', (e) => {
            e.preventDefault();
            openModal('login-modal');
        });
    }
}

document.addEventListener('DOMContentLoaded', () => {
    addStyles();
    createModals();
    wireUI();
});