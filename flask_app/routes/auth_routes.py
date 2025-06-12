from flask import Blueprint, render_template

auth_bp = Blueprint('auth', __name__, url_prefix='/auth', template_folder='../../templates/auth')

@auth_bp.route('/login', methods=['GET'])
def login_get(): # Renamed from login to login_get to distinguish from POST
    return "Hello from Auth Blueprint - Login Page (GET)!"

@auth_bp.route('/login', methods=['POST'])
def login_post():
    return "Hello from Auth Blueprint - Login Action (POST)!"

@auth_bp.route('/register', methods=['GET'])
def register_get(): # Renamed from register to register_get
    return "Hello from Auth Blueprint - Register Page (GET)!"

@auth_bp.route('/register', methods=['POST'])
def register_post():
    return "Hello from Auth Blueprint - Register Action (POST)!"

@auth_bp.route('/verify') # Typically GET with token in query param
def verify_account():
    return "Hello from Auth Blueprint - Verify Account (GET)!"

@auth_bp.route('/resend-verification', methods=['POST'])
def resend_verification_email():
    return "Hello from Auth Blueprint - Resend Verification Email (POST)!"

@auth_bp.route('/password-recovery', methods=['GET'])
def password_recovery_get():
    return "Hello from Auth Blueprint - Password Recovery Form (GET)!"

@auth_bp.route('/password-recovery', methods=['POST'])
def password_recovery_post():
    return "Hello from Auth Blueprint - Password Recovery Action (POST)!"

@auth_bp.route('/logout') # Typically GET, but can be POST for CSRF protection
def logout():
    return "Hello from Auth Blueprint - Logout!"
